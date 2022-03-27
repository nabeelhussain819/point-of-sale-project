<?php

namespace App\Traits;

use App\Models\Finance;
use App\Models\FinancesSchedules;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\PurchaseOrder;
use App\Models\Refund;
use App\Models\RefundsProduct;
use App\Models\Repair;
use App\Models\RepairsProduct;
use App\Models\RepairsSchedules;
use App\Models\StockTransfer;
use App\Models\Store;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

trait InteractWithReports
{
    public function report_sales(Request $request): Builder
    {
        return OrderProduct::selectRaw("product_id,sum(quantity) as quantity,sum(total) as total  ")
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

    public function report_sales_total(Request $request)
    {

        return Order::selectRaw("sum(sub_total) as total ,sum(cash_paid) as cash_paid,sum(card_paid) as card_paid,sum(cash_back) as cash_back,sum(sub_total - without_tax) as tax")
            ->when($request->get('date_range'), function (Builder $builder, $date_range) {
                $builder->whereRaw("created_at BETWEEN' " . $date_range[0] . "'AND '" . $date_range[1] . "'");
            });
    }

    public function report_refund_total(Request $request)
    {
        return Refund::selectRaw("sum(return_cost) as total ")
            ->when($request->get('date_range'), function (Builder $builder, $date_range) {
                $builder->whereRaw("created_at BETWEEN' " . $date_range[0] . "'AND '" . $date_range[1] . "'");
            });
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

    public function report_finance_total(Request $request)
    {
        return FinancesSchedules::selectRaw("sum(amount) as total")
            ->when($request->get('date_range'), function (Builder $builder, $date_range) {
                $builder->whereRaw("date_of_payment BETWEEN' " . $date_range[0] . "'AND '" . $date_range[1] . "'");
            })->whereExists(function (QueryBuilder $query) use ($request) {
                return $query->from('finances')
                    ->where('store_id', Store::currentId());
            });
    }

    public function report_repair_total(Request $request)
    {
//        return RepairsSchedules::selectRaw("sum(received_amount) as total")
//            ->when($request->get('date_range'), function (Builder $builder, $date_range) {
//                $builder->whereRaw("updated_at BETWEEN' " . $date_range[0] . "'AND '" . $date_range[1] . "'");
//            })->whereExists(function (QueryBuilder $query) use ($request) {
//                return $query->from('repairs')
//                    ->where('store_id', Store::currentId());
//            });
        $date_range = $request->get('date_range');
        $storeId = Store::currentId();
        return DB::select("SELECT
	r.ID,
	r.status,
	r.total_cost,
	r.advance_cost,
	Sum(rs.received_amount) AS amount_received_in_duration,
	Sum(rs.discount) AS discounted_on

FROM
	repairs AS r
	JOIN repairs_schedules AS rs ON rs.repair_id = r.ID
WHERE
  rs.created_at
	BETWEEN  '$date_range[0]' AND  '$date_range[1]'
	And r.store_id = $storeId
	GROUP BY r.Id
");
    }

    public function repairSummaryWithData(array $queryData)
    {
        $summary = [];
        $totalCost = 0;
        $totalDiscount = 0;
        $totalReceivedAmount = 0;
        collect($queryData)->each(function ($data) use (&$summary, &$totalCost, &$totalDiscount, &$totalReceivedAmount) {

            $totalCost += $data->total_cost;
            $totalDiscount += $data->discounted_on;
            $totalReceivedAmount += $data->amount_received_in_duration;
            $summary = ['total_cost' => $totalCost, 'total_discount' => $totalDiscount, 'amount_received_in_duration' => $totalReceivedAmount];

        });
        $summary['data'] = $queryData;
        return $summary;
    }

    public function report_purchase_total(Request $request)
    {
        return PurchaseOrder::selectRaw("sum(price) as total")
            ->when($request->get('date_range'), function (Builder $builder, $date_range) {
                $builder->whereRaw("updated_at BETWEEN' " . $date_range[0] . "'AND '" . $date_range[1] . "'");
            });
    }

    public function report_transfer_total(Request $request)
    {
        return StockTransfer::selectRaw("sum(price) as total")
            ->when($request->get('date_range'), function (Builder $builder, $date_range) {
                $builder->whereRaw("updated_at BETWEEN' " . $date_range[0] . "'AND '" . $date_range[1] . "'");
            });
    }


    public function report_repairold(Request $request): Builder
    {
        return RepairsSchedules::selectRaw("product_id,sum(quantity) as quantity,sum(received_amount) as total ")
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
            ->where('store_id', Store::currentId())
            ->join('repairs_schedules', function (JoinClause $join) use ($request) {

                $join->on('repairs.id', '=', 'repairs_schedules.repair_id');
            })
            ->when($request->get('date_range'), function (Builder $builder, $date_range) {

                $builder->WhereRaw("repairs.created_at BETWEEN' " . $date_range[0] . "'AND '" . $date_range[1] . "'")
                    ->whereRaw("repairs_schedules.created_at BETWEEN' " . $date_range[0] . "'AND '" . $date_range[1] . "'");

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
            ->whereHas('refund', function (Builder $query) {
                $query->where('store_id', Store::currentId());
            })
            ->when($request->get('date_range'), function (Builder $builder, $date_range) {
                $builder->whereRaw("created_at BETWEEN' " . $date_range[0] . "'AND '" . $date_range[1] . "'");
            })->orderBy("total");
    }

    public function singleDetailResponse(Collection $data)
    {

        return ["data" => $data, "total" => $data->sum('total')];
    }

    public function getSerialsFromOrder(Request $request)
    {
        return OrderProduct::selectRaw("serial_number,product_id ")
            ->whereNull("refund_id")
            ->where("product_id", $request->get('product_id'))
            ->whereNotNull('serial_number')
            ->whereHas('order', function (Builder $query) {
                $query->whereNull("finance_id");
            })
            ->when($request->get('date_range'), function (Builder $builder, $date_range) {
                $builder->whereRaw("created_at BETWEEN' " . $date_range[0] . "'AND '" . $date_range[1] . "'");
            });
    }


}
