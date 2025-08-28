<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category');

        if ($request->has('search')) {
            $query->where('name', 'like', '%'.$request->search.'%')
                  ->orWhere('description', 'like', '%'.$request->search.'%');
        }

        $products = $query->paginate(10);
        return view('products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('products.create', compact('categories'));
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
        'status' => 'required|in:active,inactive'
    ]);

    $data = $request->except(['image', 'gallery']);
    $data['slug'] = Str::slug($request->name);

    // ✅ Génération d’un barcode unique prod-XXXXXXXXXX
    do {
        $barcode = 'prod-' . Str::upper(Str::random(10)); // 10 caractères aléatoires
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

    Product::create($data);

    return redirect()->route('products.index')->with('success', 'Produit créé avec succès');
}

    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('products.edit', compact('product', 'categories'));
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
        'status' => 'required|in:active,inactive'
    ]);

    $data = $request->except(['image', 'gallery', 'removed_gallery_images', 'remove_image']);

    if ($request->filled('name') && $request->name !== $product->name) {
        $data['slug'] = Str::slug($request->name);
    }

    // Mise à jour image principale
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

    // Gestion de la galerie
    $currentGallery = $product->gallery ? json_decode($product->gallery, true) : [];

    // Supprimer certaines images
    if ($request->filled('removed_gallery_images')) {
        $removedImages = explode(',', $request->removed_gallery_images);
        foreach ($removedImages as $image) {
            Storage::disk('public')->delete($image);
        }
        $currentGallery = array_diff($currentGallery, $removedImages);
    }

    // Ajouter de nouvelles images à la galerie
    if ($request->hasFile('gallery')) {
        foreach ($request->file('gallery') as $file) {
            $currentGallery[] = $file->store('products/gallery', 'public');
        }
    }

    $data['gallery'] = json_encode(array_values($currentGallery)); // réindexer proprement

    $product->update($data);

    return redirect()->route('products.index')->with('success', 'Produit mis à jour avec succès');
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
        return redirect()->route('products.index')->with('success', 'Produit supprimé avec succès');
    }
}