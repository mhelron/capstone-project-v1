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

    public function checkStatus(Request $request)
    {
        $request->validate([
            'reference_number' => 'required|string|size:12'
        ], [
            'reference_number.required' => 'The reference number is required.',
            'reference_number.size' => 'The reference number must be exactly 12 characters.',
        ]);

        $foodtaste = $this->database->getReference($this->foodtaste)
            ->orderByChild('reference_number')
            ->equalTo($request->reference_number)
            ->getValue();

        if (empty($reservation)) {
            return back()->withInput()->with('error', 'No reservation found.');
        }

        $foodtasteKey = array_key_first($foodtaste);
        $foodtasteData = $foodtaste[$foodtasteKey];
        $foodtasteData['reservation_id'] = $foodtasteKey;

        $content = $this->database->getReference('contents')->getValue();

        // Store reference number in session
        session(['last_reference_number' => $request->reference_number]);

        return view('guest.foodtaste.index', [
            'foodtaste' => $foodtasteData,
            'reference_number' => $request->reference_number,
            'content' => $content
        ]);
    }

    public function create(){
        $content = $this->database->getReference('contents')->getValue();
        $addressData = json_decode(file_get_contents(public_path('address_ph.json')), true);
        return view('guest.foodtaste.create', compact('content', 'addressData'));
    }

    public function store(Request $request){
        $validatedData = $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'phone' => [
                'required',
                'regex:/^09\d{9}$/',
            ],
            'email' => [
                'required',
                'regex:/^[a-zA-Z0-9._%+-]+@(gmail\.com|yahoo\.com|outlook\.com|hotmail\.com|icloud\.com|aol\.com|proton\.me|protonmail\.com|yahoo\.co\.uk|msn\.com)$/i'
            ],
            'region' => 'required',
            'province' => 'required',
            'city' => 'required',
            'barangay' => 'required',
            'street_houseno' => [
                'required',
            ],
            'package_name' => 'required',
            'event_title' => 'required',
            'menu_name' => 'required',
            'event_date' => 'required',
            'guests_number' => 'required',
            'sponsors' => 'nullable',
            'venue' => 'required',
            'event_time' => 'required',
            'theme' => 'required',
            'other_requests' => 'nullable',
            'total_price' => 'nullable|numeric'
            ], [
            'first_name.required' => 'The first name is required.',
            'first_name.regex' => 'The first name must only contain letters, spaces, or hyphens.',
            'last_name.required' => 'The last name is required.',
            'last_name.regex' => 'The last name must only contain letters and spaces.',
            'phone.required' => 'The phone is required.',
            'phone.regex' => 'The phone number must start with "09" and contain exactly 11 digits.',
            'email.required' => 'The email is required.',
            'email.email' => 'The email must be a valid email address.',
            'region.required' => 'Please select a region.',
            'province.required' => 'Please select a province.',
            'city.required' => 'Please select a city.',
            'barangay.required' => 'Please select a barangay.',
            'street_houseno.required' => 'The House Number, Building, Street is required.',
            'package_name.required' => 'Please select a package.',
            'event_title.required' => 'The event title is required.',
            'menu_name.required' => 'Please select a menu.',
            'guests_number.required' => 'The number of guest is required.',
            'venue.required' => 'The location or venue is required',
            'event_date.required' => 'Please select a date.',
            'event_time.required' => 'Please select a time.',
            'theme.required' => 'The color motif or/and theme is required.',
        ]);

        $foodtasteData = [
            'status' => 'New',
            'reserve_type' => 'New',
            'first_name' => $validatedData['first_name'],
            'last_name' => $validatedData['last_name'],
            'phone' => $validatedData['phone'],
            'email' => $validatedData['email'],
            'region' => $validatedData['region'],
            'province' => $validatedData['province'],
        ];
    }
}