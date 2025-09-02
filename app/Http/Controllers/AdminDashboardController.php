<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use App\Models\DirectSale;
use App\Models\DirectSaleItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Category;

class AdminDashboardController extends Controller
{
    public function index(Request $request)
    {
        // période par défaut (30 jours)
        $start = $request->query('start') ? Carbon::parse($request->query('start'))->startOfDay() : Carbon::now()->subDays(29)->startOfDay();
        $end   = $request->query('end')   ? Carbon::parse($request->query('end'))->endOfDay()   : Carbon::now()->endOfDay();

        // Totaux (période)
        $directTotal = (float) DirectSale::whereBetween('created_at', [$start, $end])->sum('total');
        $ordersTotal = (float) Order::whereBetween('created_at', [$start, $end])
                                    ->where('payment_status', 'paid')
                                    ->sum('total_amount');
        $totalRevenue = $directTotal + $ordersTotal;

        // Nombres de ventes
        $directCount = DirectSale::whereBetween('created_at', [$start, $end])->count();
        $ordersCount = Order::whereBetween('created_at', [$start, $end])->where('payment_status', 'paid')->count();
        $totalSalesCount = $directCount + $ordersCount;

        // Produits vendus (quantité)
        $directQty = (int) DB::table('direct_sale_items')
                        ->join('direct_sales','direct_sales.id','direct_sale_items.direct_sale_id')
                        ->whereBetween('direct_sales.created_at', [$start, $end])
                        ->sum('direct_sale_items.quantity');

        $ordersQty = (int) DB::table('order_items')
                        ->join('orders','orders.id','order_items.order_id')
                        ->whereBetween('orders.created_at', [$start, $end])
                        ->where('orders.payment_status', 'paid')
                        ->sum('order_items.quantity');

        $totalProductsSold = $directQty + $ordersQty;

        // Nombre clients (distinct users sur commandes payées)
        $customersCount = Order::whereBetween('created_at', [$start, $end])
                               ->where('payment_status', 'paid')
                               ->distinct('user_id')
                               ->count('user_id');

        // Top produit (par quantité, combiné direct + commandes)
        // Récupère totaux par product_id séparément puis combine en PHP (simple & compatible)
        $directByProduct = DB::table('direct_sale_items')
            ->join('direct_sales','direct_sales.id','direct_sale_items.direct_sale_id')
            ->whereBetween('direct_sales.created_at', [$start, $end])
            ->groupBy('direct_sale_items.product_id')
            ->select('direct_sale_items.product_id', DB::raw('SUM(direct_sale_items.quantity) as qty'))
            ->pluck('qty','product_id')->toArray();

        $orderByProduct = DB::table('order_items')
            ->join('orders','orders.id','order_items.order_id')
            ->whereBetween('orders.created_at', [$start, $end])
            ->where('orders.payment_status', 'paid')
            ->groupBy('order_items.product_id')
            ->select('order_items.product_id', DB::raw('SUM(order_items.quantity) as qty'))
            ->pluck('qty','product_id')->toArray();

        $combined = [];
        foreach ($directByProduct as $pid => $q) { $combined[$pid] = ($combined[$pid] ?? 0) + (int)$q; }
        foreach ($orderByProduct as $pid => $q)  { $combined[$pid] = ($combined[$pid] ?? 0) + (int)$q; }

        arsort($combined);
        $topProduct = null;
        if (!empty($combined)) {
            $topId = array_key_first($combined);
            $topProductModel = Product::find($topId);
            $topProduct = [
                'product' => $topProductModel,
                'quantity' => $combined[$topId],
            ];
        }

        // Top 5 produits
        $topFive = [];
        if (!empty($combined)) {
            $topIds = array_slice(array_keys($combined), 0, 5);
            $productsMap = Product::whereIn('id', $topIds)->get()->keyBy('id');
            foreach ($topIds as $pid) {
                $topFive[] = [
                    'product' => $productsMap->get($pid),
                    'quantity' => $combined[$pid] ?? 0,
                ];
            }
        }

        // Totaux jour/semaine/mois (direct & orders)
        $todayStart = Carbon::now()->startOfDay();
        $todayEnd   = Carbon::now()->endOfDay();
        $weekStart  = Carbon::now()->startOfWeek();
        $weekEnd    = Carbon::now()->endOfWeek();
        $monthStart = Carbon::now()->startOfMonth();
        $monthEnd   = Carbon::now()->endOfMonth();

        $metrics = [
            'direct' => [
                'day' => (float) DirectSale::whereBetween('created_at', [$todayStart, $todayEnd])->sum('total'),
                'week'=> (float) DirectSale::whereBetween('created_at', [$weekStart, $weekEnd])->sum('total'),
                'month'=> (float) DirectSale::whereBetween('created_at', [$monthStart, $monthEnd])->sum('total'),
            ],
            'orders' => [
                'day' => (float) Order::whereBetween('created_at', [$todayStart, $todayEnd])->where('payment_status','paid')->sum('total_amount'),
                'week'=> (float) Order::whereBetween('created_at', [$weekStart, $weekEnd])->where('payment_status','paid')->sum('total_amount'),
                'month'=> (float) Order::whereBetween('created_at', [$monthStart, $monthEnd])->where('payment_status','paid')->sum('total_amount'),
            ],
        ];

        // Courbe journalière (période start..end)
        $days = [];
        $labels = [];
        $cursor = $start->copy();
        while ($cursor->lte($end)) {
            $labels[] = $cursor->toDateString();
            $days[] = $cursor->toDateString();
            $cursor->addDay();
        }

        $directDaily = DB::table('direct_sales')
            ->select(DB::raw('DATE(created_at) as d'), DB::raw('SUM(total) as s'))
            ->whereBetween('created_at', [$start, $end])
            ->groupBy('d')
            ->pluck('s','d')->toArray();

        $ordersDaily = DB::table('orders')
            ->select(DB::raw('DATE(created_at) as d'), DB::raw('SUM(total_amount) as s'))
            ->whereBetween('created_at', [$start, $end])
            ->where('payment_status','paid')
            ->groupBy('d')
            ->pluck('s','d')->toArray();

        $seriesDirect = [];
        $seriesOrders = [];
        foreach ($labels as $d) {
            $seriesDirect[] = isset($directDaily[$d]) ? (float)$directDaily[$d] : 0;
            $seriesOrders[] = isset($ordersDaily[$d]) ? (float)$ordersDaily[$d] : 0;
        }

        // Répartition par catégorie (CA combiné)
        $dsByCat = DB::table('direct_sale_items')
            ->join('direct_sales','direct_sales.id','direct_sale_items.direct_sale_id')
            ->join('products','products.id','direct_sale_items.product_id')
            ->whereBetween('direct_sales.created_at', [$start, $end])
            ->groupBy('products.category_id')
            ->select('products.category_id', DB::raw('SUM(direct_sale_items.quantity * direct_sale_items.unit_price) as total'))
            ->pluck('total','products.category_id')->toArray();

        $oiByCat = DB::table('order_items')
            ->join('orders','orders.id','order_items.order_id')
            ->join('products','products.id','order_items.product_id')
            ->whereBetween('orders.created_at', [$start, $end])
            ->where('orders.payment_status','paid')
            ->groupBy('products.category_id')
            ->select('products.category_id', DB::raw('SUM(order_items.total) as total'))
            ->pluck('total','products.category_id')->toArray();

        $catTotals = [];
        foreach ($dsByCat as $cid => $t) { $catTotals[$cid] = ($catTotals[$cid] ?? 0) + (float)$t; }
        foreach ($oiByCat as $cid => $t) { $catTotals[$cid] = ($catTotals[$cid] ?? 0) + (float)$t; }

        $categoryLabels = [];
        $categoryValues = [];
        if (!empty($catTotals)) {
            $catModels = Category::whereIn('id', array_keys($catTotals))->pluck('name','id')->toArray();
            foreach ($catTotals as $cid => $val) {
                $categoryLabels[] = $catModels[$cid] ?? "Catégorie {$cid}";
                $categoryValues[] = (float) $val;
            }
        }

        // Ventes récentes (10 dernières directes + 10 dernières commandes)
        $recentDirectSales = DirectSale::with('items.product')->latest()->take(10)->get();
        $recentOrders = Order::with('items.product')->where('payment_status','paid')->latest()->take(10)->get();

        return view('admin.dashboard', compact(
            'start','end',
            'directTotal','ordersTotal','totalRevenue',
            'directCount','ordersCount','totalSalesCount',
            'totalProductsSold','customersCount',
            'topProduct','topFive',
            'metrics','labels','seriesDirect','seriesOrders',
            'categoryLabels','categoryValues',
            'recentDirectSales','recentOrders'
        ));
    }

    public function salesOrders(Request $request)
{
    // Filtrage et recherche
    $type = $request->get('type'); // 'direct', 'order' ou null
    $status = $request->get('status'); // statut paiement / commande
    $paymentMethod = $request->get('payment_method'); // mobile_money, card, etc.
    $startDate = $request->get('start_date');
    $endDate = $request->get('end_date');

    // Query pour ventes directes
    $directSalesQuery = \App\Models\DirectSale::with('items.product');
    if ($status) $directSalesQuery->where('payment_method', $status);
    if ($startDate && $endDate) $directSalesQuery->whereBetween('created_at', [$startDate, $endDate]);

    // Query pour commandes
    $ordersQuery = \App\Models\Order::with('items.product', 'user');
    if ($status) $ordersQuery->where('order_status', $status);
    if ($paymentMethod) $ordersQuery->where('payment_method', $paymentMethod);
    if ($startDate && $endDate) $ordersQuery->whereBetween('created_at', [$startDate, $endDate]);

    // Sélection type
    if ($type === 'direct') {
        $directSales = $directSalesQuery->latest()->get();
        $orders = collect();
    } elseif ($type === 'order') {
        $directSales = collect();
        $orders = $ordersQuery->latest()->get();
    } else {
        $directSales = $directSalesQuery->latest()->get();
        $orders = $ordersQuery->latest()->get();
    }

    // KPI / résumés
    $kpi = [
        'total_direct_sales' => $directSales->sum('total'),
        'total_orders' => $orders->sum('total_amount'),
        'pending_orders' => $orders->where('order_status', 'pending')->count(),
        'cancelled_orders' => $orders->where('order_status', 'cancelled')->count(),
        'total_products_sold' => $directSales->sum(fn($sale) => $sale->items->sum('quantity')) +
                                 $orders->sum(fn($order) => $order->items->sum('quantity'))
    ];

    // Évolution des ventes (7 derniers jours)
    $dates = [];
    $directSalesData = [];
    $ordersData = [];
    for ($i = 6; $i >= 0; $i--) {
        $date = now()->subDays($i)->format('d/m');
        $dates[] = $date;
        $directSalesData[] = $directSales->whereBetween('created_at', [now()->subDays($i)->startOfDay(), now()->subDays($i)->endOfDay()])->count();
        $ordersData[] = $orders->whereBetween('created_at', [now()->subDays($i)->startOfDay(), now()->subDays($i)->endOfDay()])->count();
    }

    $salesEvolution = [
        'dates' => $dates,
        'direct_sales' => $directSalesData,
        'orders' => $ordersData
    ];

    return view('admin.sales_orders', compact('directSales', 'orders', 'kpi', 'salesEvolution'));
}


public function inventaire(Request $request)
    {
        // Filtres
        $query = Product::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%$search%");
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('stock_status')) {
            if ($request->stock_status === 'low') {
                $query->where('stock_quantity', '<=', 5)->where('stock_quantity', '>', 0);
            } elseif ($request->stock_status === 'out') {
                $query->where('stock_quantity', '=', 0);
            }
        }

        $products = $query->with('category')->paginate(15);

        // Top Metrics
        $totalStock = Product::sum('stock_quantity');
        $lowStock = Product::where('stock_quantity', '<=', 5)->where('stock_quantity', '>', 0)->count();
        $outStock = Product::where('stock_quantity', 0)->count();
        $totalValue = Product::sum(DB::raw('price * stock_quantity'));
        $recentProducts = Product::orderBy('created_at','desc')->limit(5)->get();

        // Catégories pour filtre
        $categories = Category::all();

        return view('admin.inventory', compact(
            'products', 'totalStock', 'lowStock', 'outStock', 'totalValue', 'recentProducts', 'categories'
        ));
    }

}
