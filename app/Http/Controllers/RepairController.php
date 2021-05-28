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
            $repair = new Repair();
            $customerId = $request->get('customer_id')[0]; //

            if (!StringHelper::isInt($customerId)) {
                $customer = Customer::basicCreate($customerId, $request->get('phone'));
                $customerId = $customer->id;
            }

            $repair->fill(array_merge($request->all(), ['customer_id' => $customerId, 'store_id' => Store::currentId()]));


            $repair->save();
            $products = array_filter($request->get('productItem'));
            $products = collect($products)->map(function ($product) {

                $product['device_type_id'] = DevicesType::getIdByRequest($product['device_type_id']);
                $product['product_id'] = Product::getIdByRequest($product['product_id']);
                $product['issue_id'] = IssueType::getIdByRequest($product['issue_id']);
                $product['brand_id'] = Brand::getIdByRequest($product['brand_id']);
                return ArrayHelper::merge($product, ['guid' => GuidHelper::getGuid()]);
            })->all();
            $repair->products()->sync($products);
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
        $products = collect($products)->map(function ($product) {

            $product['device_type_id'] = DevicesType::getIdByRequest($product['device_type_id']);
            $product['product_id'] = Product::getIdByRequest($product['product_id']);

            $product['issue_id'] = IssueType::getIdByRequest($product['issue_id']);
            $product['brand_id'] = Brand::getIdByRequest($product['brand_id']);
            return ArrayHelper::merge($product, ['guid' => GuidHelper::getGuid()]);
        })->all();

        $repair->products()->sync ($products);
//        $repair->products()->sync(array_filter($request->get('productItem')));
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
        return Repair::whereIn('status', [Repair::IN_PROGRESS_STATUS, Repair::IN_COMPLETED_STATUS])
            ->with("customer")->orderBy('created_at')->get();
    }

    public function statuses()
    {
        return Repair::statuses();
    }
}
