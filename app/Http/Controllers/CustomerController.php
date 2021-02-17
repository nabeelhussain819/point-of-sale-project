<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\OrderProduct;
use App\Models\Product;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('admin.customers.index',['customers' => OrderProduct::with('customer','product')->get()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('admin.customers.create',['products' => Product::all()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $orderCustomer = new OrderProduct();
        $customer = new Customer();
        $customer->fill($request->all())->save();
        $orderCustomer->order_id = 'PO' .rand(1111,999999);
        //only saving single product for now will change this in future
        foreach ($request->products as $product) {
            $item = $product;
            $orderCustomer->product_id = $item;
        }
        $orderCustomer->customer()->associate($customer)->save();
        return redirect('admin/customers')->with('success','Customer Created');
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
        return view('admin.customers.edit',['customer' => Customer::find($id)]);
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
        $customer = Customer::find($id);
        $customer->fill($request->all())->update();
        return redirect()->back()->with('success',"Customer $customer->name Updated");
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
        $customer = Customer::find($id);
        $customer->delete();
        return redirect()->back()->with('success',"Customer $customer->name Deleted");
    }
}
