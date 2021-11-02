<?php

namespace App\Traits;

use App\Models\Finance;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\RefundsProduct;
use App\Models\Repair;
use App\Models\RepairsProduct;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Http\Request;

trait InteractWithReports
{
    public function report_sales(Request $request): Builder
    {
        return OrderProduct::selectRaw("product_id,sum(quantity) as quantity,sum(total) as total ")
            ->groupBy("product_id")->with(["product" => function (BelongsTo $builder) {
                $builder->select(['id', 'name', 'category_id', 'department_id'])
                    ->with(["category" => function (BelongsTo $builder) {
                        $builder->select(['id', 'name']);
                    }, "department" => function (BelongsTo $builder) {
                        $builder->select(['id', 'name']);
                    }
                    ]);
            }])
            ->whereHas('order', function (Builder $query) {
                $query->whereNull("finance_id");
            })
            ->when($request->get('date_range'), function (Builder $builder, $date_range) {
                $builder->whereRaw("created_at BETWEEN' " . $date_range[0] . "'AND '" . $date_range[1] . "'");
            })->orderBy("total");
    }

    public function report_finance(Request $request): Builder
    {
        return Finance::selectRaw(" product_id, count(id) as quantity,sum(advance) as advance,sum(total) as total,sum(payable) as payable ")
            ->groupBy("product_id")
            ->with(["product" => function (BelongsTo $builder) {
                $builder->select(['id', 'name', 'department_id', 'category_id'])
                    ->with(["category" => function (BelongsTo $builder) {
                        $builder->select(['id', 'name']);
                    }, "department" => function (BelongsTo $builder) {
                        $builder->select(['id', 'name']);
                    }
                    ]);
            }])
            ->when($request->get('date_range'), function (Builder $builder, $date_range) {
                $builder->whereRaw("created_at BETWEEN' " . $date_range[0] . "'AND '" . $date_range[1] . "'");
            })->orderBy("total");
    }


    public function report_repair2(Request $request): Builder
    {
        return Repair::select(["id", "status", "total_cost", "advance_cost"])->when($request->get('date_range'), function (Builder $builder, $date_range) {

            $builder->where(function (Builder $builder) use ($date_range) {
                $builder->whereRaw("repairs.created_at BETWEEN' " . $date_range[0] . "'AND '" . $date_range[1] . "'")
                    ->orWhereExists(function (\Illuminate\Database\Query\Builder $query) use ($date_range) {
                        $query->from("repairs_schedules")->
                        where('repairs.id', \DB::raw('repairs_schedules.repair_id'))->
                        whereRaw("repairs_schedules.created_at BETWEEN' " . $date_range[0] . "'AND '" . $date_range[1] . "'");
                    });
            });

        })->with(["schedules" => function (HasMany $belongsTo) use ($request) {
            $date_range = $request->get('date_range');
            if (!empty($date_range)) {
                $belongsTo->select(["id", "additional_charge", "repair_id", "discount", "received_amount"])->whereRaw("repairs_schedules.created_at BETWEEN' " . $date_range[0] . "'AND '" . $date_range[1] . "'");
            }

        }])->dd();
    }

    public function report_repair(Request $request): Builder
    {
        return Repair::selectRaw('repairs."status" as name,sum(repairs."total_cost") as total,sum(repairs."advance_cost") as advance ,	sum(repairs_schedules."received_amount") as received_amount')
            ->selectRaw("Sum( CASE repairs_schedules.pay_by_card
	WHEN true THEN
	received_amount
	ELSE
		0
END) as card")
            ->selectRaw("Sum( CASE repairs_schedules.pay_by_card
	WHEN false THEN
	received_amount
	ELSE
		0
END  
) as cash")
            ->groupBy("status")
            ->join('repairs_schedules', function (JoinClause $join) use ($request) {

                $join->on('repairs.id', '=', 'repairs_schedules.repair_id');
            })
            ->when($request->get('date_range'), function (Builder $builder, $date_range) {

                $builder->orWhereRaw("repairs.created_at BETWEEN' " . $date_range[0] . "'AND '" . $date_range[1] . "'")
                    ->orwhereRaw("repairs_schedules.created_at BETWEEN' " . $date_range[0] . "'AND '" . $date_range[1] . "'");

            })
            ->orderBy("total");


    }

    public function report_refund(Request $request): Builder
    {
        return RefundsProduct::selectRaw("product_id,sum(quantity) as quantity,sum(return_cost) as total ")
            ->groupBy("product_id")->with(["product" => function (BelongsTo $builder) {
                $builder->select(['id', 'name', 'category_id', 'department_id'])
                    ->with(["category" => function (BelongsTo $builder) {
                        $builder->select(['id', 'name']);
                    }, "department" => function (BelongsTo $builder) {
                        $builder->select(['id', 'name']);
                    }
                    ]);
            }])
            ->when($request->get('date_range'), function (Builder $builder, $date_range) {
                $builder->whereRaw("created_at BETWEEN' " . $date_range[0] . "'AND '" . $date_range[1] . "'");
            })->orderBy("total");
    }

    public function singleDetailResponse(Collection $data)
    {

        return ["data" => $data, "total" => $data->sum('total')];
    }

}
