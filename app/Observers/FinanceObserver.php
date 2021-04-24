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
        if ($finance->isCreatingScenario()) {
            $finance->store_id = Store::currentId();
            $this->handleDuration($finance);
            $this->validationSerialNumber($finance);
        }
    }

    public function saved(Finance $finance)
    {
        if ($finance->isCreatingScenario()) {
            $this->createOrder($finance);
        }
    }

    public function updating(Finance $finance)
    {
        $this->handleSchedule($finance);
    }

    private function handleSchedule(Finance &$finance)
    {

        if ($finance->isAddInstallmentScenario()) {

            if ($finance->payable <= 0) {
                $finance->status = Finance::STATUS_COMPLETE;
            }
            $schedules = $finance->postedScheduled;
            $scheduleData = [
                [
                    'comment' => empty($schedules['comment']) ? '' : $schedules['comment'],
                    'received_amount' => $schedules['received_amount'],
                    'date_of_payment' => Carbon::now(),
                    'due_date' => Carbon::now(),
                    'amount' => $schedules['received_amount'],
                    'status' => $finance->status
                ]
            ];
            $finance->schedules()->attach($scheduleData);
        }
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
        $order = Order::financeCreate($finance);
        $finance->order_id = $order->id;
        $finance->saveQuietly();
    }
}
