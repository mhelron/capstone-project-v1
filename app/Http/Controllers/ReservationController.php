<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function index(){

        $isExpanded = session()->get('sidebar_is_expanded', true);
        return view('admin.reservation.index', compact('isExpanded'));
    }
}
