<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

/**
 * Associe à chaque catégorie sa photo locale (public/images/categories/<slug>.jpg).
 * Idempotent : ne modifie que image_url, et seulement si le fichier existe.
 * Crédits des photos : docs/CREDITS.md
 */
class CategoryImageSeeder extends Seeder
{
    public function run(): void
    {
        foreach (Category::all() as $category) {
            $file = 'images/categories/' . $category->slug . '.jpg';
            if (is_file(public_path($file))) {
                $category->update(['image_url' => $file]);
            }
        }
    }
}
