<?php

namespace App\Observers;

use App\Models\Inventory;
use App\Models\Order;
use App\Models\Store;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

class OrderObserver
{
    public function creating(Order $order)
    {
        $order->store_id = Store::currentId();
    }

    public function saved(Order $order)
    {
        $this->inventoryAdjustment($order);
    }

    //
    private function inventoryAdjustment(Order $order)
    {
        Inventory::serialNumberDetach($order->POSTEDPRODUCTS, $this->getLogData($order));
        $this->detachWithoutSerialNumber($order);
    }

    private function detachWithoutSerialNumber(Order $order)
    {
        $productIdsWithOutSerial = [];
        $nonSerialProducts = [];


        collect($order->POSTEDPRODUCTS)
            ->filter(function ($product) {
                return empty($product['serial_number']);
            })
            ->each(function ($product) use (&$nonSerialProducts) {

                // for repetitive product n the product list like if I select 1001, 1001 2 time single single  it was consider as on before no it has been fixid
                if (!empty($nonSerialProducts[$product['product_id']])) {
                    $key = $nonSerialProducts[$product['product_id']];

                    $nonSerialProducts[$product['product_id']] = [
                        'product_id' => $product['product_id'],
                        'quantity' => $product['quantity'] + $key['quantity'],
                    ];

                } else {
                    $nonSerialProducts[$product['product_id']] = [
                        'product_id' => $product['product_id'],
                        'quantity' => $product['quantity'],
                    ];
                }
            });
        $productIdsWithOutSerial = array_keys($nonSerialProducts);

        Inventory::where('store_id', Store::currentId())
            ->whereIn('product_id', $productIdsWithOutSerial)
            ->get()
            ->each(function (Inventory $inventory) use ($nonSerialProducts) {
                $inventory->OUTGOING_PRODUCTS = true;
                if ($inventory->quantity < $nonSerialProducts[$inventory->product_id]['quantity']) {
                    throw new ConflictHttpException($inventory->product->name . " not enough Quantity, only " . $inventory->quantity . " are on inventory");
                }

                $inventory->update(['quantity' =>
                    $inventory->quantity - $nonSerialProducts[$inventory->product_id]['quantity']
                ]);
            });
    }

    private function getLogData(Order $order): array
    {
        if (!empty($order->LOGDATA) && !isset($order->LOGDATA['subject_id'])) {
            $order->LOGDATA['subject_id'] = $order->id;
        }
        return $order->LOGDATA;
    }
}
