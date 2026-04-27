<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    //
    public function index() {
        return response()->json(Client::all());
    }
    public function store(Request $request) {
    $client = Client::create([
        'nom_complet' => $request->name, // React كيصيفط name، Laravel كيسجل nom_complet
        'telephone' => $request->phone,
        'adresse' => $request->address ?? '---'
    ]);
    return response()->json($client, 201);
}
public function show($id)
{
    $client = \App\Models\Client::find($id);
    
    if (!$client) {
        return response()->json(['message' => 'Client non trouvé'], 404);
    }
    
    return response()->json($client);
}
}
