<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{

    public function index()
    {
        $orders = Order::where('user_id', Auth::id())
                        ->orderBy('created_at', 'desc')
                        ->get();

        return view('shop.orders', compact('orders'));
    }

    // Détails d'une commande
    public function show($id)
    {
        $order = Order::with(['items.product'])->where('id', $id)
                    ->where('user_id', Auth::id())
                    ->firstOrFail();

        return response()->json($order);
    }
    public function store(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|in:mobile_money,card,cash_on_delivery',
            'delivery_address_id' => 'required|exists:delivery_addresses,id',
        ]);

        $userId = Auth::id();
        $cartItems = CartItem::where('user_id', $userId)->get();

        if ($cartItems->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'Votre panier est vide']);
        }

        DB::beginTransaction();

        try {
            // Calcul total
            $totalAmount = $cartItems->sum(fn($item) => $item->price * $item->quantity);

            // Création de la commande avec l'adresse de livraison
            $order = Order::create([
                'user_id' => $userId,
                'delivery_address_id' => $request->delivery_address_id,
                'order_number' => 'ORD-' . strtoupper(Str::random(8)),
                'total_amount' => $totalAmount,
                'payment_status' => 'pending',
                'order_status' => 'pending',
                'payment_method' => $request->payment_method
            ]);

            // Enregistrer chaque ligne de commande
            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'total' => $item->price * $item->quantity
                ]);

                // Déduire le stock
                if ($item->variant_id) {
                    $variant = ProductVariant::find($item->variant_id);
                    if($variant){
                        $variant->stock_quantity -= $item->quantity;
                        $variant->save();
                    }
                } else {
                    $product = Product::find($item->product_id);
                    if($product){
                        $product->stock_quantity -= $item->quantity;
                        $product->save();
                    }
                }
            }

            // Vider le panier
            CartItem::where('user_id', $userId)->delete();

            // Notification
            Notification::create([
                'user_id' => $userId,
                'title' => 'Nouvelle commande',
                'content' => 'Votre commande ' . $order->order_number . ' a été enregistrée.',
                'type' => 'order'
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Commande passée avec succès',
                'order_id' => $order->id
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Erreur : ' . $e->getMessage()
            ]);
        }
    }

}
