<?php

namespace App\Http\Controllers;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Contract\Database;

class FirebaseController extends Controller
{
    protected $database, $reservations;

    public function __construct(Database $database){
        $this->database = $database;
        $this->reservations = 'reservations';
    }

    public function deleteReservations()
    {
        // Delete the 'reservations' node
        $this->database->getReference('/reservations')->remove();

        return response()->json(['message' => 'Reservations node deleted successfully!']);
    }
}
