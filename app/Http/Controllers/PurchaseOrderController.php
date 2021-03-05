<?php

namespace App\Http\Controllers;

use App\Models\OrderProduct;
use App\Models\PurchaseOrder;
use App\Models\Vendor;
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
            ['sales' => OrderProduct::with('inventory', 'vendor')->get(),
                'vendors' => Vendor::get()]
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $purchaseOrder = new PurchaseOrder();
        $totalPrice = 0;

        $productData = [];
        collect($request->get('products'))->each(function ($product) use (&$productData, $request) {
            $productData[] = array_merge($product,
                [
                    'type_id' => $request->get('type_id'),
                    'vendor_id' => $request->get('vendor_id'),
                    'customer_id' => $request->get('customer_id')
                ]
            );
        });

        $purchaseOrder->fill($request->all());
        dd($request->all());

        $purchaseOrder
            ->fill($purchasesOrderData)
            ->save();
        $purchaseOrder->purchaseOrdersProducts()->sync($productData);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PurchaseOrder $purchaseOrder
     * @return \Illuminate\Http\Response
     */
    public function show(PurchaseOrder $purchaseOrder)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PurchaseOrder $purchaseOrder
     * @return \Illuminate\Http\Response
     */
    public function edit(PurchaseOrder $purchaseOrder)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\PurchaseOrder $purchaseOrder
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PurchaseOrder $purchaseOrder)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PurchaseOrder $purchaseOrder
     * @return \Illuminate\Http\Response
     */
    public function destroy(PurchaseOrder $purchaseOrder)
    {
        //
    }
}
