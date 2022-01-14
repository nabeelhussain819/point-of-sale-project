<?php

namespace App\Http\Controllers;

use App\Helpers\ArrayHelper;
use App\Models\Inventory;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\ProductSerialNumbers;
use App\Models\Store;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        return DB::transaction(function () use ($request) {
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
                    'without_tax' => $summaryData['withoutTax'],
                    'sub_total' => $summaryData['subTotal'],
                    'without_discount' => $summaryData['withoutDiscount'],
                    'cash_paid' => empty($cashDetail['cash_paid']) ? 0 : $cashDetail['cash_paid'],
                    'cash_back' => empty($cashDetail['cash_back']) ? 0 : $cashDetail['cash_back'],
                    'card_paid' => empty($cashDetail['card_paid']) ? 0 : $cashDetail['card_paid'],
                    'customer_card_number' => empty($cashDetail['customer_card_number']) ? null : $cashDetail['customer_card_number'],
                    'tax' => empty($summaryData['tax']) ? null : $summaryData['tax'],
                    'notes' => empty($cashDetail['notes']) ? null : $cashDetail['notes'],
                ];
                if ($summary['cash_paid'] + $summary['card_paid'] < $summary['sub_total']) {
                    throw  new \Exception("sub total not match");
                }
            }

            $productsData = [];
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
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ];

            });

            $data = ArrayHelper::merge($summary, [
                'customer_id' => $customerId,
                'store_id' => Store::currentId()
            ]);

            /// logging data for serial products
            $order->LOGDATA = [
                'subject' => Order::class,
                'subject_id' => $order->id,
                'subject_data' => $order,
            ];

            $order->POSTEDPRODUCTS = $productsData;

            $order->fill($data)->save();

            $order->ordersProducts()->sync($productsData);
            return $order;
        });
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        return $order->withCustomer()
            ->withRefund()
            ->withProductsProduct();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function fetch(Request $request)
    {
        return Order::with("customer")
            ->where($this->applyFilters($request))
            ->orderBy('created_at', 'desc')
            ->paginate($this->pageSize);
    }

    public function printableDetail()
    {
        $user = Auth::user();
        return $this->genericResponse(true, " Purcahse order has been created", 200,
            ['causer' => [
                'name' => $user->name,
                'id' => $user->id
            ], 'store' => Store::where('id', Store::currentId())->first()]);
    }


    public function print(Order $order)
    {
        return view('admin.orders.print', ['order' => $order->withCustomer()
            ->withRefund()
            ->withProductsProduct(), 'store' => Store::where('id', Store::currentId())->firstOrfail()]);
    }

    public function getIds(Request $request)
    {
        return OrderProduct::
        where($this->applyFilters($request))
            ->get();
    }
}
