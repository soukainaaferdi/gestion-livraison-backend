<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Client extends Model
{
    use HasFactory;
    //
    protected $fillable = ['nom_complet', 'telephone', 'adresse','user_id'];
    public function orders() {
    return $this->hasMany(Order::class);
}
}
