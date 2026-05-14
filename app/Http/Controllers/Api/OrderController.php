<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Client;
use App\Models\Livreur;
class OrderController extends Controller
{
    //index
 public function index(Request $request) {
    $query = Order::query();
    if ($request->has('client_id')) {
        $query->where('client_id', $request->client_id);
    }
    return response()->json($query->get());
}
//stroe
 public function store(Request $request)
{
    $validated = $request->validate([
        'produit' => 'required',
        'destination' => 'required',
        'destination_zone' => 'required', // زيدي هادي
        'prix_total' => 'required|numeric',
        'priority' => 'integer', // زيدي هادي (1, 2, 3)
        'client_id' => 'required|exists:clients,id',
        'destinataire_name'=>'required',
        
    ]);

    // كنسيفيو كلشي بمرة وحدة
    $order = Order::create($request->all());
    
    return response()->json($order, 201);
}
// getLivreurOrders
public function getLivreurOrders($user_id) {
    // 1. أولاً كنجيبو الـ ID ديال الليفرور من جدول livreurs باستعمال الـ user_id
    $livreur = \App\Models\Livreur::where('user_id', $user_id)->first();

    if (!$livreur) {
        return response()->json([], 404); // إيلا مالقيناش هاد الليفرور
    }

    // 2. دابا كنجيبو الطلبيات باستعمال الـ ID الصحيح (livreur_id)
    return Order::where('livreur_id', $livreur->id)
                ->orderBy('priority', 'desc')
                ->get();
}
public function updateStatus(Request $request, $id) {
    $order = Order::findOrFail($id);
    $order->statut = $request->statut;
    $order->save();

    if ($order->livreur_id) {
        
        // ✅ livre فقط → زيد الsolde
        if ($request->statut === 'livre') {
            $livreur = \App\Models\Livreur::find($order->livreur_id);
            if ($livreur) {
                $livreur->solde += $order->prix_total;
                $livreur->save();

                $client = Client::find($order->client_id);
                if ($client) {
                    $client->solde += $order->prix_marchandise;
                    $client->save();
                }
            }
        }

        // ✅ retour أو annule → حرر الليفرور مباشرة
        if (in_array($request->statut, ['retour', 'annule'])) {
            \App\Models\Livreur::where('id', $order->livreur_id)
                               ->update(['est_disponible' => true]);
        }

        // ✅ livre → شوف واش بقاو assigne
        if ($request->statut === 'livre') {
            $remaining = Order::where('livreur_id', $order->livreur_id)
                              ->where('statut', 'assigne')
                              ->count();
            if ($remaining == 0) {
                \App\Models\Livreur::where('id', $order->livreur_id)
                                   ->update(['est_disponible' => true]);
            }
        }
    }

    return response()->json(['message' => 'Statut mis à jour avec succès']);
}

//getAvailableLivreursForOrder
public function getAvailableLivreursForOrder($id) {
    // 1. كنجيبو الطلبية باش نعرفو المنطقة ديالها
    $order = Order::findOrFail($id);
    
    // 2. كنجيبو غير الليفرورات اللي في نفس المنطقة وموجودين
    $livreurs = \App\Models\Livreur::where('zone', $order->destination_zone)->get();
                                    // ->where('est_disponible', true)
                                    

    return response()->json($livreurs);
}

//destroy
public function destroy($id)
{
    $order = Order::findOrFail($id);
    $order->delete();
    return response()->json(['message' => 'Commande supprimée']);
}
//show
public function show($id) {
    return response()->json(Order::with('client')->findOrFail($id));
}
//update
public function update(Request $request, $id) {
    $order = Order::findOrFail($id);
    // كانديرو التحديث للحقول اللي تصيفطات
    $order->update($request->all()); 
    
    return response()->json(['message' => 'Commande mise à jour avec succès', 'order' => $order]);
}
}
