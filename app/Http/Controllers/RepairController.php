<?php

namespace App\Http\Controllers;

use App\Models\Repair;
use DB;
use Illuminate\Http\Request;

class RepairController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.repair.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws \Throwable
     */
    public function store(Request $request)
    {
        return DB::transaction(function () use ($request) {

            $repair = new Repair();
            $repair->fill(array_merge($request->all(), ['customer_id' => 1]));
            $repair->save();
            $repair->products()->sync(array_filter($request->get('productItem')));
            return $this->genericResponse(true, " repair has been created", 201);
        });

    }

    /**
     *
     */
    public function show(Repair $repair)
    {
        return $repair->load(['relatedProducts', 'customer']);
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

    public function fetch(Request $request)
    {
        return Repair::where('status', Repair::IN_PROGRESS_STATUS)
            ->with("customer")->orderBy('created_at')->get();
    }
}
