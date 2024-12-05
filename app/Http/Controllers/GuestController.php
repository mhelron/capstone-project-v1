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

    public function indexCalendar()
    {
        // Fetch and sanitize reservations
        $reservations = $this->database->getReference($this->reservations)->getValue();
        $reservations = is_array($reservations) ? array_filter($reservations, function($reservation) {
            return is_array($reservation) && 
                isset($reservation['event_date']) && 
                isset($reservation['status']);
        }) : [];

        // Fetch and sanitize packages
        $packages = $this->database->getReference($this->packages)->getValue();
        $packages = is_array($packages) ? array_filter($packages, function($package) {
            return is_array($package) && 
                isset($package['package_name']) && 
                isset($package['package_type']) && 
                isset($package['menus']);
        }) : [];

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
