<?php

namespace Database\Seeders;

use App\Models\Route;
use Illuminate\Database\Seeder;

class RouteSeeder extends Seeder
{
    public function run(): void
    {
        $routes = [
            [
                'nume'         => 'Ruta Nord-Vest (Lipcani–Edineț)',
                'zile_livrare' => ['luni', 'joi'],
                'zi_cutoff'    => 'duminica',
                'ora_cutoff'   => '18:00',
            ],
            [
                'nume'         => 'Ruta Nord (Drochia–Soroca)',
                'zile_livrare' => ['marti', 'vineri'],
                'zi_cutoff'    => 'luni',
                'ora_cutoff'   => '18:00',
            ],
            [
                'nume'         => 'Ruta Centru (Bălți–Rezina)',
                'zile_livrare' => ['miercuri', 'sambata'],
                'zi_cutoff'    => 'marti',
                'ora_cutoff'   => '18:00',
            ],
            [
                'nume'         => 'Ruta Centru-Sud (Orhei–Ungheni)',
                'zile_livrare' => ['joi'],
                'zi_cutoff'    => 'miercuri',
                'ora_cutoff'   => '18:00',
            ],
        ];

        foreach ($routes as $r) {
            Route::create($r);
        }
    }
}
