<?php

use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\StoreController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin'], function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    });
    Route::Resources([
        'users' => UserController::class,
        'stores' => StoreController::class,
        'roles' => RoleController::class
    ]);
    Route::post('assign',[StoreController::class,'assignUserToStore'])->name('addusertostore');
});
