<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\CategoryAttribute;
use App\Models\CategoryAttributeValue;
use App\Services\CategoryAttributeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class CategoryController extends Controller
{
    // Public pages
    public function publicIndex()
    {
        $hasIsActive = Schema::hasColumn('categories', 'is_active');

        $categories = Category::when($hasIsActive, function ($query) {
                return $query->where('is_active', true);
            })
            ->withCount(['products' => function ($query) use ($hasIsActive) {
                if ($hasIsActive && Schema::hasColumn('products', 'is_active')) {
                    $query->where('is_active', true);
                }
            }])
            ->orderBy('name')
            ->get();

        return view('admin.categories.public-index', compact('categories'));
    }

    public function publicShow($slug)
    {
        $hasIsActive = Schema::hasColumn('categories', 'is_active');

        $category = Category::when($hasIsActive, function ($query) {
                return $query->where('is_active', true);
            })
            ->where('slug', $slug)
            ->firstOrFail();

        $products = Product::with('images')
            ->where('category_id', $category->id)
            ->when($hasIsActive && Schema::hasColumn('products', 'is_active'), function ($query) {
                return $query->where('is_active', true);
            })
            ->paginate(12);

        return view('admin.categories.public-show', compact('category', 'products'));
    }

    // Admin pages
    public function index()
    {
        $categories = Category::with(['children', 'parent'])
            ->withCount(['products', 'children'])
            ->orderBy('name')
            ->paginate(30);

        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        $parents = Category::orderBy('name')->get();
        return view('admin.categories.create', compact('parents'));
    }

    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'slug' => 'required|string|max:255|unique:categories,slug',
                'parent_id' => 'nullable|exists:categories,id',
                'description' => 'nullable|string|max:2000',
                'icon' => 'nullable|string|max:255',
            ]);

            if (Schema::hasColumn('categories', 'is_active')) {
                $data['is_active'] = $request->has('is_active') ? $request->boolean('is_active') : true;
            }

            $category = Category::create($data);

            // Set sensible default attributes based on category context, without blocking creation.
            try {
                CategoryAttributeService::setDefaultAttributesForCategory($category);
            } catch (\Throwable $attributeException) {
                report($attributeException);

                return redirect()
                    ->route('admin.categories.index')
                    ->with('warning', 'Categorie creee, mais les attributs automatiques n ont pas pu etre ajoutes.');
            }

            return redirect()->route('admin.categories.index')->with('success', 'Categorie creee avec succes.');
        } catch (\Throwable $e) {
            report($e);
            return redirect()->back()->withInput()->with('error', 'Erreur lors de la creation de la categorie.');
        }
    }

    public function edit(Category $category)
    {
        $parents = Category::where('id', '!=', $category->id)->orderBy('name')->get();
        $category->load('attributes.values');

        return view('admin.categories.edit', compact('category', 'parents'));
    }

    public function update(Request $request, Category $category)
    {
        try {
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'slug' => 'required|string|max:255|unique:categories,slug,' . $category->id,
                'parent_id' => 'nullable|exists:categories,id',
                'description' => 'nullable|string|max:2000',
                'icon' => 'nullable|string|max:255',
            ]);

            if (Schema::hasColumn('categories', 'is_active')) {
                $data['is_active'] = $request->boolean('is_active');
            }

            $category->update($data);

            try {
                CategoryAttributeService::setDefaultAttributesForCategory($category->fresh());
            } catch (\Throwable $attributeException) {
                report($attributeException);

                return redirect()
                    ->route('admin.categories.index')
                    ->with('warning', 'Categorie mise a jour, mais les attributs automatiques n ont pas pu etre ajoutes.');
            }

            return redirect()->route('admin.categories.index')->with('success', 'Categorie mise a jour avec succes.');
        } catch (\Throwable $e) {
            report($e);
            return redirect()->back()->withInput()->with('error', 'Erreur lors de la mise a jour de la categorie.');
        }
    }

    public function destroy(Category $category)
    {
        try {
            if ($category->products()->exists()) {
                return redirect()->back()->with('error', 'Impossible de supprimer: des produits sont associes a cette categorie.');
            }

            $category->delete();

            return redirect()->route('admin.categories.index')->with('success', 'Categorie supprimee avec succes.');
        } catch (\Throwable $e) {
            report($e);
            return redirect()->back()->with('error', 'Erreur lors de la suppression de la categorie.');
        }
    }

    public function show(Category $category)
    {
        $products = $category->products()->with('images')->paginate(10);
        return view('admin.categories.show', compact('category', 'products'));
    }

    // Attribute management
    public function storeAttribute(Request $request, Category $category)
    {
        try {
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'type' => 'required|in:select,text,number',
                'is_required' => 'boolean',
                'values' => 'nullable',
            ]);

            $attribute = $category->attributes()->create([
                'name' => $data['name'],
                'type' => $data['type'],
                'is_required' => $request->has('is_required'),
                'sort_order' => $category->attributes()->count() + 1,
            ]);

            $rawValues = $request->input('values', []);
            $values = [];

            if (is_string($rawValues)) {
                $values = preg_split('/\r\n|\r|\n/', $rawValues) ?: [];
            } elseif (is_array($rawValues)) {
                $values = $rawValues;
            }

            $values = collect($values)
                ->map(fn($value) => trim((string) $value))
                ->filter()
                ->values()
                ->all();

            if (!empty($values)) {
                foreach ($values as $index => $value) {
                    $attribute->values()->create([
                        'value' => $value,
                        'display_value' => $value,
                        'sort_order' => $index,
                    ]);
                }
            }

            return redirect()->back()->with('success', 'Attribut cree avec succes.');
        } catch (\Throwable $e) {
            report($e);
            return redirect()->back()->withInput()->with('error', 'Erreur lors de la creation de l attribut.');
        }
    }

    public function updateAttribute(Request $request, CategoryAttribute $attribute)
    {
        try {
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'type' => 'required|in:select,text,number',
                'is_required' => 'boolean',
            ]);

            $attribute->update([
                'name' => $data['name'],
                'type' => $data['type'],
                'is_required' => $request->has('is_required'),
            ]);

            return redirect()->back()->with('success', 'Attribut mis a jour avec succes.');
        } catch (\Throwable $e) {
            report($e);
            return redirect()->back()->withInput()->with('error', 'Erreur lors de la mise a jour de l attribut.');
        }
    }

    public function destroyAttribute(CategoryAttribute $attribute)
    {
        try {
            $attribute->delete();
            return redirect()->back()->with('success', 'Attribut supprime avec succes.');
        } catch (\Throwable $e) {
            report($e);
            return redirect()->back()->with('error', 'Erreur lors de la suppression de l attribut.');
        }
    }

    public function storeAttributeValue(Request $request, CategoryAttribute $attribute)
    {
        try {
            $data = $request->validate([
                'value' => 'required|string|max:255',
                'display_value' => 'nullable|string|max:255',
                'color_code' => 'nullable|string|max:7',
            ]);

            $attribute->values()->create([
                'value' => $data['value'],
                'display_value' => $data['display_value'] ?? $data['value'],
                'color_code' => $data['color_code'] ?? null,
                'sort_order' => $attribute->values()->count(),
            ]);

            return redirect()->back()->with('success', 'Valeur ajoutee avec succes.');
        } catch (\Throwable $e) {
            report($e);
            return redirect()->back()->withInput()->with('error', 'Erreur lors de l ajout de la valeur.');
        }
    }

    public function destroyAttributeValue(CategoryAttributeValue $value)
    {
        try {
            $value->delete();
            return redirect()->back()->with('success', 'Valeur supprimee avec succes.');
        } catch (\Throwable $e) {
            report($e);
            return redirect()->back()->with('error', 'Erreur lors de la suppression de la valeur.');
        }
    }

    /**
     * Get category attributes as JSON (for product creation form)
     */
    public function getAttributesJson(Category $category)
    {
        $attributes = $category->attributes()
            ->with('values')
            ->orderBy('sort_order')
            ->get()
            ->map(function ($attr) {
                return [
                    'id' => $attr->id,
                    'name' => $attr->name,
                    'type' => $attr->type,
                    'is_required' => $attr->is_required,
                    'values' => $attr->values->map(fn($v) => [
                        'id' => $v->id,
                        'value' => $v->value,
                        'display_value' => $v->display_value ?? $v->value,
                        'color_code' => $v->color_code,
                    ]),
                ];
            });

        return response()->json([
            'attributes' => $attributes,
            'has_required' => $attributes->where('is_required', true)->isNotEmpty(),
        ]);
    }
}
