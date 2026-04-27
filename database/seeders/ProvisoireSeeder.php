<?php

namespace Database\Seeders;
use App\Models\Livreur; // <--- زيد هادا
use App\Models\Order;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProvisoireSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $livreur = Livreur::create([
            'nom_complet' => 'Ahmed Amine',
            'telephone' => '0600112233',
            'est_disponible' => true
        ]);
        Order::create([
            'produit' => 'Smartphone Samsung',
            'destination' => 'Casablanca, Maarif',
            'prix_total' => 3500.00,
            'statut' => 'assigne',
            'livreur_id' => $livreur->id
        ]);
        Order::create([
            'produit' => 'PC Portable HP',
            'destination' => 'Rabat, Agdal',
            'prix_total' => 7200.00,
            'statut' => 'en_attente',
            'livreur_id' => null
        ]);
    }
}
