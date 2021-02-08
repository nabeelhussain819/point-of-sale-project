<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Models\UserStore;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class StoreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $userStore = UserStore::with('store','user','role')->get();
        return view('admin.stores.index',['store' => $userStore]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('admin.stores.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $store = new Store();
        $store->guid = Str::uuid();
        $store->fill($request->all())->save();
        return redirect('admin/dashboard')->with('success','Store Created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Store $store)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Store $store)
    {
        //
        return view('admin.stores.edit',['store' => $store]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $store = Store::find($id);
        $store->fill($request->all())->update();
        return redirect('admin/dashboard')->with('success',"$store->name Updated");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $stores = Store::find($id);
        $stores->delete();
        return back()->with('success',"Deleted Store");
    }

    public function assignUserToStore(Request $request)
    {
        $userStore = new UserStore();
        $request->validate([
            'role_id' => 'required',
            'user_id' => 'required'
        ]);
        $userStore->fill($request->all())->save();
        return redirect('admin/stores')->with('success','User Assigned To the Store');

    }
}
