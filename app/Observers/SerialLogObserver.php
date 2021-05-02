<?php

namespace App\Observers;

use App\Data\SerialLogDTO;
use App\Models\Finance;
use App\Models\Order;
use App\Models\ProductSerialNumbers;
use App\Models\PurchaseOrder;
use App\Models\SerialLogs;
use App\Models\StockTransfer;
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
                break;
            case StockTransfer::class:
                $this->belongsToTransfer($productSerialNumbers);
                break;
            case Order::class:
                $this->belongsToOrder($productSerialNumbers);
                break;
            case Finance::class:
                $this->belongsToFinance($productSerialNumbers);
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

    private function belongsToTransfer(ProductSerialNumbers $productSerialNumbers)
    {
        $serialLog = new SerialLogs();
        $serialLog->add(
            $productSerialNumbers->id,
            $productSerialNumbers->serial_no,
            StockTransfer::class,
            $productSerialNumbers->stockTransfer->id,
            $this->transferOrderOptions($productSerialNumbers)
        );
    }

    private function belongsToFinance(ProductSerialNumbers $productSerialNumbers)
    {
        $serialLog = new SerialLogs();
        $serialLog->add(
            $productSerialNumbers->id,
            $productSerialNumbers->serial_no,
            $productSerialNumbers->subject,
            $productSerialNumbers->subject_id,
            $this->financeOptions($productSerialNumbers)
        );
    }

    private function belongsToOrder(ProductSerialNumbers $productSerialNumbers)
    {
        $serialLog = new SerialLogs();
        $serialLog->add(
            $productSerialNumbers->id,
            $productSerialNumbers->serial_no,
            Order::class,
            $productSerialNumbers->subject_id,
            $this->orderOptions($productSerialNumbers)
        );
    }

    private function baseOptions(): SerialLogDTO
    {
        $serialLog = new   SerialLogDTO();
        $serialLog->postedBy = Auth::user()->name;
        $serialLog->date = Carbon::now();
        return $serialLog;
    }

    private function transferOrderOptions(ProductSerialNumbers $productSerialNumbers)
    {
        $serialLog = $this->baseOptions();
        $serialLog->doc = $productSerialNumbers->stockTransfer->id;
        $serialLog->subject = "Stock Transfer";

        $serialLog->from = $productSerialNumbers->stockTransfer->storeOut->name;
        $serialLog->to = $productSerialNumbers->stockTransfer->storeIn->name;
        $serialLog->url = route('transfer.view', $productSerialNumbers->stockTransfer->id);

        return json_encode($serialLog);
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

    private function orderOptions(ProductSerialNumbers $productSerialNumbers)
    {
        $serialLog = $this->baseOptions();
        $serialLog->doc = $productSerialNumbers->stockTransfer->id;
        $serialLog->subject = "Orders | Sales";
        $customer = 'No Customer';
        if ($productSerialNumbers->subject_data->customer) {
            $customer = $productSerialNumbers->subject_data->customer->name;
        }

        $serialLog->from = $productSerialNumbers->subject_data->store->name;
        $serialLog->to = $customer;
        $serialLog->url = route('order.view', $productSerialNumbers->subject_id);

        return json_encode($serialLog);
    }

    private function financeOptions(ProductSerialNumbers $productSerialNumbers)
    {
        $serialLog = $this->baseOptions();
        $serialLog->doc = $productSerialNumbers->subject_id;
        $serialLog->subject = "Finance";
        $customer = 'No Customer';
        if ($productSerialNumbers->subject_data->customer) {
            $customer = $productSerialNumbers->subject_data->customer->name;
        }

        $serialLog->from = $productSerialNumbers->subject_data->store->name;
        $serialLog->to = $customer;
        $serialLog->url = route('order.view', $productSerialNumbers->subject_id);

        return json_encode($serialLog);
    }

}
