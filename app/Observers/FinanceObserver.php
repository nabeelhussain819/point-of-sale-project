<?php

namespace App\Observers;

use App\Models\Finance;
use App\Models\Store;
use Carbon\Carbon;

class FinanceObserver
{
    public function creating(Finance $finance)
    {
        $finance->store_id = Store::currentId();

        if (!empty($finance->start_date)) {
            $finance->end_date = $finance->start_date->addMonths($finance->duration_period);
        }
        else{
            $finance->start_date= Carbon::now();
            $finance->end_date= Carbon::now();
        }

        // validate Serial number
        // add customer if not exist
        //showing Refund ID
        //serial number se search chal jaye
        // print
    }
}
