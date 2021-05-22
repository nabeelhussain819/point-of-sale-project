<?php

namespace App\Observers;

use App\Models\Inventory;
use App\Models\Product;
use App\Models\StockBin;
use App\Models\StockTransfer;
use App\Models\StockTransferProduct;
use App\Models\Type;
use App\Scopes\StockBinGlobalScope;
use App\Scopes\StoreGlobalScope;

class StockTransferObserver
{
    public function saving(StockTransfer $stockTransfer)
    {

        if ($stockTransfer->isReceiving) {

            $stockTransfer->products->each(function (StockTransferProduct $stockTransferProduct) use ($stockTransfer) {
                $productPrice = Product::getPrice($stockTransferProduct->product_id);

                $postInventory = Inventory::withoutGlobalScope(new StockBinGlobalScope())
                    ->withoutGlobalScope(new StoreGlobalScope())
                    ->where('product_id', $stockTransferProduct->product_id)
                    ->where('store_id', $stockTransfer->store_in_id)
                    ->where('stock_bin_id', Type::RETAIL)
                    ->first();

                if ($postInventory) {
                    $postInventory->update([
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
                } else {
                    $postInventory = new Inventory();
                    $postInventory->fill([
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

                    $postInventory->save();
                }

            });

            Inventory::where('store_id', $stockTransfer->store_out_id)
                ->where('stock_bin_id', Type::RETAIL)
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
