<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Database;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class FoodTasteController extends Controller
{
    protected $database, $foodtaste, $reservations;
    public function __construct(Database $database){
        $this->database = $database;
        $this->foodtaste = 'foodtaste';
        $this->reservations = 'reservations';

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
    
        // This line was using $reservation instead of $foodtaste
        if (empty($foodtaste)) {  // <-- Fixed here
            return back()->withInput()->with('error', 'No reservation found.');
        }
    
        $foodtasteKey = array_key_first($foodtaste);
        $foodtasteData = $foodtaste[$foodtasteKey];
        $foodtasteData['reservation_id'] = $foodtasteKey;
    
        $content = $this->database->getReference('contents')->getValue();
    
        session(['last_reference_number' => $request->reference_number]);
    
        return view('guest.foodtaste.index', [
            'foodtaste' => $foodtasteData,
            'reference_number' => $request->reference_number,
            'content' => $content
        ]);
    }

    public function create()
    {
        // Fetch and sanitize reservation data
        $reservationsData = $this->database->getReference($this->reservations)->getValue() ?? [];
        
        // Filter only confirmed reservations with valid event_date and status fields
        $reservations = is_array($reservationsData) ? array_filter($reservationsData, function($reservation) {
            return is_array($reservation) && 
                isset($reservation['event_date']) && 
                isset($reservation['status']) && 
                $reservation['status'] === 'Confirmed';  // Filter for confirmed reservations
        }) : [];

        // Format the reservations by event_date
        $formattedReservations = [];
        if ($reservations) {
            foreach ($reservations as $reservation) {
                // Get the event date
                $eventDate = Carbon::parse($reservation['event_date'])->format('l, F j, Y');
                
                // Fetch the menu details (menu_name and menu_content)
                $menuName = $reservation['menu_name'] ?? 'No Menu Selected';
                $menuContent = $reservation['menu_content'] ?? [];

                // Organize the menu content (category and food items)
                $foods = [];
                if (!empty($menuContent)) {
                    foreach ($menuContent as $menuItem) {
                        $foods[] = [
                            'category' => $menuItem['category'] ?? 'Unknown',
                            'food' => $menuItem['food'] ?? 'Unknown'
                        ];
                    }
                }

                // Add the reservation to the formattedReservations array
                $formattedReservations[$eventDate][] = [
                    'menu_name' => $menuName,
                    'foods' => $foods
                ];
            }
        }

        // Fetch additional content and address data
        $content = $this->database->getReference('contents')->getValue();
        $addressData = json_decode(file_get_contents(public_path('address_ph.json')), true);

        return view('guest.foodtaste.create', compact('content', 'addressData', 'formattedReservations'));
    }

    private function generateUniqueReferenceNumber()
    {
        do {
            // Generate a random 12-character string with numbers and uppercase letters
            $reference = strtoupper(Str::random(6)) . rand(100000, 999999);
            
            // Check if this reference number already exists in Firebase
            $existingReservation = $this->database->getReference($this->foodtaste)
                ->orderByChild('reference_number')
                ->equalTo($reference)
                ->getValue();
                
        } while (!empty($existingReservation));
        
        return $reference;
    }

    public function store(Request $request)
    {
        try {
            // Validate the incoming data
            $validatedData = $request->validate([
                'firstname' => 'required|regex:/^[a-zA-Z\s-]+$/',
                'lastname' => 'required|regex:/^[a-zA-Z\s-]+$/',
                'email' => 'required|email',
                'phone' => 'required|regex:/^09\d{9}$/',
                'delivery_option' => 'required|in:pickup,delivery',
                'preferred_time' => 'required',
                'preferred_date' => 'required|date|after_or_equal:today',
                'understanding' => 'required|accepted',
                'agreement' => 'required|accepted',
                // Conditional validation for delivery option
                'region' => 'required_if:delivery_option,delivery',
                'province' => 'required_if:delivery_option,delivery',
                'city' => 'required_if:delivery_option,delivery',
                'barangay' => 'required_if:delivery_option,delivery',
                'street_houseno' => 'required_if:delivery_option,delivery',
            ]);

            // Generate unique reference number
            $reference_number = $this->generateUniqueReferenceNumber();

            // Prepare data for Firebase
            $foodtasteData = [
                'reference_number' => $reference_number,
                'firstname' => $validatedData['firstname'],
                'lastname' => $validatedData['lastname'],
                'email' => $validatedData['email'],
                'phone' => $validatedData['phone'],
                'delivery_option' => $validatedData['delivery_option'],
                'preferred_time' => $validatedData['preferred_time'],
                'preferred_date' => $validatedData['preferred_date'],
                'status' => 'pending',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'set_time' => '',
                'set_date' => '',
            ];

            // Add address fields separately if delivery is selected
            if ($validatedData['delivery_option'] === 'delivery') {
                $foodtasteData['region'] = $request->region;
                $foodtasteData['province'] = $request->province;
                $foodtasteData['city'] = $request->city;
                $foodtasteData['barangay'] = $request->barangay;
                $foodtasteData['street_houseno'] = $request->street_houseno;
            }

            // Store in Firebase with error logging
            $newPostRef = $this->database->getReference($this->foodtaste)->push($foodtasteData);
            
            if (!$newPostRef->getKey()) {
                throw new \Exception('Failed to get Firebase reference key');
            }

            // Store reference number in session
            session(['last_reference_number' => $reference_number]);

            // Redirect with success message
            return redirect()->route('guest.foodtaste')->with([
                'success' => 'Food tasting request submitted successfully! Your reference number is: ' . $reference_number,
                'reference_number' => $reference_number
            ]);

        } catch (\Exception $e) {
            // Log the error
            Log::error('Food Tasting Submission Error: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            
            // Return more specific error message
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to submit food tasting request: ' . $e->getMessage());
        }
    }

}