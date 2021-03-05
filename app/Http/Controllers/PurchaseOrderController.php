<?php

namespace App\Http\Controllers;

use App\Models\OrderProduct;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrdersProduct;
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

        //@todo Overser ugent piece of work
        $PurchaseOrderData = array_merge($request->all(), [
            'expected_price' => $totalPrice,
            'price' => $totalPrice
        ]);


        $purchaseOrder
            ->fill($PurchaseOrderData)
            ->save();
        $purchaseOrder->purchaseOrdersProducts()->sync($productData);

        return redirect()->back()->with('success', 'New Sale Created');
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
