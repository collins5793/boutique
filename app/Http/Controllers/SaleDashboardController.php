<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Delivery;
use App\Models\Product;
use App\Models\Category;
use App\Models\OrderItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\DirectSale;
use App\Models\DirectSaleItem;
use Illuminate\Support\Facades\DB;

class SaleDashboardController extends Controller
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

        return view('sale.dashboard', compact(
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

    public function sales(Request $request)
{
    $employeeId = Auth::id();

    // 🔹 Filtres période
  $startDate = Carbon::now()->subDays(7)->startOfDay(); // 7 derniers jours
$endDate   = Carbon::now()->endOfDay();



    // 🔹 Résumé rapide
    $totalSales = DirectSale::where('employee_id', $employeeId)
                    ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
                    ->sum('total');

    $totalProducts = DirectSaleItem::whereHas('sale', function ($q) use ($employeeId, $startDate, $endDate) {
                            $q->where('employee_id', $employeeId)
                              ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
                        })
                        ->sum('quantity');

    $topProduct = DirectSaleItem::select('product_id', DB::raw('SUM(quantity) as qty'))
                    ->whereHas('sale', function ($q) use ($employeeId, $startDate, $endDate) {
                        $q->where('employee_id', $employeeId)
                          ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
                    })
                    ->groupBy('product_id')
                    ->orderByDesc('qty')
                    ->with('product')
                    ->first();

    // 🔹 Graphique évolution journalière
    $salesByDay = DirectSale::select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total) as total'))
                    ->where('employee_id', $employeeId)
                    ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
                    ->groupBy(DB::raw('DATE(created_at)'))
                    ->orderBy('date')
                    ->pluck('total', 'date');

    // 🔹 Répartition par catégorie (camembert)
    $salesByCategory = DirectSaleItem::select('products.category_id', DB::raw('SUM(direct_sale_items.quantity * direct_sale_items.unit_price) as total'))
                    ->join('products', 'products.id', '=', 'direct_sale_items.product_id')
                    ->whereHas('sale', function ($q) use ($employeeId, $startDate, $endDate) {
                        $q->where('employee_id', $employeeId)
                          ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
                    })
                    ->groupBy('products.category_id')
                    ->pluck('total', 'products.category_id');

    // 🔹 Top 5 produits (bar chart)
    $topProducts = DirectSaleItem::select('product_id', DB::raw('SUM(quantity) as qty'))
                    ->whereHas('sale', function ($q) use ($employeeId, $startDate, $endDate) {
                        $q->where('employee_id', $employeeId)
                          ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
                    })
                    ->groupBy('product_id')
                    ->orderByDesc('qty')
                    ->with('product')
                    ->take(5)
                    ->get();

    // 🔹 Tableau des ventes récentes
    $recentSales = DirectSale::where('employee_id', $employeeId)
                    ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
                    ->with('items')
                    ->latest()
                    ->take(10)
                    ->get();

    // Récupérer les noms de catégories pour le graphique
    $categoryNames = [];
    if ($salesByCategory->count() > 0) {
        $categoryIds = $salesByCategory->keys()->toArray();
        $categories = Category::whereIn('id', $categoryIds)->pluck('name', 'id');
        $salesByCategory = $salesByCategory->mapWithKeys(function ($value, $key) use ($categories) {
            return [$categories[$key] ?? 'Catégorie ' . $key => $value];
        });
    }

    return view('sale.direct_sales.mes_ventes', compact(
        'totalSales', 'totalProducts', 'topProduct',
        'salesByDay', 'salesByCategory', 'topProducts', 'recentSales',
        'startDate', 'endDate'
    ));
}
}
