<?php

namespace App\Http\Controllers;

use App\Helpers\ArrayHelper;
use App\Models\Customer;
use App\Models\Finance;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
            $finance->scenario = Finance::SCENARIO_CREATING;
            if (!empty($request->get("customer_id"))) {
                $customer = Customer::moduleCreate($request->all());
                $finance->customer_name = $customer->name;
                $finance->customer_id = $customer->id;
            }

            $finance->save();
            return $this->genericResponse(true, " Finance has been created", 200, ['finance' =>
                $finance->withSchedules()
                    ->withCustomer()
                    ->withProduct()
                    ->withStatus()
            ]);
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
            return $this->genericResponse(true, " repair has been updated", 200, ['finance' =>
                $finance->withSchedules()
                    ->withCustomer()
                    ->withProduct()
                    ->withStatus()
            ]);
        });
    }

    public function show(Finance $finance)
    {
        return $finance->withCustomer()
            ->withProduct()
            ->withStatus()
            ->withSchedules();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Finance $finance)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Finance $finance)
    {
        $finance->update($request->all());
        return $this->genericResponse(true, " Finance has been updated", 200, ['finance' =>
            $finance->withSchedules()
                ->withCustomer()
                ->withProduct()
        ]);
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
        return Finance::where($this->applyFilters($request))
            ->with(['customer' => function (BelongsTo $query) {
                $query->select(["id", "name", "phone"]);
            }, 'product' => function (BelongsTo $query) {
                $query->select(["id", "name"]);
            }, 'status' => function (BelongsTo $query) {
                $query->select(["id", "name", "color"]);
            }, 'releatedSchedules'])
            ->orderBy('created_at', 'desc')
            ->paginate();
    }

    public function installment(Request $request, Finance $finance)
    {
        $instalment = $finance->releatedSchedules->where('id', $request->get('id'))->first();
        $instalment = $instalment->fill($request->all());
        $instalment->update();
        return $finance;
    }

    public function payInstallment(Request $request, Finance $finance)
    {
        return \DB::transaction(function () use (&$finance, &$request) {

            $finance->scenario = Finance::SCENARIO_ADD_INSTALLMENT;
            $finance->postedScheduled = $request->all();

            $finance->update([
                'advance' => $finance->advance + $request->get('received_amount'),
                'payable' => $finance->payable - $request->get('received_amount')
            ]);
            return $this->genericResponse(true, " Finance has been updated", 200, ['finance' =>
                $finance->withSchedules()
                    ->withCustomer()
                    ->withProduct()
                    ->withStatus()
            ]);
        });

    }

    public function upload(Request $request, Finance $finance)
    {
        $file = $request->file('files')[0];

        $path = 'finance/' . $finance->id;
        //  $name = "{$path}/{$finance->guid}.{$extension}";
        $filename = Storage::disk('public')->put($path, $file);

        $finance->update(['attachments' => [$filename]]);
    }
}
