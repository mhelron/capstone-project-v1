<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(){

        $isExpanded = session()->get('sidebar_is_expanded', true);
        return view('admin.dashboard.index', compact('isExpanded'));
    }
}
