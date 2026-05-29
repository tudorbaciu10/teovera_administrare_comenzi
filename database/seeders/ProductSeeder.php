<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $refrigerate = Category::where('nume', 'Refrigerate')->first();
        $congelate   = Category::where('nume', 'Congelate')->first();
        $marinate    = Category::where('nume', 'Marinate')->first();

        $products = [
            // Refrigerate
            ['nume' => 'Pulpă întreagă',  'unitate' => 'kg', 'category_id' => $refrigerate->id],
            ['nume' => 'Piept',            'unitate' => 'kg', 'category_id' => $refrigerate->id],
            ['nume' => 'Aripi',            'unitate' => 'kg', 'category_id' => $refrigerate->id],
            ['nume' => 'Gambe',            'unitate' => 'kg', 'category_id' => $refrigerate->id],
            ['nume' => 'Șolduri',          'unitate' => 'kg', 'category_id' => $refrigerate->id],
            ['nume' => 'Carcasă întreagă', 'unitate' => 'kg', 'category_id' => $refrigerate->id],

            // Congelate
            ['nume' => 'Pulpă congelată',   'unitate' => 'kg', 'category_id' => $congelate->id],
            ['nume' => 'Piept congelat',     'unitate' => 'kg', 'category_id' => $congelate->id],
            ['nume' => 'Carcasă congelată',  'unitate' => 'kg', 'category_id' => $congelate->id],

            // Marinate
            ['nume' => 'Pulpă dezosată marinată', 'unitate' => 'kg', 'category_id' => $marinate->id],
            ['nume' => 'Aripi marinate',           'unitate' => 'kg', 'category_id' => $marinate->id],
            ['nume' => 'Gambe marinate',           'unitate' => 'kg', 'category_id' => $marinate->id],
            ['nume' => 'Șolduri marinate',         'unitate' => 'kg', 'category_id' => $marinate->id],
        ];

        foreach ($products as $p) {
            Product::create(array_merge($p, ['activ' => true]));
        }
    }
}
