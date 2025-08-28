<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Delivery;
use App\Models\Product;
use App\Models\OrderItem;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Statistiques globales
        $totalOrders = Order::count();
        $pendingOrders = Order::where('order_status', 'pending')->count();
        $processingOrders = Order::where('order_status', 'processing')->count();
        $shippedOrders = Order::where('order_status', 'shipped')->count();
        $deliveredOrders = Order::where('order_status', 'delivered')->count();

        $totalRevenue = Order::where('payment_status', 'paid')->sum('total_amount');
        $totalProducts = Product::count();
        $lowStockProducts = Product::where('stock_quantity', '<=', 5)->count();

        // Graphiques : ventes par jour (7 derniers jours)
        $salesChart = Order::selectRaw('DATE(created_at) as date, SUM(total_amount) as total')
            ->where('payment_status', 'paid')
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Graphique commandes par statut
        $ordersStatusChart = Order::selectRaw('order_status, COUNT(*) as count')
            ->groupBy('order_status')
            ->get();

        // Dernières commandes
        $recentOrders = Order::latest()->take(5)->get();

        // Produits faibles en stock
        $lowStockItems = Product::where('stock_quantity', '<=', 5)->get();

        return view('admin.dashboard', compact(
            'totalOrders',
            'pendingOrders',
            'processingOrders',
            'shippedOrders',
            'deliveredOrders',
            'totalRevenue',
            'totalProducts',
            'lowStockProducts',
            'salesChart',
            'ordersStatusChart',
            'recentOrders',
            'lowStockItems'
        ));
    }
}
