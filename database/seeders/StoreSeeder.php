<?php

namespace Database\Seeders;

use App\Models\Route;
use App\Models\Store;
use Illuminate\Database\Seeder;

class StoreSeeder extends Seeder
{
    public function run(): void
    {
        $nordVest  = Route::where('nume', 'like', '%Nord-Vest%')->first();
        $nord      = Route::where('nume', 'like', '%Nord (%')->first();
        $centru    = Route::where('nume', 'like', '%Centru (%')->first();
        $centruSud = Route::where('nume', 'like', '%Centru-Sud%')->first();

        $stores = [
            // Ruta Nord (Drochia, Soroca, Dondușeni, Tîrnova, Florești)
            ['denumire' => 'Magazin Tîrnova 1',      'localitate' => 'Tîrnova',   'adresa' => 's. Tîrnova, r-l Dondușeni',              'route_id' => $nord->id],
            ['denumire' => 'Magazin Drochia 1',       'localitate' => 'Drochia',   'adresa' => 'or. Drochia, str. 27 August 34/2',        'route_id' => $nord->id],
            ['denumire' => 'Magazin Drochia 2',       'localitate' => 'Drochia',   'adresa' => 'or. Drochia, str. 31 August 27B',         'route_id' => $nord->id],
            ['denumire' => 'Magazin Drochia 3',       'localitate' => 'Drochia',   'adresa' => 'or. Drochia, str. Independenței 8/1',     'route_id' => $nord->id],
            ['denumire' => 'Magazin Soroca 1',        'localitate' => 'Soroca',    'adresa' => 'or. Soroca, str. D. Cantemir f/n',        'route_id' => $nord->id],
            ['denumire' => 'Magazin Florești 1',      'localitate' => 'Florești',  'adresa' => 'or. Florești, str. 31 August 53-2',       'route_id' => $nord->id],
            ['denumire' => 'Magazin Soroca 2',        'localitate' => 'Soroca',    'adresa' => 'or. Soroca, str. Ștefan cel Mare 18/a',   'route_id' => $nord->id],
            ['denumire' => 'Magazin Soroca 3',        'localitate' => 'Soroca',    'adresa' => 'or. Soroca, str. Independenței 75A',      'route_id' => $nord->id],
            ['denumire' => 'Magazin Florești 2',      'localitate' => 'Florești',  'adresa' => 'or. Florești, str. Ștefan cel Mare 21',   'route_id' => $nord->id],
            ['denumire' => 'Magazin Florești 3',      'localitate' => 'Florești',  'adresa' => 'or. Florești, str. Independenței 1',      'route_id' => $nord->id],
            ['denumire' => 'Magazin Dondușeni',       'localitate' => 'Dondușeni', 'adresa' => 'or. Dondușeni, str. Feroviarilor 5',      'route_id' => $nord->id],
            ['denumire' => 'Magazin Tîrnova 2',       'localitate' => 'Tîrnova',   'adresa' => 's. Tîrnova, r-nul Dondușeni',             'route_id' => $nord->id],

            // Ruta Nord-Vest (Lipcani, Briceni, Edineț, Otaci)
            ['denumire' => 'Magazin Edineț',          'localitate' => 'Edineț',    'adresa' => 'or. Edineț, str. Alexandru cel Bun 94',   'route_id' => $nordVest->id],
            ['denumire' => 'Magazin Lipcani',         'localitate' => 'Lipcani',   'adresa' => 'or. Lipcani, r-nul Briceni',              'route_id' => $nordVest->id],
            ['denumire' => 'Magazin Briceni 1',       'localitate' => 'Briceni',   'adresa' => 'or. Briceni, str. Independenței 1',       'route_id' => $nordVest->id],
            ['denumire' => 'Magazin Briceni 2',       'localitate' => 'Briceni',   'adresa' => 'or. Briceni, str. Ferarilor 1',           'route_id' => $nordVest->id],
            ['denumire' => 'Magazin Otaci',           'localitate' => 'Otaci',     'adresa' => 'or. Otaci, str. Drujba',                  'route_id' => $nordVest->id],

            // Ruta Centru (Bălți, Rezina)
            ['denumire' => 'Magazin Rezina',          'localitate' => 'Rezina',    'adresa' => 'or. Rezina, str. Sciusev 2/42',           'route_id' => $centru->id],
            ['denumire' => 'Magazin Bălți 1',         'localitate' => 'Bălți',     'adresa' => 'mun. Bălți, str. A. Pușchin f/n',        'route_id' => $centru->id],
            ['denumire' => 'Magazin Bălți 2',         'localitate' => 'Bălți',     'adresa' => 'mun. Bălți, str. Kiev 2',                 'route_id' => $centru->id],
            ['denumire' => 'Magazin Bălți 3',         'localitate' => 'Bălți',     'adresa' => 'mun. Bălți, str. Bulgară 57',             'route_id' => $centru->id],
            ['denumire' => 'Magazin Bălți 4',         'localitate' => 'Bălți',     'adresa' => 'mun. Bălți, str. Calea Eșilor (raionul pieței)', 'route_id' => $centru->id],

            // Ruta Centru-Sud (Orhei, Ungheni)
            ['denumire' => 'Magazin Orhei',           'localitate' => 'Orhei',     'adresa' => 'mun. Orhei, str. Vasile Lupu 26',         'route_id' => $centruSud->id],
            ['denumire' => 'Magazin Ungheni 1',       'localitate' => 'Ungheni',   'adresa' => 'mun. Ungheni, str. Feroviarilor 29',      'route_id' => $centruSud->id],
            ['denumire' => 'Magazin Ungheni 2',       'localitate' => 'Ungheni',   'adresa' => 'mun. Ungheni, str. Barbu Lăutaru 27',     'route_id' => $centruSud->id],
            ['denumire' => 'Magazin Ungheni 3',       'localitate' => 'Ungheni',   'adresa' => 'mun. Ungheni, str. Bernardazzi 11/1',     'route_id' => $centruSud->id],
        ];

        foreach ($stores as $s) {
            Store::create(array_merge($s, ['tip' => 'magazin']));
        }
    }
}
