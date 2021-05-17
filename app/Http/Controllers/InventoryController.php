<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\ProductSerialNumbers;
use App\Models\Refund;
use App\Models\Store;
use App\Models\Type;
use App\Models\User;
use App\Scopes\StockBinGlobalScope;
use App\Scopes\StoreGlobalScope;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use \Illuminate\Database\Eloquent\Builder as eloquentBuilder;

class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
        return view('admin.inventory.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
        $inventories = Inventory::where('store_id', Session::get('store_id'))->get();
        return view('admin.inventory.create', ['inventories' => $inventories]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'name' => 'required',
            'product_id' => 'required',
            'vendor_id' => 'required',
            'quantity' => 'required'
        ]);
        $inventory = new Inventory();
        $inventory->guid = Str::uuid();
        $inventory->store_id = Session::get('store_id');
        $inventory->fill($request->all())->save();
        return redirect('inventory-management/inventory/create')->with('success', 'Inventory Added');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        //
        return view('admin.inventory.edit', ['inventory' => Inventory::find($id)]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
        $request->validate([
            'name' => 'required',
            'product_id' => 'required',
            'vendor_id' => 'required'
        ]);
        $inventory = Inventory::find($id);
        $inventory->fill($request->all())->update();
        return redirect('inventory-management/inventory/create')->with('success', 'Inventory Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
        $inventory = Inventory::find($id);
        $inventory->delete();
        return redirect()->back()->with('success', 'Inventory Deleted');
    }

    /**
     * todo not use that method on any other ways causing issue change name also
     * @param Request $request
     */
    public function products(Request $request)
    {
        $inventory = new Inventory();
        return $inventory->generalSearch($request);
    }

    public function list(Request $request)
    {
        $inventory = new Inventory();
        return $inventory->getAllProducts($request);
    }

    public function all(Request $request)
    {
        $inventory = new Inventory();
        return $inventory->getAll($request)
            ->with(['bin' => function (BelongsTo $belongsTo) {
                $belongsTo->select(['name', 'id']);
            }])
            ->orderBy('stock_bin_id')
            ->paginate($this->pageSize);
    }

    /**
     * @param Request $request
     * @param $inventory
     * @return mixed
     * @throws \Throwable
     */
    public function changeBin(Request $request, $inventory)
    {
        return DB::transaction(function () use ($request, $inventory) {

            $inventory = Inventory::where('id', $inventory)->withoutGlobalScope(new StockBinGlobalScope())->firstOrFail();
            $isNotVendor = $request->get('return_to') !== 1;

            // If Changing the bin not related to Vandour
            if ($isNotVendor) {
                $postInventory = Inventory::withoutGlobalScope(new StockBinGlobalScope())
                    ->where('product_id', $inventory->product_id)
                    ->where('store_id', $inventory->store_id)
                    ->where('stock_bin_id', $request->get("stock_bin_id"))
                    ->first();

                if ($postInventory) {
                    $postInventory->update(['quantity' => $request->get("quantity")]);
                } else {
                    $postInventory = new Inventory();
                    $postInventory->fill([
                        'name' => 'test',
                        'product_id' => $inventory->product_id,
                        'vendor_id' => $inventory->vendor_id,
                        'quantity' => $request->get("quantity"),
                        'extended_cost' => $inventory->price,
                        'cost' => $inventory->price,
                        'stock_bin_id' => $request->get("stock_bin_id"),
                        'store_id' => $inventory->store_id,
                    ]);

                    $postInventory->save();
                }
            }

            ProductSerialNumbers::changeBins($request->get("serial_numbers"), $request->get("stock_bin_id"), [
                'subject' => Inventory::class,
                'subject_id' => $inventory->id,
                'subject_data' => $inventory
            ], !$isNotVendor);

            $inventory->OUTGOING_PRODUCTS = true;
            $inventory->update(['quantity' => $inventory->quantity - $request->get("quantity")]);

            return $this->genericResponse(true, "Stock has been transfer", 200, ['inventory' => $inventory]);
        });


    }
}
