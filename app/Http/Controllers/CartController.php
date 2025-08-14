<?php
// app/Http/Controllers/CartController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    // Afficher le panier
    public function index()
    {
        $cartItems = CartItem::with(['product', 'variant'])
                        ->where('user_id', Auth::id())
                        ->get();
        return view('shop.cart', compact('cartItems'));
    }

    // Ajouter un produit au panier
    public function add(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Vous devez être connecté pour ajouter au panier'], 401);
        }

        $request->validate([
            'product_id' => 'required|exists:products,id',
            'variant_id' => 'nullable|exists:product_variants,id',
            'quantity'   => 'required|integer|min:1'
        ]);

        // Récupérer le prix du produit ou de la variante
        $price = null;

        if ($request->variant_id) {
            $variant = ProductVariant::find($request->variant_id);
            $price = $variant->price ?? 0;
        } else {
            $product = Product::find($request->product_id);
            $price = $product->discount_price ?? $product->price ?? 0;
        }

        $cartItem = CartItem::where('user_id', Auth::id())
            ->where('product_id', $request->product_id)
            ->where('variant_id', $request->variant_id)
            ->first();

        if ($cartItem) {
            $cartItem->quantity += $request->quantity;
            $cartItem->price = $price; // Mettre à jour le prix
            $cartItem->save();
        } else {
            CartItem::create([
                'user_id'    => Auth::id(),
                'product_id' => $request->product_id,
                'variant_id' => $request->variant_id,
                'quantity'   => $request->quantity,
                'price'      => $price
            ]);
        }

        return response()->json(['message' => 'Produit ajouté au panier avec succès']);
    }

    // Mettre à jour la quantité
    public function update(Request $request, $id)
    {
        $request->validate(['quantity' => 'required|integer|min:1']);

        $cartItem = CartItem::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $cartItem->quantity = $request->quantity;
        $cartItem->save();

        return response()->json(['message' => 'Quantité mise à jour']);
    }

    // Supprimer un produit du panier
    public function destroy($id)
    {
        $cartItem = CartItem::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $cartItem->delete();

        return response()->json(['message' => 'Produit supprimé du panier']);
    }

    // Vider le panier
    public function clear()
    {
        CartItem::where('user_id', Auth::id())->delete();
        return response()->json(['message' => 'Panier vidé avec succès']);
    }
}
