<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\OrderItem;
use App\Models\DirectSaleItem;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
{
    $query = Product::with('category');

    if ($request->filled('search')) {
        $query->where(function($q) use ($request) {
            $q->where('name', 'like', '%'.$request->search.'%')
              ->orWhere('description', 'like', '%'.$request->search.'%');
        });
    }

    $products = $query->paginate(10);

    // Statistiques
    $topProductOverall = Product::withSum('orderItems', 'quantity')
        ->withSum('directSaleItems', 'quantity')
        ->get()
        ->map(function($p){
            $p->total_sold = ($p->order_items_sum_quantity ?? 0) + ($p->direct_sale_items_sum_quantity ?? 0);
            return $p;
        })
        ->sortByDesc('total_sold')
        ->first();

    $topProductOrder = Product::withSum('orderItems', 'quantity')
        ->orderByDesc('order_items_sum_quantity')
        ->first();

    $topProductDirect = Product::withSum('directSaleItems', 'quantity')
        ->orderByDesc('direct_sale_items_sum_quantity')
        ->first();

    $stats = [
        'total' => Product::count(),
        'in_stock' => Product::where('stock_quantity', '>', 0)->count(),
        'out_of_stock' => Product::where('stock_quantity', '<=', 0)->count(),
        'top_product_overall' => $topProductOverall,
        'top_product_order' => $topProductOrder,
        'top_product_direct' => $topProductDirect,
    ];

    return view('sale.products.index', compact('products', 'stats'));
}

public function search(Request $request)
{
    $q = $request->get('q');

    $products = Product::with('category')
        ->when($q, function ($query, $q) {
            return $query->where('name', 'like', "%$q%")
                         ->orWhere('description', 'like', "%$q%");
        })
        ->paginate(10);

    return view('sale.products.index', compact('products'));
}

    public function create()
    {
        $categories = Category::all();
        return view('sale.products.create', compact('categories'));
    }

   public function store(Request $request)
{
    $request->validate([
        'category_id' => 'required|exists:categories,id',
        'name' => 'required|string|max:191',
        'description' => 'nullable|string',
        'price' => 'required|numeric|min:0',
        'discount_price' => 'nullable|numeric|min:0',
        'stock_quantity' => 'required|integer|min:0',
        'image' => 'nullable|image|max:2048',
        'gallery.*' => 'nullable|image|max:2048',
        'status' => 'required|in:active,inactive',
        'discounts' => 'nullable|array',
        'discounts.*.min_quantity' => 'required_with:discounts|integer|min:1',
        'discounts.*.price' => 'required_with:discounts|numeric|min:0',
    ]);

    $data = $request->except(['image', 'gallery', 'discounts']);
    $data['slug'] = Str::slug($request->name);

    // Génération d’un barcode unique
    do {
        $barcode = 'prod-' . Str::upper(Str::random(10));
    } while (Product::where('barcode', $barcode)->exists());
    $data['barcode'] = $barcode;

    if ($request->hasFile('image')) {
        $data['image'] = $request->file('image')->store('products', 'public');
    }

    if ($request->hasFile('gallery')) {
        $gallery = [];
        foreach ($request->file('gallery') as $file) {
            $gallery[] = $file->store('products/gallery', 'public');
        }
        $data['gallery'] = json_encode($gallery);
    }

    $product = Product::create($data);

    // Gestion des réductions
    $discounts = $request->input('discounts', []);
    foreach ($discounts as $d) {
        if (!empty($d['min_quantity']) && !empty($d['price'])) {
            $product->discounts()->create([
                'min_quantity' => $d['min_quantity'],
                'price' => $d['price'],
            ]);
        }
    }

    return redirect()->route('sale.products.index')->with('success', 'Produit créé avec succès');
}

    public function show(Product $product)
{
    $product->load(['discounts', 'variants', 'category']);
    return view('sale.products.show', compact('product'));
}
    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('sale.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
{
    $request->validate([
        'category_id' => 'required|exists:categories,id',
        'name' => 'required|string|max:191',
        'description' => 'nullable|string',
        'price' => 'required|numeric|min:0',
        'discount_price' => 'nullable|numeric|min:0',
        'stock_quantity' => 'required|integer|min:0',
        'image' => 'nullable|image|max:2048',
        'gallery.*' => 'nullable|image|max:2048',
        'status' => 'required|in:active,inactive',
        'discounts' => 'nullable|array',
        'discounts.*.min_quantity' => 'required_with:discounts|integer|min:1',
        'discounts.*.price' => 'required_with:discounts|numeric|min:0',
    ]);

    $data = $request->except(['image', 'gallery', 'removed_gallery_images', 'remove_image', 'discounts']);

    if ($request->filled('name') && $request->name !== $product->name) {
        $data['slug'] = Str::slug($request->name);
    }

    // Image principale
    if ($request->hasFile('image')) {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        $data['image'] = $request->file('image')->store('products', 'public');
    }

    // Supprimer image principale si demandé
    if ($request->has('remove_image') && $request->remove_image) {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        $data['image'] = null;
    }

    // Gestion galerie
    $currentGallery = $product->gallery ? json_decode($product->gallery, true) : [];
    if ($request->filled('removed_gallery_images')) {
        $removedImages = explode(',', $request->removed_gallery_images);
        foreach ($removedImages as $image) {
            Storage::disk('public')->delete($image);
        }
        $currentGallery = array_diff($currentGallery, $removedImages);
    }
    if ($request->hasFile('gallery')) {
        foreach ($request->file('gallery') as $file) {
            $currentGallery[] = $file->store('products/gallery', 'public');
        }
    }
    $data['gallery'] = json_encode(array_values($currentGallery));

    $product->update($data);

    // Gestion réductions
    $product->discounts()->delete(); // supprime anciennes
    $discounts = $request->input('discounts', []);
    foreach ($discounts as $d) {
        if (!empty($d['min_quantity']) && !empty($d['price'])) {
            $product->discounts()->create([
                'min_quantity' => $d['min_quantity'],
                'price' => $d['price'],
            ]);
        }
    }

    return redirect()->route('sale.products.index')->with('success', 'Produit mis à jour avec succès');
}


public function getActiveProductsByCategory()
{
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

    return view('shop.products_by_category', compact('categories'));
}


    public function destroy(Product $product)
    {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        if ($product->gallery) {
            foreach (json_decode($product->gallery) as $image) {
                Storage::disk('public')->delete($image);
            }
        }

        $product->delete();
        return redirect()->route('sale.products.index')->with('success', 'Produit supprimé avec succès');
    }
}