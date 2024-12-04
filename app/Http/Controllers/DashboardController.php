<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Database;

class DashboardController extends Controller
{
    protected $database, $reservations, $packages;
    public function __construct(Database $database){
        $this->database = $database;
        $this->reservations = 'reservations';
        $this->packages = 'packages';
    }
    public function index(){
        $reservations = $this->database->getReference($this->reservations)->getValue();
        $reservations = is_array($reservations) ? $reservations : [];
        $packages = $this->database->getReference($this->packages)->getValue();
        $packages = is_array($packages) ? $packages : [];

        $counts = [
            'pending' => count(array_filter($reservations, fn($r) => $r['status'] === 'Pending')),
            'confirmed' => count(array_filter($reservations, fn($r) => $r['status'] === 'Confirmed')),
            'cancelled' => count(array_filter($reservations, fn($r) => $r['status'] === 'Cancelled')), 
            'finished' => count(array_filter($reservations, fn($r) => $r['status'] === 'Finished')),
            'pencil' => count(array_filter($reservations, fn($r) => $r['reserve_type'] === 'Pencil')),
            'packages' => count($packages)
        ];

        $packageCounts = [];
            foreach ($reservations as $reservation) {
                $packageName = $reservation['package_name']; // Ensure this field exists in your reservation structure
                if ($packageName) {
                    if (isset($packageCounts[$packageName])) {
                        $packageCounts[$packageName]++;
                    } else {
                        $packageCounts[$packageName] = 1;
                    }
                }
            }

            // Sort packages by count in descending order and take the top 5
            arsort($packageCounts);  // Sort in descending order by count
            $topPackages = array_slice($packageCounts, 0, 5, true);  // Get the top 5 packages

        $isExpanded = session()->get('sidebar_is_expanded', true);
        return view('admin.dashboard.index', compact('isExpanded', 'counts', 'topPackages'));
    }
}
