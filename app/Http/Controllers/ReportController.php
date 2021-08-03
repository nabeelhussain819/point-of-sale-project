<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Traits\InteractWithReports;
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
            ->selectRaw("ROUND(sum(total)) as total,'Finances' as name ");


        $repairs = \DB::table('repairs')
            ->where('store_id', Store::currentId())
            ->selectRaw("ROUND(sum(total_cost)) as total,'Repair' as name ");


        $products = \DB::table('orders')
            ->where('store_id', Store::currentId())
            ->selectRaw("ROUND(sum(sub_total)) as total,'Sales' as name ")
            ->union($repairs)
            ->union($finance)
            ->get();

        return $products;
    }

    public function detail($name, Request $request)
    {
        if ($name === "Sales") {
            return $this->report_sales($request);
        } else if ($name === "Finances") {
            return $this->report_finance($request);
        }

    }
}
