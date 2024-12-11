<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Kreait\Firebase\Contract\Database;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Log;
use DateTime;
use Carbon\Carbon;

class FirebaseReservationSeeder extends Seeder
{
    protected $database;
    protected $faker;

    public function __construct(Database $database)
    {
        $this->database = $database;
        $this->faker = Faker::create();
    }

    private function getPackages()
    {
        return [
            'package1' => [
                'package_name' => 'Adult Birthday Marikina',
                'price' => '50000',
                'menus' => $this->getMenus()
            ],
            'package2' => [
                'package_name' => 'Debut Marikina',
                'price' => '55000',
                'menus' => $this->getMenus()
            ],
            'package3' => [
                'package_name' => 'Kiddie Birthday Marikina',
                'price' => '42000',
                'menus' => $this->getMenus()
            ],
            'package4' => [
                'package_name' => 'Wedding Marikina',
                'price' => '65000',
                'menus' => $this->getMenus()
            ],
            'package5' => [
                'package_name' => 'Adult Birthday San Mateo',
                'price' => '42000',
                'menus' => $this->getMenus()
            ],
            'package6' => [
                'package_name' => 'Debut San Mateo',
                'price' => '47000',
                'menus' => $this->getMenus()
            ],
            'package7' => [
                'package_name' => 'Kiddie Birthday San Mateo',
                'price' => '40000',
                'menus' => $this->getMenus()
            ],
            'package8' => [
                'package_name' => 'Wedding San Mateo',
                'price' => '55000',
                'menus' => $this->getMenus()
            ],
            'package9' => [
                'package_name' => 'Adult Birthday Montalban',
                'price' => '40000',
                'menus' => $this->getMenus()
            ],
            'package10' => [
                'package_name' => 'Kiddie Birthday Montalban',
                'price' => '38000',
                'menus' => $this->getMenus()
            ],
            'package11' => [
                'package_name' => 'Debut Montalban',
                'price' => '45000',
                'menus' => $this->getMenus()
            ],
            'package12' => [
                'package_name' => 'Corporate Event Marikina',
                'price' => '60000',
                'menus' => $this->getMenus()
            ],
            'package13' => [
                'package_name' => 'Corporate Event San Mateo',
                'price' => '50000',
                'menus' => $this->getMenus()
            ],
            'package14' => [
                'package_name' => 'Corporate Event Montalban',
                'price' => '40000',
                'menus' => $this->getMenus()
            ],
            'package15' => [
                'package_name' => 'Wedding Montalban',
                'price' => '50000',
                'menus' => $this->getMenus()
            ]
        ];
    }

    private function getMenus()
    {
        return [
            [
                'menu_name' => 'Menu A',
                'foods' => [
                    'Beef with Creamy Mushroom Sauce',
                    'Chicken Cordon Bleu',
                    'Golden Pork Hamonado',
                    'Buttered Mixed Vegetables with Quail Eggs',
                    'Creamy Carbonara',
                    'Pandan Rice',
                    'Fruit Salad',
                    'Pine-Orange Juice'
                ]
            ],
            [
                'menu_name' => 'Menu B',
                'foods' => [
                    'Beef Caldereta',
                    'Honey Glazed Chicken',
                    'Pork Black Hawaiian',
                    'Creamy Green Peas with Quail Eggs',
                    'Aglio Olio Pasta',
                    'Pandan Rice',
                    'Buko Pandan',
                    'Four Seasons'
                ]
            ],
            [
                'menu_name' => 'Menu C',
                'foods' => [
                    'Beef with Brocolli',
                    'Creamy Chicken Pastel',
                    'Roasted Pork Coated with Gravy Sauce',
                    'Kare-Kare with Beef Tripe',
                    'Cheesy Baked Macaroni',
                    'Pandan Rice',
                    'Leche Flan',
                    'Red Tea Juice'
                ]
            ]
        ];
    }

    private function generateUniqueReferenceNumber()
    {
        Log::info("Starting reference number generation");
        
        // Generate a reference number using timestamp and random string
        $timestamp = date('YmdHis');
        $random = strtoupper(substr(md5(uniqid(rand(), true)), 0, 4));
        $referenceNumber = "REF{$timestamp}{$random}";
        
        Log::info("Generated reference number: " . $referenceNumber);
        return $referenceNumber;
    }

    private function getRandomAddress()
{
    // Hardcoded address data
    $regions = [
        'NCR' => [
            'SECOND DISTRICT' => [
                'CITY OF MARIKINA' => [
                    'BARANGKA', 'CALUMPANG', 'CONCEPCION DOS', 'CONCEPCION UNO', 'FORTUNE',
                    'INDUSTRIAL VALLEY', 'JESUS DE LA PEÑA', 'MALANDAY', 'MARIKINA HEIGHTS (CONCEPCION)',
                    'NANGKA', 'PARANG', 'SAN ROQUE', 'SANTA ELENA (POB.)', 'SANTO NIÑO', 'TAÑONG', 'TUMANA'
                ]
            ]
        ],
        'CALABARZON' => [
            'RIZAL' => [
                'RODRIGUEZ (MONTALBAN)' => [
                    'BALITE (POB.)', 'BURGOS', 'GERONIMO', 'MACABUD', 'MANGGAHAN', 'MASCAP', 'PURAY',
                    'ROSARIO', 'SAN ISIDRO', 'SAN JOSE', 'SAN RAFAEL'
                ],
                'SAN MATEO' => [
                    'AMPID I', 'AMPID II', 'BANABA', 'DULONG BAYAN 1', 'DULONG BAYAN 2', 'GUINAYANG',
                    'GUITNANG BAYAN I (POB.)', 'GUITNANG BAYAN II (POB.)', 'GULOD MALAYA', 'MALANDAY',
                    'MALY', 'PINTONG BOCAWE', 'SANTA ANA', 'SANTO NIÑO', 'SILANGAN'
                ]
            ]
        ]
    ];

    // Select a random region
    $region = $this->faker->randomElement(array_keys($regions));

    // Select a random province within the region
    $province = $this->faker->randomElement(array_keys($regions[$region]));

    // Select a random city within the province
    $city = $this->faker->randomElement(array_keys($regions[$region][$province]));

    // Select a random barangay within the city
    $barangay = $this->faker->randomElement($regions[$region][$province][$city]);

    return [
        'region' => $region,
        'province' => $province,
        'city' => $city,
        'barangay' => $barangay,
    ];
}
public function run()
{
    try {
        $packages = $this->getPackages();
        $futureStatuses = ['Pending', 'Confirmed', 'Cancelled', 'Pencil'];
        $themes = ['Rustic', 'Modern', 'Vintage', 'Garden', 'Beach', 'Traditional'];

        $reservations_data = [];
        $reservationsPerDay = [];
        
        Log::info("Seeding process started");
        
        $totalReservations = 1000;
        Log::info("Attempting to create {$totalReservations} reservations");
        
        // Current date for reference
        $currentDate = new DateTime();
        
        for ($i = 0; $i < $totalReservations; $i++) {
            try {
                Log::info("Starting to find date for reservation {$i}");
                
                // Date selection
                $eventDate = new DateTime(date('Y-m-d', mt_rand(
                    (new DateTime('2020-01-01'))->getTimestamp(),
                    (new DateTime('2024-12-31'))->getTimestamp()
                )));
                
                $dateKey = $eventDate->format('Y-m-d');
                
                if (!isset($reservationsPerDay[$dateKey])) {
                    $reservationsPerDay[$dateKey] = 0;
                    Log::info("New date encountered: {$dateKey}, initializing count to 0");
                }
                
                Log::info("Current reservations for {$dateKey}: {$reservationsPerDay[$dateKey]}");
                
                if ($reservationsPerDay[$dateKey] >= 5) {
                    Log::info("Date {$dateKey} already has 5 reservations, skipping");
                    continue;
                }
                
                $reservationsPerDay[$dateKey]++;
                Log::info("Valid date found: {$dateKey}. New reservation count: {$reservationsPerDay[$dateKey]}");

                // Handle created_at date based on event date
                if ($eventDate > $currentDate) {
                    // Future event - created_at should be between now and 3 months ago
                    $createdAt = $this->faker->dateTimeBetween('-3 months', 'now');
                } else {
                    // Past event - created_at should be 3 months before event
                    $eventDateTime = clone $eventDate;
                    $threeMonthsBefore = $eventDateTime->modify('-3 months');
                    $createdAt = $this->faker->dateTimeBetween($threeMonthsBefore->format('Y-m-d'), $eventDate->format('Y-m-d'));
                }
                Log::info("Created at date set to: " . $createdAt->format('Y-m-d H:i:s'));

                // Generate basic data
                $selectedPackage = $this->faker->randomElement($packages);
                Log::info("Selected package: " . $selectedPackage['package_name']);
                
                $selectedMenu = $this->faker->randomElement($selectedPackage['menus']);
                Log::info("Selected menu: " . $selectedMenu['menu_name']);
                
                // Set status based on event date
                if ($eventDate < $currentDate) {
                    // Past events are always finished unless specifically cancelled
                    $status = $this->faker->boolean(90) ? 'Finished' : 'Cancelled';  // 90% chance of Finished
                } else {
                    // Future events can be any status except Finished
                    $status = $this->faker->randomElement($futureStatuses);
                }
                Log::info("Selected status: " . $status);
                
                // Generate reference number
                $timestamp = date('YmdHis');
                $random = strtoupper(substr(md5(uniqid(rand(), true)), 0, 4));
                $reference_number = "REF{$timestamp}{$random}";
                Log::info("Generated reference number: " . $reference_number);
                
                $address = $this->getRandomAddress();
                Log::info("Got address for: " . $address['city']);
                
                // Update payment status logic - Finished events are always Paid
                $payment_status = ($status === 'Finished' || $status === 'Confirmed') ? 'Paid' : 'Not Paid';
                $payment_submitted_at = $payment_status === 'Paid' ? $this->faker->dateTimeBetween(
                    $createdAt->format('Y-m-d'),
                    min($eventDate->format('Y-m-d'), $currentDate->format('Y-m-d'))
                )->format('Y-m-d H:i:s') : '';

                $basePrice = $selectedPackage['price'];
                $guestsNumber = $this->faker->numberBetween(100, 500);
                
                $additionalPricePerGuest = strpos(strtolower($selectedPackage['package_name']), 'wedding') !== false ? 400 : 350;
                $additionalPrice = ($guestsNumber - 100) * $additionalPricePerGuest;
                $totalPrice = $basePrice + $additionalPrice;

                $sponsors = strpos(strtolower($selectedPackage['package_name']), 'wedding') !== false
                    ? $this->faker->numberBetween(1, 50)
                    : 0;

                $reservation = [
                    'reference_number' => $reference_number,
                    'status' => $status,
                    'reserve_type' => $status === 'Pencil' ? 'Pencil' : 'Reserve',
                    'first_name' => $this->faker->firstName,
                    'last_name' => $this->faker->lastName,
                    'phone' => $this->faker->phoneNumber,
                    'email' => $this->faker->email,
                    'region' => $address['region'],
                    'province' => $address['province'],
                    'city' => $address['city'],
                    'barangay' => $address['barangay'],
                    'street_houseno' => strtoupper($this->faker->streetAddress),
                    'package_name' => $selectedPackage['package_name'],
                    'event_title' => $this->faker->randomElement(['Birthday', 'Wedding', 'Debut', 'Corporate Event']),
                    'menu_name' => $selectedMenu['menu_name'],
                    'menu_content' => $selectedMenu['foods'],
                    'guests_number' => $guestsNumber,
                    'sponsors' => $sponsors,
                    'event_date' => $eventDate->format('Y-m-d'),
                    'event_time' => $this->faker->time('H:00'),
                    'venue' => $this->faker->company . ' Hall',
                    'theme' => $this->faker->randomElement($themes),
                    'other_requests' => $this->faker->optional(0.3)->sentence,
                    'total_price' => $totalPrice,
                    'reserve_fee' => '5000',
                    'payment_proof' => 'None',
                    'payment_status' => $payment_status,
                    'payment_submitted_at' => $payment_submitted_at,
                    'created_at' => $createdAt->format('Y-m-d H:i:s'),
                    'cancellation_reason' => '',  // Default empty string
                    'cancelled_at' => '',   
                    'pencil_created_at' => '',
                    'pencil_expires_at' => '',      // Default empty string
                    'read' => true
                ];

                if ($status === 'Pencil') {
                    $pencilCreatedAt = Carbon::parse($createdAt);
                    $reservation['pencil_created_at'] = $pencilCreatedAt->toDateTimeString();
                    $reservation['pencil_expires_at'] = $pencilCreatedAt->copy()->addWeek()->toDateTimeString();
                }

                if ($status === 'Cancelled') {
                    $reservation['cancellation_reason'] = $this->faker->sentence;
                    $reservation['cancelled_at'] = $this->faker->dateTimeBetween(
                        $createdAt->format('Y-m-d'),
                        min($eventDate->format('Y-m-d'), $currentDate->format('Y-m-d'))
                    )->format('Y-m-d H:i:s');
                }

                Log::info("Pushing reservation to Firebase");
                
                $reference = $this->database->getReference('reservations');
                $reference->push($reservation);
                Log::info("Successfully pushed reservation {$i}");
                
                $reservations_data[] = $reservation;
                
            } catch (\Exception $e) {
                Log::error("Error in reservation {$i}: " . $e->getMessage());
                continue;
            }
        }

        Log::info("Seeder finished. Created " . count($reservations_data) . " reservations");
        
    } catch (\Exception $e) {
        Log::error("Fatal error in seeder: " . $e->getMessage());
        throw $e;
    }
}
}