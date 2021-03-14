<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\Reconciliation;
use App\Models\Store;
use App\Models\ReconciliationProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReconciliationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $reconciliation = new Reconciliation();
        return view('admin.reconciliation.index', [
            'reconciliations' => $reconciliation->getIncomplete()->get(),
//            'products' => Inventory::storeProducts()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.reconciliation.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function conciliation(Reconciliation $reconciliation)
    {
        $concileData = [];
        collect($reconciliation->products)->map(function (ReconciliationProduct $reconciliationProduct) use (&$concileData) {

            $concileData[$reconciliationProduct->product_id] = [
                'physical_quantity' => $reconciliationProduct->physical_quantity
            ];
        });

        return view('admin.reconciliation.conciliation', [
            'conciliation' => $reconciliation, // no need of join
            'inventories' => Inventory::storeProductsById($reconciliation->products->pluck('product_id')->toArray()),
            'concileData' => $concileData
        ]);

    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Throwable
     */
    public function matchConciliation(Request $request)
    {
        $iProductId = $request->input("product_id");
        $iInventoryId = $request->input("inventory_id");
        $sType = strtolower($request->input("type"));
        $iConcileId = strtolower($request->input("concile_id"));

        switch ($sType) {
            case 'overstocks':
                # code...
                $this->updateOverStock($request);
                break;
            case 'deficit':
                # code...
                $this->updateDeficitStock($request);
                break;
            
            default:
                # code...
                break;
        }
        $this->isConciled($iConcileId);

        return redirect('/reconciliation')->with('success', "Stock has been update!");
    } 

    public function updateOverStock(Request $request){   
        $iProductId     = (int)$request->input("product_id");
        $iInventoryId   = (int)$request->input("inventory_id");
        $iConcileId     = (int)$request->input("concile_id");
        $iPhysicalQuantity = 0;

        $aInventoryData = Inventory::find($iInventoryId);
        $aConcileData = ReconciliationProduct::find($iConcileId);

        $iPhysicalQuantity = $aConcileData->physical_quantity;
        $iDifference = $iPhysicalQuantity - $aInventoryData->quantity;

        ReconciliationProduct::updateSystemQuantityAndAjustQuantityByReconcilationIdAndProductId(
            $iConcileId,
            $iProductId,
            $aInventoryData->quantity,
            $iDifference
        );

        //update inventory 
        $iTotalQuantity = $iDifference  + $aInventoryData->quantity;

        Inventory::updateProductQuantityByIdAndProductId($iInventoryId, $iProductId, $iTotalQuantity);
        
        // dd(DB::getQueryLog());
    } 

    public function updateDeficitStock(Request $request){   
        $iProductId     = $request->input("product_id");
        $iInventoryId   = $request->input("inventory_id");
        $iConcileId     = $request->input("concile_id");
        $iPhysicalQuantity = 0;

        $aInventoryData = Inventory::find($iInventoryId);
        $aConcileData = ReconciliationProduct::find($iConcileId);

        $iPhysicalQuantity = $aConcileData->physical_quantity;
        $iDifference = $aInventoryData->quantity - $iPhysicalQuantity;

        ReconciliationProduct::updateSystemQuantityAndAjustQuantityByReconcilationIdAndProductId(
            $iConcileId,
            $iProductId,
            $aInventoryData->quantity,
            $iDifference
        );

        //update inventory 
        $iTotalQuantity = $aInventoryData->quantity - $iDifference ;

        Inventory::updateProductQuantityByIdAndProductId($iInventoryId, $iProductId, $iTotalQuantity);
        
        // dd(DB::getQueryLog());
    } 

    public function isConciled($iConcileId){
        // DB::enableQueryLog();
        // marked is conciled 1
        $aResult = Reconciliation::where('id', '=', $iConcileId)->update(array('is_reconciled' => 1));
        // dd(DB::getQueryLog());
    } 


}
