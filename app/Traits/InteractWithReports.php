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
    public function report_sales(Request $request)
    {
        return OrderProduct::selectRaw("product_id,sum(quantity) as quantity,sum(total) as total ")
            ->groupBy("product_id")->with(["product" => function (BelongsTo $builder) {
                $builder->select(['id', 'name']);
            }])->orderBy("total")->get();
    }

    public function report_finance(Request $request)
    {
        return Finance::selectRaw(" product_id, count(id) as quantity,sum(advance) as advance,sum(total) as total,sum(payable) as payable ")
            ->groupBy("product_id")->with(["product" => function (BelongsTo $builder) {
                $builder->select(['id', 'name']);
            }])->orderBy("total")->get();
    }
}
