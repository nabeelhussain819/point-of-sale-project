<?php

namespace App\Http\Controllers;

use App\Helpers\ArrayHelper;
use App\Http\Requests\TransferRequest;
use App\Models\Product;
use App\Models\ProductSerialNumbers;
use App\Models\PurchaseOrder;
use App\Models\StockTransfer;
use App\Models\StockTransferProduct;
use App\Models\Store;
use Carbon\Carbon;
use DB;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;

class TransferController extends Controller
{
    //

    public function index()
    {
        return view('admin.transfers.index',
            [
                'transfers' => StockTransfer::whereNull('received_date')->with('storeIn', 'storeOut')->get(), //@todo optimization so many relation
                'products' => Product::all(),// should be paginated record
            ]);
    }

    public function stockTransfer()
    {
        return view('admin.transfers.stock_transfer',
            [
                'products' => Product::all(),
                'stores' => Store::all(),
            ]);
    }


    public function store(TransferRequest $request)
    {
        DB::transaction(function () use ($request) {
            $products = $request->get("products");

            // save Stock Transfer
            $transfer = new StockTransfer();

            $transfer->fill($request->all())->save();

            //Adding Products to transfer stock
            $productData = [];
            collect($request->get('products'))->each(function ($product) use (&$productData) {
                unset($product['serials']); // hot fix
                unset($product['has_serials']); // hot fix
                $product['created_at'] = Carbon::now();
                $product['updated_at'] = Carbon::now();

                return $productData[] = $product;
            });

            //Adding Products to transfer stock


            $transfer->transferProducts()->sync($productData);

            collect($products)->each(function ($product) use ($transfer) {
                if (!empty($product["has_serials"]) && $product["has_serials"]) {

                    ProductSerialNumbers::where('product_id', $product["product_id"])
                        ->whereIn('serial_no', $product["serials"])
                        ->update(['stock_transfer_id' => $transfer->id]);
                }
            });

        });

    }

    /**
     * @param TransferRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Throwable
     */
    public function transfer(TransferRequest $request)
    {
        DB::transaction(function () use ($request) {
            $transfer = new StockTransfer();
            $productData = collect($request->get('products'))->map(function ($product) {
                unset($product['id']); // hot fix
                $product['created_at'] = Carbon::now();
                $product['updated_at'] = Carbon::now();
                return $product;
            });

            $transfer->fill($request->all())->save();
            $transfer->transferProducts()->sync($productData);


            collect($request->all('postSerial'))->each(function ($product, $productId) use ($transfer) {
                collect($product)->each(function ($serials, $productId) use ($transfer) {

                    ProductSerialNumbers::where('product_id', $productId)
                        ->whereIn('serial_no', $serials)
                        ->update(['stock_transfer_id' => $transfer->id]);
                });

            });
        });
        return redirect()->back()->with('success', 'Transfer Created');
    }


    public function received(StockTransfer $transfer)
    {
        return view('admin.transfers.received',
            [
                'transfer' => $transfer->withProducts(),
                'stores' => Store::all(),
                'products' => Product::all(),
            ]);
    }

    /**
     * @param StockTransfer $transfer
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Throwable
     */
    public function markAsReceived(StockTransfer $transfer, Request $request)
    {
        //todo Some centric logic

        DB::transaction(function () use ($transfer, $request) {
            $productData = collect($request->get('products'))->map(function ($product) {
                return $product;
            });

            $transfer->isReceiving = true;
            $transfer->productsSerials->each(function (ProductSerialNumbers $productSerialNumbers) use ($transfer) {
                $productSerialNumbers->subject = StockTransfer::class;
                $productSerialNumbers->subject_id = $transfer->id;
                $productSerialNumbers->update(['store_id' => $transfer->store_in_id]);
            });
//            $transfer->productsSerials()->update(['store_id' => $transfer->store_in_id]);

            $transfer->fill(ArrayHelper::merge([
                'received_date' => Carbon::now()
            ], $request->all())
            )->update();

//            $transfer->transferProducts()->sync($productData);
        });

        return redirect('/inventory-management/stock-transfer')->with('success', 'Transfer Created');
    }

    /**
     * @param StockTransfer $transfer
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function delete(StockTransfer $transfer)
    {
        return DB::transaction(function () use ($transfer) {
            ProductSerialNumbers::where("stock_transfer_id", $transfer->id)->update(["stock_transfer_id" => null]);
            $transfer->delete();
            return $this->genericResponse(true, "delete successfully");
        });

    }

    /**
     * @param StockTransfer $transfer
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Throwable
     */
    public function showAssociateProductSerial(StockTransfer $transfer)
    {
        $products = $transfer->products->filter(function (StockTransferProduct $product) {
            return $product->product->has_serial_number;
        });
        // if purchase order has  no serial type product
        if ($products->count() > 0) {
            return view('admin.transfers.associate-serial-product', ['transfer' => $transfer,
                'pOProducts' => $products]);
        }

        DB::transaction(function () use ($transfer) {
            if (is_null($transfer->received_date)) {
                $transfer->isReceiving = true;
                $transfer->update(['received_date' => Carbon::now()]);
            }
        });

        return redirect('/inventory-management/stock-transfer')->with('success', 'Transfer Created');
    }

    /**
     * @param Request $request
     * @param StockTransfer $transfer
     * @return mixed
     * @throws \Throwable
     */
    public function associateProductSerial(Request $request, StockTransfer $transfer)
    {
//        $products = [];
//        collect($request->get("serialProducts"))->each(function (&$productSerials, $productId) use (&$products, $transfer) {
//
//            collect($productSerials)->each(function ($serial) use (&$products, $productId, $transfer) {
//
//                $products[] = [
//                    'product_id' => $productId,
//                    'store_id' => $transfer->store_in_id,
//                    'serial_no' => $serial,
//                    'stock_transfer_id' => $transfer->id,
//                ];
//            });
//        });
//
//        //ProductSerialNumbers::upsert($products, ['serial_no', 'product_id'], ['store_id', 'stock_transfer_id']);
//
//
//        dd($products);
//
//
//        return DB::transaction(function () use ($purchaseOrder, $products) {
//            if (is_null($purchaseOrder->received_date)) {
//                $purchaseOrder->isReceiving = true;
//                $purchaseOrder->update(['received_date' => Carbon::now()]);
//                $purchaseOrder->save();
//                $purchaseOrder->productSerialNumbers()->sync($products);
//            }
//            return redirect()->route('purchase-order.index')->with('success', 'Purchase Order received');
//        });

    }

    public function all(Request $request)
    {

        return StockTransfer::select(['id', 'transfer_date', 'store_in_id', 'store_out_id', 'received_date', 'created_at'])
            ->with(['storeOut' => function (BelongsTo $builder) {
                $builder->select(['id', 'name']);
            }, 'storeIn' => function (BelongsTo $builder) {
                $builder->select(['id', 'name']);
            }])
            ->where($this->applyFilters($request))
            ->where(function (\Illuminate\Database\Eloquent\Builder $builder) {
                $builder->orWhere("store_in_id", Store::currentId())
                    ->orWhere('store_out_id', Store::currentId());
            })
            ->orderBy("created_at", 'desc')
            ->paginate($this->pageSize);
    }

    public function show(StockTransfer $transfer)
    {
        return view('admin.transfers.show', ["transfer" => $transfer]);
    }

}
