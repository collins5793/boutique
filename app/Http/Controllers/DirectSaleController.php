<?php

namespace App\Http\Controllers;

use App\Models\DirectSale;
use App\Models\DirectSaleItem;
use App\Models\CartItem;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DirectSaleController extends Controller {
    // middleware pour role employé

    public function index() {
    $sales = DirectSale::with('items.product')->latest()->get();
    $products = \App\Models\Product::all(); // Récupère tous les produits

    return view('admin.direct_sales.index', compact('sales', 'products'));
}

public function getData()
{
    $cartItems = CartItem::with(['product', 'variant'])
        ->where('user_id', Auth::id())
        ->get();

    return view('sale.direct_sales.partials.cart_content', compact('cartItems'));
}

public function status()
{
    $cartItems = CartItem::where('user_id', Auth::id())->get();
    return response()->json([
        'isEmpty' => $cartItems->isEmpty(),
        'count' => $cartItems->count(),
    ]);
}



public function vente(Request $request)
{
$query = Product::with('variants', 'category', 'discounts')
                ->where('status', 'active');
    // Recherche
    if ($request->has('search') && $request->search != '') {
        $search = $request->search;
        $query->where('name', 'like', "%$search%");
    }
     $cartItems = CartItem::with(['product', 'variant'])
                        ->where('user_id', Auth::id())
                        ->get();

    // Tri / Filtrage
    if ($request->has('sort') && $request->sort != '') {
        if (str_starts_with($request->sort, 'category_')) {
            // Filtrer par catégorie
            $categoryId = intval(str_replace('category_', '', $request->sort));
            $query->where('category_id', $categoryId);
        } else {
            switch ($request->sort) {
                case 'popularity':
                    $query->orderBy('rating', 'desc');
                    break;
                case 'price_low':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_high':
                    $query->orderBy('price', 'desc');
                    break;
                case 'newest':
                    $query->orderBy('created_at', 'desc');
                    break;
            }
        }
    } else {
        $query->latest();
    }

    $products = $query->paginate(12)->withQueryString();

    $categories = Category::withCount([
        'products as active_products_count' => function ($query) {
            $query->where('status', 'active');
        }
    ])->with([
        'subcategories.products' => function ($query) {
            $query->where('status', 'active')->with('variants');
        },
        'products' => function ($query) {
            $query->where('status', 'active')->with('variants');
        }
    ])->whereNull('parent_id')->get();

    return view('sale.direct_sales.vente', compact('products', 'categories', 'cartItems'));
}

    public function create() {
        $products = Product::all();
        return view('admin.direct_sales.create', compact('products'));
    }

    public function store(Request $request)
{
    $request->validate([
        'payment_method' => 'required|in:cash,mobile_money,card',
    ]);

    // ⚡ Récupérer le vendeur connecté
    $employeeId = Auth::id();

    // ⚡ Récupérer le panier 
    $cartItems = CartItem::with('product')
                ->where('user_id', Auth::id())
                ->get();

    if ($cartItems->isEmpty()) {
        return response()->json(['success' => false, 'message' => 'Le panier est vide']);
    }

    DB::beginTransaction();

    try {
        // ✅ Calcul du total
        $totalAmount = $cartItems->sum(fn($item) => $item->price * $item->quantity);

        // ✅ Créer la vente
        $sale = DirectSale::create([
            'employee_id'    => $employeeId,
            'total'          => $totalAmount,
            'payment_method' => $request->payment_method,
        ]);

        // ✅ Créer les items
        foreach ($cartItems as $item) {
            DirectSaleItem::create([
                'direct_sale_id' => $sale->id,
                'product_id'     => $item->product_id,
                'quantity'       => $item->quantity,
                'unit_price'     => $item->price,
                'total_price'    => $item->price * $item->quantity,
            ]);

            // ✅ Déduire du stock
            $product = $item->product;
            if ($product) {
                $product->stock_quantity -= $item->quantity;
                $product->save();
            }
        }

        // ✅ Vider le panier
        CartItem::where('user_id', Auth::id())->delete();

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Vente enregistrée avec succès',
            'sale_id' => $sale->id
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