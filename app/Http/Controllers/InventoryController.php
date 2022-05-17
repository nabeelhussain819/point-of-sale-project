<?php

namespace App\Http\Controllers;

use App\Helpers\StringHelper;
use App\Models\Inventory;
use App\Models\Product;
use App\Models\ProductSerialNumbers;
use App\Models\Refund;
use App\Models\StockBin;
use App\Models\Store;
use App\Models\Type;
use App\Models\User;
use App\Models\VendorReturn;
use App\Scopes\StockBinGlobalScope;
use App\Scopes\StoreGlobalScope;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Spatie\Activitylog\Models\Activity;
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

    public function search(Request $request)
    {

        $inventory = new Inventory();
        return $inventory->
        withProduct()->select(['id', 'quantity', 'product_id'])
            ->where($this->applyFilters($request))
            ->where('store_id', Store::currentId())
            ->get();
    }

    public function all(Request $request)
    {
        $inventory = new Inventory();
        return $inventory->getAll($request)
            ->whereNotExists(function (Builder $builder) {
                $builder->select('id')->where("quantity", "<=", 0)
                    ->where("stock_bin_id", "=", StockBin::TYPE_RETURN);

            })
            ->with(['bin' => function (BelongsTo $belongsTo) {
                $belongsTo->select(['name', 'id']);
            }])
            ->orderBy('name')
            ->paginate($this->pageSize);
    }

    /**
     * 1 return true
     * 0 return false
     * @param Request $request
     * @param $inventory
     * @return mixed
     * @throws \Throwable
     */
    // so much logic on controller
    public function changeBin(Request $request, $inventory)
    {
        return DB::transaction(function () use ($request, $inventory) {
            $inventory = Inventory::where('id', $inventory)->withoutGlobalScope(new StockBinGlobalScope())->firstOrFail();
            $isNotVendor = $request->get('return_to') !== 1;

            // If Changing the bin not related to Vendor
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

                    $inventoryData = [
                        'name' => 'test',
                        'product_id' => $inventory->product_id,
                        'vendor_id' => $inventory->vendor_id,
                        'quantity' => $request->get("quantity"),
                        'extended_cost' => $inventory->price,
                        'cost' => $inventory->price,
                        'stock_bin_id' => $request->get("stock_bin_id"),
                        'store_id' => $inventory->store_id,
                    ];

                    $postInventory->fill($inventoryData);


                    $postInventory->save();
                }
            }

            ProductSerialNumbers::changeBins($request->get("serial_numbers"), $request->get("stock_bin_id"), [
                'subject' => Inventory::class,
                'subject_id' => $inventory->id,
                'subject_data' => $inventory,
                'vendor_id' => $request->get('vendor_id'),
            ], !$isNotVendor);

            $inventory->OUTGOING_PRODUCTS = true;


            $inventory->update(['quantity' => $inventory->quantity - $request->get("quantity")]);

            if (!$isNotVendor) {
                $product_has_serial = collect($request->get("serial_numbers"))->count() > 0;

                VendorReturn::editOrInsert(
                    [
                        'product_id' => $inventory->product_id,
                        'store_id' => $inventory->store_id,
                        'quantity' => $request->get('quantity'),
                        'vendor_id' => $request->get('vendor_id'),
                        'has_serial_number' => $product_has_serial
                    ]
                );
            }


            return $this->genericResponse(true, "Stock has been transfer", 200, ['inventory' => $inventory]);
        });


    }

    public function checkProductCount(Request $request)
    {
        $inventory = Inventory::getProductQuantity($request->get("store_out_id"), $request->get("product_id"));

        if (!empty($inventory)) {
            $originalQuantity = $inventory->quantity;

            $quantity = $originalQuantity - $request->get('quantity');

            if ($quantity < 0) {
                return $this->genericResponse(false, " only  {$originalQuantity} item available in store", 409, ["code" => 409]);
            }
            return true;
        }
        return $this->genericResponse(false, "no item available in store", 409, ["code" => 409]);
    }

    public function log()
    {
        return Activity::getChangesAttribute();

    }

    public function getBackFromVendor(Request $request)
    {
        dd($request->all());
    }


}
