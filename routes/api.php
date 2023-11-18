<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\V1\InvoiceController;
use App\Http\Controllers\Api\V1\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('v1')->group(function(){
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{id}', [UserController::class, 'show']);

    Route::apiResource('invoices', InvoiceController::class);
    // Route::get('/invoices', [InvoiceController::class, 'index']);
    // Route::post('/invoices', [InvoiceController::class, 'store']);
    // Route::get('/invoices/{id}', [InvoiceController::class, 'show']);
    // Route::put('/invoices/{id}', [InvoiceController::class, 'update']);
    // Route::delete('/invoices/{id}', [InvoiceController::class, 'destroy']);
});

Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
