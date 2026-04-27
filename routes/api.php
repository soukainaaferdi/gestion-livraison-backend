<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\LivreurController;
use App\Http\Controllers\Api\ClientController;

// Routes
Route::get('/orders', [OrderController::class, 'index']);
Route::post('/orders', [OrderController::class, 'store']);
Route::patch('/orders/{id}', [OrderController::class, 'update']);
Route::put('/orders/{id}/assign', [OrderController::class, 'assignLivreur']);

Route::get('/livreurs', [LivreurController::class, 'index']);
Route::post('/livreurs', [LivreurController::class, 'store']);

Route::get('/clients', [ClientController::class, 'index']);
Route::post('/clients', [ClientController::class, 'store']);
// زيد هادي باش تجيب معلومات تاجر واحد بـ ID ديالو
Route::get('/clients/{id}', [ClientController::class, 'show']);
use App\Http\Controllers\Api\AuthController;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);
// جلب طلبيات ليفرور معين
Route::get('/my-orders/{livreur_id}', [OrderController::class, 'getLivreurOrders']);
// تحديث حالة الطلبية
Route::patch('/orders/{id}/status', [OrderController::class, 'updateStatus']);