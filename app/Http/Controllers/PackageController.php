<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Database;

class PackageController extends Controller
{
    protected $database, $packages, $archived_packages;

    public function __construct(Database $database){
        $this->database = $database;
        $this->packages = 'packages';
        $this->archived_packages = 'archived_packages';
    }

    public function index(){
        $packages = $this->database->getReference('packages')->getValue();
        $packages = is_array($packages) ? $packages : [];

        $isExpanded = session()->get('sidebar_is_expanded', true);
        return view('admin.packages.index', compact('isExpanded', 'packages'));
    }
}
