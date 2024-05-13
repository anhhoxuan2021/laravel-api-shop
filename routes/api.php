<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProductController;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('/product-list', [ProductController::class, 'productList']);
Route::post('/product', [ProductController::class, 'saveProduct']);
Route::post('product/{prd_id}', [ProductController::class, 'updateproduct']);
Route::get('product-id', [ProductController::class, 'edit'])->name('file.edit');


//Route::delete('/product/{id}', [ProductController::class, 'destroy']);