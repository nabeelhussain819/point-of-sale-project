<?php

namespace App\Observers;

use App\Models\Inventory;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrdersProduct;
use App\Models\Type;


class PurchaseOrderObserver
{

    /**
     * @param PurchaseOrder $purchaseOrder
     */
    public function updating(PurchaseOrder $purchaseOrder)
    {
        if ($purchaseOrder->isReceiving) {
            $purchaseOrder->products->each(function (PurchaseOrdersProduct $purchaseOrdersProduct) use ($purchaseOrder) {

                //That method should be in inventory model optimization after 3 moduel integration
                Inventory::updateOrCreate([
                    'product_id' => $purchaseOrdersProduct->product_id,
                    'store_id' => $purchaseOrder->store_id
                ], [
                    'name' => 'test',
                    'product_id' => $purchaseOrdersProduct->product_id,
                    'vendor_id' => $purchaseOrder->vendor_id,
                    'quantity' => $purchaseOrdersProduct->quantity,
                    'extended_cost' => $purchaseOrdersProduct->price,
                    'lookup' => 12, // todo IDK
                    'description' => 'test',
                    'UPC' => 12,
                    'cost' => $purchaseOrdersProduct->price,
                    'stock_bin_id' => Type::RETAIL,
                    'store_id' => $purchaseOrder->store_id,

                ]);
            });
        }
    }
}
