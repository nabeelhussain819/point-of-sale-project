<?php

namespace App\Http\Controllers;

use mysql_xdevapi\Session;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __construct()
    {
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function getStoreId(Request $request)
    {
        \Illuminate\Support\Facades\Session::put('store_id', $request->get('store_id'));
        return back()->with('success','Store Selected');
    }
}
