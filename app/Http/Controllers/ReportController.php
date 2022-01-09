<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Traits\InteractWithReports;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Query\JoinClause;
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

    public function sales()
    {
        return view('admin.reports.sales');
    }

    public function finance()
    {
        return view('admin.reports.finance');
    }

    public function repair()
    {
        return view('admin.reports.repair');
    }


    public function return()
    {
        return view('admin.reports.return');
    }

    public function transfer()
    {
        return view('admin.reports.transfer');
    }


    public function purchase()
    {
        return view('admin.reports.purchase');
    }


    public function entitySearch(Request $request)
    {
//        $finance = \DB::table('finances')
//            ->where('store_id', Store::currentId())
//            ->when($request->get('date_range'), function (Builder $builder, $date_range) {
//                $builder->whereRaw("created_at BETWEEN' " . $date_range[0] . "'AND '" . $date_range[1] . "'");
//            })
//            ->selectRaw("ROUND(sum(total)) as total,'Finances' as name ");

        $finance = \DB::table('finances_schedules')
            ->when($request->get('date_range'), function (Builder $builder, $date_range) {
                $builder->whereRaw("finances_schedules.date_of_payment BETWEEN' " . $date_range[0] . "'AND '" . $date_range[1] . "'");
            })->join('finances', function (JoinClause $joinClause) {
                $joinClause->on('finances_schedules.finance_id', '=', 'finances.id')
                    ->where('finances.store_id', Store::currentId());
            })->when($request->get('pay_by_card'), function (Builder $builder, $pay_by_card) {
                $builder->where('pay_by_card', $pay_by_card);
            })
            ->selectRaw("ROUND(sum(received_amount)) as total,'Finances' as name ");

        $repairs = \DB::table('repairs_schedules')
            ->when($request->get('date_range'), function (Builder $builder, $date_range) {
                $builder->whereRaw("repairs_schedules.created_at BETWEEN' " . $date_range[0] . "'AND '" . $date_range[1] . "'");
            })->join('repairs', function (JoinClause $joinClause) {
                $joinClause->on('repairs_schedules.repair_id', '=', 'repairs.id')
                    ->where('repairs.store_id', Store::currentId());
            })
            ->when($request->get('pay_by_card'), function (Builder $builder, $pay_by_card) {
                if ($pay_by_card) {
                    $builder->where('repairs_schedules.id', 0);
                }

            })
            ->selectRaw("ROUND(sum(received_amount)) as total,'Repair' as name ");

        $refunds = \DB::table('refunds')
            ->where('store_id', Store::currentId())
            ->when($request->get('date_range'), function (Builder $builder, $date_range) {
                $builder->whereRaw("created_at BETWEEN' " . $date_range[0] . "'AND '" . $date_range[1] . "'");
            })
            ->when($request->get('pay_by_card'), function (Builder $builder, $pay_by_card) {
                if ($pay_by_card) {
                    $builder->where('id', 0);
                }

            })
            ->selectRaw("ROUND(sum(return_cost)) as total,'Refunds' as name ");


        $products = \DB::table('orders')
            ->when($request->get('date_range'), function (Builder $builder, $date_range) {
                $builder->whereRaw("created_at BETWEEN' " . $date_range[0] . "'AND '" . $date_range[1] . "'");
            })
            ->when($request->get('pay_by_card'), function (Builder $builder, $pay_by_card) {
                if ($pay_by_card) {
                    $builder->whereNotNull('customer_card_number');
                } else {
                    $builder->whereNull('customer_card_number');
                }
            })
            ->where('store_id', Store::currentId())
            ->whereNull("finance_id")
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
            return $this->singleDetailResponse($this->report_sales($request)->where($this->applyFilters($request))->get());
        } else if ($name === "Finances") {
            return $this->singleDetailResponse($this->report_finance($request)->where($this->applyFilters($request))->get());
        } else if ($name === "Repair") {
            return $this->singleDetailResponse($this->report_repair($request)->get());
        } else if ($name === "Refunds") {
            return $this->singleDetailResponse($this->report_refund($request)->where($this->applyFilters($request))->get());
        }

    }

    public function getSalesStates(Request $request)
    {
        return ['data' => $this->report_sales($request)->where($this->applyFilters($request))->get(),
            'summary' => $this->report_sales_total($request)->get()];
    }


    public function getFinanceStates(Request $request)
    {
        return $this->report_finance_total($request)->get();
    }

    public function getRepairStates(Request $request)
    {
        return $this->report_repair_total($request)->get();
    }

    public function getReportingSerialNumbers(Request $request)
    {
        return $this->getSerialsFromOrder($request)->get();
    }

    public function getReturnStates(Request $request)
    {
        return ['data' => $this->report_refund($request)->where($this->applyFilters($request))->get(),
            'summary' => $this->report_refund_total($request)->get()];
        // return $this->report_refund($request)->where($this->applyFilters($request))->get();
    }

    public function getTransferStates(Request $request)
    {
        return $this->report_repair_total($request)->get();
    }

    public function getPurchaseStates(Request $request)
    {
        return $this->report_purchase_total($request)->get();
    }
}
