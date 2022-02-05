<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () { return redirect()->route('login'); });

Auth::routes();

// Access Client
// Delete Purchases
Route::delete('/purchases/{id}/{status}', 'App\Http\Controllers\PurchaseController@destroy')->name('purchases.destroyPurchase');

// Purchase Product
Route::get('/purchases/{purchase}/product/{number}/{qty?}', 'App\Http\Controllers\PurchaseController@purchaseProduct')->name('purchases.product');
Route::resource('/purchases', 'App\Http\Controllers\PurchaseController');

// Edit User
Route::get('/users/{user}/edit', 'App\Http\Controllers\UserController@edit')->name('users.edit');
Route::put('/users/{user}/update', 'App\Http\Controllers\UserController@update')->name('users.update');

// Access Administrator
Route::get('/products/{id}/detail', 'App\Http\Controllers\ProductController@detail')->name('products.detail');

// List Invoices Pending
Route::get('/invoices/pending', 'App\Http\Controllers\Admin\InvoiceController@pending')->name('invoices.pending')->middleware('is_admin');

// Mark as Paid
Route::get('/invoices/{invoice}/mark/{action}', 'App\Http\Controllers\Admin\InvoiceController@markPaid')->name('invoices.mark')->middleware('is_admin');

//Cancel Invoice
Route::get('/invoices/{invoice}/cancel', 'App\Http\Controllers\Admin\InvoiceController@cancel')->name('invoices.cancel')->middleware('is_admin');

// Route for Ajax
Route::get('/invoices/{id}/pending', 'App\Http\Controllers\Admin\InvoiceController@detail')->name('invoices.detail')->middleware('is_admin');

Route::resource('/products', 'App\Http\Controllers\Admin\ProductController')->middleware('is_admin');
Route::resource('/invoices', 'App\Http\Controllers\Admin\InvoiceController')->middleware('is_admin');
