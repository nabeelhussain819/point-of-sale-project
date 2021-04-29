<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\ProductSerialNumbers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Request;

class TrackingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.tracking.index');
    }
    //

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $productSerialNumber = ProductSerialNumbers::where('serial_no', $request->get('serial_number'))
            ->firstOrFail();

        $sales = Order::whereHas("products", function (Builder $belongsTo) use ($productSerialNumber) {
            $belongsTo->where('serial_number', $productSerialNumber->serial_no)
                ->with(['store' => function (BelongsTo $belongsTo) {
                    $belongsTo->select(['id','name']);
                }]);
        })->first();
        dd($sales);
        $productSerialNumber->sales = $sales;
        return $productSerialNumber->withProduct()->withTransfer()->withPurchaseOrder();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\ProductSerialNumbers $productSerialNumbers
     * @return \Illuminate\Http\Response
     */
    public function show(ProductSerialNumbers $productSerialNumbers)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\ProductSerialNumbers $productSerialNumbers
     * @return \Illuminate\Http\Response
     */
    public function edit(ProductSerialNumbers $productSerialNumbers)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\ProductSerialNumbers $productSerialNumbers
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProductSerialNumbers $productSerialNumbers)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\ProductSerialNumbers $productSerialNumbers
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductSerialNumbers $productSerialNumbers)
    {
        //
    }
}
