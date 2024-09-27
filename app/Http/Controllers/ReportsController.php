<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportsController extends Controller
{
    public function reservation(){

        $isExpanded = session()->get('sidebar_is_expanded', true);
        return view('admin.reports.reservation.index', compact('isExpanded'));
    }

    public function sales(){

        $isExpanded = session()->get('sidebar_is_expanded', true);
        return view('admin.reports.sales.index', compact('isExpanded'));
    }
}
