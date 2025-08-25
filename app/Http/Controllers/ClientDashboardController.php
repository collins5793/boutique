<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ClientDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // --- Widgets
        $totalOrders = DB::table('orders')
            ->where('user_id', $user->id)
            ->count();

        $ordersInProgress = DB::table('orders')
            ->where('user_id', $user->id)
            ->whereIn('order_status', ['pending', 'processing', 'shipped'])
            ->count();

        // Total dépensé (livrées + cash_on_delivery)
        $totalSpent = DB::table('orders')
            ->where('user_id', $user->id)
            ->where('payment_method', 'cash_on_delivery')
            ->where('order_status', 'delivered')
            ->sum('total_amount');

        // Points fidélité
        $loyaltyPoints = DB::table('loyalty_points')
            ->where('user_id', $user->id)
            ->sum('points');

        // --- Donut commandes par statut
        $ordersByStatusRaw = DB::table('orders')
            ->select('order_status', DB::raw('COUNT(*) as total'))
            ->where('user_id', $user->id)
            ->groupBy('order_status')
            ->get();

        // on met des labels jolis
        $statusLabelsMap = [
            'delivered' => 'Livrées',
            'processing'=> 'En cours',
            'shipped'   => 'Expédiées',
            'pending'   => 'En attente',
            'cancelled' => 'Annulées',
            'failed'    => 'Échouées',
            'refunded'  => 'Remboursées',
        ];

        $ordersByStatus = [
            'labels' => [],
            'values' => [],
        ];
        foreach ($ordersByStatusRaw as $row) {
            $label = $statusLabelsMap[$row->order_status] ?? ucfirst($row->order_status);
            $ordersByStatus['labels'][] = $label;
            $ordersByStatus['values'][] = (int)$row->total;
        }

        // --- Historique mensuel (commandes + dépenses)
        // On prend les 12 derniers mois contenant des commandes de l'utilisateur
        $monthlyStats = DB::table('orders')
            ->select(
                DB::raw("DATE_FORMAT(created_at, '%Y-%m') as ym"),
                DB::raw("COUNT(*) as total_orders"),
                DB::raw("SUM(CASE WHEN payment_method='cash_on_delivery' AND order_status='delivered' THEN total_amount ELSE 0 END) as total_spent")
            )
            ->where('user_id', $user->id)
            ->groupBy('ym')
            ->orderBy('ym')
            ->get();

        // --- Commandes récentes
        $recentOrders = DB::table('orders')
            ->where('user_id', $user->id)
            ->latest()
            ->limit(8)
            ->get();

        // --- Dépenses totales année en cours + moyenne / mois (COD + livrées)
        $year = now()->year;
        $yearly = DB::table('orders')
            ->select(
                DB::raw("SUM(total_amount) as spent_year")
            )
            ->where('user_id', $user->id)
            ->where('payment_method', 'cash_on_delivery')
            ->where('order_status', 'delivered')
            ->whereYear('created_at', $year)
            ->first();

        $spentThisYear = (float)($yearly->spent_year ?? 0);
        $avgPerMonth = $spentThisYear > 0
            ? round($spentThisYear / (int)now()->format('n'), 2)
            : 0.0;

        // --- Top produits les plus achetés (par quantité et dépense)
        // nécessite une table products (id, name, image nullable)
        $topProducts = DB::table('order_items as oi')
            ->join('orders as o', 'o.id', '=', 'oi.order_id')
            ->leftJoin('products as p', 'p.id', '=', 'oi.product_id')
            ->where('o.user_id', $user->id)
            ->where('o.order_status', '!=', 'cancelled')
            ->select(
                'oi.product_id',
                DB::raw('COALESCE(p.name, CONCAT("Produit #", oi.product_id)) as name'),
                DB::raw('SUM(oi.quantity) as qty'),
                DB::raw('SUM(oi.total) as spent'),
                'p.image'
            )
            ->groupBy('oi.product_id', 'p.name', 'p.image')
            ->orderByDesc(DB::raw('SUM(oi.quantity)'))
            ->limit(6)
            ->get();

        return view('client.dashboard', compact(
            'user',
            'totalOrders',
            'ordersInProgress',
            'totalSpent',
            'loyaltyPoints',
            'ordersByStatus',
            'monthlyStats',
            'recentOrders',
            'spentThisYear',
            'avgPerMonth',
            'topProducts'
        ));
    }

    public function order(Request $request)
{
    $user = Auth::user();

    // Statistiques
    $stats = [
        'total'     => Order::where('user_id', $user->id)->count(),
        'pending'   => Order::where('user_id', $user->id)->where('order_status', 'pending')->count(),
        'processing'=> Order::where('user_id', $user->id)->where('order_status', 'processing')->count(),
        'shipped'   => Order::where('user_id', $user->id)->where('order_status', 'shipped')->count(),
        'delivered' => Order::where('user_id', $user->id)->where('order_status', 'delivered')->count(),
        'cancelled' => Order::where('user_id', $user->id)->where('order_status', 'cancelled')->count(),
    ];

    // Filtrage
    $query = Order::with('items.product')->where('user_id', $user->id);

    if ($request->filled('status')) {
        $query->where('order_status', $request->status);
    }

    if ($request->filled('search')) {
        $search = $request->search;
        $query->where('order_number', 'like', "%$search%");
    }

    if ($request->filled('date')) {
        if ($request->date == '30days') {
            $query->where('created_at', '>=', now()->subDays(30));
        } elseif ($request->date == 'month') {
            $query->whereMonth('created_at', now()->month);
        } elseif ($request->date == 'year') {
            $query->whereYear('created_at', now()->year);
        }
    }

    $orders = $query->latest()->paginate(10);

    return view('client.orders', compact('orders', 'stats'));
}

// API JSON pour la modale
public function ordershow($id)
{
    $order = Order::with(['items.product', 'deliveryAddress'])
        ->where('id', $id)
        ->where('user_id', Auth::id())
        ->firstOrFail();

    return view('client.order_detail', compact('order'));

}

public function panier()
    {
        $cartItems = CartItem::with(['product', 'variant'])
                        ->where('user_id', Auth::id())
                        ->get();
        return view('client.panier', compact('cartItems'));
    }
}