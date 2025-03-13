<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    public function run()
    {
        $countries = [
            [
                'name' => 'France',
                'capital' => 'Paris',
                'population' => 67000000,
                'region' => 'Europe',
                'flag_url' => 'https://upload.wikimedia.org/wikipedia/commons/c/c3/Flag_of_France.svg',
                'language' => 'Français',
                'currency' => 'Euro',
                'motto' => 'Liberté, Égalité, Fraternité'
            ],
            [
                'name' => 'Maroc',
                'capital' => 'Rabat',
                'population' => 36910558,
                'region' => 'Afrique',
                'flag_url' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQKEmSzfWpnPOJ-5YiSDwSHneUXYSbCrybwgQ&s',
                'language' => 'Arabe, Amazigh',
                'currency' => 'Dirham marocain',
                'motto' => 'Dieu, La Patrie, Le Roi'
            ],
            [
                'name' => 'États-Unis',
                'capital' => 'Washington D.C.',
                'population' => 331000000,
                'region' => 'Amérique du Nord',
                'flag_url' => 'https://www.larousse.fr/encyclopedie/data/images/1009488-Drapeau_des_%C3%89tats-Unis.jpg',
                'language' => 'Anglais',
                'currency' => 'Dollar américain',
                'motto' => 'In God We Trust'
            ],
        ];

        foreach ($countries as $country) {
            Country::create($country);
        }
    }
}