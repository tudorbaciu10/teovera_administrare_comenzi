<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $refrigerate = Category::where('nume_ro', 'Refrigerate')->first();
        $congelate   = Category::where('nume_ro', 'Congelate')->first();
        $marinate    = Category::where('nume_ro', 'Marinate')->first();

        $products = [
            // Refrigerate
            ['nume_ro' => 'Pulpă întreagă',  'nume_ru' => 'Целое бедро',          'unitate' => 'kg', 'category_id' => $refrigerate->id],
            ['nume_ro' => 'Piept',            'nume_ru' => 'Грудка',               'unitate' => 'kg', 'category_id' => $refrigerate->id],
            ['nume_ro' => 'Aripi',            'nume_ru' => 'Крылышки',             'unitate' => 'kg', 'category_id' => $refrigerate->id],
            ['nume_ro' => 'Gambe',            'nume_ru' => 'Голень',               'unitate' => 'kg', 'category_id' => $refrigerate->id],
            ['nume_ro' => 'Șolduri',          'nume_ru' => 'Бедро',                'unitate' => 'kg', 'category_id' => $refrigerate->id],
            ['nume_ro' => 'Carcasă întreagă', 'nume_ru' => 'Тушка целая',          'unitate' => 'kg', 'category_id' => $refrigerate->id],

            // Congelate
            ['nume_ro' => 'Pulpă congelată',   'nume_ru' => 'Бедро замороженное',  'unitate' => 'kg', 'category_id' => $congelate->id],
            ['nume_ro' => 'Piept congelat',     'nume_ru' => 'Грудка замороженная', 'unitate' => 'kg', 'category_id' => $congelate->id],
            ['nume_ro' => 'Carcasă congelată',  'nume_ru' => 'Тушка замороженная',  'unitate' => 'kg', 'category_id' => $congelate->id],

            // Marinate
            ['nume_ro' => 'Pulpă dezosată marinată', 'nume_ru' => 'Бедро б/к маринованное', 'unitate' => 'kg', 'category_id' => $marinate->id],
            ['nume_ro' => 'Aripi marinate',           'nume_ru' => 'Крылышки маринованные',  'unitate' => 'kg', 'category_id' => $marinate->id],
            ['nume_ro' => 'Gambe marinate',           'nume_ru' => 'Голень маринованная',     'unitate' => 'kg', 'category_id' => $marinate->id],
            ['nume_ro' => 'Șolduri marinate',         'nume_ru' => 'Бедро маринованное',      'unitate' => 'kg', 'category_id' => $marinate->id],
        ];

        foreach ($products as $p) {
            Product::create(array_merge($p, ['activ' => true]));
        }
    }
}
