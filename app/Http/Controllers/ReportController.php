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
                $builder->whereRaw("created_at BETWEEN' " . $date_range[0] . "'AND '" . $date_range[1] . "'");
            })
            ->selectRaw("ROUND(sum(total_cost)) as total,'Repair' as name ");


        $products = \DB::table('orders')
            ->when($request->get('date_range'), function (Builder $builder, $date_range) {
                $builder->whereRaw("created_at BETWEEN' " . $date_range[0] . "'AND '" . $date_range[1] . "'");
            })
            ->where('store_id', Store::currentId())
            ->selectRaw("ROUND(sum(sub_total)) as total,'Sales' as name ")
            ->union($repairs)
            ->union($finance)
            ->get();
        $total = $products->sum('total');
        return $products->push(['total' => $total, 'name' => ' Report Total', 'link' => false]);
    }

    public function detail($name, Request $request)
    {
        if ($name === "Sales") {
            return $this->report_sales($request)->where($this->applyFilters($request))->get();
        } else if ($name === "Finances") {
            return $this->report_finance($request)->where($this->applyFilters($request))->get();
        }

    }
}
