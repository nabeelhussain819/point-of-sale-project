<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class ReportController extends Controller
{
    public function index()
    {
        //
        return view('admin.reports.index');
    }

    public function entitySearch(Request $request)
    {
        $finance = \DB::table('finances')
            ->selectRaw("sum(total) as total,'Finances' as name ");


        $repairs = \DB::table('repairs')
            ->selectRaw("sum(total_cost) as total,'Repair' as name ");


        $products = \DB::table('orders')
            ->selectRaw("sum(sub_total) as total,'Products' as name ")
            ->union($repairs)
            ->union($finance)
            ->get();

        return $products;
    }
}
