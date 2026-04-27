<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Livreur;
use App\Models\User; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; // ضروري باش تشفر Password

class LivreurController extends Controller
{
    public function index()
    {
        return response()->json(Livreur::all());
    }

    public function store(Request $request) 
    {
        // 1. تسجيل الحساب في جدول Users
        $user = User::create([
            'name'     => $request->nom_complet,
            'email'    => $request->email,
            'password' => Hash::make($request->password), // تشفير
            'role'     => 'livreur',
        ]);

        // 2. تسجيل المعلومات في جدول Livreurs
        $livreur = Livreur::create([
            'user_id'        => $user->id,
            'nom_complet'    => $request->nom_complet,
            'telephone'      => $request->telephone,
            'est_disponible' => $request->est_disponible ?? true,
        ]);

        return response()->json([
            'message' => 'Livreur et compte créés!',
            'user'    => $user,
            'livreur' => $livreur
        ], 201);
    }
}