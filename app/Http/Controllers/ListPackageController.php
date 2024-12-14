<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Database;

class ListPackageController extends Controller
{
    public function marikina(Database $database)
    {
        // Fetch data for Marikina
        $packages = $database->getReference('packages')
                             ->orderByChild('area_name')
                             ->equalTo('Marikina')
                             ->getValue();
        $content = $database->getReference('contents')->getValue();
        return view('guest.packages.marikina', compact('packages', 'content'));
    }

    public function sanmateo(Database $database)
    {
        // Fetch data for San Mateo
        $packages = $database->getReference('packages')
                             ->orderByChild('area_name')
                             ->equalTo('San Mateo')
                             ->getValue();
        $content = $database->getReference('contents')->getValue();
        return view('guest.packages.sanmateo', compact('packages', 'content'));
    }

    public function montalban(Database $database)
    {
        // Fetch data for Montalban
        $packages = $database->getReference('packages')
                             ->orderByChild('area_name')
                             ->equalTo('Montalban')
                             ->getValue();
        $content = $database->getReference('contents')->getValue();
        return view('guest.packages.montalban', compact('packages', 'content'));
    }

    public function show($id)
    {
        $database = app('firebase.database');
        $packageRef = $database->getReference('packages/' . $id);
        $package = $packageRef->getValue();
        if ($package) {
            $package['id'] = $id;
        }
        $content = $database->getReference('contents')->getValue();
        return view('guest.packages.show', compact('package', 'content'));
    }
}
