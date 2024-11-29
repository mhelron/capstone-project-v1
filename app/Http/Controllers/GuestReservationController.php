<?php

namespace App\Http\Controllers;

use Kreait\Firebase\Contract\Database;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Mail\PencilConfirmationMail;
use App\Mail\PendingConfirmationMail;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

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

        $addressData = json_decode(file_get_contents(public_path('address_ph.json')), true);

        return view('guest.reservation.index', compact('packages', 'addressData'));
    }

    public function store(Request $request)
    {
        Log::info('Form submission started');
        
        // Validation rules
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
                'regex:/^\d+\s+[a-zA-Z0-9\s]+$/',
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
            'street_houseno.regex' => 'The input must start with a house number followed by the street name (e.g., "123 Sample Street").',
            'package_name.required' => 'Please select a package.',
            'event_title.required' => 'The event title is required.',
            'menu_name.required' => 'Please select a menu.',
            'guests_number.required' => 'The number of guest is required.',
            'venue.required' => 'The location or venue is required',
            'event_date.required' => 'Please select a date.',
            'event_time.required' => 'Please select a time.',
            'theme.required' => 'The color motif or/and theme is required.',
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
                            break 2; // Exit both loops once found
                        }
                    }
                }
            }
        }
    
        // Prepare reservation data
        $reserveData = [
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
            'total_price' => $validatedData['total_price'] ?? null,
            'payment_proof' => 'None',
            'payment_status' => 'Pending',
            'payment_submitted_at' => null,
            'created_at' => Carbon::now()->toDateTimeString(),
        ];
    
        try {
            Log::info('Saving reservation to Firebase...', ['reservation_data' => $reserveData]);
        
            $postRef = $this->database->getReference($this->reservations)->push($reserveData);
        
            if ($postRef->getKey()) {
                Log::info('Reservation saved to Firebase', ['reservation_id' => $postRef->getKey()]);
        
                // Queue confirmation email
                try {
                    Mail::mailer('clients')  // Specify 'admin' mailer here
                        ->to($validatedData['email'])
                        ->send(new PencilConfirmationMail($reserveData));
                    Log::info('Confirmation email queued for user', ['email' => $validatedData['email']]);
                } catch (\Exception $mailException) {
                    Log::error('Error queuing confirmation email', ['error' => $mailException->getMessage()]);
                    // Optionally, you can redirect with an error if email queuing fails
                    return redirect()->route('guest.payment', ['reservation_id' => $postRef->getKey()])
                                     ->with('status', 'Reservation Added, but email failed to send');
                }
        
                return redirect()->route('guest.payment', ['reservation_id' => $postRef->getKey()])
                                 ->with('status', 'Reservation Added');
            } else {
                Log::warning('Reservation not added to Firebase - postRef has no key');
                return redirect('/reserve')->with('status', 'Reservation Not Added');
            }
        } catch (\Exception $e) {
            Log::error('Error saving reservation to Firebase', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'There was an error saving the reservation: ' . $e->getMessage());
        }
        
    }

    public function payment($reservation_id)
    {
        // Fetch the reservation details
        $reservation = $this->database->getReference($this->reservations)->getChild($reservation_id)->getValue();
        
        if (!$reservation) {
            return redirect()->route('guest.reserve')->with('error', 'Reservation not found.');
        }

        return view('guest.reservation.payment', compact('reservation', 'reservation_id'));
    }

    public function storePaymentProof(Request $request, $reservation_id)
    {
        Log::info('Payment proof upload started', ['reservation_id' => $reservation_id]);

        // Validate the request
        $request->validate([
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        try {
            // Get the reservation reference
            $reservationRef = $this->database->getReference($this->reservations)->getChild($reservation_id);
    
            // Check if reservation exists
            if (!$reservationRef->getValue()) {
                return redirect()->back()->with('error', 'Reservation not found.');
            }
    
            if ($request->hasFile('payment_proof')) {
                // Store the payment proof in storage/app/public/payment_proofs
                $paymentProofPath = $request->file('payment_proof')->store('payment_proofs', 'public');
                $paymentProofUrl = str_replace('public/', 'storage/', $paymentProofPath);
    
                // Update the reservation with payment proof details
                $updates = [
                    'reserve_type' => 'Reserve',
                    'status' => 'Pending',
                    'payment_proof' => $paymentProofUrl,
                    'payment_status' => 'proof_submitted',
                    'payment_submitted_at' => date('Y-m-d H:i:s')
                ];
    
                $reservationRef->update($updates);
    
                // Send an email notification
                $reservationData = [
                    'id' => $reservation_id,
                    'first_name' => 'John', // Replace with actual data from the reservation
                    'status' => $updates['status'],
                    'payment_proof_url' => $paymentProofUrl,
                ];
    
                Mail::mailer('clients') // Specify the mailer configuration if needed
                    ->to('user@example.com') // Replace with recipient email
                    ->send(new PendingConfirmationMail($reservationData));

    
                Log::info('Payment proof uploaded successfully', [
                    'reservation_id' => $reservation_id,
                    'path' => $paymentProofUrl
                ]);
    
                return redirect()->route('guest.home')->with('status', 'Payment uploaded successfully. We will verify your payment shortly.');
            }
    
            return redirect()->back()->with('error', 'No file uploaded.');
        } catch (\Exception $e) {
            Log::error('Error uploading payment proof', [
                'reservation_id' => $reservation_id,
                'error' => $e->getMessage()
            ]);

            return redirect()->back()->with('error', 'There was an error uploading your payment proof. Please try again.');
        }
    }
}
