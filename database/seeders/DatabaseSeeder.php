<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            CategorySeeder::class,
            RouteSeeder::class,
            ProductSeeder::class,
            StoreSeeder::class,
            UserSeeder::class,
        ]);
    }
}
