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
use App\Models\RepairsSchedules;
use Carbon\Carbon;
use DB;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\Builder;
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

            $repair = new Repair();
            $customerId = $request->get('customer_id')[0]; //

            if (!StringHelper::isInt($customerId)) {

                $customer = Customer::moduleCreate(['customer_id' => $customerId, "customer_phone" => $request->get('phone')]);

                $customerId = $customer->id;
            }

            $repairData = $request->all();
            $repairData['advance_cost'] = $request->get('received_amount');
            $repair->fill(array_merge($repairData, ['customer_id' => $customerId, 'store_id' => Store::currentId()]));


            $repair->save();
            $productData = collect($request->get('productItem'))->map(function ($d) {
                $d["created_at"] = Carbon::now();
                $d["updated_at"] = Carbon::now();
                return $d;
            })->all();

            $products = array_filter($productData);
            $repair->syncProducts($products);

            // repair scheduled save
            $repairSchedule = new RepairsSchedules();
            $repairData['repair_id'] = $repair->id;

            $repairSchedule->fill($repairData);
            $repairSchedule->save();
            return $this->genericResponse(true, " repair has been created", 201);
        });

    }

    /**
     *
     */
    public function show(Repair $repair)
    {
        return $repair->load(['relatedProducts', 'customer', 'schedules']);
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
     * @param Request $request
     * @param Repair $repair
     * @return mixed
     * @throws \Throwable
     */
    public function update(Request $request, Repair $repair)
    {
        return DB::transaction(function () use ($request, $repair) {
            $data = $request->all();

            if ($request->has("received_amount")) {
                $data["advance_cost"] = $data["advance_cost"] + $data["received_amount"];
            }

            if ($request->has("additional_charge")) {
                $data["total_cost"] = $data["total_cost"] + $data["additional_charge"];
            }

            if ($request->has("discount")) {
                $data["total_cost"] = $data["total_cost"] - $data["discount"];
            }

            $this->validateOnUpdate($data);
            $repair->update($data);
            $products = array_filter($request->get('productItem'));

            $repair->syncProducts($products, false);

            $repairSchedule = new RepairsSchedules();
            $data['repair_id'] = $repair->id;

            $repairSchedule->fill($data);
            $repairSchedule->save();

            return $this->genericResponse(true, " repair has been updated", 200);
        });
    }

    /**
     * @param array $data
     * @throws \Exception
     */
    private function validateOnUpdate(array $data)
    {
        if ($data["status"] === Repair::IN_COLLECTED_STATUS) {
            if ($data["total_cost"] != $data["advance_cost"]) {
                throw new \Exception("Please adjust the cost");
            }
        }

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
            ->with(["customer" => function (BelongsTo $belongsTo) {
                $belongsTo->select(['id', 'name','phone']);
            },
                'relatedProducts' => function (HasMany $hasMany) {
                    $hasMany->select('product_id', 'repair_id', 'id','product');
//                        ->with(['product' => function (BelongsTo $belongsTo) {
//                            $belongsTo->select(['id', 'name']);
//                        }]);
                }])
            ->when($request->get('repair_product_id'), function (\Illuminate\Database\Eloquent\Builder $query, $productId) {
                return $query->whereHas('relatedProducts', function (\Illuminate\Database\Eloquent\Builder $query) use ($productId) {
                    $query->where('product_id', $productId);
                });
            })
            ->when($request->get('model_name'), function (\Illuminate\Database\Eloquent\Builder $query, $search) {
                return $query->whereHas('relatedProducts', function (\Illuminate\Database\Eloquent\Builder $query) use ($search) {
                    return $query->where('product', 'ilike', "%" . $search . "%");
                });
            })
            ->orderBy('updated_at', "desc")->paginate($this->getPageSize());
    }

    public function statuses()
    {
        return Repair::statuses();
    }
}
