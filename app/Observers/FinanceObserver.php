<?php

namespace App\Observers;

use App\Models\Finance;
use App\Models\Order;
use App\Models\ProductSerialNumbers;
use App\Models\Store;
use Carbon\Carbon;

class FinanceObserver
{
    /**
     * @param Finance $finance
     * @throws \Facade\FlareClient\Http\Exceptions\NotFound
     */
    public function creating(Finance $finance)
    {
        $finance->store_id = Store::currentId();

        $this->handleDuration($finance);

        $this->validationSerialNumber($finance);


        //creating order and remove from inventory
        //


        // print
    }

    public function saved(Finance $finance)
    {
        $this->createOrder($finance);
    }

    private function handleDuration(Finance &$finance)
    {
        if (!empty($finance->start_date)) {
            $finance->end_date = $finance->start_date->addMonths($finance->duration_period);
        } else {
            $finance->start_date = Carbon::now();
            $finance->end_date = Carbon::now();
        }
    }

    /**
     * @param Finance $finance
     * @throws \Facade\FlareClient\Http\Exceptions\NotFound
     */
    private function validationSerialNumber(Finance &$finance)
    {
        $isAvailable = ProductSerialNumbers::isAvailable($finance->product_id, $finance->serial_number);
//        if ($isAvailable) {
//            ProductSerialNumbers::updateStatusSold($finance->product_id, $finance->store_id, $finance->serial_number);
//        }
    }

    private function createOrder(Finance &$finance)
    {
        Order::financeCreate($finance);
    }
}
