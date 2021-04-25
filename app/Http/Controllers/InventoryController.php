<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\Store;
use App\Models\User;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
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
        (User::isSuperAdmin()) ?
            $inventories = Inventory::with('store')->get() : $inventories =
            Inventory::where('store_id', Session::get('store_id'))->get();
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
}
