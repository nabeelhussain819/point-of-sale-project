<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\PurchaseOrdersProduct;
use App\Models\Vendor;
use App\Models\ProductSerialNumbers;
use App\Models\Store;
use App\Observers\PurchaseOrderObserver;
use Carbon\Carbon;
use DB;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;

class PurchaseOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.purchase_order.index',
            [
                'vendors' => Vendor::get(),
                'purchaseOrder' => PurchaseOrder::whereNull('received_date')
                    ->with(['products' => function (HasMany $belongsTo) {
                        $belongsTo->with('product');
                    }, 'vendor'])
                    ->get()
            ]// optimize this as well]
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    public function boot()
    {
        parent::boot();
        PurchaseOrder::observe(PurchaseOrderObserver::class);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'vendor_id' => 'required',
            'store_id' => 'required',
            'expected_date' => 'required',
            'products' => 'required',
        ]);

        $purchaseOrder = new PurchaseOrder();
        $purchaseOrderProduct = new PurchaseOrdersProduct();
        $totalPrice = 0;
        $productData = [];
        ////@todo Ugly code Move this in observer

        collect($request->get('products'))->each(function ($product) use (&$productData, $request, &$totalPrice, $purchaseOrderProduct) {
            $total = $product['price'] * $product['quantity'];
            $totalPrice += $total;

            $productData[] = array_merge($product,
                [
                    'expected_price' => $product['price'],
                    'total' => $total,
                    'expected_total' => $total,
                ]
            //@todo move all this work in observer   after meeting
            );
        });

        //@todo Observer ugent piece of work
        $PurchaseOrderData = array_merge($request->all(), [
            'expected_price' => $totalPrice,
            'price' => $totalPrice
        ]);


        $purchaseOrder->fill($PurchaseOrderData)->save();
        $purchaseOrder->purchaseOrdersProducts()->sync($productData);

        return redirect()->back()->with('success', 'New Sale Created');
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\PurchaseOrder $purchaseOrder
     * @return \Illuminate\Http\Response
     */
    public function show(PurchaseOrder $purchaseOrder)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\PurchaseOrder $purchaseOrder
     * @return \Illuminate\Http\Response
     */
    public function edit(PurchaseOrder $purchaseOrder)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\PurchaseOrder $purchaseOrder
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PurchaseOrder $purchaseOrder)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\PurchaseOrder $purchaseOrder
     * @return \Illuminate\Http\Response
     */
    public function destroy(PurchaseOrder $purchaseOrder)
    {
        //
    }

    /**
     * @param PurchaseOrder $purchaseOrder
     * @throws \Throwable
     */
    public function receivedForm(PurchaseOrder $purchaseOrder)
    {
        return view('admin.purchase_order.received', ['purchaseOrder' => $purchaseOrder]);
    }

    public function received(Request $request, PurchaseOrder $purchaseOrder)
    {
        $aRequestParams = $request->input('product_serial');

     // dd($purchaseOrder);
     //    dd($request);
     //    die("her");
        DB::transaction(function () use ($aRequestParams, $purchaseOrder) {
            if(is_null($purchaseOrder->received_date)){
                $this->storeSerialNo($aRequestParams);
                $purchaseOrder->isReceiving = true;
                $purchaseOrder->update(['received_date' => Carbon::now()]);
            }

        });
        return redirect()->back()->with('success', 'Purchase Order received');
    }

    public function storeSerialNo($aRequestParams){
        if(!empty($aRequestParams)){
            foreach($aRequestParams as $key => $value){
                $aTempData = $value;
                // dd($value);
                $aTempData['store_id'] = Store::currentId();
                $aTempData['created_at'] = Carbon::now();
                $aTempData['updated_at'] = Carbon::now();
                $aData[] = $aTempData;
            }
            // dd($aData);
            return ProductSerialNumbers::insert($aData);
        }
    }
}
