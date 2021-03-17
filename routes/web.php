<?php

use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\DeviceTypeController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\IssueTypeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\ReconciliationController;
use App\Http\Controllers\RepairController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\StockBinController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\TransferController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserStoreController;
use App\Http\Controllers\VendorController;
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
Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::group(['middleware' => ['auth']], function () {
    Route::get('/home', function () {
        return view('admin.dashboard');
    })->middleware('changedPassword');
    Route::post('/home',[HomeController::class,'getStoreId'])->name('store.id');


    Route::group(['prefix' => 'product'], function (){
        Route::get('/device-brand', [ProductController::class, 'deviceBrand']);
    });

    Route::group(['prefix' => 'repair'], function () {
        Route::get('/fetch', [RepairController::class, 'fetch']);
        Route::get('/statuses', [RepairController::class, 'statuses']);
    });

    Route::group(['prefix' => 'customers'], function () {
        Route::get('/search', [CustomerController::class, 'search']);
        Route::get('/statuses', [RepairController::class, 'statuses']);
    });

    Route::group(['prefix' => 'reconciliation'], function () {
        Route::get('/conciliation/{reconciliation}', [ReconciliationController::class, 'conciliation'])->name('conciliation');
        Route::post('/conciliation', [ReconciliationController::class, 'saveConciliation'])->name('conciliation.save');
        Route::post('/matchConciliation', [ReconciliationController::class, 'matchConciliation']) ;
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
        Route::post('/stock-transfer-received/{transfer}', [TransferController::class, 'markAsReceived'])->name('transfer.markasreceived');
//        revamp
    });


    Route::post('assign', [StoreController::class, 'assignUserToStore'])->name('addusertostore');
    Route::post('assign-role', [UserController::class, 'assignRole'])->name('addroletouser');
    Route::post('deassign-role', [UserController::class, 'deassignRole'])->name('remove.role');
    Route::get('change-password', [ChangePasswordController::class, 'index'])->name('password.change');
    Route::post('change-password', [ChangePasswordController::class, 'store'])->name('change.password');

    Route::get('deviceType/lists', [DeviceTypeController::class, 'lists']);

    Route::group(['prefix' => 'purchase-order'], function () {
        Route::get('/received-form/{purchaseOrder}', [PurchaseOrderController::class, 'receivedForm'])
            ->name('purchaseOrder.received');
        Route::get('/received/{purchaseOrder}', [PurchaseOrderController::class, 'received'])
            ->name('purchaseOrder.received-done');

    });

    Route::Resources([
        'users' => UserController::class,
        'stores' => StoreController::class,
        'roles' => RoleController::class,
        'user-store' => UserStoreController::class,
        'customers' => CustomerController::class,
        'sales' => SalesController::class,
        'orders' => OrderController::class,
        'purchase-order' => PurchaseOrderController::class,
        'reconciliation' => ReconciliationController::class,
        'repair' => RepairController::class,
        'deviceType' => DeviceTypeController::class,
        'brand' => BrandController::class,
        'issue-type' => IssueTypeController::class,
    ]);

    Route::group(['prefix' => 'product-management'], function (){
        Route::Resources([
            'categories' => CategoryController::class,
            'departments' => DepartmentController::class,
            'products' => ProductController::class,
            'stock-bin' => StockBinController::class
        ]);
    });

});