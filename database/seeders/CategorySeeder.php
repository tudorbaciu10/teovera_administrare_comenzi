<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['nume_ro' => 'Refrigerate', 'nume_ru' => 'Охлаждённые', 'ordine_sortare' => 1],
            ['nume_ro' => 'Congelate',   'nume_ru' => 'Замороженные', 'ordine_sortare' => 2],
            ['nume_ro' => 'Marinate',    'nume_ru' => 'Маринованные', 'ordine_sortare' => 3],
        ];

        foreach ($categories as $cat) {
            Category::create($cat);
        }
    }
}
