<?php

namespace App\Observers;

use App\Models\Finance;
use App\Models\Store;

class FinanceObserver
{
    public function creating(Finance $finance)
    {
        $finance->store_id = Store::currentId();
        $finance->payable = 1;//temp
        $finance->end_date = $finance->start_date->addMonths($finance->duration_period);
    }
}
