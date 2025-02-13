<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Vendor;
use App\Models\VendorReturn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('admin.vendors.index', ['vendors' => Vendor::all()]);
    }

    public function vendorReturns()
    {
        //
        return view('admin.vendors.refunded');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('admin.vendors.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'name' => 'required',
            'telephone' => 'required',

        ]);
        $vendors = new Vendor();
        $vendors->fill($request->all())->save();
        return redirect('inventory-management/vendors')->with('success', "Vendor {$vendors->name} Created");
    }


    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Vendor $vendor)
    {
        //
        return view('admin.vendors.edit', ['vendor' => $vendor]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $vendor = Vendor::find($id);
        $vendor->fill($request->all())->update();
        return redirect()->back()->with('success', "$vendor->name Updated");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Vendor $vendor)
    {
        //
        $vendor->delete();
        return redirect()->back()->with('success', 'Vendor Deleted');

    }

    public function search(Request $request)
    {
        //Todo AppyQuery Params
        return Vendor::where($this->applyFilters($request))
            ->paginate();
    }

    public function getList(Request $request)
    {
        return Vendor::where($this->applyFilters($request))
            ->get();
    }

    public function refundedList(Request $request)
    {
        return VendorReturn::where($this->applyFilters($request))
            ->with(['vendor' => function (BelongsTo $builder) {
                $builder->select(['id', 'name']);
            }, 'product' => function (BelongsTo $builder) {
                $builder->select(['id', 'name']);
            }])
            ->paginate($this->getPageSize());
    }
}
