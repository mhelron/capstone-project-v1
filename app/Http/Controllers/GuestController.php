<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GuestController extends Controller
{
    public function indexHome(){
        return view('guest.home.index');
    }

    public function indexReservation(){
        return view('guest.reservation.index');
    }

    public function indexCalendar(){
        return view('guest.calendar.index');
    }

    public function indexContact(){
        return view('guest.contact.index');
    }

    public function indexAbout(){
        return view('guest.about.index');
    }
}
