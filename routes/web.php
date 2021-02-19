<?php

use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\OrderController;
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
        'sales' => SalesController::class,
        'orders' => OrderController::class,
    ]);
    Route::post('assign', [StoreController::class, 'assignUserToStore'])->name('addusertostore');
    Route::post('assign-role', [UserController::class, 'assignRole'])->name('addroletouser');
    Route::post('deassign-role', [UserController::class, 'deassignRole'])->name('remove.role');
    Route::get('change-password', [ChangePasswordController::class, 'index'])->name('password.change');
    Route::post('change-password', [ChangePasswordController::class, 'store'])->name('change.password');
});
