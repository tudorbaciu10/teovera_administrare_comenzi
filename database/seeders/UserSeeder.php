<?php

namespace Database\Seeders;

use App\Models\Store;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin și operator pentru Filament
        User::create([
            'name'     => 'Administrator',
            'email'    => 'admin@teovera.md',
            'password' => Hash::make('admin1234'),
            'rol'      => 'admin',
        ]);

        User::create([
            'name'     => 'Operator',
            'email'    => 'operator@teovera.md',
            'password' => Hash::make('operator1234'),
            'rol'      => 'operator',
        ]);

        // Vânzătoare demo: câte 1-2 per magazin
        $storeVanzatoare = [
            'Magazin Tîrnova 1'   => ['Maria Cojocaru'],
            'Magazin Drochia 1'   => ['Elena Lupu', 'Ana Rusu'],
            'Magazin Drochia 2'   => ['Natalia Botnari'],
            'Magazin Drochia 3'   => ['Irina Ciobanu', 'Olga Popescu'],
            'Magazin Soroca 1'    => ['Tatiana Moraru'],
            'Magazin Florești 1'  => ['Valentina Gînju'],
            'Magazin Soroca 2'    => ['Ludmila Balan', 'Svetlana Popa'],
            'Magazin Soroca 3'    => ['Galina Rotaru'],
            'Magazin Florești 2'  => ['Cristina Munteanu'],
            'Magazin Florești 3'  => ['Mariana Duca'],
            'Magazin Dondușeni'   => ['Nina Coban'],
            'Magazin Tîrnova 2'   => ['Ala Ștefan'],
            'Magazin Edineț'      => ['Victoria Mocanu', 'Doina Călin'],
            'Magazin Lipcani'     => ['Rodica Pavel'],
            'Magazin Briceni 1'   => ['Eugenia Hîțu'],
            'Magazin Briceni 2'   => ['Larisa Negru'],
            'Magazin Otaci'       => ['Alina Bobu'],
            'Magazin Rezina'      => ['Corina Ene'],
            'Magazin Bălți 1'     => ['Nadejda Luca', 'Daniela Sorocan'],
            'Magazin Bălți 2'     => ['Liuba Toma'],
            'Magazin Bălți 3'     => ['Vera Cebanu'],
            'Magazin Bălți 4'     => ['Tamara Micu'],
            'Magazin Orhei'       => ['Silvia Iovu'],
            'Magazin Ungheni 1'   => ['Carmen Ceban', 'Monica Vlas'],
            'Magazin Ungheni 2'   => ['Daniela Ionescu'],
            'Magazin Ungheni 3'   => ['Ioana Mircea'],
        ];

        foreach ($storeVanzatoare as $storeName => $names) {
            $store = Store::where('denumire', $storeName)->first();
            if (! $store) {
                continue;
            }

            foreach ($names as $name) {
                User::create([
                    'name'     => $name,
                    'email'    => null,
                    'password' => null,
                    'rol'      => 'vanzatoare',
                    'store_id' => $store->id,
                ]);
            }
        }
    }
}
