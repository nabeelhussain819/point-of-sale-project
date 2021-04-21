<?php

namespace App\Http\Controllers;

use App\Helpers\ArrayHelper;
use App\Models\Customer;
use App\Models\Finance;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Request;

class FinanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.finance.index');
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

        return \DB::transaction(function () use ($request) {
            $finance = new Finance();

            $financeData = ArrayHelper::merge($request->all());
            $finance->fill($financeData);

            if (!empty($request->get("customer_id"))) {
                $customer = Customer::moduleCreate($request->all());
                $finance->customer_name = $customer->name;
                $finance->customer_id = $customer->id;
            }

            $finance->save();
            return $this->genericResponse(true, " Finance has been created", 200);
        });
    }

    /**
     * This was an old automatic scheduling
     * @param Request $request
     * @return mixed
     * @throws \Throwable
     */
    public function storeFeature(Request $request)
    {
        return \DB::transaction(function () use ($request) {
            $finance = new Finance();

            $financeData = ArrayHelper::merge($request->all(), ['type' => 1]);
            $finance->fill($financeData);
            $installments = [];
            collect($request->get('installmentItem'))->each(function ($installment) use (&$installments) {
                $installments[] = $installment;
            });
            $finance->save();
            $finance->schedules()->sync($installments);
            return $this->genericResponse(true, " repair has been updated", 200);
        });
    }


    public function show(Finance $finance)
    {
     return $finance->withCustomer()
         ->withProduct()
         ->withSchedules();
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

    public function fetch(Request $request)
    {
        return Finance::where($this->applyFilters($request))
            ->with(['customer' => function (BelongsTo $query) {
                $query->select(["id", "name", "phone"]);
            }, 'product' => function (BelongsTo $query) {
                $query->select(["id", "name"]);
            }])
            ->orderBy('created_at','desc')
            ->paginate();
    }

    public function installment(Request $request, Finance $finance)
    {
        $instalment = $finance->releatedSchedules->where('id', $request->get('id'))->first();
        $instalment = $instalment->fill($request->all());
        $instalment->update();
        return $finance;
    }
}
