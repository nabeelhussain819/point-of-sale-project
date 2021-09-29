<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Traits\InteractWithReports;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class ReportController extends Controller
{
    use InteractWithReports;

    public function index()
    {
        //
        return view('admin.reports.index');
    }

    public function entitySearch(Request $request)
    {
        $finance = \DB::table('finances')
            ->where('store_id', Store::currentId())
            ->when($request->get('date_range'), function (Builder $builder, $date_range) {
                $builder->whereRaw("created_at BETWEEN' " . $date_range[0] . "'AND '" . $date_range[1] . "'");
            })
            ->selectRaw("ROUND(sum(total)) as total,'Finances' as name ");


        $repairs = \DB::table('repairs')
            ->where('store_id', Store::currentId())
            ->when($request->get('date_range'), function (Builder $builder, $date_range) {
                $builder->whereRaw("created_at BETWEEN' " . $date_range[0] . "'AND '" . $date_range[1] . "'")
                    ->orWhereExists(function (\Illuminate\Database\Query\Builder $query) use ($date_range) {
                        $query->from("repairs_schedules")->
                        where('repairs.id', \DB::raw('repairs_schedules.repair_id'))->
                        whereRaw("repairs_schedules.created_at BETWEEN' " . $date_range[0] . "'AND '" . $date_range[1] . "'");
                    });;
            })
            ->selectRaw("ROUND(sum(total_cost)) as total,'Repair' as name ");

        $refunds = \DB::table('refunds')
            ->where('store_id', Store::currentId())
            ->when($request->get('date_range'), function (Builder $builder, $date_range) {
                $builder->whereRaw("created_at BETWEEN' " . $date_range[0] . "'AND '" . $date_range[1] . "'");
            })
            ->selectRaw("ROUND(sum(return_cost)) as total,'Refunds' as name ");


        $products = \DB::table('orders')
            ->when($request->get('date_range'), function (Builder $builder, $date_range) {
                $builder->whereRaw("created_at BETWEEN' " . $date_range[0] . "'AND '" . $date_range[1] . "'");
            })
            ->where('store_id', Store::currentId())
            ->selectRaw("ROUND(sum(sub_total)) as total,'Sales' as name ")
            ->union($repairs)
            ->union($finance)
            ->union($refunds)
            ->get();

        $products->map(function ($data) {
            $data->total = $data->total === null ? 0 : $data->total;
            return $data;
        });
        $repairCol = $products->where('name', 'Refunds')->first();
        $total = $products->sum('total') - $repairCol->total;
        return $products->push(['total' => $total, 'name' => ' Report Total', 'link' => false]);
    }

    public function detail($name, Request $request)
    {
        if ($name === "Sales") {
            return $this->report_sales($request)->where($this->applyFilters($request))->get();
        } else if ($name === "Finances") {
            return $this->report_finance($request)->where($this->applyFilters($request))->get();
        } else if ($name === "Repair") {
            $repairs = $this->report_repair2($request)->get();
            $repairs1 = $repairs->groupBy("status")->map(function ($repair) {
                return [
                    'name' => $repair->first()->status,
                    'total' => $repair->sum('total_cost'),
                    'advance' => $repair->sum('advance_cost')
                ];

            });
            return $repairs1->values();

        } else if ($name === "Refunds") {
            return $this->report_refund($request)->where($this->applyFilters($request))->get();
        }

    }
}
