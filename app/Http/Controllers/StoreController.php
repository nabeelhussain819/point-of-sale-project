<?php

namespace App\Http\Controllers;

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
        $this->validateFields($request);
        $store = new Store();
        $store->guid = Str::uuid();
        $store->code = Store::code();
        $store->fill($request->all())->save();
        return back()->with('success','Store Created');
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
    public function edit($id)
    {
        //
        $store = Store::with('userStores')->find($id);
        return view('admin.stores.edit',['store' => $store, 
        'role_id' => $store->userStores->pluck('role_id')->first(),
        'user_id' => $store->userStores->pluck('user_id')->first(),
        'storeOfUser' => UserStore::with('store','user','role')->where('store_id',$id)->get()
        ]);
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
        if($request->get('assignform'))
        {
         $this->assignUserToStore($request);
        }
        else
        {
        $store = Store::find($id);
        $store->fill($request->all())->update();
        }
        return back()->with('success',"$store->name Updated");
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
            'user_id' => 'required'
        ]);
        $userStore->fill($request->all())->save();
        return redirect()->back()->with('success','User Assigned To the Store');

    }

    private function validateFields($request)
    {
        $request->validate([
            'name' => 'required',
            'full_name' => 'required',
            'primary_phone' => 'required',
        ]);
    }
}
