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
            'zone'           => $request->zone,
            'est_disponible' => $request->est_disponible ?? true,
        ]);

        return response()->json([
            'message' => 'Livreur et compte créés!',
            'user'    => $user,
            'livreur' => $livreur
        ], 201);
    }




    public function updatePayment(Request $request, $id)
{
    $livreur = Livreur::findOrFail($id);
    
    if ($request->status == 'done') {
        $livreur->solde = 0; // خلصناه، الرصيد يرجع لصفر
    }
    
    // تقدري تزيد منطق للـ "nodone" هنا إيلا بغيتي
    
    $livreur->save();

    return response()->json(['message' => 'Paiement mis à jour', 'solde' => $livreur->solde]);
}



// في Controller المسوؤل عن الليفرور
public function toggleStatus($id) {
    $livreur = \App\Models\Livreur::findOrFail($id);
    
    // إيلا كان true كيرجع false والعكس (Toggle)
    $livreur->is_active = !$livreur->is_active; 
    $livreur->save();

    return response()->json([
        'message' => 'Statut changé avec succès',
        'is_active' => $livreur->is_active
    ]);
}



public function show($id)
{
    $livreur = Livreur::find($id);
    
    if (!$livreur) {
        return response()->json(['message' => 'Livreur non trouvé'], 404);
    }

    return response()->json($livreur);
}



public function getByUserId($userId)
{
    // كنقلبو في جدول livreurs على السطر اللي فيه user_id كيساوي الـ ID اللي صيفطنا
    $livreur = Livreur::where('user_id', $userId)->first();

    if (!$livreur) {
        return response()->json(['message' => 'Livreur non trouvé'], 404);
    }

    return response()->json($livreur);
}
}