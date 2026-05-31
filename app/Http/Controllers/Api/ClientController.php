<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index() {
        return response()->json(Client::all());
    }
    public function store(Request $request) {
           $user = \App\Models\User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => \Illuminate\Support\Facades\Hash::make($request->password),
        'role' => 'marchand',
    ]);
    $client = Client::create([
        'nom_complet' => $request->name, // React كيصيفط name، Laravel كيسجل nom_complet
        'telephone' => $request->phone,
        'adresse' => $request->address ?? '---',
        'user_id' => $user->id,
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





public function settlePayment($id) 
{
    // كنقلبو على الطلبيات اللي متعلقة فقط بـ هاد الـ ID
    // واللي الحالة ديالها "livre" (وصلات) ومزال ما تخلصاتش
    $updated = \App\Models\Order::where('client_id', $id)
        ->where('statut', 'livre')
        ->where('is_paid', false)
        ->update(['is_paid' => true]);

    return response()->json([
        'message' => 'تم تصفير الحساب بنجاح',
        'orders_updated' => $updated
    ]);
}




public function destroy($id)
{
    try {
        $client = Client::findOrFail($id);
        
        // 1. msa7 l user li mrt9 bih
        if ($client->user_id) {
            \App\Models\User::where('id', $client->user_id)->delete();
        }
        
        // 2. msa7 l client
        $client->delete();
        
        return response()->json(['message' => 'Marchand supprimé avec succès']);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Impossible de supprimer ce marchand'], 500);
    }
}
}
