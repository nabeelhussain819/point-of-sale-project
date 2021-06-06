<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\ProductSerialNumbers;

use App\Models\SerialLogs;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Request;

class TrackingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.tracking.index');
    }

    //


    public function serialTracking(ProductSerialNumbers $productSerialNumbers)
    {
        return $productSerialNumbers->load(['track']);
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
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\ProductSerialNumbers $productSerialNumbers
     * @return \Illuminate\Http\Response
     */
    public function show($trackingId)
    {
        return SerialLogs::getView($trackingId);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\ProductSerialNumbers $productSerialNumbers
     * @return \Illuminate\Http\Response
     */
    public function edit(ProductSerialNumbers $productSerialNumbers)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\ProductSerialNumbers $productSerialNumbers
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProductSerialNumbers $productSerialNumbers)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\ProductSerialNumbers $productSerialNumbers
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductSerialNumbers $productSerialNumbers)
    {
        //
    }
}
