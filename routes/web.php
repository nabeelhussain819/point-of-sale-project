<?php

use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\TransferController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserStoreController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\SalesController;

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
Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::group(['middleware' => ['auth']], function () {
    Route::get('/home', function () {
        return view('admin.dashboard');
    })->middleware('changedPassword');
    Route::post('/home',[HomeController::class,'getStoreId'])->name('store.id');
    Route::Resources([
        'users' => UserController::class,
        'stores' => StoreController::class,
        'roles' => RoleController::class,
        'user-store' => UserStoreController::class,
        'customers' => CustomerController::class,
        'sales' => SalesController::class,
        'orders' => OrderController::class,
    ]);
    Route::group(['prefix' => 'product-management'], function (){
       Route::Resources([
           'categories' => CategoryController::class,
           'departments' => DepartmentController::class,
           'products' => ProductController::class,
           'stocks' => StockController::class
       ]);
    });
    Route::group(['prefix' => 'inventory-management'],function(){
        Route::Resources([
            'inventory' => InventoryController::class,
            'vendors' => VendorController::class,
        ]);
        Route::get('/sales-purchase',[SalesController::class,'purchase'])->name('purchase.index');
        Route::get('/sales-purchase-received/{vendor}',[SalesController::class,'purchaseReceived'])->name('purchase.received');
        Route::delete('/sales-purchase-delete/{vendor}',[SalesController::class,'destroyVendorProduct'])->name('purchase-vendor.delete');
        Route::post('/sales-purchase-received',[SalesController::class,'storeInInventory'])->name('purchase.received.generate');
        Route::get('/stock-transfer',[TransferController::class,'index'])->name('transfer.index');
        Route::get('/stock-transfer/create',[TransferController::class,'stockTransfer'])->name('transfer.create');
        Route::post('/stock-transfer',[TransferController::class,'transfer'])->name('transfer.store');
        Route::get('/stock-transfer/{transfer}',[TransferController::class,'received'])->name('transfer.received');
        Route::delete('/stock-transfer/{transfer}',[TransferController::class,'delete'])->name('transfer.delete');
    });
    Route::post('assign', [StoreController::class, 'assignUserToStore'])->name('addusertostore');
    Route::post('assign-role', [UserController::class, 'assignRole'])->name('addroletouser');
    Route::post('deassign-role', [UserController::class, 'deassignRole'])->name('remove.role');
    Route::get('change-password', [ChangePasswordController::class, 'index'])->name('password.change');
    Route::post('change-password', [ChangePasswordController::class, 'store'])->name('change.password');
});