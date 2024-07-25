<?php

use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProductController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('/invoices', [InvoiceController::class, 'index']);
Route::get('/invoices/{id}', [InvoiceController::class, 'show']);
Route::get('/create-invoice', [InvoiceController::class, 'create']);
Route::post('/add-invoice', [InvoiceController::class, 'addInvoice']);
Route::post('/update-invoice/{id}', [InvoiceController::class, 'updateInvoice']);
Route::get('/delete-invoice/{id}', [InvoiceController::class, 'deleteInvoice']);
Route::get('/delete-invoice-item/{id}', [InvoiceController::class, 'deleteInvoiceItem']);

Route::get('/customers', [CustomerController::class, 'index']);
Route::get('/products', [ProductController::class, 'index']);
