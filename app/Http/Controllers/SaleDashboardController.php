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

// en haut du controller (si absent)


public function sales(Request $request)
{
    $employeeId = Auth::id();

    // Période filtrée (optionnelle via ?start_date=YYYY-MM-DD&end_date=YYYY-MM-DD)
    $startDate = $request->filled('start_date')
        ? Carbon::parse($request->input('start_date'))->startOfDay()
        : Carbon::now()->subDays(6)->startOfDay(); // par défaut : 7 derniers jours (inclus)

    $endDate = $request->filled('end_date')
        ? Carbon::parse($request->input('end_date'))->endOfDay()
        : Carbon::now()->endOfDay();

    // — Résumé période choisie —
    $totalSales = DirectSale::where('employee_id', $employeeId)
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->sum('total');

    $totalProducts = DirectSaleItem::whereHas('sale', function ($q) use ($employeeId, $startDate, $endDate) {
                        $q->where('employee_id', $employeeId)
                          ->whereBetween('created_at', [$startDate, $endDate]);
                    })->sum('quantity');

    // Top produit sur la période (quantité)
    $topProduct = DirectSaleItem::select('product_id', DB::raw('SUM(quantity) as qty'))
                    ->whereHas('sale', function ($q) use ($employeeId, $startDate, $endDate) {
                        $q->where('employee_id', $employeeId)
                          ->whereBetween('created_at', [$startDate, $endDate]);
                    })
                    ->groupBy('product_id')
                    ->orderByDesc('qty')
                    ->with('product')
                    ->first();

    // Évolution journalière (pour courbe)
    $salesByDay = DirectSale::select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total) as total'))
                    ->where('employee_id', $employeeId)
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->groupBy(DB::raw('DATE(created_at)'))
                    ->orderBy('date')
                    ->pluck('total', 'date'); // collection ['2025-08-20' => 12000, ...]

    // Répartition par catégorie (montant)
    $salesByCategory = DirectSaleItem::select('products.category_id', DB::raw('SUM(direct_sale_items.quantity * direct_sale_items.unit_price) as total'))
                    ->join('products', 'products.id', '=', 'direct_sale_items.product_id')
                    ->whereHas('sale', function ($q) use ($employeeId, $startDate, $endDate) {
                        $q->where('employee_id', $employeeId)
                          ->whereBetween('created_at', [$startDate, $endDate]);
                    })
                    ->groupBy('products.category_id')
                    ->pluck('total', 'products.category_id'); // key = category_id

    // Top 5 produits (bar chart)
    $topProducts = DirectSaleItem::select('product_id', DB::raw('SUM(quantity) as qty'))
                    ->whereHas('sale', function ($q) use ($employeeId, $startDate, $endDate) {
                        $q->where('employee_id', $employeeId)
                          ->whereBetween('created_at', [$startDate, $endDate]);
                    })
                    ->groupBy('product_id')
                    ->orderByDesc('qty')
                    ->with('product')
                    ->take(5)
                    ->get();

    // Ventes récentes
    $recentSales = DirectSale::where('employee_id', $employeeId)
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->with(['items.product'])
                    ->latest()
                    ->take(10)
                    ->get();

    // Breakdown par moyen de paiement (sur la période)
    $paymentBreakdown = DirectSale::select('payment_method', DB::raw('SUM(total) as total'), DB::raw('COUNT(*) as count'))
                        ->where('employee_id', $employeeId)
                        ->whereBetween('created_at', [$startDate, $endDate])
                        ->groupBy('payment_method')
                        ->get()
                        ->keyBy('payment_method'); // accès par $paymentBreakdown['cash']->total

    // — Infos pour "AUJOURD'HUI" (utile pour point de caisse / dépôt) —
    $todayStart = Carbon::today()->startOfDay();
    $todayEnd   = Carbon::today()->endOfDay();

    $todayTotal = DirectSale::where('employee_id', $employeeId)
                    ->whereBetween('created_at', [$todayStart, $todayEnd])
                    ->sum('total');

    $todaySalesCount = DirectSale::where('employee_id', $employeeId)
                    ->whereBetween('created_at', [$todayStart, $todayEnd])
                    ->count();

    $todayProductsSold = DirectSaleItem::whereHas('sale', function ($q) use ($employeeId, $todayStart, $todayEnd) {
                            $q->where('employee_id', $employeeId)
                              ->whereBetween('created_at', [$todayStart, $todayEnd]);
                        })->sum('quantity');

    // Montant cash à déposer (adapter les valeurs selon tes méthodes : 'cash_on_delivery' ou 'cash', etc.)
    // Ici j'inclus 'cash_on_delivery' et 'cash' — adapte la liste si tes valeurs sont différentes.
    $cashToDeposit = DirectSale::where('employee_id', $employeeId)
                        ->whereBetween('created_at', [$todayStart, $todayEnd])
                        ->whereIn('payment_method', ['cash', 'cash_on_delivery'])
                        ->sum('total');

    // Si tu veux aussi une répartition des moyens AUJOURD'HUI :
    $todayPaymentBreakdown = DirectSale::select('payment_method', DB::raw('SUM(total) as total'), DB::raw('COUNT(*) as count'))
                        ->where('employee_id', $employeeId)
                        ->whereBetween('created_at', [$todayStart, $todayEnd])
                        ->groupBy('payment_method')
                        ->get()
                        ->keyBy('payment_method');

    // Transforme salesByCategory keys (ids) en noms lisibles
    if ($salesByCategory->isNotEmpty()) {
        $categoryIds = $salesByCategory->keys()->map(fn($k) => (int) $k)->toArray();
        $cats = Category::whereIn('id', $categoryIds)->pluck('name', 'id')->toArray();
        $salesByCategory = $salesByCategory->mapWithKeys(
    fn($val, $key) => [
        ($cats[(int)$key] ?? "Catégorie {$key}") => $val
    ]
);

    }

    return view('sale.direct_sales.mes_ventes', compact(
        'startDate','endDate',
        'totalSales','totalProducts','topProduct',
        'salesByDay','salesByCategory','topProducts','recentSales',
        'paymentBreakdown',
        'todayTotal','todaySalesCount','todayProductsSold','cashToDeposit','todayPaymentBreakdown'
    ));
}

}
