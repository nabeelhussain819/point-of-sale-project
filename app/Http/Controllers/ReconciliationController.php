<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\Reconciliation;
use App\Models\ReconciliationProduct;
use Illuminate\Http\Request;

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
}
