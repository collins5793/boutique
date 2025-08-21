<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Produits populaires = produits actifs triés par date de création descendante
        $popularProducts = Product::where('status', 'active')
                            ->orderBy('created_at', 'desc')
                            ->limit(8)
                            ->get()
                            ->map(function($p) {
                                $p->is_new = $p->created_at->diffInDays(now()) <= 30; // nouveaux = moins de 30 jours
                                return $p;
                            });

        // Nouveaux produits
        $newProducts = Product::where('status', 'active')
                            ->orderBy('created_at', 'desc')
                            ->limit(8)
                            ->get()
                            ->map(function($p) {
                                $p->is_new = true;
                                return $p;
                            });

        // Promotions = produits avec discount_price défini
        $promotions = Product::whereNotNull('discount_price')
                            ->where('status', 'active')
                            ->limit(8)
                            ->get()
                            ->map(function($p) {
                                $p->discount_percent = round((($p->price - $p->discount_price) / $p->price) * 100);
                                return $p;
                            });

        return view('home', compact('popularProducts', 'newProducts', 'promotions'));
    }
}
