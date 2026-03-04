<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    public function index()
    {
        $promotions = Promotion::with(['category', 'product'])->latest()->paginate(20);
        return view('admin.promotions.index', compact('promotions'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        $products = Product::orderBy('name')->get();
        return view('admin.promotions.create', compact('categories', 'products'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
            'category_id' => 'nullable|exists:categories,id',
            'product_id' => 'nullable|exists:products,id',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after:starts_at',
            'is_active' => 'boolean',
            'min_quantity' => 'nullable|integer|min:1'
        ]);

        // Vérifier qu'au moins category_id ou product_id est fourni
        if (empty($data['category_id']) && empty($data['product_id'])) {
            return redirect()->back()
                ->withErrors(['category_id' => 'Vous devez sélectionner une catégorie ou un produit'])
                ->withInput();
        }

        Promotion::create($data);

        return redirect()->route('admin.promotions.index')
            ->with('status', 'Promotion créée avec succès');
    }

    public function edit(Promotion $promotion)
    {
        $categories = Category::orderBy('name')->get();
        $products = Product::orderBy('name')->get();
        return view('admin.promotions.edit', compact('promotion', 'categories', 'products'));
    }

    public function update(Request $request, Promotion $promotion)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
            'category_id' => 'nullable|exists:categories,id',
            'product_id' => 'nullable|exists:products,id',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after:starts_at',
            'is_active' => 'boolean',
            'min_quantity' => 'nullable|integer|min:1'
        ]);

        // Vérifier qu'au moins category_id ou product_id est fourni
        if (empty($data['category_id']) && empty($data['product_id'])) {
            return redirect()->back()
                ->withErrors(['category_id' => 'Vous devez sélectionner une catégorie ou un produit'])
                ->withInput();
        }

        $promotion->update($data);

        return redirect()->route('admin.promotions.index')
            ->with('status', 'Promotion mise à jour avec succès');
    }

    public function destroy(Promotion $promotion)
    {
        $promotion->delete();
        return redirect()->route('admin.promotions.index')
            ->with('status', 'Promotion supprimée');
    }
}

