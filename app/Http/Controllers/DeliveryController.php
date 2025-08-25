<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Delivery;
use App\Models\OrderItem;
use App\Models\Notification;
use App\Models\DeliveryAddress;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests; // ← ajouter ceci


class DeliveryController extends Controller
{
    // Liste des commandes pending
    public function pendingOrders()
    {
        $orders = Order::with([
            'user:id,name',
            'orderItems.product:id,name',
            // on ne sélectionne que ce qu'il faut
            'deliveryAddress:id,order_id,address_type,full_address,latitude,longitude',
        ])
        ->where('order_status', 'pending')
        ->latest()
        ->get();

        return view('delivery.pending_orders', compact('orders'));
    }


    // Marquer comme livré
    public function markDelivered($orderId)
    {
        $order = Order::findOrFail($orderId);
        $order->order_status = 'delivered';
        $order->save();

        return response()->json(['success' => true, 'message' => 'Commande livrée !']);
    }

    public function startDelive(Order $order)
    {
        // Si le paiement est encore pending → valider
        // if($order->payment_status === 'pending') {
        //     $order->payment_status = 'paid';
        // }

        // // Mise à jour du statut de la commande
        // $order->order_status = 'processing';
        // $order->save();

        // Créer la livraison
        $delivery = Delivery::create([
            'order_id' => $order->id,
            'delivery_person_id' => Auth::id(),
            'status' => 'in_transit',
            'tracking_number' => strtoupper(Str::random(10))
        ]);

        // Notification au client
        Notification::create([
            'user_id' => $order->user_id,
            'title' => "Votre commande est en livraison 🚚",
            'content' => "La commande {$order->order_number} est en cours de livraison. Veuillez confirmer sa réception une fois livrée.",
            'type' => 'order'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Livraison démarrée avec succès.',
            'delivery_id' => $delivery->id,
            'redirect_url' => route('delivery.tracki', $order->id),
        ]);
    }

    public function startDelivery(Order $order)
    {
        // Si le paiement est encore pending → valider
        // if($order->payment_status === 'pending') {
        //     $order->payment_status = 'paid';
        // }

        // Mise à jour du statut de la commande
        $order->order_status = 'processing';
        $order->save();

        // Créer la livraison
        $delivery = Delivery::create([
            'order_id' => $order->id,
            'delivery_person_id' => Auth::id(),
            'status' => 'in_transit',
            'tracking_number' => strtoupper(Str::random(10))
        ]);

        // Notification au client
        Notification::create([
            'user_id' => $order->user_id,
            'title' => "Votre commande est en livraison 🚚",
            'content' => "La commande {$order->order_number} est en cours de livraison. Veuillez confirmer sa réception une fois livrée.",
            'type' => 'order'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Livraison démarrée avec succès.',
            'delivery_id' => $delivery->id,
            'redirect_url' => route('delivery.tracking', $order->id),
        ]);
    }

    public function FinDelivery(Order $order)
    {
        // Vérifier le paiement : si toujours pending → on le marque payé
        if ($order->payment_status === 'pending') {
            $order->payment_status = 'paid';
        }

        // Mettre à jour le statut de la commande
        $order->order_status = 'shipped';
        $order->save();

        // Mettre à jour la livraison existante ou créer si elle n'existe pas
        $delivery = $order->deliveries()->first();
        if (!$delivery) {
            $delivery = $order->deliveries()->create([
                'delivery_person_id' => Auth::id(), // livreur connecté
                'status' => 'delivered',
                'delivered_at' => now(),
            ]);
        } else {
            $delivery->status = 'delivered';
            $delivery->delivered_at = now();
            $delivery->save();
        }

        // Envoyer une notification au client pour validation
        \App\Models\Notification::create([
            'user_id' => $order->user_id,
            'title' => "Commande livrée ✅",
            'content' => "Votre commande {$order->order_number} a été livrée. Veuillez confirmer la réception.",
            'type' => 'system'
        ]);

        return redirect()->route('delivery.tracking', $order->id)
                        ->with('success', 'Livraison finalisée avec succès !');
    }
    public function FinDelive(Order $order)
    {
        // Vérifier le paiement : si toujours pending → on le marque payé
        if ($order->payment_status === 'pending') {
            $order->payment_status = 'paid';
        }

        // Mettre à jour le statut de la commande
        $order->order_status = 'shipped';
        $order->save();

        // Mettre à jour la livraison existante ou créer si elle n'existe pas
        $delivery = $order->deliveries()->first();
        if (!$delivery) {
            $delivery = $order->deliveries()->create([
                'delivery_person_id' => Auth::id(), // livreur connecté
                'status' => 'delivered',
                'delivered_at' => now(),
            ]);
        } else {
            $delivery->status = 'delivered';
            $delivery->delivered_at = now();
            $delivery->save();
        }

        // Envoyer une notification au client pour validation
        \App\Models\Notification::create([
            'user_id' => $order->user_id,
            'title' => "Commande livrée ✅",
            'content' => "Votre commande {$order->order_number} a été livrée. Veuillez confirmer la réception.",
            'type' => 'system'
        ]);

        return redirect()->route('delivery.tracki', $order->id)
                        ->with('success', 'Livraison finalisée avec succès !');
    }


// Confirmation par le client
    

public function valideDelivery(Order $order)
{
    // Vérifier si la commande a bien le statut "shipped"
    if ($order->order_status !== 'shipped') {
        return redirect()->back()->with('error', "Cette commande n'est pas encore validée par le livreur. ⏳");
    }

    // Mettre à jour le statut de la commande en "delivered"
    $order->order_status = 'delivered';
    $order->save();

    // Notification au client
    Notification::create([
        'user_id' => $order->user_id,
        'title' => "Commande livrée ✅",
        'content' => "Votre commande {$order->order_number} a été confirmée comme livrée.",
        'type' => 'system'
    ]);

    return redirect()->back()->with('success', 'Livraison confirmée avec succès !');
}


    // Confirmation par le client
   


    public function tracking(Order $order)
    {
        // Charger toutes les infos utiles avec les relations
        $order->load(['deliveryAddress', 'user']);

        if (!$order->deliveryAddress || !$order->deliveryAddress->latitude || !$order->deliveryAddress->longitude) {
            abort(404, "Adresse de livraison invalide.");
        }

        return view('delivery.tracking', compact('order'));
}

    public function tracki(Order $order)
    {
        // Charger toutes les infos utiles avec les relations
        $order->load(['deliveryAddress', 'user']);

        return view('delivery.tracki', compact('order'));
}


    public function trackingPage(Order $order)
    {
        // Charger l'adresse avec ses coordonnées
        $order->load('deliveryAddress');

        return view('delivery.tracking', compact('order'));
    }

public function deliveredOrders()
{
    $deliveries = Delivery::with([
        'order.user:id,name',
        'order.orderItems.product:id,name',
        'order.deliveryAddress:id,order_id,full_address'
    ])
    ->where('delivery_person_id', Auth::id())
    ->where('status', 'delivered')
    ->orderBy('delivered_at', 'desc')
    ->paginate(10);

    return view('delivery.delivered_orders', compact('deliveries'));
}

// Dashboard du livreur
public function dashboard()
{
    $user = Auth::user();
    $today = now()->format('Y-m-d');
    
    // Statistiques
    $stats = [
        'today_deliveries' => Delivery::where('delivery_person_id', $user->id)
            ->whereDate('created_at', $today)
            ->count(),
            
        'weekly_deliveries' => Delivery::where('delivery_person_id', $user->id)
            ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->count(),
            
        'pending_deliveries' => Delivery::where('delivery_person_id', $user->id)
            ->where('status', 'in_transit')
            ->count(),
            
        'completed_deliveries' => Delivery::where('delivery_person_id', $user->id)
            ->where('status', 'delivered')
            ->count(),
    ];
    
    // Commandes du jour
    $today_orders = Delivery::with(['order.user', 'order.deliveryAddress'])
        ->where('delivery_person_id', $user->id)
        ->whereDate('created_at', $today)
        ->orderBy('created_at', 'desc')
        ->take(5)
        ->get();
    
    // Prochaines livraisons
    $upcoming_deliveries = Order::with(['user', 'deliveryAddress'])
        ->where('order_status', 'pending')
        ->whereDoesntHave('deliveries')
        ->orderBy('created_at', 'asc')
        ->take(5)
        ->get();
    
    // Performances
    $performance = [
        'success_rate' => $stats['completed_deliveries'] > 0 ? 
            round(($stats['completed_deliveries'] / ($stats['completed_deliveries'] + $stats['pending_deliveries'])) * 100, 2) : 0,
        
        'average_time' => '35 min', // À calculer based on real data
        'rating' => 4.8 // À récupérer from ratings table
    ];
    
    return view('delivery.dashboard', compact('stats', 'today_orders', 'upcoming_deliveries', 'performance'));
}

}
