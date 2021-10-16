<?php
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Auth;
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

Auth::routes(['register'=>false]);
Route::get('/data/{query}', [App\Http\Controllers\DataController::class, 'index'])->name('data');
Route::get('/total', [App\Http\Controllers\TotalController::class, 'index'])->name('total');
Route::get('/search/{query}', [App\Http\Controllers\SearchController::class, 'index'])->name('search');
Route::group(['middleware' => 'auth'], function () {

Route::get('/', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
Route::resources([
            'providers' => '\App\Http\Controllers\ProviderController',
            'products/categories' => '\App\Http\Controllers\ProductCategoryController',
            'products' => '\App\Http\Controllers\ProductController',
            'clients' => '\App\Http\Controllers\ClientController',
            'methods' => '\App\Http\Controllers\MethodController',
        ]);
 Route::get('clients/{client}/transactions/add', ['as' => 'clients.transactions.add', 'uses' => '\App\Http\Controllers\ClientController@addtransaction']);

 Route::resource('transactions', TransactionController::class)->except(['create', 'show']);
 Route::get('transactions/{type}', ['as' => 'transactions.type', 'uses' => '\App\Http\Controllers\TransactionController@type']);
 Route::get('transactions/{type}/create', ['as' => 'transactions.create', 'uses' => '\App\Http\Controllers\TransactionController@create']);



Route::resource('sales', '\App\Http\Controllers\SaleController')->except(['edit', 'update']);
Route::get('sales/{sale}/finalize', ['as' => 'sales.finalize', 'uses' => '\App\Http\Controllers\SaleController@finalize']);
Route::get('sales/{sale}/product/add', ['as' => 'sales.product.add', 'uses' => '\App\Http\Controllers\SaleController@addproduct']);
Route::post('sales/{sale}/product', ['as' => 'sales.product.store', 'uses' => '\App\Http\Controllers\SaleController@storeproduct']);
Route::post('sales/{sale}/transaction', ['as' => 'sales.transaction.store', 'uses' => '\App\Http\Controllers\SaleController@storetransaction']);
Route::delete('sales/{sale}/transaction/{transaction}', ['as' => 'sales.transaction.destroy', 'uses' => '\App\Http\Controllers\SaleController@destroytransaction']);
Route::post('sales/{sale}/transaction', ['as' => 'sales.transaction.store', 'uses' => '\App\Http\Controllers\SaleController@storetransaction']);
Route::get('sales/{sale}/product/{soldproduct}/edit', ['as' => 'sales.product.edit', 'uses' => '\App\Http\Controllers\SaleController@editproduct']);
Route::patch('sales/{sale}/product/{soldproduct}', ['as' => 'sales.product.update', 'uses' => '\App\Http\Controllers\SaleController@updateproduct']);
Route::delete('sales/{sale}/product/{soldproduct}', ['as' => 'sales.product.destroy', 'uses' => '\App\Http\Controllers\SaleController@destroyproduct']);
Route::get('unreadNotifications', ['as' => 'notifications', 'uses' => '\App\Http\Controllers\SaleController@unreadNotifications']);


Route::resource('purchases', '\App\Http\Controllers\ReceiptController')->except(['edit', 'update']);
Route::get('purchases/{purchase}/finalize', ['as' => 'purchases.finalize', 'uses' => '\App\Http\Controllers\ReceiptController@finalize']);
Route::get('purchases/{purchase}/product/add', ['as' => 'purchases.product.add', 'uses' => '\App\Http\Controllers\ReceiptController@addproduct']);
Route::get('purchases/{purchase}/product/{receivedproduct}/edit', ['as' => 'purchases.product.edit', 'uses' => '\App\Http\Controllers\ReceiptController@editproduct']);
Route::post('purchases/{purchase}/product', ['as' => 'purchases.product.store', 'uses' => '\App\Http\Controllers\ReceiptController@storeproduct']);
Route::match(['put', 'patch'], 'purchases/{purchase}/product/{receivedproduct}', ['as' => 'purchases.product.update', 'uses' => '\App\Http\Controllers\ReceiptController@updateproduct']);
Route::delete('purchases/{purchase}/product/{receivedproduct}', ['as' => 'purchases.product.destroy', 'uses' => '\App\Http\Controllers\ReceiptController@destroyproduct']);
Route::post('purchases/{purchase}/transaction', ['as' => 'purchase.transaction.store', 'uses' => '\App\Http\Controllers\ReceiptController@storetransaction']);
Route::post('purchases/{purchase}/transaction', ['as' => 'purchase.transaction.store', 'uses' => '\App\Http\Controllers\ReceiptController@storetransaction']);
Route::delete('purchases/{purchase}/transaction/{transaction}', ['as' => 'purchase.transaction.destroy', 'uses' => '\App\Http\Controllers\ReceiptController@destroytransaction']);
});
