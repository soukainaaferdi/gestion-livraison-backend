<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    //
    protected $fillable = ['client_id', 'produit', 'prix_total', 'prix_marchandise', 'frais_livraison', 'destination', 'statut', 'livreur_id','priority','destination_zone','destinataire_name','is_paid'];
    public function client() {
    return $this->belongsTo(Client::class);
}
    public function livreur()
{
    return $this->belongsTo(Livreur::class);
}
}
