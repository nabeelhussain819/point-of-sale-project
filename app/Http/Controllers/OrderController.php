<?php

namespace App\Http\Controllers;

use App\Helpers\ArrayHelper;
use App\Models\Order;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('admin.orders.index');
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
     */
    public function store(Request $request)
    {
        DB::transaction(function () use ($request) {
            $order = new Order();

            $customerId = null;
            if (!empty($request->get('customer'))) {
                $customerId = $request->get('customer')['id'];
            }
            $summary = [];

            if (!empty($request->get('summary'))) {
                $summaryData = $request->get('summary');
                $summary = [
                    'discount' => $summaryData['discount'],
                    'without_tax' => $summaryData['wihtoutTax'],
                    'sub_total' => $summaryData['subTotal'],
                    'without_discount' => $summaryData['withoutDiscount']
                ];
            }

            $productsData = [];
            collect($request->get('products'))->each(function ($product) use (&$productsData, $customerId) {

                $productsData[] = [
                    'product_id' => $product['id'],
                    'customer_id' => $customerId,
                    'store_id' => Store::currentId(),
                    'retail_price' => $product['retail_price'],
                    'serial_number' => $product['serial_number'],
                    'total' => $product['total'],
                    'min_price' => $product['min_price'],
                    'quantity' => $product['quantity'],
                ];
            });

            $data = ArrayHelper::merge($summary, [
                'customer_id' => $customerId,
            ]);

            $order->fill($data)->save();
            $order->ordersProducts()->sync($productsData);
        });
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
