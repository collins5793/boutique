<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\DirectSale;
use App\Models\DirectSaleItem;

class DirectSaleCartController extends Controller
{
    // // Ajouter un produit dans le panier temporaire de vente
    // public function add(Request $request)
    // {
    //     $request->validate([
    //         'product_id' => 'required|exists:products,id',
    //         'quantity'   => 'required|integer|min:1',
    //     ]);

    //     $employeeId = Auth::id(); // employé connecté
    //     $product = Product::findOrFail($request->product_id);
    //     $price = $product->discount_price ?? $product->price ?? 0;

    //     // on peut gérer un panier temporaire en session avant validation
    //     $cart = session()->get("direct_sale_cart.$employeeId", []);

    //     if (isset($cart[$product->id])) {
    //         $cart[$product->id]['quantity'] += $request->quantity;
    //         $cart[$product->id]['total_price'] = $cart[$product->id]['quantity'] * $cart[$product->id]['unit_price'];
    //     } else {
    //         $cart[$product->id] = [
    //             'product_id' => $product->id,
    //             'name' => $product->name,
    //             'quantity' => $request->quantity,
    //             'unit_price' => $price,
    //             'total_price' => $request->quantity * $price,
    //         ];
    //     }

    //     session()->put("direct_sale_cart.$employeeId", $cart);

    //     return response()->json(['message' => 'Produit ajouté au panier de vente', 'cart' => $cart]);
    // }

    // // Mettre à jour quantité
    // public function update(Request $request, $productId)
    // {
    //     $request->validate([
    //         'action' => 'nullable|string',
    //         'quantity' => 'nullable|integer|min:1'
    //     ]);

    //     $employeeId = Auth::id();
    //     $cart = session()->get("direct_sale_cart.$employeeId", []);

    //     if (!isset($cart[$productId])) {
    //         return response()->json(['message' => 'Produit introuvable dans le panier'], 404);
    //     }

    //     if ($request->action === 'increase') {
    //         $cart[$productId]['quantity'] += 1;
    //     } elseif ($request->action === 'decrease') {
    //         $cart[$productId]['quantity'] = max(1, $cart[$productId]['quantity'] - 1);
    //     } elseif ($request->quantity) {
    //         $cart[$productId]['quantity'] = $request->quantity;
    //     }

    //     $cart[$productId]['total_price'] = $cart[$productId]['quantity'] * $cart[$productId]['unit_price'];

    //     session()->put("direct_sale_cart.$employeeId", $cart);

    //     return response()->json([
    //         'message' => 'Quantité mise à jour',
    //         'cart' => $cart
    //     ]);
    // }

    // // Supprimer un produit du panier
    // public function destroy($productId)
    // {
    //     $employeeId = Auth::id();
    //     $cart = session()->get("direct_sale_cart.$employeeId", []);

    //     if (isset($cart[$productId])) {
    //         unset($cart[$productId]);
    //         session()->put("direct_sale_cart.$employeeId", $cart);
    //     }

    //     return response()->json(['message' => 'Produit supprimé du panier', 'cart' => $cart]);
    // }

    // // Vider le panier
    // public function clear()
    // {
    //     $employeeId = Auth::id();
    //     session()->forget("direct_sale_cart.$employeeId");

    //     return response()->json(['message' => 'Panier de vente vidé']);
    // }

    // Valider la vente et enregistrer dans la BDD
    public function checkout(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|string'
        ]);

        $employeeId = Auth::id();
        $cart = session()->get("direct_sale_cart.$employeeId", []);

        if (empty($cart)) {
            return response()->json(['message' => 'Le panier est vide'], 400);
        }

        $total = array_sum(array_column($cart, 'total_price'));

        // Créer la vente
        $sale = DirectSale::create([
            'employee_id' => $employeeId,
            'total' => $total,
            'payment_method' => $request->payment_method,
        ]);

        // Insérer les items
        foreach ($cart as $item) {
            DirectSaleItem::create([
                'direct_sale_id' => $sale->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'total_price' => $item['total_price'],
            ]);
        }

        // Vider le panier session
        session()->forget("direct_sale_cart.$employeeId");

        return response()->json(['message' => 'Vente validée avec succès', 'sale' => $sale]);
    }
}