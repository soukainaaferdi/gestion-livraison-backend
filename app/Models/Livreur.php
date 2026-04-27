<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Livreur extends Model
{
    protected $fillable = ['nom_complet', 'telephone', 'est_disponible','user_id'];
    public function orders()
{
    // هادي كتقول لـ Laravel بلي الليفرور يقدر يكون عنده بزاف ديال الطلبيات
    return $this->hasMany(Order::class);
}
}
