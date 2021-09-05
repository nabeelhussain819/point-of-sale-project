<?php

namespace App\Traits;

use App\Models\Finance;
use App\Models\Order;
use App\Models\OrderProduct;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
}
