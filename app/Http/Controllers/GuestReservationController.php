<?php

namespace App\Http\Controllers;

use Kreait\Firebase\Contract\Database;
use Illuminate\Http\Request;

class GuestReservationController extends Controller
{
    protected $database, $packages;

    public function __construct(Database $database){
        $this->database = $database;
        $this->packages = 'packages';
    }
    public function index(){
        $packages = $this->database->getReference($this->packages)->getValue();
        $packages = is_array($packages) ? array_map(fn($package) => $package, $packages) : [];

        return view('guest.reservation.index', compact('packages'));
    }
}
