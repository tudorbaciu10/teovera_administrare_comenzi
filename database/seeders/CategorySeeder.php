<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['nume' => 'Refrigerate', 'ordine_sortare' => 1],
            ['nume' => 'Congelate',   'ordine_sortare' => 2],
            ['nume' => 'Marinate',    'ordine_sortare' => 3],
        ];

        foreach ($categories as $cat) {
            Category::create($cat);
        }
    }
}
