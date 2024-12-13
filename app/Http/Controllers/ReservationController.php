<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Kreait\Firebase\Contract\Database;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\PaymentConfirmationMail;
use App\Mail\ConfirmedByAdmin;
use App\Mail\PencilConfirmationMail;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ReservationController extends Controller
{
    protected $database, $packages, $reservations, $archived_reservations;

    public function __construct(Database $database){
        $this->database = $database;
        $this->reservations = 'reservations';
        $this->packages = 'packages';
        $this->archived_reservations = 'archived_reservations';
    }
    public function index(Request $request){
        $reservations = $this->database->getReference($this->reservations)->getValue();
        $reservations = is_array($reservations) ? $reservations : [];

        $pendingReservations = array_filter($reservations, fn($reservation) => $reservation['status'] === 'Pending');
        $confirmedReservations = array_filter($reservations, fn($reservation) => $reservation['status'] === 'Confirmed');
        $cancelledReservations = array_filter($reservations, fn($reservation) => $reservation['status'] === 'Cancelled');
        $finishedReservations = array_filter($reservations, fn($reservation) => $reservation['status'] === 'Finished');
        $pencilReservations = array_filter($reservations, fn($reservation) => $reservation['reserve_type'] === 'Pencil');

        $perPage = 10;
        $currentPage = $request->input('page', 1);
        $totalItems = count($reservations);
        $totalPages = ceil($totalItems / $perPage);
        $offset = ($currentPage - 1) * $perPage;

        $pagedService = array_slice($reservations, $offset, $perPage);

        $isExpanded = session()->get('sidebar_is_expanded', true);
        return view('admin.reservation.index', [
            'pendingReservations' => $pendingReservations,
            'confirmedReservations' => $confirmedReservations,
            'cancelledReservations' => $cancelledReservations,
            'finishedReservations' => $finishedReservations,
            'pencilReservations' => $pencilReservations,
            'totalPages' => $totalPages,
            'currentPage' => $currentPage,
            'totalItems' => $totalItems,
            'reservations' => $pagedService,
            'isExpanded' => $isExpanded
        ]);
    }

    public function createReservation()
    {
        // Fetching packages data from Firebase
        $packages = $this->database->getReference($this->packages)->getValue();
        // Ensure packages are an array, fallback to an empty array if not
        $packages = is_array($packages) ? $packages : [];

        // Fetching reservations data from Firebase and filtering for valid entries
        $reservationsData = $this->database->getReference($this->reservations)->getValue() ?? [];
        $reservations = is_array($reservationsData) ? array_filter($reservationsData, function($reservation) {
            return is_array($reservation) && 
                isset($reservation['event_date']) && 
                isset($reservation['status']);
        }) : [];

        // Get the sidebar state from the session, defaulting to true if not set
        $isExpanded = session()->get('sidebar_is_expanded', true);

        // Fetch address data from local JSON file
        $addressData = json_decode(file_get_contents(public_path('address_ph.json')), true);

        // Return the view with necessary data
        return view('admin.reservation.createreservation', compact(
            'packages', 
            'isExpanded', 
            'addressData', 
            'reservations'
        ));
    }

    public function createPencil(){   
        $packages = $this->database->getReference($this->packages)->getValue();
        $packages = is_array($packages) ? array_map(fn($package) => $package, $packages) : [];

        $reservationsData = $this->database->getReference($this->reservations)->getValue() ?? [];
        $reservations = is_array($reservationsData) ? array_filter($reservationsData, function($reservation) {
            return is_array($reservation) && 
                isset($reservation['event_date']) && 
                isset($reservation['status']);
        }) : [];

        $addressData = json_decode(file_get_contents(public_path('address_ph.json')), true);   

        $isExpanded = session()->get('sidebar_is_expanded', true);    
        return view('admin.reservation.createpencilreservation', compact('packages', 'isExpanded', 'addressData', 'reservations'));
    }

    private function generateUniqueReferenceNumber()
    {
        do {
            // Generate a random 12-character string with numbers and uppercase letters
            $reference = strtoupper(Str::random(6)) . rand(100000, 999999);
            
            // Check if this reference number already exists in Firebase
            $existingReservation = $this->database->getReference($this->reservations)
                ->orderByChild('reference_number')
                ->equalTo($reference)
                ->getValue();
                
        } while (!empty($existingReservation));
        
        return $reference;
    }

    public function reservation(Request $request)
    {
    // Validation rules
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
        'total_price' => 'required|numeric'
    ], [
        'first_name.required' => 'The first name is required.',
        'first_name.regex' => 'The first name must only contain letters, spaces, or hyphens.',
        'last_name.required' => 'The last name is required.',
        'last_name.regex' => 'The last name must only contain letters and spaces.',
        'phone.required' => 'The phone is required.',
        'phone.regex' => 'The phone number must start with "09" and contain exactly 11 digits.',
        'email.required' => 'The email is required.',
        'email.email' => 'The email must be a valid email address.',
        'email.regex' => 'Please use a common email provider (Gmail, Yahoo, Outlook, etc.)',
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

        $reference_number = $this->generateUniqueReferenceNumber();
    
        // Fetch packages from Firebase
        $packages = $this->database->getReference($this->packages)->getValue();
        $packages = is_array($packages) ? $packages : [];

        $totalPrice = floatval($validatedData['total_price']);
    
        $menuContent = [];
        // Only process menu if both package_name and menu_name are provided
        if (!empty($validatedData['package_name']) && !empty($validatedData['menu_name'])) {
            foreach ($packages as $package) {
                if ($package['package_name'] === $validatedData['package_name']) {
                    foreach ($package['menus'] as $menu) {
                        if ($menu['menu_name'] === $validatedData['menu_name']) {
                            $menuContent = $menu['foods']; // Get the foods from the selected menu
                            break 2; // Exit both loops once found
                        }
                    }
                }
            }
        }

        $reserveData = [
            'reference_number' => $reference_number,
            'status' => 'Confirmed',
            'reserve_type' => 'Reserve',
            'first_name' => $validatedData['first_name'],
            'last_name' => $validatedData['last_name'],
            'phone' => $validatedData['phone'],
            'email' => $validatedData['email'],
            'region' => $validatedData['region'],
            'province' => $validatedData['province'],
            'city' => $validatedData['city'],
            'barangay' => $validatedData['barangay'],
            'street_houseno' => strtoupper($validatedData['street_houseno']),
            'package_name' => $validatedData['package_name'] ?? null,
            'event_title' => $validatedData['event_title'],
            'menu_name' => $validatedData['menu_name'] ?? null,
            'menu_content' => $menuContent,
            'guests_number' => $validatedData['guests_number'],
            'sponsors' => $validatedData['sponsors'] ?? null,
            'event_date' => $validatedData['event_date'],
            'event_time' => $validatedData['event_time'],
            'venue' => $validatedData['venue'],
            'theme' => $validatedData['theme'],
            'other_requests' => $validatedData['other_requests'],
            'total_price' => $totalPrice,
            'reserve_fee' => '5000',
            'payment_proof' => 'Walk in',
            'payment_status' => 'Paid',
            'payment_submitted_at' => Carbon::now()->toDateTimeString(),
            'created_at' => Carbon::now()->toDateTimeString(),
            'cancellation_reason' => '',
            'cancelled_at' => '',
            'read' => false,
        ];
    
        try {      
            $postRef = $this->database->getReference($this->reservations)->push($reserveData);
            
            if ($postRef->getKey()) {
                // Send confirmation email
                try {
                    Mail::mailer('clients')
                            ->to($reserveData['email'])
                            ->send(new ConfirmedByAdmin($reserveData));
                    
                            return redirect()->route('admin.reservation', ['tab' => 'pencil'])->with('status', 'Reservation confirmed! A confirmation email has been sent to your email address.');
                } catch (\Exception $e) {
                    Log::error('Error sending confirmation email', ['error' => $e->getMessage()]);
                    return redirect()->route('admin.reservation', ['tab' => 'pencil'])->with('status', 'Reservation confirmed! However, there was an error sending the confirmation email.');
                }
            } else {
                Log::warning('Reservation not added to Firebase - postRef has no key');
                return redirect()->route('admin.reservation', ['tab' => 'pencil'])->with('status', 'Reservation Not Added');
            }
        } catch (\Exception $e) {
            Log::error('Error saving reservation to Firebase', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'There was an error saving the reservation: ' . $e->getMessage());
        }
    }

    public function pencilReservation(Request $request){

        $validatedData = $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'phone' => [
                'required',
                'regex:/^09\d{9}$/',
            ],
            'email' => 'required',
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
            'total_price' => 'required|numeric'
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
    
            $reference_number = $this->generateUniqueReferenceNumber();
        
            // Fetch packages from Firebase
            $packages = $this->database->getReference($this->packages)->getValue();
            $packages = is_array($packages) ? $packages : [];
    
            $totalPrice = floatval($validatedData['total_price']);
        
            $menuContent = [];
            // Only process menu if both package_name and menu_name are provided
            if (!empty($validatedData['package_name']) && !empty($validatedData['menu_name'])) {
                foreach ($packages as $package) {
                    if ($package['package_name'] === $validatedData['package_name']) {
                        foreach ($package['menus'] as $menu) {
                            if ($menu['menu_name'] === $validatedData['menu_name']) {
                                $menuContent = $menu['foods']; // Get the foods from the selected menu
                                break 2; // Exit both loops once found
                            }
                        }
                    }
                }
            }
    
            $reserveData = [
                'reference_number' => $reference_number,
                'status' => 'Pencil',
                'reserve_type' => 'Pencil',
                'first_name' => $validatedData['first_name'],
                'last_name' => $validatedData['last_name'],
                'phone' => $validatedData['phone'],
                'email' => $validatedData['email'],
                'region' => $validatedData['region'],
                'province' => $validatedData['province'],
                'city' => $validatedData['city'],
                'barangay' => $validatedData['barangay'],
                'street_houseno' => strtoupper($validatedData['street_houseno']),
                'package_name' => $validatedData['package_name'] ?? null,
                'event_title' => $validatedData['event_title'],
                'menu_name' => $validatedData['menu_name'] ?? null,
                'menu_content' => $menuContent,
                'guests_number' => $validatedData['guests_number'],
                'sponsors' => $validatedData['sponsors'] ?? null,
                'event_date' => $validatedData['event_date'],
                'event_time' => $validatedData['event_time'],
                'venue' => $validatedData['venue'],
                'theme' => $validatedData['theme'],
                'other_requests' => $validatedData['other_requests'],
                'total_price' => $totalPrice,
                'reserve_fee' => '5000',
                'payment_proof' => 'None',
                'payment_status' => 'Not Paid',
                'payment_submitted_at' => '',
                'created_at' => Carbon::now()->toDateTimeString(),
                'cancellation_reason' => '',
                'cancelled_at' => '',
                'read' => false,
            ];
        
            try {      
                $postRef = $this->database->getReference($this->reservations)->push($reserveData);
                
                if ($postRef->getKey()) {
                    // Send confirmation email
                    try {
                        Mail::mailer('clients')
                                ->to($reserveData['email'])
                                ->send(new PencilConfirmationMail($reserveData));
                        
                                return redirect()->route('admin.reservation', ['tab' => 'pending'])->with('status', 'Pencil Added! A confirmation email has been sent to your email address.');
                    } catch (\Exception $e) {
                        Log::error('Error sending confirmation email', ['error' => $e->getMessage()]);
                        return redirect()->route('admin.reservation', ['tab' => 'pencil'])->with('status', 'Pencil confirmed! However, there was an error sending the confirmation email.');
                    }
                } else {
                    Log::warning('Pencilnot added to Firebase - postRef has no key');
                    return redirect()->route('admin.reservation', ['tab' => 'pencil'])->with('status', 'Pencil Not Added');
                }
            } catch (\Exception $e) {
                Log::error('Error saving reservation to Firebase', ['error' => $e->getMessage()]);
                return redirect()->back()->with('error', 'There was an error saving the reservation: ' . $e->getMessage());
            }
    }

    public function confirmPencil($id){
        $key = $id;

        $reserveData = [
            'status' => 'Pending',
            'reserve_type' => 'Reserve',
            'pencil_created_at' => '',
            'pencil_expires_at' => ''

        ];

        $res_updated = $this->database->getReference($this->reservations. '/'.$key)->update($reserveData);

        if ($res_updated) {
            return redirect()->route('admin.reservation', ['tab' => 'penbook'])->with('status', 'Pencil Confirmed');
        } else {
            return redirect()->route('admin.reservation', ['tab' => 'penbook'])->with('status', 'Pencil not Confirmed');
        }
    }

    public function cancelPencil($id){
        $key = $id;

        $reserveData = [
            'status' => 'Cancelled',
            'reserve_type' => 'Reserve'
        ];

        $res_updated = $this->database->getReference($this->reservations. '/'.$key)->update($reserveData);

        if ($res_updated) {
            return redirect()->route('admin.reservation', ['tab' => 'penbook'])->with('status', 'Pencil Cancelled');
        } else {
            return redirect()->route('admin.reservation', ['tab' => 'penbook'])->with('status', 'Pencil Not Cancelled');
        }
    }

    public function confirmReservation($id) {
        $key = $id;
        
        // Get reservation details before updating
        $reservation = $this->database->getReference($this->reservations . '/' . $key)->getValue();
        
        $reserveData = [
            'status' => 'Confirmed',
            'payment_status' => 'Paid'
        ];
    
        $res_updated = $this->database->getReference($this->reservations . '/' . $key)->update($reserveData);
    
        // Update the status in the reservation array instead of trying to merge
        if ($res_updated) {
            $reservation['status'] = 'Confirmed';
            $reservation['payment_status'] = 'Paid';
            
            try {
                Mail::mailer('clients')
                            ->to($reservation['email'])
                            ->send(new PaymentConfirmationMail($reservation));
    
                return redirect()
                    ->route('admin.reservation', ['tab' => 'pending'])
                    ->with('status', 'Reservation Confirmed and Email Sent');
            } catch (\Exception $e) {
                Log::error('Failed to send confirmation email: ' . $e->getMessage());
                
                return redirect()
                    ->route('admin.reservation', ['tab' => 'pending'])
                    ->with('status', 'Reservation Confirmed but Failed to Send Email');
            }
        } else {
            return redirect()
                ->route('admin.reservation', ['tab' => 'pending'])
                ->with('status', 'Reservation Not Confirmed');
        }
    }

    public function finishReservation($id){
        $key = $id;

        $reserveData = [
            'status' => 'Finished'
        ];

        $res_updated = $this->database->getReference($this->reservations. '/'.$key)->update($reserveData);

        if ($res_updated) {
            return redirect()->route('admin.reservation', ['tab' => 'confirmed'])->with('status', 'Reservation Finished');
        } else {
            return redirect()->route('admin.reservation', ['tab' => 'confirmed'])->with('status', 'Reservation Not Finished');
        }
    }

    public function cancelReservation($id){
        $key = $id;

        $reserveData = [
            'status' => 'Cancelled'
        ];

        $res_updated = $this->database->getReference($this->reservations. '/'.$key)->update($reserveData);

        if ($res_updated) {
            return redirect()->route('admin.reservation', ['tab' => 'pending'])->with('status', 'Reservation Cancelled');
        } else {
            return redirect()->route('admin.reservation', ['tab' => 'pending'])->with('status', 'Reservation not Cancelled');
        }
    }

    public function destroy($id)
    {
        $key = $id;

        $reservations_data = $this->database->getReference($this->reservations.'/'.$key)->getValue();

        $archive_data = $this->database->getReference($this->archived_reservations.'/'.$key)->set($reservations_data);

        $del_data = $this->database->getReference($this->reservations.'/'.$key)->remove();

        if ($del_data && $archive_data) {
            return redirect()->route('admin.reservation', ['tab' => 'finished'])->with('status', 'Reservation Archived');
        } else {
            return redirect()->route('admin.reservation', ['tab' => 'finished'])->with('status', 'Reservation Not Archived');
        }
    }
}