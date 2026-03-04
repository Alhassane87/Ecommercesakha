<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductVariation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    protected function getCart(): Cart
    {
        $user = Auth::user();

        if ($user) {
            return Cart::firstOrCreate(['user_id' => $user->id]);
        }

        // fallback: temporary cart per session
        $cart = session('cart_id') ? Cart::find(session('cart_id')) : null;
        if (! $cart) {
            $cart = Cart::create();
            session(['cart_id' => $cart->id]);
        }

        return $cart;
    }

    public function index(Request $request)
    {
        // Si c'est une requête POST, ajouter le produit au panier
        if ($request->isMethod('POST')) {
            $request->validate([
                'product_id' => 'required|exists:products,id',
                'qty' => 'required|integer|min:1',
                'variation_id' => 'nullable|exists:product_variations,id',
                'attributes' => 'nullable'
            ]);

            $rawAttributes = $request->input('attributes', []);
            if (is_string($rawAttributes) && $rawAttributes !== '') {
                $decoded = json_decode($rawAttributes, true);
                $attributes = (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) ? $decoded : [];
            } elseif (is_array($rawAttributes)) {
                $attributes = $rawAttributes;
            } else {
                $attributes = [];
            }

            $product = Product::findOrFail($request->product_id);
            $cart = $this->getCart();
            $qty = $request->input('qty', 1);
            
            $variation = null;
            $selectedAttributes = $attributes;
            $unitPrice = $product->getFinalPrice($qty);
            
            if ($request->has('variation_id') && $request->variation_id) {
                $variation = ProductVariation::findOrFail($request->variation_id);
                if ($variation->product_id !== $product->id) {
                    if ($request->expectsJson()) {
                        return response()->json(['error' => 'Variation invalide pour ce produit.'], 422);
                    }
                    return back()->with('error', 'Variation invalide pour ce produit.');
                }
                $unitPrice = $variation->getFinalPriceAttribute();
                $selectedAttributes = $variation->attributes;
            } elseif (!empty($selectedAttributes)) {
                $variation = $product->getVariationByAttributes($selectedAttributes);
                if ($variation) {
                    $unitPrice = $variation->getFinalPriceAttribute();
                }
            }

            if ($variation) {
                if ($variation->stock < $qty) {
                    if ($request->expectsJson()) {
                        return response()->json(['error' => 'Stock insuffisant pour cette variation.'], 422);
                    }
                    return back()->with('error', 'Stock insuffisant pour cette variation.');
                }
            } else {
                if ($product->stock < $qty) {
                    if ($request->expectsJson()) {
                        return response()->json(['error' => 'Stock insuffisant.'], 422);
                    }
                    return back()->with('error', 'Stock insuffisant.');
                }
            }

            $item = $cart->items()
                ->where('product_id', $product->id)
                ->where('product_variation_id', $variation?->id)
                ->where('selected_attributes', json_encode($selectedAttributes))
                ->first();

            if ($item) {
                $item->qty += $qty;
                $item->unit_price = $unitPrice;
                $item->save();
            } else {
                $cart->items()->create([
                    'product_id' => $product->id,
                    'product_variation_id' => $variation?->id,
                    'selected_attributes' => $selectedAttributes,
                    'qty' => $qty,
                    'unit_price' => $unitPrice,
                ]);
            }

            // Retourner une réponse JSON pour les requêtes AJAX
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Produit ajouté au panier avec succès',
                    'cart_count' => $cart->items()->sum('qty'),
                    'product_name' => $product->name
                ]);
            }
        }

        // Afficher le panier (GET ou après POST traditionnel)
        $cart = $this->getCart();
        $cart->load('items.product.images', 'items.variation');

        return view('cart.index', compact('cart'));
    }

    public function update(Request $request, CartItem $item)
    {
        // Récupérer le panier actuel de l'utilisateur
        $currentCart = $this->getCart();
        
        // Vérifier que l'article appartient bien au panier actuel
        if ($item->cart_id !== $currentCart->id) {
            abort(403, 'Vous ne pouvez pas modifier cet article.');
        }

        $request->validate([
            'action' => 'required|in:increase,decrease'
        ]);

        // Modifier la quantité selon l'action
        if ($request->action === 'increase') {
            $item->qty += 1;
        } elseif ($request->action === 'decrease') {
            // Ne pas descendre en dessous de 1
            $item->qty = max(1, $item->qty - 1);
        }

        $item->save();

        return redirect()->route('cart.index')->with('success', 'Quantité mise à jour !');
    }

    public function remove(CartItem $item)
    {
        // Récupérer le panier actuel de l'utilisateur
        $currentCart = $this->getCart();
        
        // Vérifier que l'article appartient bien au panier actuel
        if ($item->cart_id !== $currentCart->id) {
            abort(403, 'Vous ne pouvez pas supprimer cet article.');
        }

        $item->delete();

        return redirect()->route('cart.index')->with('success', 'Produit retiré du panier !');
    }
}
                