<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Database;

class FoodTasteController extends Controller
{
    protected $database, $foodtaste;
    public function __construct(Database $database){
        $this->database = $database;
        $this->foodtaste = 'foodtaste';
    }
    public function index(){
        $content = $this->database->getReference('contents')->getValue();
        return view('guest.foodtaste.index', compact('content'));
    }
}
