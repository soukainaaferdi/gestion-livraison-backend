<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    //
    protected $fillable = ['client_id', 'produit', 'prix_total', 'destination', 'statut', 'livreur_id'];
    public function client() {
    return $this->belongsTo(Client::class);
}
    public function livreur()
{
    return $this->belongsTo(Livreur::class);
}
}
