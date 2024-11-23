<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Kreait\Firebase\Contract\Database;
use Illuminate\Http\Request;

class GuestController extends Controller
{
    protected $database, $reservations, $packages;

    public function __construct(Database $database)
    {
        $this->database = $database;
        $this->reservations = 'reservations';
        $this->packages = 'packages'; // The path to the reservations node
    }

    public function indexHome(){
        return view('guest.home.index');
    }

    public function indexPackages(){
        return view('guest.packages.index');
    }

    public function indexCalendar(){
        $reservations = $this->database->getReference($this->reservations)->getValue();
        $reservations = is_array($reservations) ? $reservations : [];

        $packages = $this->database->getReference($this->packages)->getValue();
        $packages = is_array($packages) ? $packages : [];

        return view('guest.calendar.index', compact('reservations', 'packages'));
    }

    public function indexContact(){
        return view('guest.contact.index');
    }

    public function indexAbout(){
        return view('guest.about.index');
    }

    public function indexGallery(){
        return view('guest.gallery.index');
    }
}
