<?php

namespace App\Http\Controllers;

use App\Helpers\ArrayHelper;
use App\Models\Inventory;
use App\Models\Order;
use App\Models\ProductSerialNumbers;
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
     * Uff my god controller I did this job at night please forgive me for this
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
                $cashDetail = $request->get('sales');

                $summary = [
                    'discount' => $summaryData['discount'],
                    'withoutTax' => $summaryData['without_tax'],
                    'sub_total' => $summaryData['sub_total'],
                    'without_discount' => $summaryData['without_discount'],
                    'cash_paid' => $cashDetail['cash_paid'],
                    'cash_back' => empty($cashDetail['cash_paid']) ? null : $cashDetail['cash_paid'],
                    'customer_card_number' => empty($cashDetail['customer_card_number']) ? null : $cashDetail['customer_card_number'],
                ];
            }

            $productsData = [];
            $InventoryProducts = [];
            collect($request->get('products'))->each(function ($product) use (&$productsData, $customerId, &$InventoryProducts) {
                $serialNumber = empty($product['serial_number']) ? null : $product['serial_number'];
                $productsData[] = [
                    'product_id' => $product['id'],
                    'customer_id' => $customerId,
                    'store_id' => Store::currentId(),
                    'retail_price' => $product['retail_price'],
                    'serial_number' => $serialNumber,
                    'total' => $product['total'],
                    'min_price' => $product['min_price'],
                    'quantity' => $product['quantity'],
                ];

                // these are the product those do not have serial_number
                if (empty($product['serial_number'])) {
                    $InventoryProducts[$product['id']] = [
                        'product_id' => $product['id'],
                        'quantity' => $product['quantity'],
                    ];
                }

            });

            $data = ArrayHelper::merge($summary, [
                'customer_id' => $customerId,
            ]);


            //update inventory serial number @todo please use a human readable
            $productWithSerial = collect($productsData)->filter(function ($inventoryProduct) {
                return !empty($inventoryProduct['serial_number']);
            })->each(function ($inventoryProduct) {
                Inventory::where('store_id', Store::currentId())
                    ->where('product_id', $inventoryProduct['product_id'])
                    ->get()
                    ->each(function (Inventory $inventory) use ($inventoryProduct) {
                        $inventory->OUTGOING_PRODUCTS = true;
                        // because in current scenrio we sell 1 serial product at once
                        $inventory->update(['quantity' => $inventory->quantity - $inventoryProduct['quantity']]); // inventory mai se quantity kam karhe hain
                        ProductSerialNumbers::updateStatusSold($inventoryProduct['product_id'], Store::currentId(), $inventoryProduct['serial_number']);
                    });
                return $inventoryProduct;
            });
            //update inventory serial number @todo please use a human readable


            // update without serial number
            $productIdsWithOutSerial = array_keys($InventoryProducts);

            Inventory::where('store_id', Store::currentId())
                ->whereIn('product_id', $productIdsWithOutSerial)
                ->get()
                ->each(function (Inventory $inventory) use ($InventoryProducts) {
                    $inventory->OUTGOING_PRODUCTS = true;

                    $inventory->update(['quantity' =>
                        $inventory->quantity - $InventoryProducts[$inventory->product_id]['quantity']
                    ]);
                });
            //update inventory


            $order->fill($data)->save();
            $order->ordersProducts()->sync($productsData);
            return $order;
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
