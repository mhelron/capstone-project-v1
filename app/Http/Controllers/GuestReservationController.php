<?php

namespace App\Http\Controllers;

use Kreait\Firebase\Contract\Database;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GuestReservationController extends Controller
{
    protected $database, $packages, $reservations;

    public function __construct(Database $database){
        $this->database = $database;
        $this->packages = 'packages';
        $this->reservations = 'reservations';
    }
    public function index(){
        $packages = $this->database->getReference($this->packages)->getValue();
        $packages = is_array($packages) ? array_map(fn($package) => $package, $packages) : [];

        return view('guest.reservation.index', compact('packages'));
    }
    public function store(Request $request)
    {
        Log::info('Form submission started');
        
        // Validation rules
        $validatedData = $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'address' => 'required',
            'phone' => 'required',
            'email' => 'required',
            'package_name' => 'required', // Ensure package is selected
            'menu_name' => 'required',
            'event_date' => 'required',
            'guests_number' => 'required',
            'sponsors' => 'nullable|integer',
            'venue' => 'required',
            'event_time' => 'required',
            'theme' => 'required',
            'other_requests' => 'nullable',
            'total_price' => 'required|numeric'
        ], [
            'menu_name.required_if' => 'You must select a menu when a package is selected.',
        ]);
    
        Log::info('Validation passed', ['validatedData' => $validatedData]);
    
        // Fetch packages from Firebase
        $packages = $this->database->getReference($this->packages)->getValue();
        $packages = is_array($packages) ? $packages : [];
    
        $menuContent = [];
        // Only process menu if both package_name and menu_name are provided
        if (!empty($validatedData['package_name']) && !empty($validatedData['menu_name'])) {
            foreach ($packages as $package) {
                if ($package['package_name'] === $validatedData['package_name']) {
                    foreach ($package['menus'] as $menu) {
                        if ($menu['menu_name'] === $validatedData['menu_name']) {
                            $menuContent = $menu['foods']; // Get the foods from the selected menu
                            Log::info('Menu content found', ['menuContent' => $menuContent]);
                            break 2; // Exit both loops once found
                        }
                    }
                }
            }
        }
    
        // Prepare reservation data
        $reserveData = [
            'status' => 'Pencil',
            'reserve_type' => 'Reserve',
            'first_name' => $validatedData['first_name'],
            'last_name' => $validatedData['last_name'],
            'address' => $validatedData['address'],
            'phone' => $validatedData['phone'],
            'email' => $validatedData['email'],
            'package_name' => $validatedData['package_name'] ?? null,
            'menu_name' => $validatedData['menu_name'] ?? null,
            'menu_content' => $menuContent,
            'event_date' => $validatedData['event_date'],
            'guests_number' => $validatedData['guests_number'],
            'sponsors' => $validatedData['sponsors'] ?? null,
            'venue' => $validatedData['venue'],
            'event_time' => $validatedData['event_time'],
            'theme' => $validatedData['theme'],
            'other_requests' => $validatedData['other_requests'],
            'total_price' => $validatedData['total_price'],
        ];
    
        Log::info('Total price included in reserveData', ['reserveData' => $reserveData]);
    
        // Push reservation data to Firebase
        try {
            $postRef = $this->database->getReference($this->reservations)->push($reserveData);
    
            if ($postRef) {
                return redirect('/')->with('status', 'Reservation Added');
            } else {
                return redirect('/')->with('status', 'Reservation Not Added');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'There was an error saving the reservation: ' . $e->getMessage());
        }
    }
    

}
