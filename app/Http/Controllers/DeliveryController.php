<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Delivery;
use App\Models\LoyaltyPoint;
use App\Models\OrderItem;
use App\Models\Notification;
use App\Models\DeliveryAddress;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
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

    public function cancel(Order $order)
    {
        $delivery = Delivery::where('order_id', $order->id)->firstOrFail();

        // if($delivery->status === 'in_transit') {
        //     return redirect()->back()->with('error', 'Impossible d’annuler : la commande est déjà en route.');
        // }

        $delivery->status = 'pending';
        $delivery->save();

        $order->order_status = 'pending';
        $order->save();

    return redirect()->route('delivery.pending')->with('success', 'Commande annulée avec succès !');
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

        $button = '
    <form action="'.route('delivery.valide', $order->id).'" method="POST" style="margin-top:10px;">
        '.csrf_field().'
        <button type="submit" class="btn btn-success btn-sm" 
            style="padding:8px 15px; border-radius:8px; font-weight:bold;">
            ✅ Confirmer la réception
        </button>
    </form>
';


        Notification::create([
            'user_id' => $order->user_id,
            'title' => "Confirmation Commande livrée ✅",
            'content' => "Votre commande {$order->order_number} a éé livrée. Veuillez confirmer la réception.<br>".$button,
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

 $button = '
    <form action="'.route('delivery.valide', $order->id).'" method="POST" style="margin-top:10px;">
        '.csrf_field().'
        <button type="submit" class="btn btn-success btn-sm" 
            style="padding:8px 15px; border-radius:8px; font-weight:bold;">
            ✅ Confirmer la réception
        </button>
    </form>
';


Notification::create([
    'user_id' => $order->user_id,
    'title' => "Confirmation Commande livrée ✅",
    'content' => "Votre commande {$order->order_number} a éé livrée. Veuillez confirmer la réception.<br>".$button,
    'type' => 'system'
]);

        return redirect()->route('delivery.tracki', $order->id)
                        ->with('success', 'Livraison finalisée avec succès !');
    }


// Confirmation par le client
    

public function valideDelivery(Order $order)
{
    // Vérifier si la commande est bien au statut "shipped"
    if ($order->order_status !== 'shipped') {
        return redirect()->back()->with('error', "Cette commande n'est pas encore validée par le livreur. ⏳");
    }

    // Mettre à jour le statut de la commande
    $order->order_status = 'delivered';
    $order->save();

    // Mettre à jour la livraison liée
    $delivery = Delivery::where('order_id', $order->id)->first();

    // --- Attribution des points fidélité ---
    $total = $order->total_amount;
    $points = 0;

    if ($total >= 5000) {
        // Exemple : 1 point pour chaque tranche de 1000F
        $points = floor($total / 1000);

        // Bonus si total >= 5000
        $points += 1;
    }

    if ($points > 0) {
        LoyaltyPoint::create([
            'user_id' => $order->user_id,
            'points'  => $points,
            'reason'  => "Commande #{$order->order_number} d’un montant de {$order->total_amount}F"
        ]);
    }

    // --- Notification au client ---
    Notification::create([
        'user_id' => $order->user_id,
        'title'   => "Commande livrée ✅",
        'content' => "Votre commande {$order->order_number} a été confirmée comme livrée. 
                      Vous avez gagné {$points} points de fidélité 🎉",
        'type'    => 'system'
    ]);

    // --- Notification au livreur ---
    if ($delivery) {
        Notification::create([
            'user_id' => $delivery->delivery_person_id, // ID du livreur
            'title'   => "Livraison confirmée 📦",
            'content' => "La commande {$order->order_number} que vous avez livrée a bien été confirmée par le client ✅. Merci pour votre service 👏",
            'type'    => 'system'
        ]);
    }

    return redirect()->back()->with('success', 'Livraison confirmée avec succès ✅, points fidélité accordés et notifications envoyées !');
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
        
        
    ];
    
    // Données pour le graphique des 7 derniers jours
    $chartData = $this->getDeliveryChartData($user->id);
    
    return view('delivery.dashboard', compact('stats', 'today_orders', 'upcoming_deliveries', 'performance', 'chartData'));
}


private function getDeliveryChartData($userId)
{
    $sevenDaysAgo = now()->subDays(6)->startOfDay();
    
    // Récupérer les livraisons des 7 derniers jours
    $deliveries = Delivery::where('delivery_person_id', $userId)
        ->where('created_at', '>=', $sevenDaysAgo)
        ->get()
        ->groupBy(function($delivery) {
            return $delivery->created_at->format('Y-m-d');
        });
    
    // Préparer les données pour le graphique
    $labels = [];
    $deliveredData = [];
    $inProgressData = [];
    
    // Générer les données pour chaque jour des 7 derniers jours
    for ($i = 6; $i >= 0; $i--) {
        $date = now()->subDays($i)->format('Y-m-d');
        $dayName = now()->subDays($i)->format('D');
        
        $labels[] = $dayName;
        
        if (isset($deliveries[$date])) {
            $dayDeliveries = $deliveries[$date];
            $deliveredData[] = $dayDeliveries->where('status', 'delivered')->count();
            $inProgressData[] = $dayDeliveries->where('status', 'in_transit')->count();
        } else {
            $deliveredData[] = 0;
            $inProgressData[] = 0;
        }
    }
    
    return [
        'labels' => $labels,
        'datasets' => [
            [
                'label' => 'Livraisons effectuées',
                'data' => $deliveredData,
                'backgroundColor' => 'rgba(16, 185, 129, 0.2)',
                'borderColor' => 'rgba(16, 185, 129, 1)',
                'borderWidth' => 2,
                'tension' => 0.4,
                'fill' => true
            ],
            [
                'label' => 'Livraisons en cours',
                'data' => $inProgressData,
                'backgroundColor' => 'rgba(245, 158, 11, 0.2)',
                'borderColor' => 'rgba(245, 158, 11, 1)',
                'borderWidth' => 2,
                'tension' => 0.4,
                'fill' => true
            ]
        ]
    ];
}

public function support()
{
    // Vérifier si l'utilisateur est un livreur (basé sur les livraisons)
    $userDeliveries = Delivery::where('delivery_person_id', auth()->id())->count();
    
    if ($userDeliveries === 0) {
        abort(403, 'Accès réservé aux livreurs');
    }

    $faqCategories = [
        'livraison' => [
            'question' => 'Comment marquer une livraison comme complétée ?',
            'reponse' => 'Cliquez sur le bouton "Livraison effectuée" dans les détails de la commande.'
        ],
        'technique' => [
            'question' => 'Problèmes de géolocalisation',
            'reponse' => 'Vérifiez que la localisation est activée sur votre appareil.'
        ],
        'paiement' => [
            'question' => 'Quand serai-je payé ?',
            'reponse' => 'Les paiements sont effectués chaque vendredi.'
        ]
    ];

    $contacts = [
        [
            'service' => 'Support Technique',
            'telephone' => '+229 01 02 03 04',
            'email' => 'tech@akuesleystore.bj',
            'horaires' => 'Lun-Ven: 8h-18h'
        ],
        [
            'service' => 'Urgences Livraison',
            'telephone' => '+229 05 06 07 08',
            'email' => 'urgent@akuesleystore.bj',
            'horaires' => '24h/24 - 7j/7'
        ]
    ];

    return view('delivery.support', compact('faqCategories', 'contacts'));
}

public function createSupportTicket(Request $request)
{
    // Vérification livreur
    $userDeliveries = Delivery::where('delivery_person_id', auth()->id())->count();
    if ($userDeliveries === 0) {
        abort(403, 'Accès réservé aux livreurs');
    }

    $validated = $request->validate([
        'sujet' => 'required|string|max:200',
        'message' => 'required|string|min:10|max:1000',
        'categorie' => 'required|in:technique,livraison,paiement,autre',
        'urgence' => 'required|in:normal,urgent'
    ]);

    // Envoyer l'email de support
    Mail::send('emails.support-ticket', $validated, function($message) use ($validated) {
        $message->to('moussaamir12346@gmail.com')
                ->subject('[Support Livreur] ' . $validated['sujet'])
                ->replyTo(auth()->user()->email);
    });

    return redirect()->back()->with('success', 'Votre message a été envoyé au support !');
}

public function faq()
{
    $userDeliveries = Delivery::where('delivery_person_id', auth()->id())->count();
    if ($userDeliveries === 0) {
        abort(403, 'Accès réservé aux livreurs');
    }

    $faqs = [
        [
            'question' => 'Comment accepter une livraison ?',
            'reponse' => 'Dans la liste des commandes, cliquez sur "Accepter la livraison".'
        ],
        [
            'question' => 'Que faire si le client est absent ?',
            'reponse' => 'Contactez le support au +229 01 02 03 04 pour instructions.'
        ],
        [
            'question' => 'Comment sont calculés mes gains ?',
            'reponse' => 'Vous gagnez 500 FCFA par livraison + bonus selon la distance.'
        ],
        [
            'question' => 'Problème avec l\'application ?',
            'reponse' => 'Redémarrez l\'application ou contactez le support technique.'
        ]
    ];

    return view('delivery.faq', compact('faqs'));
}


}
