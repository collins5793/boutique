<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\Category;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class ShopController extends Controller
{
    // Liste des produits
    public function index(Request $request)
    {
        $query = Product::with('variants', 'category', 'discounts')
                ->where('status', 'active');
    // Recherche
    if ($request->has('search') && $request->search != '') {
        $search = $request->search;
        $query->where('name', 'like', "%$search%");
    }

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

    return view('boutique.index', compact('products', 'categories'));
}

    

    // Détail d’un produit
    public function show($id)
    {
        $product = Product::with('category')->findOrFail($id);

        return view('boutique.show', compact('product'));
    }

    // Commander (redirection vers login si non connecté)
    public function order($id)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        return redirect()->back()->with('success', 'Produit ajouté au panier !');
    }
}