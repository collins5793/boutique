<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Product;
use App\Models\Category;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Récupérer toutes les catégories
        $categories = Category::all();

        if ($categories->isEmpty()) {
            $this->command->info('Veuillez remplir d’abord la table categories !');
            return;
        }

        // Exemple de produits à générer
        $products = [
            [
                'name' => 'Smartphone Samsung Galaxy S23',
                'description' => 'Un smartphone haut de gamme avec un excellent appareil photo.',
                'price' => 950.00,
                'discount_price' => 899.00,
                'stock_quantity' => 50,
                'image' => 'products/samsung_s23.jpg',
                'gallery' => json_encode([
                    'products/samsung_s23_1.jpg',
                    'products/samsung_s23_2.jpg',
                    'products/samsung_s23_3.jpg',
                ]),
                'status' => 'active',
            ],
            [
                'name' => 'Laptop Apple MacBook Pro 16"',
                'description' => 'Puissant MacBook Pro pour les professionnels.',
                'price' => 2500.00,
                'discount_price' => 2399.00,
                'stock_quantity' => 20,
                'image' => 'products/macbook_pro.jpg',
                'gallery' => json_encode([
                    'products/macbook_pro_1.jpg',
                    'products/macbook_pro_2.jpg',
                ]),
                'status' => 'active',
            ],
            [
                'name' => 'Écouteurs sans fil Bose QuietComfort',
                'description' => 'Écouteurs avec suppression de bruit active.',
                'price' => 299.99,
                'discount_price' => null,
                'stock_quantity' => 100,
                'image' => 'products/bose_qc.jpg',
                'gallery' => json_encode([
                    'products/bose_qc_1.jpg',
                    'products/bose_qc_2.jpg',
                ]),
                'status' => 'active',
            ],
        ];

        foreach ($products as $productData) {
            // Choisir une catégorie aléatoire
            $category = $categories->random();

            Product::create([
                'category_id' => $category->id,
                'name' => $productData['name'],
                'slug' => Str::slug($productData['name']) . '-' . Str::random(5),
                'description' => $productData['description'],
                'price' => $productData['price'],
                'discount_price' => $productData['discount_price'],
                'stock_quantity' => $productData['stock_quantity'],
                'barcode' => (string) mt_rand(100000000000, 999999999999),
                'image' => $productData['image'],
                'gallery' => $productData['gallery'],
                'status' => $productData['status'],
            ]);
        }

        $this->command->info('Table products remplie avec succès !');
    }
}