<?php

namespace App\Observers;

use App\Models\Inventory;
use App\Models\Product;
use App\Models\StockTransfer;
use App\Models\StockTransferProduct;
use App\Models\Type;

class StockTransferObserver
{
    public function saving(StockTransfer $stockTransfer)
    {

        if ($stockTransfer->isReceiving) {

            $stockTransfer->products->each(function (StockTransferProduct $stockTransferProduct) use ($stockTransfer) {
                $productPrice = Product::getPrice($stockTransferProduct->product_id);
                Inventory::updateOrCreate([
                    'product_id' => $stockTransferProduct->product_id,
                    'store_id' => $stockTransfer->store_in_id
                ], [
                    'name' => 'test',
                    'product_id' => $stockTransferProduct->product_id,
                    'vendor_id' => $stockTransferProduct->vendor_id,
                    'description' => 'update transfer',
                    'quantity' => $stockTransferProduct->quantity,
                    'extended_cost' => $productPrice,
                    'lookup' => 12, // todo IDK
                    'UPC' => 12,
                    'cost' => $productPrice, // @todo handling and optimization
                    'stock_bin_id' => Type::RETAIL,
                    'store_id' => $stockTransfer->store_in_id,
                ]);
            });

            Inventory::where('store_id', $stockTransfer->store_out_id)
                ->whereIn('product_id', $stockTransfer->products->pluck('product_id')->all())
                ->get()
                ->each(function (Inventory $inventory) use ($stockTransfer) {
                    $inventory->OUTGOING_PRODUCTS = true;

                    $inventory->update(['quantity' =>
                        $inventory->quantity - $stockTransfer->products->where('product_id', $inventory->product_id)->first()->quantity]);
                });

        }
    }
}
