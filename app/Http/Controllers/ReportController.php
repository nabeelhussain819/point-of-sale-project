<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class ReportController extends Controller
{
    public function index()
    {
        //
        return view('admin.reports.index');
    }
}
