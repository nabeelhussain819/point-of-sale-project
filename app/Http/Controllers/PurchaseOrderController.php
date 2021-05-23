<?php

namespace App\Http\Controllers;

use App\Models\ProductSerialNumbers;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrdersProduct;
use App\Models\Vendor;
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

        return redirect()->back()->with('success', 'New Purcahse Order Created');
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
        $purchaseOrder->delete();
        return redirect()->back()->with('success', 'Purchase Order delete');
    }

    /**
     * @param PurchaseOrder $purchaseOrder
     * @throws \Throwable
     */
    public function receivedForm(PurchaseOrder $purchaseOrder)
    {
        return view('admin.purchase_order.received', ['purchaseOrder' => $purchaseOrder]);
    }

    /**
     * @param Request $request
     * @param PurchaseOrder $purchaseOrder
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Throwable
     */
    public function received(Request $request, PurchaseOrder $purchaseOrder)
    {
        $aRequestParams = $request->input('product_serial');


        return redirect()->route('purchaseOrder.show-associate-product-serial', $purchaseOrder->id);
//        return redirect()->back()->with('success', 'Purchase Order received');
    }


    /**
     * @param PurchaseOrder $purchaseOrder
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     * @throws \Throwable
     */
    public function showAssociateProductSerial(PurchaseOrder $purchaseOrder)
    {
        $products = $purchaseOrder->products->filter(function (PurchaseOrdersProduct $product) {
            return $product->product->has_serial_number;
        });

        // if purchase order has
        if ($products->count() > 0) {
            return view('admin.purchase_order.associate-serial-product', ['purchaseOrder' => $purchaseOrder,
                'pOProducts' => $products]);
        }

        DB::transaction(function () use ($purchaseOrder) {
            if (is_null($purchaseOrder->received_date)) {
                $purchaseOrder->isReceiving = true;
                $purchaseOrder->update(['received_date' => Carbon::now()]);
            }
        });

        return redirect()->route('purchase-order.index')->with('success', 'Purchase Order received');
    }

    /**
     * @param Request $request
     * @param PurchaseOrder $purchaseOrder
     * @throws \Throwable
     */
    public function associateProductSerial(Request $request, PurchaseOrder $purchaseOrder)
    {
        return DB::transaction(function () use ($purchaseOrder, &$request) {
            if (is_null($purchaseOrder->received_date)) {
                $purchaseOrder->isReceiving = true;
                $purchaseOrder->update(['received_date' => Carbon::now()]);
                $purchaseOrder->save();

                ProductSerialNumbers::syncSerialWIthPurchaseOrder($request->get("serialProducts"), $purchaseOrder);
                //  $purchaseOrder->productSerialNumbers()->sync($products);
            }
            return redirect()->route('purchase-order.index')->with('success', 'Purchase Order received');
        });

    }

    public function view(PurchaseOrder $purchaseOrder)
    {
        
    }


}
