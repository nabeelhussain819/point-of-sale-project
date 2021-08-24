<?php

namespace App\Http\Controllers;

use App\Helpers\ArrayHelper;
use App\Helpers\GuidHelper;
use App\Helpers\StringHelper;
use App\Models\Brand;
use App\Models\Customer;
use App\Models\DevicesType;
use App\Models\IssueType;
use App\Models\Product;
use App\Models\Repair;
use App\Models\Store;
use DB;
use Illuminate\Http\Request;

class RepairController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.repair.index');
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
     * @return mixed
     * @throws \Throwable
     */
    public function store(Request $request)
    {
        return DB::transaction(function () use ($request) {
            if (empty($request->get('productItem'))) {
                throw new \Exception("Please associated item to the repair");
            }
            if ($request->get('advance_cost')>=$request->get('total_cost')) {
                throw new \Exception("advance cost could not be greater than or equals to total cost ");
            }
            $repair = new Repair();
            $customerId = $request->get('customer_id')[0]; //

            if (!StringHelper::isInt($customerId)) {

                $customer = Customer::moduleCreate(['customer_id' => $customerId, "customer_phone" => $request->get('phone')]);

                $customerId = $customer->id;
            }

            $repair->fill(array_merge($request->all(), ['customer_id' => $customerId, 'store_id' => Store::currentId()]));


            $repair->save();
            $products = array_filter($request->get('productItem'));
            $repair->syncProducts($products);
            return $this->genericResponse(true, " repair has been created", 201);
        });

    }

    /**
     *
     */
    public function show(Repair $repair)
    {
        return $repair->load(['relatedProducts', 'customer']);
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
     * @param Repair $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Repair $repair)
    {
        $repair->update($request->all());
        $products = array_filter($request->get('productItem'));
        $repair->syncProducts($products);
        return $this->genericResponse(true, " repair has been updated", 200);
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

        return Repair::where($this->applyFilters($request))
            ->with("customer")->orderBy('created_at', "desc")->paginate($this->pageSize);
    }

    public function statuses()
    {
        return Repair::statuses();
    }
}
