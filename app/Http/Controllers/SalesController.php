<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Inventory;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Store;
use App\Models\Vendor;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class SalesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Adding in inventory ,Sales Controller ,method purchase and module is Purchase Order (Optimization)
        return view('admin.sales.index',
            ['sales' => OrderProduct::with('inventory', 'customer')->get(),
                'customers' => Customer::with('orderProducts')->get()]);
    }

    public function purchase()
    {
        return view('admin.sales.purchase_order',
            ['sales' => OrderProduct::with('inventory', 'vendor')->get(),
                'vendors' => Vendor::with(['orderProducts' => function (HasMany $belongsTo) {
                    $belongsTo->with("product");
                }])
                    ->get()]);
    }

    public function purchaseReceived(Vendor $vendor)
    {
        return view('admin.sales.purchase_recieved', ['vendor' => $vendor]);
    }

    // why repeating and handling of code on every method which hav the same functionality
    public function storeInInventory(Request $request)
    {
        foreach ($request->input('products', []) as $product) {
            $lookup = str_replace('PO', '', $request->get('p_order_id'));
            $request->validate(['vendor_id' => 'required', 'sale_id', 'products' => 'required', 'stock_id' => 'required', 'store_id' => 'required', 'quantity' => 'required']);
            Inventory::insert([
                'product_id' => $product,
                'guid' => Str::uuid(),
                'description' => $request->get('description'),
                'lookup' => $lookup,
                'name' => $request->get('p_order_id'),
                'quantity' => $request->get('quantity'),
                'store_id' => $request->get('store_id'),
                'cost' => $request->get('cost'),
                'extended_cost' => $request->get('cost'),
                'vendor_id' => $request->get('vendor_id'),
                'bin' => $request->get('stock_id'),
            ]);
            OrderProduct::where('id', $request->get('order_id'))->update([
                'type_id' => $request->get('type_id')
            ]);
        }
        return redirect()->back()->with('success', 'Received ');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $preloadProduct = '';
        if ($request->get('OrUPC')) {
            $Inventory = new Inventory();
            $preloadProduct = $Inventory->generalSearch($request);

        }
        return view('admin.sales.create', ['preloadProduct' => $preloadProduct]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'lookup' => 'required',
            'customer_id' => 'required',
            'description' => 'required',
            'type_id' => 'required',
            'products' => 'required',
            'quantity' => 'required'
        ]);

        $order = new Order();
        $order->fill($request->all())->save();
        $productData = [];
        collect($request->get('products'))->each(function ($product) use (&$productData, $request) {
            $productData[] = array_merge($product,
                [
                    'type_id' => $request->get('type_id'),
                    'vendor_id' => $request->get('vendor_id'),
                    'customer_id' => $request->get('customer_id'),
                ]
            );
        });
        $order->orderProducts()->sync($productData);

        /*
         * Worst code ever
         * Repetitive code
         * if new case will come it will not handle
         * Dirty way of using Controller
         * query in loop not using sync method of relation
         * creating custom data
         */
//
//        foreach ($request->input('products', []) as $product) {
//            if ($request->get('customer_id')) {
//                $request->validate(['customer_id' => 'required', 'sale_id', 'products' => 'required', 'stock_id' => 'required', 'store_id' => 'required', 'quantity' => 'required']);
//                OrderProduct::insert([
//                    'order_id' => 'PO' . rand(1111, 999999),
//                    'quantity' => $request->get('quantity'),
//                    'lookup' => $request->get('lookup'),
//                    'inventory_id' => $product,
//                    'store_id' => $request->get('store_id'),
//                    'stock_id' => $request->get('stock_id'),
//                    'type_id' => $request->get('type_id'),
//                    'customer_id' => $request->get('customer_id')
//                ]);
//            } else {
//                $request->validate(['vendor_id' => 'required', 'sale_id', 'products' => 'required', 'stock_id' => 'required', 'store_id' => 'required', 'quantity' => 'required']);
//                OrderProduct::insert([
//                    'order_id' => 'PO' . rand(1111, 999999),
//                    'quantity' => $request->get('quantity'),
//                    'inventory_id' => $product,
//                    'lookup' => $request->get('lookup'),
//                    'store_id' => $request->get('store_id'),
//                    'stock_id' => $request->get('stock_id'),
//                    'type_id' => $request->get('type_id'),
//                    'vendor_id' => $request->get('vendor_id')
//                ]);
//            }
//        }
//        $sales = new Order();
//        $sales->fill($request->all())->save();
        return redirect()->back()->with('success', 'New Sale Created');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        $orderProduct = OrderProduct::where('customer_id', $id);
        $orderProduct->delete();
        return back()->with('success', 'Product Deleted');
    }

    public function destroyVendorProduct($id)
    {
        //
        $orderProduct = OrderProduct::where('customer_id', $id);
        $orderProduct->delete();
        return back()->with('success', 'Product Deleted');
    }

}
