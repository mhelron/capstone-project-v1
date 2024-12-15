<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Database;

class QuotationController extends Controller
{
    protected $database, $quotations;
    public function __construct(Database $database){
        $this->database = $database;
        $this->quotations = 'quotations';
    }
    public function index(){
        $content = $this->database->getReference('contents')->getValue();
        return view('guest.quotation.index', compact('content'));
    }
}
