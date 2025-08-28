<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Catégories principales
        $mainCategories = [
            ['name' => 'Smartphones', 'description' => 'Tous les types de smartphones.'],
            ['name' => 'Ordinateurs portables', 'description' => 'Laptops pour tous les usages.'],
            ['name' => 'Écouteurs et casques', 'description' => 'Écouteurs et casques audio.'],
            ['name' => 'Accessoires', 'description' => 'Accessoires électroniques variés.'],
        ];

        $categories = [];

        foreach ($mainCategories as $cat) {
            $category = Category::create([
                'name' => $cat['name'],
                'slug' => Str::slug($cat['name']),
                'description' => $cat['description'],
                'parent_id' => null,
            ]);
            $categories[] = $category;
        }

        // Sous-catégories (exemples)
        $subCategories = [
            ['name' => 'Android', 'parent' => 'Smartphones'],
            ['name' => 'iOS', 'parent' => 'Smartphones'],
            ['name' => 'Gaming', 'parent' => 'Ordinateurs portables'],
            ['name' => 'Ultrabooks', 'parent' => 'Ordinateurs portables'],
            ['name' => 'Bluetooth', 'parent' => 'Écouteurs et casques'],
            ['name' => 'Filaire', 'parent' => 'Écouteurs et casques'],
        ];

        foreach ($subCategories as $sub) {
            $parent = Category::where('name', $sub['parent'])->first();
            if ($parent) {
                Category::create([
                    'name' => $sub['name'],
                    'slug' => Str::slug($sub['name']),
                    'description' => 'Sous-catégorie de ' . $parent->name,
                    'parent_id' => $parent->id,
                ]);
            }
        }

        $this->command->info('Table categories remplie avec succès !');
    }
}