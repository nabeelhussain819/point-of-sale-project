<?php

namespace App\Http\Controllers;

use App\Helpers\ArrayHelper;
use App\Models\Order;
use App\Models\Refund;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RefundController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * @param Request $request
     * @throws \Throwable
     */
    public function store(Request $request)
    {
        $request->validate([
            'return_cost' => 'required|integer|min:1',
        ]);
        return \DB::transaction(function () use ($request) {
            $refund = new Refund();
            $refund->fill(ArrayHelper::merge($request->all(), ['store_id' => Store::currentId()]));
            $refund->PostedProducts = $request->get('returnProducts');
            $refund->save();

            $refund->refundsProducts()->sync($request->get('returnProducts'));
            $order = $refund->order;
            $order = $order->withProductsProduct()->withCustomer();
            return $this->genericResponse(true, " Refund has been created", 200,
                ['refund' => $refund,
                    'order' => $order]);
        });
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Refund $refund
     * @return \Illuminate\Http\Response
     */
    public function show(Refund $refund)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Refund $refund
     * @return \Illuminate\Http\Response
     */
    public function edit(Refund $refund)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Refund $refund
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Refund $refund)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Refund $refund
     * @return \Illuminate\Http\Response
     */
    public function destroy(Refund $refund)
    {
        //
    }

    public function order(Order $order)
    {
        $order = $order->withProductsProduct()->withCustomer();
        return view('admin.refund.order', ['order' => $order]);
    }
}
