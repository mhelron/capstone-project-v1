<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Database;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\QuotationCreateMail;
use App\Mail\QuotationStatusUpdateMail;

class QuotationController extends Controller
{
    protected $database, $quotations;
    
    public function __construct(Database $database){
        $this->database = $database;
        $this->quotations = 'quotations';
    }

    public function index(){
        $content = $this->database->getReference('contents')->getValue();
        return view('guest.quotation.index', compact('content'));
    }

    public function checkStatus(Request $request)
    {
        $request->validate([
            'reference_number' => 'required|string|size:12'
        ], [
            'reference_number.required' => 'The reference number is required.',
            'reference_number.size' => 'The reference number must be exactly 12 characters.',
        ]);
    
        $quotation = $this->database->getReference($this->quotations)
            ->orderByChild('reference_number')
            ->equalTo($request->reference_number)
            ->getValue();
    
        if (empty($quotation)) {
            return back()->withInput()->with('error', 'No quotation found.');
        }
    
        $quotationKey = array_key_first($quotation);
        $quotationData = $quotation[$quotationKey];
        $quotationData['quotation_id'] = $quotationKey;
    
        $content = $this->database->getReference('contents')->getValue();
    
        session(['last_reference_number' => $request->reference_number]);
    
        return view('guest.quotation.index', [
            'quotation' => $quotationData,
            'reference_number' => $request->reference_number,
            'content' => $content
        ]);
    }

    private function generateUniqueReferenceNumber()
    {
        do {
            $reference = strtoupper(Str::random(6)) . rand(100000, 999999);
            
            $existingQuotation = $this->database->getReference($this->quotations)
                ->orderByChild('reference_number')
                ->equalTo($reference)
                ->getValue();
                
        } while (!empty($existingQuotation));
        
        return $reference;
    }

    public function create()
    {
        // Fetch additional content and address data
        $content = $this->database->getReference('contents')->getValue();
        $addressData = json_decode(file_get_contents(public_path('address_ph.json')), true);
        
        // Fetch packages for dropdown
        $packages = $this->database->getReference('packages')->getValue() ?? [];
        
        // Fetch menus for dropdown
        $menus = $this->database->getReference('menus')->getValue() ?? [];
        
        // Format packages for selection
        $formattedPackages = [];
        foreach ($packages as $key => $package) {
            if (isset($package['name']) && isset($package['price'])) {
                $formattedPackages[$key] = [
                    'name' => $package['name'],
                    'price' => $package['price']
                ];
            }
        }
        
        // Format menus for selection
        $formattedMenus = [];
        foreach ($menus as $key => $menu) {
            if (isset($menu['name'])) {
                $formattedMenus[$key] = [
                    'name' => $menu['name'],
                    'items' => $menu['items'] ?? []
                ];
            }
        }

        return view('guest.quotation.create', compact(
            'content', 
            'addressData', 
            'formattedPackages', 
            'formattedMenus'
        ));
    }

    public function store(Request $request)
    {
        try {
            // Add menu validation rules
            $validatedData = $request->validate([
                'firstname' => 'required|regex:/^[a-zA-Z\s-]+$/',
                'lastname' => 'required|regex:/^[a-zA-Z\s-]+$/',
                'email' => 'required|email',
                'phone' => 'required|regex:/^09\d{9}$/',
                'event_date' => 'required|date|after:today',
                'event_time' => 'required',
                'venue' => 'required|string',
                'theme' => 'required|string',
                'guest_count' => 'required|integer|min:1',
                'event' => 'required|string',
                'is_wedding' => 'required|in:0,1',
                // Regular menu validation
                'menu_beef' => 'required_if:is_wedding,0',
                'menu_chicken' => 'required_if:is_wedding,0',
                'menu_pork' => 'required_if:is_wedding,0',
                'menu_side' => 'required_if:is_wedding,0',
                'menu_pasta' => 'required_if:is_wedding,0',
                'menu_rice' => 'required_if:is_wedding,0',
                'menu_dessert' => 'required_if:is_wedding,0',
                'menu_drinks' => 'required_if:is_wedding,0',
                // Wedding menu validation
                'menu_beef_wedding' => 'required_if:is_wedding,1',
                'menu_chicken_wedding' => 'required_if:is_wedding,1',
                'menu_pork_wedding' => 'required_if:is_wedding,1',
                'menu_fish' => 'required_if:is_wedding,1',
                'menu_side_wedding' => 'required_if:is_wedding,1',
                'menu_pasta_wedding' => 'required_if:is_wedding,1',
                'menu_rice_wedding' => 'required_if:is_wedding,1',
                'menu_dessert_wedding' => 'required_if:is_wedding,1',
                'menu_drinks_wedding' => 'required_if:is_wedding,1',
                'understanding' => 'required|accepted',
                'agreement' => 'required|accepted',
                'region' => 'required',
                'province' => 'required',
                'city' => 'required',
                'barangay' => 'required',
                'street_houseno' => 'required',
            ]);

            // Generate unique reference number
            $reference_number = $this->generateUniqueReferenceNumber();

            // Prepare menu content based on event type
            $menuContent = [];
            if ($request->input('is_wedding') == '1') {
                $menuContent = [
                    [
                        'category' => 'Main Course (Beef)',
                        'food' => $validatedData['menu_beef_wedding']
                    ],
                    [
                        'category' => 'Main Course (Chicken)',
                        'food' => $validatedData['menu_chicken_wedding']
                    ],
                    [
                        'category' => 'Main Course (Pork)',
                        'food' => $validatedData['menu_pork_wedding']
                    ],
                    [
                        'category' => 'Main Course (Fish)',
                        'food' => $validatedData['menu_fish']
                    ],
                    [
                        'category' => 'Side Dish',
                        'food' => $validatedData['menu_side_wedding']
                    ],
                    [
                        'category' => 'Pasta',
                        'food' => $validatedData['menu_pasta_wedding']
                    ],
                    [
                        'category' => 'Rice',
                        'food' => $validatedData['menu_rice_wedding']
                    ],
                    [
                        'category' => 'Dessert',
                        'food' => $validatedData['menu_dessert_wedding']
                    ],
                    [
                        'category' => 'Drinks',
                        'food' => $validatedData['menu_drinks_wedding']
                    ]
                ];
            } else {
                $menuContent = [
                    [
                        'category' => 'Main Course (Beef)',
                        'food' => $validatedData['menu_beef']
                    ],
                    [
                        'category' => 'Main Course (Chicken)',
                        'food' => $validatedData['menu_chicken']
                    ],
                    [
                        'category' => 'Main Course (Pork)',
                        'food' => $validatedData['menu_pork']
                    ],
                    [
                        'category' => 'Side Dish',
                        'food' => $validatedData['menu_side']
                    ],
                    [
                        'category' => 'Pasta',
                        'food' => $validatedData['menu_pasta']
                    ],
                    [
                        'category' => 'Rice',
                        'food' => $validatedData['menu_rice']
                    ],
                    [
                        'category' => 'Dessert',
                        'food' => $validatedData['menu_dessert']
                    ],
                    [
                        'category' => 'Drinks',
                        'food' => $validatedData['menu_drinks']
                    ]
                ];
            }

            // Prepare data for Firebase
            $quotationData = [
                'reference_number' => $reference_number,
                'firstname' => $validatedData['firstname'],
                'lastname' => $validatedData['lastname'],
                'email' => $validatedData['email'],
                'phone' => $validatedData['phone'],
                'event_date' => $validatedData['event_date'],
                'event_time' => $validatedData['event_time'],
                'venue' => $validatedData['venue'],
                'theme' => $validatedData['theme'],
                'guest_count' => $validatedData['guest_count'],
                'event' => $validatedData['event'],
                'is_wedding' => $validatedData['is_wedding'],
                'menu_content' => $menuContent,
                'region' => $request->region,
                'province' => $request->province,
                'city' => $request->city,
                'barangay' => $request->barangay,
                'street_houseno' => $request->street_houseno,
                'status' => 'pending',
                'total_price' => '0',
                'payment_status' => 'Not Paid',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];

            // Store in Firebase
            $newPostRef = $this->database->getReference($this->quotations)->push($quotationData);
            
            if (!$newPostRef->getKey()) {
                throw new \Exception('Failed to get Firebase reference key');
            }

            try {
                Mail::mailer('clients')
                    ->to($validatedData['email'])
                    ->send(new QuotationCreateMail($quotationData));
    
                Log::info('Quotation confirmation email sent successfully', [
                    'email' => $validatedData['email'],
                    'reference_number' => $reference_number
                ]);
            } catch (\Exception $mailException) {
                Log::error('Failed to send quotation confirmation email:', [
                    'error' => $mailException->getMessage(),
                    'email' => $validatedData['email']
                ]);
                // Continue with the process even if email fails
            }
            
            return redirect()->route('guest.quotation.index')->with([
                'success' => 'Quotation request submitted successfully! Your reference number is: ' . $reference_number,
                'reference_number' => $reference_number
            ]);

        } catch (\Exception $e) {
            Log::error('Quotation Submission Error: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to submit quotation request: ' . $e->getMessage());
        }
    }
}