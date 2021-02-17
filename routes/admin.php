<?php

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
use \App\Http\Controllers\SalesController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin'], function () {

    Route::get('/home', function () {
        return view('admin.dashboard');
    })->middleware('role:super-admin');

    Route::Resources([
        'users' => UserController::class,
        'stores' => StoreController::class,
        'roles' => RoleController::class,
        'user-store' => UserStoreController::class,
        'vendors' => VendorController::class,
        'categories' => CategoryController::class,
        'departments' => DepartmentController::class,
        'products' => ProductController::class,
        'inventory' => InventoryController::class,
        'customers' => CustomerController::class,
        'sales' => SalesController::class
    ]);
    Route::post('assign',[StoreController::class,'assignUserToStore'])->name('addusertostore');
    Route::post('assign-role',[UserController::class,'assignRole'])->name('addroletouser');
    Route::post('deassign-role',[UserController::class,'deassignRole'])->name('remove.role');
});
