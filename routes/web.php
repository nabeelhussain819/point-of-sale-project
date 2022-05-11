<?php

use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\DeviceTypeController;
use App\Http\Controllers\FinanceController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\IssueTypeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\ReconciliationController;
use App\Http\Controllers\RefundController;
use App\Http\Controllers\RepairController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\StockBinController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\TransferController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserStoreController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\TrackingController;
use \App\Http\Controllers\ReportController;
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
    return redirect('login');
});

Auth::routes();


Route::group(['middleware' => ['auth']], function () {
    Route::get('/home', function () {
        return view('admin.dashboard');
    })->middleware('changedPassword')
        ->name('home');

    Route::get('/welcome', function () {
        return view('common.comingsoon');
    })->name('welcome');

    Route::post('/home', [HomeController::class, 'getStoreId'])->name('store.id');

    Route::group(['prefix' => 'product'], function () {
        Route::get('/device-brand', [ProductController::class, 'deviceBrand']);
        Route::get('/all', [ProductController::class, 'all']);
        Route::get('/getAll', [ProductController::class, 'getAll']);
        Route::get('/associate-device-brand', [ProductController::class, 'associateDeviceBrand'])
            ->name('associate-device-brand');
        Route::get('/serials/{product}', [ProductController::class, 'getSerials']);

        Route::get('/track/{productSerialNumbers:serial_no}', [ProductController::class, 'serialTracking']);
        Route::get('/validate-serial/', [ProductController::class, 'validateSerial']);
    });

    Route::group(['prefix' => 'tracking'], function () {
        Route::get('/', [TrackingController::class, 'index'])->name('tracking');
        Route::get('/{productSerialNumbers:serial_no}', [TrackingController::class, 'serialTracking']);
        Route::get('/by-id/{serialLogs}', [TrackingController::class, 'show']);
    });


    Route::group(['prefix' => 'repair'], function () {
        Route::get('/fetch', [RepairController::class, 'fetch']);
        Route::get('/statuses', [RepairController::class, 'statuses']);
    });


    Route::group(['prefix' => 'customers'], function () {
        Route::get('/search', [CustomerController::class, 'search']);
        Route::get('/statuses', [RepairController::class, 'statuses']);
    });

    Route::group(['prefix' => 'vendor'], function () {
        Route::get('/search', [VendorController::class, 'search']);
        Route::get('/list', [VendorController::class, 'getList']);
        Route::get('/refunded', [VendorController::class, 'vendorReturns']);
        Route::get('/refunded-list', [VendorController::class, 'refundedList']);
    });

    Route::group(['prefix' => 'reconciliation'], function () {
        Route::get('/conciliation/{reconciliation}', [ReconciliationController::class, 'conciliation'])->name('conciliation');
        Route::post('/conciliation', [ReconciliationController::class, 'saveConciliation'])->name('conciliation.save');
        Route::post('/matchConciliation', [ReconciliationController::class, 'matchConciliation']);
    });
    Route::group(['prefix' => 'inventory-management'], function () {
        Route::Resources([
            'inventory' => InventoryController::class,
            'vendors' => VendorController::class,
        ]);
        Route::get('/sales-purchase', [SalesController::class, 'purchase'])->name('purchase.index');
        Route::get('/sales-purchase-received/{vendor}', [SalesController::class, 'purchaseReceived'])->name('purchase.received');
        Route::delete('/sales-purchase-delete/{vendor}', [SalesController::class, 'destroyVendorProduct'])->name('purchase-vendor.delete');
        Route::post('/sales-purchase-received', [SalesController::class, 'storeInInventory'])->name('purchase.received.generate');
        Route::get('/stock-transfer', [TransferController::class, 'index'])->name('transfer.index');
        Route::get('/stock-transfer/create', [TransferController::class, 'stockTransfer'])->name('transfer.create');
        Route::post('/stock-transfer', [TransferController::class, 'store'])->name('transfer.store');
        Route::get('/stock-transfer/all', [TransferController::class, 'all']);
        Route::get('/stock-transfer/{transfer}', [TransferController::class, 'received'])->name('transfer.received');
        Route::get('/transfer-view/{transfer}', [TransferController::class, 'show'])->name('transfer.show');
        Route::delete('/stock-transfer/{transfer}', [TransferController::class, 'delete'])->name('transfer.delete');

        Route::post('/stock-transfer-received/{transfer}', [TransferController::class, 'markAsReceived'])->name('transfer.markasreceived');


        Route::group(['prefix' => 'stock-transfer'], function () {

            Route::get('/associate-product-serial/{transfer}', [TransferController::class, 'showAssociateProductSerial'])
                ->name('transfer.show-associate-product-serial');

            Route::Post('/associate-product-serial/{purchaseOrder}', [TransferController::class, 'associateProductSerial'])
                ->name('transfer.associate-product-serial');
        });

//        revamp
    });

    Route::get('transfer/view/{stockTransfer}', [TransferController::class, 'view'])
        ->name('transfer.view');

    Route::get('sales/view/{order}', [SalesController::class, 'view'])
        ->name('order.view');


    Route::get('refund/view/{refund}', [SalesController::class, 'view'])
        ->name('refund.view');


    Route::post('assign', [StoreController::class, 'assignUserToStore'])->name('addusertostore');
    Route::post('assign-role', [UserController::class, 'assignRole'])->name('addroletouser');
    Route::post('deassign-role', [UserController::class, 'deassignRole'])->name('remove.role');
    Route::get('change-password', [ChangePasswordController::class, 'index'])->name('password.change');
    Route::post('change-password', [ChangePasswordController::class, 'store'])->name('change.password');

    Route::get('deviceType/lists', [DeviceTypeController::class, 'lists'])->name('deviceType');
    Route::get('brand/lists', [BrandController::class, 'lists'])->name('brand');
    Route::get('issue-type/lists', [IssueTypeController::class, 'lists'])->name('issue-type');

    Route::group(['prefix' => 'deviceType'], function () {
        Route::get('/search', [DeviceTypeController::class, 'search']);
    });

    Route::group(['prefix' => 'purchase-order'], function () {
        Route::get('/received-form/{purchaseOrder}', [PurchaseOrderController::class, 'receivedForm'])
            ->name('purchaseOrder.received')->middleware('checkPermission:department-list');
        // Route::get('/received/{purchaseOrder}', [PurchaseOrderController::class, 'received'])->name('purchaseOrder.received-done');
        Route::post('/received/{purchaseOrder}', [PurchaseOrderController::class, 'received'])
            ->name('purchaseOrder.received-done');

        Route::get('/associate-product-serial/{purchaseOrder}', [PurchaseOrderController::class, 'showAssociateProductSerial'])
            ->name('purchaseOrder.show-associate-product-serial');

        Route::Post('/associate-product-serial/{purchaseOrder}', [PurchaseOrderController::class, 'associateProductSerial'])
            ->name('purchaseOrder.associate-product-serial');

        Route::get('/view/{purchaseOrder}', [PurchaseOrderController::class, 'view'])
            ->name('purchaseOrder.view');

        Route::get('/show/{purchaseOrder}', [PurchaseOrderController::class, 'show'])
            ->name('purchaseOrder.show');

        Route::get('/all', [PurchaseOrderController::class, 'all']);
    });

    Route::group(['prefix' => 'orders'], function () {
        Route::get('/fetch', [OrderController::class, 'fetch']);
        Route::get('/printableDetail', [OrderController::class, 'printableDetail']);
        Route::get('/print/{order}', [OrderController::class, 'print']);
        Route::get('/getIds', [OrderController::class, 'getIds']);
    });

    Route::group(['prefix' => 'inventory'], function () {
        Route::get('/products', [InventoryController::class, 'products']);
        Route::get('/fetch', [InventoryController::class, 'list']);
        Route::get('/all', [InventoryController::class, 'all']);
        Route::put("{inventory}/change-bin", [InventoryController::class, 'changeBin']);
        Route::get('/search', [InventoryController::class, 'search']);
        Route::get('/product-quantity', [InventoryController::class, 'checkProductCount']);
    });

    Route::group(['prefix' => 'finance'], function () {
        Route::get('/fetch', [FinanceController::class, 'fetch']);
        Route::patch('/installment/{finance}', [FinanceController::class, 'installment']);
        Route::post('/payInstallment/{finance}', [FinanceController::class, 'payInstallment']);
        Route::post('/uploads/{finance}', [FinanceController::class, 'upload']);
    });

    Route::group(['prefix' => 'reports'], function () {
        Route::get('/sales', [ReportController::class, 'sales']);
        Route::get('/get-sales-stats', [ReportController::class, 'getSalesStates']);


        Route::get('/finance', [ReportController::class, 'finance']);
        Route::get('/get-finance-stats', [ReportController::class, 'getFinanceStates']);

        Route::get('/return', [ReportController::class, 'return']);
        Route::get('/get-return-stats', [ReportController::class, 'getReturnStates']);

        Route::get('/transfer', [ReportController::class, 'transfer']);
        Route::get('/get-transfer-stats', [ReportController::class, 'getTransferStates']);

        Route::get('/inventory', [ReportController::class, 'inventory']);
        Route::get('/get-inventory-stats', [ReportController::class, 'getInventoryStates']);

        Route::get('/purchase', [ReportController::class, 'purchase']);
        Route::get('/get-purchase-stats', [ReportController::class, 'getPurchaseStates']);

        Route::get('/repair', [ReportController::class, 'repair']);
        Route::get('/get-repair-stats', [ReportController::class, 'getRepairStates']);

        Route::get('get-report-serial', [ReportController::class, 'getReportingSerialNumbers']);
        Route::get('/', [ReportController::class, 'index'])->name("reports");
        Route::get('/entity-search', [ReportController::class, 'entitySearch']);

        Route::get('/{name}', [ReportController::class, 'detail']);
        Route::get('/sales', [ReportController::class, 'sales']);
    });

    Route::group(['prefix' => 'store'], function () {
        Route::get('/tax', [StoreController::class, 'getTax']);
        Route::get('/all', [StoreController::class, 'all']);
    });

    Route::group(['prefix' => 'refund'], function () {
        Route::get('/order/{order}', [RefundController::class, 'order']);
    });

    Route::group(['prefix' => 'stock-bin'], function () {
        Route::get('/get', [StockBinController::class, 'get']);
    });

    Route::group(['prefix' => 'category'], function () {
        Route::get('/all', [CategoryController::class, 'all']);
    });

    Route::group(['prefix' => 'department'],
        function () {
            Route::get('/all', [DepartmentController::class, 'all']);
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
        'finance' => FinanceController::class,
        'refund' => RefundController::class,
        'serialLog' => \App\Models\SerialLogs::class,
    ]);

    Route::group(['prefix' => 'product-management'], function () {
        Route::Resources([
            'categories' => CategoryController::class,
            'departments' => DepartmentController::class,
            'products' => ProductController::class,
            'stock-bin' => StockBinController::class
        ]);
    });

});
