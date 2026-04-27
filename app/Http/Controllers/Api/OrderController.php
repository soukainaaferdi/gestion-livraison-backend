<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
class OrderController extends Controller
{
    //
 public function index(Request $request) {
    $query = Order::query();
    if ($request->has('client_id')) {
        $query->where('client_id', $request->client_id);
    }
    return response()->json($query->get());
}
    public function store(Request $request)
    {
        $validated = $request->validate([
            'produit' => 'required',
            'destination' => 'required',
            'prix_total' => 'required|numeric',
            'client_id' => 'required|exists:clients,id',
        ]);
           $order = Order::create($request->all());
    return response()->json($order, 201);
    }
    public function update(Request $request, $id)
{
    $order = Order::findOrFail($id);
    
    // كنحدثو غير الحقول اللي صيفطنا من React
    $order->update([
        'livreur_id' => $request->livreur_id,
        'statut' => $request->statut
    ]);

    return response()->json($order);
}
public function getLivreurOrders($livreur_id) {
    // كنجيبو الطلبيات اللي مربوطة بهاد الليفرور
    return Order::where('livreur_id', $livreur_id)->get();
    
   
}

public function updateStatus(Request $request, $id) {
    $order = Order::find($id);
    $order->statut = $request->statut;
    $order->save();

    // إيلا بغيتي ترجع الليفرور disponible ملي يسالي
    // كتشوف واش باقي عندو شي حاجة "assigned"
    $remaining = Order::where('livreur_id', $order->livreur_id)->where('statut', 'assigne')->count();
    if ($remaining == 0) {
        $livreur = Livreur::where('id', $order->livreur_id)->update(['est_disponible' => true]);
    }

    return response()->json(['message' => 'Statut mis à jour']);
}
}
