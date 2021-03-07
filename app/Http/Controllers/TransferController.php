<?php

namespace App\Http\Controllers;

use App\Helpers\ArrayHelper;
use App\Models\Product;
use App\Models\StockTransfer;
use App\Models\Store;
use Carbon\Carbon;
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

    public function transfer(Request $request)
    {
        $transfer = new StockTransfer();

        $productData = collect($request->get('products'))->map(function ($product) {
            return $product;
        });

        $transfer->fill($request->all())->save();
        $transfer->transferProducts()->sync($productData);
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

    public function markAsReceived(StockTransfer $transfer, Request $request)
    {
        $productData = collect($request->get('products'))->map(function ($product) {
            return $product;
        });

        $transfer->fill(ArrayHelper::merge([
            'received_date' => Carbon::now()
        ], $request->all()))->update();
        $transfer->transferProducts()->sync($productData);
        return redirect('/inventory-management/stock-transfer')->with('success', 'Transfer Created');
//        return redirect()->back()
    }

    public function delete(StockTransfer $transfer)
    {
        $transfer->delete();
        return redirect()->back()->with('success', 'Transfer Rejected');
    }
}
