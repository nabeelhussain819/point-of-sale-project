<?php

use App\Http\Controllers\RoleController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserStoreController;
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
    ]);
    Route::post('assign',[StoreController::class,'assignUserToStore'])->name('addusertostore');
    Route::post('assign-role',[UserController::class,'assignRole'])->name('addroletouser');
});
