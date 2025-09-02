<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\OrderItem;
use App\Models\DirectSaleItem;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    public function index()
{
    $categories = Category::with('parent', 'products')
        ->whereNull('parent_id')
        ->paginate(10);

    $totalProducts = Product::count();
    $totalActiveProducts = Product::where('status', 'active')->count();

    // Calcul revenu total depuis orders + direct_sales
    $totalRevenueOrders = OrderItem::sum('total');
    $totalRevenueDirectSales = DirectSaleItem::sum('total_price');
    $totalRevenue = $totalRevenueOrders + $totalRevenueDirectSales;

    // Pour chaque catégorie : nb produits, stock total, CA
    foreach ($categories as $category) {
        $category->products_count = $category->products->count();
        $category->total_stock = $category->products->sum('stock_quantity');

        // Revenu par catégorie depuis commandes
        $revenueOrders = OrderItem::whereIn('product_id', $category->products->pluck('id'))
            ->sum('total');

        // Revenu par catégorie depuis ventes directes
        $revenueDirectSales = DirectSaleItem::whereIn('product_id', $category->products->pluck('id'))
            ->sum('total_price');

        $category->total_revenue = $revenueOrders + $revenueDirectSales;
    }

    return view('sale.categories.index', compact(
        'categories', 
        'totalProducts', 
        'totalActiveProducts', 
        'totalRevenue'
    ));
}

    public function create()
    {
        $parentCategories = Category::whereNull('parent_id')->get();
        return view('sale.categories.create', compact('parentCategories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:191',
            'parent_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string'
        ]);

        Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'parent_id' => $request->parent_id,
            'description' => $request->description
        ]);

        return redirect()->route('sale.categories.index')->with('success', 'Catégorie créée avec succès');
    }

    public function show(Category $category)
    {
        $parentCategories = Category::whereNull('parent_id')
            ->where('id', '!=', $category->id)
            ->get();

        return view('sale.categories.show', compact('category','parentCategories'));
    }

        public function showproduct(Product $product)
{
    $product->load(['discounts', 'variants', 'category']);
    return view('sale.categories.showproduct', compact('product'));
}


    public function edit(Category $category)
    {
        $parentCategories = Category::whereNull('parent_id')
            ->where('id', '!=', $category->id)
            ->get();

        return view('sale.categories.edit', compact('category', 'parentCategories'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:191',
            'parent_id' => [
                'nullable',
                'exists:categories,id',
                Rule::notIn([$category->id])
            ],
            'description' => 'nullable|string'
        ]);

        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'parent_id' => $request->parent_id,
            'description' => $request->description
        ]);

        return redirect()->route('sale.categories.index')->with('success', 'Catégorie mise à jour avec succès');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('sale.categories.index')->with('success', 'Catégorie supprimée avec succès');
    }

    public function productsByCategory(Category $category)
    {
        $products = $category->products()->paginate(12);
        return view('sale.categories.product', compact('category', 'products'));
    }



    //les routes pour l'admin

    public function indexa()
{
    $categories = Category::with('parent', 'products')
        ->whereNull('parent_id')
        ->paginate(10);

    $totalProducts = Product::count();
    $totalActiveProducts = Product::where('status', 'active')->count();

    // Calcul revenu total depuis orders + direct_sales
    $totalRevenueOrders = OrderItem::sum('total');
    $totalRevenueDirectSales = DirectSaleItem::sum('total_price');
    $totalRevenue = $totalRevenueOrders + $totalRevenueDirectSales;

    // Pour chaque catégorie : nb produits, stock total, CA
    foreach ($categories as $category) {
        $category->products_count = $category->products->count();
        $category->total_stock = $category->products->sum('stock_quantity');

        // Revenu par catégorie depuis commandes
        $revenueOrders = OrderItem::whereIn('product_id', $category->products->pluck('id'))
            ->sum('total');

        // Revenu par catégorie depuis ventes directes
        $revenueDirectSales = DirectSaleItem::whereIn('product_id', $category->products->pluck('id'))
            ->sum('total_price');

        $category->total_revenue = $revenueOrders + $revenueDirectSales;
    }

    return view('admin.categories.index', compact(
        'categories', 
        'totalProducts', 
        'totalActiveProducts', 
        'totalRevenue'
    ));
}

    public function createa()
    {
        $parentCategories = Category::whereNull('parent_id')->get();
        return view('admin.categories.create', compact('parentCategories'));
    }

    public function storea(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:191',
            'parent_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string'
        ]);

        Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'parent_id' => $request->parent_id,
            'description' => $request->description
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Catégorie créée avec succès');
    }

    public function showa(Category $category)
    {
        $parentCategories = Category::whereNull('parent_id')
            ->where('id', '!=', $category->id)
            ->get();

        return view('admin.categories.show', compact('category','parentCategories'));
    }

        public function showproducta(Product $product)
{
    $product->load(['discounts', 'variants', 'category']);
    return view('admin.categories.showproduct', compact('product'));
}


    public function edita(Category $category)
    {
        $parentCategories = Category::whereNull('parent_id')
            ->where('id', '!=', $category->id)
            ->get();

        return view('admin.categories.edit', compact('category', 'parentCategories'));
    }

    public function updatea(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:191',
            'parent_id' => [
                'nullable',
                'exists:categories,id',
                Rule::notIn([$category->id])
            ],
            'description' => 'nullable|string'
        ]);

        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'parent_id' => $request->parent_id,
            'description' => $request->description
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Catégorie mise à jour avec succès');
    }

    public function destroya(Category $category)
    {
        $category->delete();
        return redirect()->route('admin.categories.index')->with('success', 'Catégorie supprimée avec succès');
    }

    public function productsByCategorya(Category $category)
    {
        $products = $category->products()->paginate(12);
        return view('admin.categories.product', compact('category', 'products'));
    }
}