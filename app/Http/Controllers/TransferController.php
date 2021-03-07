<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\Product;
use App\Models\StockTransfer;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TransferController extends Controller
{
    //

    public function index()
    {
        return view('admin.transfers.index',
            [
                'transfers' => StockTransfer::with('storeIn', 'storeOut')->get(), //@todo optimization so many relation
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

        return view('admin.transfers.received', ['transfer' => $transfer]);
    }

    public function markAsReceived(Request $request)
    {
        foreach ($request->get('products') as $product) {
            Inventory::insert([
                'product_id' => $product,
                'guid' => Str::uuid(),
                'description' => $request->get('description'),
                'lookup' => $request->get('lookup'),
                'name' => $request->get('name'),
                'quantity' => $request->get('quantity'),
                'store_id' => $request->get('store_out'),
                'cost' => $request->get('cost'),
                'extended_cost' => $request->get('cost'),
                'vendor_id' => $request->get('vendor_id'),
                'bin' => $request->get('bin'),
            ]);

            $storeIn = Inventory::where('store_id', $request->get('store_in'))->first();
            $quantity = $storeIn->quantity - $request->get('quantity');
            $storeIn->update([
                'quantity' => $quantity
            ]);
        }
        return redirect()->back()->with('success', 'StockBin Transfered');
    }

    public function delete(StockTransfer $transfer)
    {
        $transfer->delete();
        return redirect()->back()->with('success', 'Transfer Rejected');
    }
}
