<?php

use App\Http\Controllers\RoleController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin'], function () {
    Route::get('/home', function () {
        return view('admin.dashboard');
    });
    Route::Resources([
        'users' => UserController::class,
        'stores' => StoreController::class,
        'roles' => RoleController::class
    ]);
    Route::post('assign',[StoreController::class,'assignUserToStore'])->name('addusertostore');
});
