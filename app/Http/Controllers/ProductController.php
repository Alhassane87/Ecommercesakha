<?php

namespace App\Http\Controllers;

use App\Models\AdCampaign;
use App\Models\Product;
use App\Models\Category;
use App\Services\AdCampaignService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('images', 'category')->where('is_active', true);

        // Produits en promotion
        if ($request->boolean('deals')) {
            $query->where(function ($q) {
                $q->where(function ($priceQuery) {
                    $priceQuery->whereNotNull('discount_price')
                        ->whereColumn('discount_price', '<', 'price');
                })->orWhereHas('promotion', function ($promotionQuery) {
                    $promotionQuery->where('is_active', true)
                        ->where(function ($startQuery) {
                            $startQuery->whereNull('starts_at')
                                ->orWhere('starts_at', '<=', now());
                        })
                        ->where(function ($endQuery) {
                            $endQuery->whereNull('ends_at')
                                ->orWhere('ends_at', '>=', now());
                        });
                })->orWhereHas('category.activePromotions');
            });
        }

        // Filtrage par catÃ©gorie
        if ($request->has('category') && $request->category) {
            $query->where('category_id', $request->category);
        }

        // Recherche
        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        // Tri
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'name':
                $query->orderBy('name', 'asc');
                break;
            default:
                $query->latest();
                break;
        }

        $products = $query->paginate(12)->withQueryString();
        $categories = Category::where('is_active', true)->get();

        return view('shop.index', compact('products', 'categories'));
    }

    /**
     * Home / landing page: show featured categories and recent products.
     */
    public function home()
    {
        // Categories actives avec compteur de produits actifs
        $categories = Category::where('is_active', true)
            ->withCount(['products' => function($query) {
                $query->where('is_active', true);
            }])
            ->orderByDesc('products_count')
            ->orderBy('name')
            ->take(12)
            ->get();

        // Produits populaires (quantites vendues)
        $popularProducts = Product::with(['images', 'category'])
            ->where('is_active', true)
            ->withSum('orderItems as sold_qty', 'qty')
            ->orderByDesc('sold_qty')
            ->latest()
            ->take(10)
            ->get();

        if ($popularProducts->isEmpty()) {
            $popularProducts = Product::with(['images', 'category'])
                ->where('is_active', true)
                ->latest()
                ->take(10)
                ->get();
        }

        // Produits recents
        $recentProducts = Product::with(['images', 'category'])
            ->where('is_active', true)
            ->latest()
            ->take(10)
            ->get();

        // Backward compatibility for existing sections.
        $products = $popularProducts;
        $totalProductsCount = Product::where('is_active', true)->count();
        $totalCategoriesCount = Category::where('is_active', true)->count();

        // Diaporama: images des produits recemment ajoutes, melangees aleatoirement
        $productSlides = Product::with('images')
            ->where('is_active', true)
            ->has('images')
            ->latest()
            ->take(24)
            ->get()
            ->flatMap(function ($product) {
                return $product->images->map(function ($image) use ($product) {
                    return [
                        'path' => $image->path,
                        'product_name' => $product->name,
                        'is_ad' => false,
                        'target_url' => null,
                        'open_in_new_tab' => false,
                    ];
                });
            });

        $adSlides = app(AdCampaignService::class)
            ->campaignsForPlacement(AdCampaign::PLACEMENT_HOME_SLIDESHOW, 8)
            ->filter(fn (AdCampaign $campaign) => $campaign->galleryImagePaths()->isNotEmpty())
            ->values()
            ->flatMap(function (AdCampaign $campaign) {
                return $campaign->galleryImagePaths()->map(function (string $path) use ($campaign) {
                    return [
                        'path' => $path,
                        'product_name' => $campaign->title,
                        'is_ad' => true,
                        'target_url' => !empty($campaign->target_url) ? route('ads.click', $campaign) : null,
                        'open_in_new_tab' => (bool) $campaign->open_in_new_tab,
                    ];
                });
            })
            ->values();

        if ($adSlides->isNotEmpty()) {
            // Guarantee at least one ad in slideshow when campaigns exist.
            $primaryAd = $adSlides->first();
            $remainingAds = $adSlides->slice(1);
            $mixedPool = $productSlides->concat($remainingAds)->shuffle()->take(5);
            $slideshowImages = collect([$primaryAd])->concat($mixedPool)->shuffle()->values();
        } else {
            $slideshowImages = $productSlides->shuffle()->take(6)->values();
        }

        return view('welcome', compact(
            'categories',
            'products',
            'popularProducts',
            'recentProducts',
            'slideshowImages',
            'totalProductsCount',
            'totalCategoriesCount'
        ));
    }

    public function show($slug)
    {
        $product = Product::with(['images', 'category', 'variations', 'promotion'])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        // Charger les attributs de la catÃ©gorie
        $categoryAttributes = null;
        if ($product->category) {
            $categoryAttributes = $product->category->attributes()->with('values')->get();
        }

        // Produits similaires (mÃªme catÃ©gorie)
        $relatedProducts = Product::with('images')
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->take(4)
            ->get();

        return view('shop.show', compact('product', 'relatedProducts', 'categoryAttributes'));
    }
}

