<?php

namespace App\Http\Controllers;

use Kreait\Firebase\Contract\Database;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Mail\PencilConfirmationMail;
use Illuminate\Support\Facades\Mail;

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
            'sponsors' => 'nullable',
            'venue' => 'required',
            'event_time' => 'required',
            'theme' => 'required',
            'other_requests' => 'nullable',
            'total_price' => 'nullable|numeric'
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
            'reserve_type' => 'Pencil',
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
            'total_price' => $validatedData['total_price'] ?? null,
            'payment_proof' => null, // Initialize payment_proof as null
            'payment_status' => 'pending' // Add payment status
        ];
    
        try {
            Log::info('Saving reservation to Firebase...', ['reservation_data' => $reserveData]);
        
            // Save to Firebase
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
