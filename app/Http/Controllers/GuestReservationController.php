<?php

namespace App\Http\Controllers;

use Kreait\Firebase\Contract\Database;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Mail\PencilConfirmationMail;
use App\Mail\PendingConfirmationMail;
use App\Mail\EditConfirmationEmail;
use App\Mail\CancelByUser;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;

class GuestReservationController extends Controller
{
    protected $database, $packages, $reservations;

    public function __construct(Database $database){
        $this->database = $database;
        $this->packages = 'packages';
        $this->reservations = 'reservations';
    }
    public function index(Request $request)
    {
        $selectedPackage = $request->query('package');
        $selectedMenu = $request->query('menu');

        $content = $this->database->getReference('contents')->getValue();
        
        // Get and sanitize reservations data
        $reservationsData = $this->database->getReference($this->reservations)->getValue() ?? [];
        $reservations = is_array($reservationsData) ? array_filter($reservationsData, function($reservation) {
            return is_array($reservation) && 
                isset($reservation['event_date']) && 
                isset($reservation['status']);
        }) : [];
        
        // Get and filter packages data
        $packagesData = $this->database->getReference($this->packages)->getValue() ?? [];
        
        // Filter packages to only include those with is_displayed set to true
        $displayedPackages = [];
        if (is_array($packagesData)) {
            foreach ($packagesData as $key => $package) {
                if (isset($package['is_displayed']) && $package['is_displayed'] === true) {
                    $displayedPackages[$key] = $package;
                }
            }
        }
        
        // Convert to array values to maintain consistent indexing
        $packages = array_values($displayedPackages);

        $addressData = json_decode(file_get_contents(public_path('address_ph.json')), true);
        
        return view('guest.reservation.index', compact(
            'packages', 
            'addressData', 
            'selectedPackage', 
            'selectedMenu',
            'reservations',
            'content'
        ));
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
    
        Log::info('Validation passed', ['validatedData' => $validatedData]);

        // Generate unique reference number
        $reference_number = $this->generateUniqueReferenceNumber();
    
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
            'reference_number' => $reference_number,
            'status' => 'New',
            'reserve_type' => 'New',
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
        
                return redirect()->route('guest.payment', ['reservation_id' => $postRef->getKey()])
                                 ->with('status', 'Reservation Form Submitted');

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

        $content = $this->database->getReference('contents')->getValue();
        return view('guest.reservation.payment', compact('reservation', 'reservation_id', 'content'));
    }

    public function storePaymentProof(Request $request, $reservation_id)
    {
        Log::info('Payment proof upload started', ['reservation_id' => $reservation_id]);

        // Validate the request
        $request->validate([
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg|max:30720',
        ],[
            'payment_proof' => 'Please input a image of your receipt.'
        ]);

        try {
            // Get the reservation reference
            $reservationRef = $this->database->getReference($this->reservations)->getChild($reservation_id);
            $reservation = $reservationRef->getValue();

            // Check if reservation exists
            if (!$reservation) {
                return redirect()->back()->with('error', 'Reservation not found.');
            }

            if ($request->hasFile('payment_proof')) {
                // Store the payment proof
                $paymentProofPath = $request->file('payment_proof')->store('payment_proofs', 'public');
                $paymentProofUrl = str_replace('public/', 'storage/', $paymentProofPath);

                // Update the reservation with payment proof details
                $updates = [
                    'reserve_type' => 'Reserve',
                    'status' => 'Pending',
                    'payment_proof' => $paymentProofUrl,
                    'payment_status' => 'Pending',
                    'payment_submitted_at' => now()->toDateTimeString(),
                ];

                $reservationRef->update($updates);

                // Merge updates into the reservation data for the email
                $reservation = array_merge($reservation, $updates);

                // Send confirmation email to the reservation email
                if (isset($reservation['email']) && !empty($reservation['email'])) {
                    try {
                        Mail::mailer('clients')
                            ->to($reservation['email'])
                            ->send(new PendingConfirmationMail($reservation));
                        Log::info('Confirmation email sent successfully', ['email' => $reservation['email']]);
                    } catch (\Exception $mailException) {
                        Log::error('Error sending confirmation email', [
                            'error' => $mailException->getMessage(),
                            'reservation_id' => $reservation_id,
                        ]);
                    }
                } else {
                    Log::warning('No email found in reservation data', ['reservation_id' => $reservation_id]);
                }

                Log::info('Payment proof uploaded successfully', [
                    'reservation_id' => $reservation_id,
                    'path' => $paymentProofUrl,
                ]);

                return redirect()->route('guest.home')->with('status', 'Payment uploaded successfully. We will verify your payment shortly.');
            }

            return redirect()->back()->with('error', 'No file uploaded.');
        } catch (\Exception $e) {
            Log::error('Error uploading payment proof', [
                'reservation_id' => $reservation_id,
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()->with('error', 'There was an error uploading your payment proof. Please try again.');
        }
    }

    public function processPencilBooking($reservation_id)
    {
        try {
            $reservationRef = $this->database->getReference($this->reservations)->getChild($reservation_id);
            $reservation = $reservationRef->getValue();

            if (!$reservation) {
                return redirect()->back()->with('error', 'Reservation not found.');
            }

            // Calculate expiration date (1 week from now)
            $expirationDate = Carbon::now()->addWeek()->toDateTimeString();

            // Update the reservation status
            $updates = [
                'status' => 'Pencil',
                'reserve_type' => 'Pencil',
                'pencil_created_at' => Carbon::now()->toDateTimeString(),
                'pencil_expires_at' => $expirationDate
            ];

            $reservationRef->update($updates);

            // Merge updates for the email
            $reservation = array_merge($reservation, $updates);

            // Send the pencil confirmation email with expiration date
            try {
                Mail::mailer('clients')
                    ->to($reservation['email'])
                    ->send(new PencilConfirmationMail($reservation));
                
                Log::info('Pencil confirmation email sent successfully', [
                    'reservation_id' => $reservation_id,
                    'email' => $reservation['email'],
                    'expires_at' => $expirationDate
                ]);
            } catch (\Exception $mailException) {
                Log::error('Error sending pencil confirmation email', [
                    'error' => $mailException->getMessage(),
                    'reservation_id' => $reservation_id
                ]);
                return redirect()->route('guest.home')
                    ->with('warning', 'Pencil booking processed but email notification failed.');
            }

            return redirect()->route('guest.home')
                ->with('status', 'Your pencil booking has been processed successfully. Please note that it will expire in 1 week.');
                
        } catch (\Exception $e) {
            Log::error('Error processing pencil booking', [
                'error' => $e->getMessage(),
                'reservation_id' => $reservation_id
            ]);
            return redirect()->back()
                ->with('error', 'There was an error processing your pencil booking. Please try again.');
        }
    }

    public function checkExpiredPencilBookings()
    {
        try {
            $now = Carbon::now()->toDateTimeString();
            
            // Get all pencil bookings
            $pencilBookings = $this->database->getReference($this->reservations)
                ->orderByChild('status')
                ->equalTo('Pencil')
                ->getValue();

            if (!$pencilBookings) {
                return;
            }

            foreach ($pencilBookings as $key => $booking) {
                if (isset($booking['pencil_expires_at'])) {
                    $expirationDate = Carbon::parse($booking['pencil_expires_at']);
                    
                    if ($expirationDate->isPast()) {
                        // Update expired booking status
                        $this->database->getReference($this->reservations)->getChild($key)->update([
                            'status' => 'Expired',
                            'expired_at' => $now
                        ]);

                        // Log the expiration
                        Log::info('Pencil booking expired', [
                            'reservation_id' => $key,
                            'expired_at' => $now
                        ]);
                    }
                }
            }
        } catch (\Exception $e) {
            Log::error('Error checking expired pencil bookings', [
                'error' => $e->getMessage()
            ]);
        }
    }

    public function showCheckStatus()
    {
        $content = $this->database->getReference('contents')->getValue();
        return view('guest.reservation.checkstatus', compact('content'));  // This just shows the form
    }

    public function checkStatus(Request $request)
    {
        $request->validate([
            'reference_number' => 'required|string|size:12'
        ], [
            'reference_number.required' => 'The reference number is required.',
            'reference_number.size' => 'The reference number must be exactly 12 characters.',
        ]);

        $reservation = $this->database->getReference($this->reservations)
            ->orderByChild('reference_number')
            ->equalTo($request->reference_number)
            ->getValue();

        if (empty($reservation)) {
            return back()->withInput()->with('error', 'No reservation found.');
        }

        $reservationKey = array_key_first($reservation);
        $reservationData = $reservation[$reservationKey];
        $reservationData['reservation_id'] = $reservationKey;

        $content = $this->database->getReference('contents')->getValue();

        // Store reference number in session
        session(['last_reference_number' => $request->reference_number]);

        return view('guest.reservation.checkstatus', [
            'reservation' => $reservationData,
            'reference_number' => $request->reference_number,
            'content' => $content
        ]);
    }
    public function cancelReservation($reservation_id, Request $request)
    {
        try {
            // Retrieve the reservation
            $reservationRef = $this->database->getReference($this->reservations)->getChild($reservation_id);
            $reservation = $reservationRef->getValue();

            if (!$reservation) {
                return redirect()->back()->with('error', 'Reservation not found.');
            }

            // Determine the cancellation reason
            $cancellationReason = $request->cancellation_reason;
            if ($cancellationReason === 'other') {
                $cancellationReason = $request->other_reason;
            }

            // Update the reservation status
            $updates = [
                'status' => 'Cancelled',
                'cancellation_reason' => $cancellationReason,
                'cancelled_at' => Carbon::now()->toDateTimeString(),
            ];

            $reservationRef->update($updates);

            // Merge updates with reservation data for email
            $reservation = array_merge($reservation, $updates);

            // Send the cancellation email
            try {
                Mail::mailer('clients')
                    ->to($reservation['email'])
                    ->send(new CancelByUser($reservation));

                Log::info('Cancellation email sent successfully', [
                    'reservation_id' => $reservation_id,
                    'email' => $reservation['email']
                ]);
            } catch (\Exception $mailException) {
                Log::error('Error sending cancellation email', [
                    'error' => $mailException->getMessage(),
                    'reservation_id' => $reservation_id
                ]);

                return redirect()->route('guest.check')
                    ->with('warning', 'Reservation cancelled but email notification failed.');
            }

            return redirect()->route('guest.check')
                ->with('success', 'Reservation has been cancelled successfully.');

        } catch (\Exception $e) {
            Log::error('Error cancelling reservation', [
                'error' => $e->getMessage(),
                'reservation_id' => $reservation_id
            ]);

            return redirect()->back()
                ->with('error', 'There was an error cancelling your reservation. Please try again.');
        }
    }


    public function edit($id){
        $key = $id;

        $packages = $this->database->getReference($this->packages)->getValue();
        $packages = is_array($packages) ? array_map(fn($package) => $package, $packages) : [];

        $addressData = json_decode(file_get_contents(public_path('address_ph.json')), true);
        
        $editdata = $this->database->getReference($this->reservations)->getChild($key)->getValue();
        $content = $this->database->getReference('contents')->getValue();

        $isExpanded = session()->get('sidebar_is_expanded', true);
        if($editdata){
            return view('guest.reservation.edit', compact('editdata', 'isExpanded', 'packages', 'addressData', 'key', 'content'));
        } else {
            return redirect('/check-status')->with('status', 'User ID Not Found');
        }
    }

    public function update(Request $request, $id)
    {
        Log::info('Update method called', ['id' => $id, 'data' => $request->all()]);

        $key = $id;

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

        try {
            $menuContent = [];
            $packages = $this->database->getReference($this->packages)->getValue() ?? [];
            
            // Get menu content
            foreach ($packages as $package) {
                if ($package['package_name'] === $validatedData['package_name']) {
                    foreach ($package['menus'] as $menu) {
                        if ($menu['menu_name'] === $validatedData['menu_name']) {
                            $menuContent = $menu['foods'];
                            break 2;
                        }
                    }
                }
            }
        
            $updateData = [
                'first_name' => $validatedData['first_name'],
                'last_name' => $validatedData['last_name'],
                'phone' => $validatedData['phone'],
                'email' => $validatedData['email'],
                'region' => $validatedData['region'],
                'province' => $validatedData['province'],
                'city' => $validatedData['city'],
                'barangay' => $validatedData['barangay'],
                'street_houseno' => strtoupper($validatedData['street_houseno']),
                'package_name' => $validatedData['package_name'],
                'event_title' => $validatedData['event_title'],
                'menu_name' => $validatedData['menu_name'],
                'menu_content' => $menuContent,
                'guests_number' => $validatedData['guests_number'],
                'sponsors' => $validatedData['sponsors'] ?? null,
                'venue' => $validatedData['venue'],
                'event_date' => $validatedData['event_date'],
                'event_time' => $validatedData['event_time'],
                'theme' => $validatedData['theme'],
                'other_requests' => $validatedData['other_requests'],
                'total_price' => $request->total_price,
            ];

            $updateData = [...$validatedData, 'menu_content' => $menuContent];

            $reservationRef = $this->database->getReference($this->reservations)->getChild($key)->update($updateData);
            $reservation = $reservationRef->getValue();
            $reservation = array_merge($reservation, $updateData);

            Log::info('Update successful');

            try {
                Mail::mailer('clients')
                    ->to($validatedData['email'])
                    ->send(new EditConfirmationEmail($reservation));
                Log::info('Edit confirmation email sent successfully');
            } catch (\Exception $mailError) {
                Log::error('Failed to send edit confirmation email:', ['error' => $mailError->getMessage()]);
            }

            $referenceNumber = $reservation['reference_number'];
            
            return redirect()->route('guest.check')
            ->with('status', 'Reservation Updated Successfully')
            ->with('reference_number', $referenceNumber);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update reservation: ' . $e->getMessage());
        }
    }

    
}
