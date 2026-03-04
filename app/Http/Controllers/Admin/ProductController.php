<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\OrderItem;
use App\Models\ProductVariation;
use App\Models\Category;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{
    public function index()
    {
        try {
            $products = Product::with('images')->latest()->paginate(20);
            \Log::info('Products loaded: ' . $products->count());
            return view('admin.products.index', compact('products'));
        } catch (\Exception $e) {
            \Log::error('Error in index method: ' . $e->getMessage());
            abort(500, 'Erreur chargement produits');
        }
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        $defaultCategoryId = request('category'); // Recupere le parametre ?category=X
        return view('admin.products.create', compact('categories', 'defaultCategoryId'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            // Validation principale
            $validationRules = [
                'name' => 'required|string|max:255',
                'slug' => 'required|string|max:255',
                'description' => 'nullable|string',
                'category_id' => 'nullable|exists:categories,id',
                'price' => 'required|numeric|min:0',
                'discount_percent' => 'nullable|numeric|min:0|max:99.99',
                'discount_price' => 'nullable|numeric|min:0',
                'stock' => 'nullable|integer|min:0',
                'images' => 'nullable|array',
                'images.*' => 'nullable|file|mimes:jpg,jpeg,png,gif,webp,heic,heif,avif|max:5120',

                // Variations
                'variations' => 'nullable|array',
                'variations.*.attributes' => 'required|array',
                'variations.*.price' => 'nullable|numeric|min:0',
                'variations.*.stock' => 'required|integer|min:0',
            ];

            // Validation
            $data = $request->validate(
                $validationRules,
                $this->productValidationMessages(),
                $this->productValidationAttributes()
            );

            // Prepare per-product options
            $productAttributes = $this->normalizeProductAttributes($request->input('product_attributes', []));

            // Product creation
            $rawSlug = trim((string) ($data['slug'] ?? ''));
            $baseSlug = Str::slug($rawSlug !== '' ? $rawSlug : $data['name']);
            if ($baseSlug === '') {
                $baseSlug = 'produit';
            }
            $resolvedSlug = $this->makeUniqueSlug($baseSlug);
            $resolvedDiscountPrice = $this->resolveDiscountPrice($data);

            $productPayload = [
                'name' => $data['name'],
                'slug' => $resolvedSlug,
                'description' => $data['description'] ?? null,
                'category_id' => $data['category_id'] ?? null,
                'price' => $data['price'],
                'stock' => $data['stock'] ?? 0,
            ];

            if (Schema::hasColumn('products', 'is_active')) {
                // Produit actif par defaut
                $productPayload['is_active'] = true;
            }

            if (Schema::hasColumn('products', 'attributes')) {
                $productPayload['attributes'] = !empty($productAttributes) ? $productAttributes : null;
            }

            if (Schema::hasColumn('products', 'discount_price')) {
                $productPayload['discount_price'] = $resolvedDiscountPrice;
            }

            $product = Product::create($productPayload);
            $warnings = [];
            $imageIssue = false;
            $variationIssue = false;

            // Gestion des images
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $file) {
                    try {
                        $path = $file->store('product_images', 'public');
                        ProductImage::create([
                            'product_id' => $product->id,
                            'path' => $path
                        ]);
                    } catch (\Throwable $imageException) {
                        report($imageException);
                        $imageIssue = true;
                    }
                }
            }

            // Gestion des variations
            if ($request->filled('variations')) {
                foreach ($request->variations as $variation) {
                    try {
                        $sku = $product->slug . '-' . strtoupper(substr(md5(json_encode($variation['attributes'])), 0, 6));
                        $product->variations()->create([
                            'sku' => $sku,
                            'attributes' => $variation['attributes'],
                            'price' => $variation['price'] ?? null,
                            'stock' => $variation['stock'],
                            'is_active' => true
                        ]);
                    } catch (\Throwable $variationException) {
                        report($variationException);
                        $variationIssue = true;
                    }
                }
            }

            DB::commit();

            $statusMessage = 'Le produit est cree avec succes.';
            if ($resolvedSlug !== $baseSlug) {
                $statusMessage .= ' Slug ajuste automatiquement: ' . $resolvedSlug . '.';
            }

            if ($imageIssue) {
                $warnings[] = 'Certaines images n ont pas pu etre enregistrees.';
            }

            if ($variationIssue) {
                $warnings[] = 'Certaines variations n ont pas pu etre enregistrees.';
            }

            if (!empty($warnings)) {
                return redirect()->to(route('admin.products.index', [], false))
                    ->with('status', $statusMessage)
                    ->with('warning', implode(' ', $warnings));
            }

            return redirect()->to(route('admin.products.index', [], false))
                ->with('status', $statusMessage);

        } catch (ValidationException $e) {
            DB::rollBack();
            return back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur creation produit', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->with('error', 'Erreur lors de la creation du produit')
                         ->withInput();
        }
    }

    public function edit(Product $product)
    {
        $categories = Category::orderBy('name')->get();
        $product->load('images', 'variations');

        // Charger les attributs de la categorie si elle existe
        $categoryAttributes = null;
        if ($product->category) {
            $categoryAttributes = $product->category->attributes()->with('values')->get();
        }

        // Sales summary
        $totalQty = OrderItem::where('product_id', $product->id)->sum('qty');
        $totalRevenue = OrderItem::where('product_id', $product->id)
            ->selectRaw('SUM(qty * unit_price) as revenue')
            ->value('revenue') ?? 0;

        $recentItems = OrderItem::with('order')
            ->where('product_id', $product->id)
            ->latest()
            ->take(5)
            ->get();

        $salesSummary = [
            'total_qty' => (int) $totalQty,
            'total_revenue' => (float) $totalRevenue,
            'recent' => $recentItems,
        ];

        return view('admin.products.edit', compact('product', 'categories', 'salesSummary', 'categoryAttributes'));
    }

    public function update(Request $request, Product $product)
    {
        DB::beginTransaction();

        try {
            $validator = validator($request->all(), [
                'name' => 'required|string|max:255',
                'slug' => 'required|string|max:255|unique:products,slug,' . $product->id,
                'description' => 'nullable|string',
                'category_id' => 'nullable|exists:categories,id',
                'price' => 'required|numeric|min:0',
                'discount_percent' => 'nullable|numeric|min:0|max:99.99',
                'discount_price' => 'nullable|numeric|min:0',
                'stock' => 'nullable|integer|min:0',
                'images' => 'nullable|array',
                'images.*' => 'nullable|file|mimes:jpg,jpeg,png,gif,webp,heic,heif,avif|max:5120',

                // Variations
                'variations' => 'nullable|array',
                'variations.*.id' => 'nullable|exists:product_variations,id',
                'variations.*.attributes' => 'required|array',
                'variations.*.price' => 'nullable|numeric|min:0',
                'variations.*.stock' => 'required|integer|min:0',
                'variations.*.is_active' => 'nullable|boolean',
            ], $this->productValidationMessages(), $this->productValidationAttributes());

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $data = $validator->validated();
            $resolvedDiscountPrice = $this->resolveDiscountPrice($data);

            $payload = [
                'name' => $data['name'],
                'slug' => $data['slug'],
                'description' => $data['description'] ?? null,
                'category_id' => $data['category_id'] ?? null,
                'price' => $data['price'],
                'stock' => $data['stock'] ?? 0,
            ];

            if (Schema::hasColumn('products', 'discount_price')) {
                $payload['discount_price'] = $resolvedDiscountPrice;
            }

            $product->update($payload);
            $warnings = [];
            $imageIssue = false;
            $variationIssue = false;

            // Images
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $file) {
                    try {
                        $path = $file->store('product_images', 'public');
                        ProductImage::create([
                            'product_id' => $product->id,
                            'path' => $path
                        ]);
                    } catch (\Throwable $imageException) {
                        report($imageException);
                        $imageIssue = true;
                    }
                }
            }

            // Variations
            if (!empty($data['variations'])) {
                foreach ($data['variations'] as $variationData) {
                    try {
                        if (!empty($variationData['id'])) {
                            // Update existing
                            $variation = ProductVariation::find($variationData['id']);
                            if ($variation) {
                                $variation->update([
                                    'attributes' => $variationData['attributes'],
                                    'price' => $variationData['price'] ?? null,
                                    'stock' => $variationData['stock'],
                                    'is_active' => $variationData['is_active'] ?? true,
                                ]);
                            }
                        } else {
                            // New variation
                            $sku = $product->slug . '-' . strtoupper(substr(md5(json_encode($variationData['attributes'])), 0, 6));
                            $product->variations()->create([
                                'sku' => $sku,
                                'attributes' => $variationData['attributes'],
                                'price' => $variationData['price'] ?? null,
                                'stock' => $variationData['stock'],
                                'is_active' => $variationData['is_active'] ?? true,
                            ]);
                        }
                    } catch (\Throwable $variationException) {
                        report($variationException);
                        $variationIssue = true;
                    }
                }
            }

            DB::commit();

            if ($imageIssue) {
                $warnings[] = 'Certaines images n ont pas pu etre enregistrees.';
            }

            if ($variationIssue) {
                $warnings[] = 'Certaines variations n ont pas pu etre enregistrees.';
            }

            if (!empty($warnings)) {
                return redirect()->to(route('admin.products.index', [], false))
                    ->with('status', 'Produit mis a jour avec succes')
                    ->with('warning', implode(' ', $warnings));
            }

            return redirect()->to(route('admin.products.index', [], false))->with('status', 'Produit mis a jour avec succes');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur mise a jour produit', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->with('error', 'Erreur lors de la mise a jour')->withInput();
        }
    }

    public function destroy(Product $product)
    {
        try {
            foreach ($product->images as $image) {
                if ($image->path) Storage::disk('public')->delete($image->path);
                $image->delete();
            }

            $product->delete();
            return redirect()->to(route('admin.products.index', [], false))->with('status', 'Produit supprime avec succes');
        } catch (\Exception $e) {
            Log::error('Erreur suppression produit', ['error' => $e->getMessage()]);
            return back()->with('error', 'Erreur lors de la suppression du produit');
        }
    }

    public function destroyImage(Product $product, ProductImage $image)
    {
        if ($image->product_id !== $product->id) abort(404);

        try {
            if ($image->path) Storage::disk('public')->delete($image->path);
            $image->delete();
            return back()->with('status', 'Image supprimee avec succes');
        } catch (\Exception $e) {
            Log::error('Erreur suppression image', ['error' => $e->getMessage()]);
            return back()->with('error', 'Erreur lors de la suppression de l\'image');
        }
    }

    public function show(Product $product)
    {
        $product->load('images', 'category');

        $totalQty = OrderItem::where('product_id', $product->id)->sum('qty');
        $totalRevenue = OrderItem::where('product_id', $product->id)
            ->selectRaw('SUM(qty * unit_price) as revenue')->value('revenue') ?? 0;

        $recentItems = OrderItem::with('order')->where('product_id', $product->id)->latest()->take(5)->get();

        $salesSummary = [
            'total_qty' => (int) $totalQty,
            'total_revenue' => (float) $totalRevenue,
            'recent' => $recentItems,
            'orders_count' => OrderItem::where('product_id', $product->id)->distinct('order_id')->count('order_id')
        ];

        return view('admin.products.show', compact('product', 'salesSummary'));
    }

    // Variations endpoints
    public function storeVariation(Request $request, Product $product)
    {
        $data = $request->validate([
            'attributes' => 'required|array',
            'sku' => 'nullable|string|max:255|unique:product_variations,sku',
            'price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'is_active' => 'boolean'
        ]);

        if (empty($data['sku'])) {
            $data['sku'] = $product->slug . '-' . strtoupper(substr(md5(json_encode($data['attributes'])), 0, 6));
        }

        $product->variations()->create([
            'sku' => $data['sku'],
            'attributes' => $data['attributes'],
            'price' => $data['price'] ?? null,
            'stock' => $data['stock'],
            'is_active' => $request->has('is_active')
        ]);

        return redirect()->back()->with('status', 'Variation creee avec succes');
    }

    public function updateVariation(Request $request, ProductVariation $variation)
    {
        $data = $request->validate([
            'sku' => 'nullable|string|max:255|unique:product_variations,sku,' . $variation->id,
            'price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'is_active' => 'boolean'
        ]);

        $variation->update([
            'sku' => $data['sku'] ?? $variation->sku,
            'price' => $data['price'] ?? null,
            'stock' => $data['stock'],
            'is_active' => $request->has('is_active')
        ]);

        return redirect()->back()->with('status', 'Variation mise a jour');
    }

    public function destroyVariation(ProductVariation $variation)
    {
        $variation->delete();
        return redirect()->back()->with('status', 'Variation supprimee');
    }

    private function makeUniqueSlug(string $baseSlug): string
    {
        $baseSlug = Str::slug($baseSlug);
        if ($baseSlug === '') {
            $baseSlug = 'produit';
        }

        $slug = $baseSlug;
        $suffix = 2;

        while (Product::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $suffix;
            $suffix++;
        }

        return $slug;
    }

    private function resolveDiscountPrice(array $data): ?float
    {
        $price = (float) ($data['price'] ?? 0);

        $discountPercent = array_key_exists('discount_percent', $data) && $data['discount_percent'] !== null && $data['discount_percent'] !== ''
            ? (float) $data['discount_percent']
            : null;

        $discountPrice = array_key_exists('discount_price', $data) && $data['discount_price'] !== null && $data['discount_price'] !== ''
            ? (float) $data['discount_price']
            : null;

        if ($price <= 0) {
            return null;
        }

        if ($discountPercent !== null && $discountPercent > 0) {
            if ($discountPercent >= 100) {
                throw ValidationException::withMessages([
                    'discount_percent' => 'La reduction doit etre strictement inferieure a 100%.',
                ]);
            }

            $computed = round($price * (1 - ($discountPercent / 100)), 2);
            if ($computed <= 0 || $computed >= $price) {
                throw ValidationException::withMessages([
                    'discount_percent' => 'Le pourcentage de reduction est invalide pour ce prix.',
                ]);
            }

            return $computed;
        }

        if ($discountPrice !== null && $discountPrice > 0) {
            if ($discountPrice >= $price) {
                throw ValidationException::withMessages([
                    'discount_price' => 'Le prix promotionnel doit etre inferieur au prix normal.',
                ]);
            }

            return round($discountPrice, 2);
        }

        return null;
    }

    private function normalizeProductAttributes(mixed $rawAttributes): array
    {
        if (!is_array($rawAttributes)) {
            return [];
        }

        $normalized = [];

        foreach ($rawAttributes as $attributeId => $value) {
            if (is_array($value)) {
                $cleanValues = collect($value)
                    ->map(fn ($item) => trim((string) $item))
                    ->filter(fn ($item) => $item !== '')
                    ->unique()
                    ->values()
                    ->all();

                if (!empty($cleanValues)) {
                    $normalized[(string) $attributeId] = $cleanValues;
                }

                continue;
            }

            $cleanValue = trim((string) $value);
            if ($cleanValue !== '') {
                $normalized[(string) $attributeId] = $cleanValue;
            }
        }

        return $normalized;
    }

    private function productValidationMessages(): array
    {
        return [
            'slug.unique' => 'Ce slug existe deja. Utilisez un slug different.',
            'images.array' => 'Le champ images doit contenir une liste de fichiers.',
            'images.*.file' => 'Un des fichiers selectionnes est invalide.',
            'images.*.mimes' => 'Chaque image doit etre au format JPG, PNG, GIF, WEBP, HEIC, HEIF ou AVIF.',
            'images.*.max' => 'Chaque image ne doit pas depasser 5 Mo.',
            'discount_percent.max' => 'La reduction ne peut pas depasser 99,99%.',
        ];
    }

    private function productValidationAttributes(): array
    {
        return [
            'images' => 'images du produit',
            'images.*' => 'image du produit',
            'discount_percent' => 'pourcentage de reduction',
            'discount_price' => 'prix promotionnel',
        ];
    }
}

