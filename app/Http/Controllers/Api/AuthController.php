<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
  public function login(Request $request)
{
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

   if (Auth::attempt($credentials)) {
    $user = Auth::user();
    $livreurId = $user->id; // افتراضي

    if ($user->role === 'livreur') {
        // كنقلبو على الليفرور اللي مربوط بهاد الـ user_id
        $livreur = \App\Models\Livreur::where('user_id', $user->id)->first();
        if ($livreur) {
            $livreurId = $livreur->id; // هنا Hamza غياخد 3 عوض 2
        }
    }

    return response()->json([
        'status' => 'success',
        'user' => [
            'id' => $livreurId, 
            'name' => $user->name,
            'role' => $user->role,
        ]
    ], 200);
}

    return response()->json([
        'status' => 'error',
        'message' => 'Email ou mot de passe incorrect'
    ], 401);
}
        // إيلا كانت المعلومات غلط
    
    public function logout()
    {
        Auth::logout();
        return response()->json(['message' => 'Déconnecté مع السلامة']);
    }
}