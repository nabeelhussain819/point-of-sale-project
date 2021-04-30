<?php

namespace App\Observers;

use App\Data\SerialLogDTO;
use App\Models\ProductSerialNumbers;
use App\Models\PurchaseOrder;
use App\Models\SerialLogs;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class SerialLogObserver
{
    public function saved(ProductSerialNumbers $productSerialNumbers)
    {
        $this->log($productSerialNumbers);
    }

    private function log(ProductSerialNumbers $productSerialNumbers)
    {
        switch ($productSerialNumbers->subject) {
            case PurchaseOrder::class:
                $this->belongsToPurchaseOrder($productSerialNumbers);
            default:
        }

    }

    private function belongsToPurchaseOrder(ProductSerialNumbers $productSerialNumbers)
    {
        $serialLog = new SerialLogs();
        $serialLog->add(
            $productSerialNumbers->id,
            $productSerialNumbers->serial_no,
            PurchaseOrder::class,
            $productSerialNumbers->purchaseOrder->id,
            $this->purchaseOrderOptions($productSerialNumbers)
        );
    }

    private function baseOptions(): SerialLogDTO
    {
        $serialLog = new   SerialLogDTO();
        $serialLog->postedBy = Auth::user()->name;
        $serialLog->date = Carbon::now();
        return $serialLog;
    }

    private function purchaseOrderOptions(ProductSerialNumbers $productSerialNumbers)
    {
        $serialLog = $this->baseOptions();
        $serialLog->doc = $productSerialNumbers->purchaseOrder->id;
        $serialLog->subject = "Purchase Order";

        $serialLog->from = $productSerialNumbers->purchaseOrder->vendor->name;
        $serialLog->to = $productSerialNumbers->purchaseOrder->store->name;
        $serialLog->url = route('purchaseOrder.view', $productSerialNumbers->purchaseOrder->id);

        return json_encode($serialLog);
    }
}
