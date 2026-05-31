<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\LivreurController;
use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\AuthController;

// --- 1. Public Routes (الكل يقدر يوصل ليهم) ---
Route::post('/login', [AuthController::class, 'login']);


// --- 2. Routes Protected by Sanctum (خاص يكون مسجل الدخول) ---
Route::middleware('auth:sanctum')->group(function () {
    Route::middleware('admin')->group(function () {
        // إدارة الطلبيات (Orders)
        Route::get('/orders', [OrderController::class, 'index']);
        // Route::post('/orders', [OrderController::class, 'store']);
        Route::get('/orders/{id}', [OrderController::class, 'show']);
        Route::patch('/orders/{id}', [OrderController::class, 'update']);
        Route::delete('/orders/{id}', [OrderController::class, 'destroy']);
        Route::put('/orders/{id}/assign', [OrderController::class, 'assignLivreur']);
        Route::get('/orders/{id}/available-livreurs', [OrderController::class, 'getAvailableLivreursForOrder']);

        // إدارة الليفرورات (Livreurs)
        Route::get('/livreurs', [LivreurController::class, 'index']);
        Route::post('/livreurs', [LivreurController::class, 'store']);
        Route::post('/livreurs/{id}/update-payment', [LivreurController::class, 'updatePayment']);
        Route::post('/livreurs/{id}/toggle-status', [LivreurController::class, 'toggleStatus']);
       // مهمة للـ Dashboard

        // إدارة الكليان/التجار (Clients)
        Route::get('/clients', [ClientController::class, 'index']);
        Route::post('/clients', [ClientController::class, 'store']);
        Route::get('/clients/{id}', [ClientController::class, 'show']);
        Route::delete('/clients/{id}', [ClientController::class, 'destroy']);
        Route::post('/clients/{id}/settle', [ClientController::class, 'settlePayment']);

        Route::get('/admin/dashboard', function () { return "Welcome Admin"; });
    });
    
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user-data', function () { return auth()->user(); });

    // -----------------------------------------------------------
    // --- A. Admin MiddleWare (خاص بالآدمين فقط) ---
    // -----------------------------------------------------------

    // -----------------------------------------------------------
    // --- B. Livreur MiddleWare (خاص بالليفرور فقط) ---
    // -----------------------------------------------------------
    Route::middleware('livreur')->group(function () {
        // جلب طلبيات الليفرور اللي مسجل الدخول حاليا
        Route::get('/my-orders/{livreur_id}', [OrderController::class, 'getLivreurOrders']);
        
        // تحديث حالة الطلبية (Livré, Annulé...)
        Route::patch('/orders/{id}/status', [OrderController::class, 'updateStatus']);
        
        // جلب معلوماته الشخصية (Solde, etc)
        Route::get('/my-profile/{userId}', [LivreurController::class, 'getByUserId']);
         Route::get('/livreurs/by-user/{userId}', [LivreurController::class, 'getByUserId']); 
    });
    

});
Route::middleware(['auth:sanctum', 'marchand'])->group(function () {
    Route::get('/marchand/commandes', [OrderController::class, 'getMarchandOrders']);
    // Route::post('/orders', [OrderController::class, 'store']);
});
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/orders', [OrderController::class, 'store']);
});