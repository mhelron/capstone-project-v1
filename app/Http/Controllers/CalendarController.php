<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Kreait\Firebase\Contract\Database;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    protected $database, $reservations;

    public function __construct(Database $database)
    {
        $this->database = $database;
        $this->reservations = 'reservations'; // The path to the reservations node
    }

    public function index()
    {
        $reservations = $this->database->getReference($this->reservations)->getValue();
        $reservations = is_array($reservations) ? $reservations : [];

        $isExpanded = session()->get('sidebar_is_expanded', true);
        return view('admin.calendar.index', compact('isExpanded', 'reservations'));
    }
}
