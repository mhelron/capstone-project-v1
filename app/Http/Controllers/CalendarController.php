<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Kreait\Firebase\Contract\Database;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    protected $database, $reservations, $packages;

    public function __construct(Database $database)
    {
        $this->database = $database;
        $this->reservations = 'reservations';
        $this->packages = 'packages';
    }

    public function index()
    {
        $reservations = $this->database->getReference($this->reservations)->getValue();
        $reservations = is_array($reservations) ? $reservations : [];

        $packages = $this->database->getReference($this->packages)->getValue();
        $packages = is_array($packages) ? $packages : [];

        $isExpanded = session()->get('sidebar_is_expanded', true);
        return view('admin.calendar.index', compact('isExpanded', 'reservations', 'packages'));
    }
}