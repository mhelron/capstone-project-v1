<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ListPackageController extends Controller
{
    public function marikina(){
        return view('guest.packages.marikina');
    }

    public function sanmateo(){
        return view('guest.packages.sanmateo');
    }

    public function montalban(){
        return view('guest.packages.montalban');
    }
}
