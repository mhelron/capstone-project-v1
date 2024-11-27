<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Kreait\Firebase\Contract\Database;
use Faker\Factory as Faker;
use DateTime;

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
                'menus' => $this->getMenus()
            ],
            'package2' => [
                'package_name' => 'Debut Marikina',
                'menus' => $this->getMenus()
            ],
            'package3' => [
                'package_name' => 'Kiddie Birthday Marikina',
                'menus' => $this->getMenus()
            ],
            'package4' => [
                'package_name' => 'Wedding Marikina',
                'menus' => $this->getMenus()
            ],
            'package5' => [
                'package_name' => 'Adult Birthday San Mateo',
                'menus' => $this->getMenus()
            ],
            'package6' => [
                'package_name' => 'Debut San Mateo',
                'menus' => $this->getMenus()
            ],
            'package7' => [
                'package_name' => 'Kiddie Birthday San Mateo',
                'menus' => $this->getMenus()
            ],
            'package8' => [
                'package_name' => 'Wedding San Mateo',
                'menus' => $this->getMenus()
            ],
            'package9' => [
                'package_name' => 'Adult Birthday Montalban',
                'menus' => $this->getMenus()
            ],
            'package10' => [
                'package_name' => 'Kiddie Birthday Montalban',
                'menus' => $this->getMenus()
            ],
            'package11' => [
                'package_name' => 'Debut Montalban',
                'menus' => $this->getMenus()
            ],
            'package12' => [
                'package_name' => 'Corporate Event Marikina',
                'menus' => $this->getMenus()
            ],
            'package13' => [
                'package_name' => 'Corporate Event San Mateo',
                'menus' => $this->getMenus()
            ],
            'package14' => [
                'package_name' => 'Corporate Event Montalban',
                'menus' => $this->getMenus()
            ],
            'package15' => [
                'package_name' => 'Wedding Montalban',
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

    public function run()
    {
        $packages = $this->getPackages();
        $statuses = ['Pending', 'Confirmed', 'Cancelled', 'Finished', 'Pencil'];
        $themes = ['Rustic', 'Modern', 'Vintage', 'Garden', 'Beach', 'Traditional'];

        $reservations_data = [];
        for ($i = 0; $i < 60; $i++) {
            $selectedPackage = $this->faker->randomElement($packages);
            $selectedMenu = $this->faker->randomElement($selectedPackage['menus']);
            $status = $this->faker->randomElement($statuses);
            
            // Generate random date between Nov and Dec 2024
            $startDate = '2024-11-01';
            $endDate = '2024-12-31';

            $today = new DateTime();
            $eventDate = $this->faker->dateTimeBetween($startDate, $endDate);

            $status = $eventDate < $today 
            ? 'Finished'  // Only Finished status for past dates
            : $this->faker->randomElement(['Pending', 'Confirmed', 'Cancelled', 'Pencil']); // All except Finished for future

            $reservations_data[] = [
            'status' => $status,
            'event_date' => $eventDate->format('Y-m-d'),
                'reserve_type' => $status === 'Pencil' ? 'Pencil' : 'Reserve',
                'first_name' => $this->faker->firstName,
                'last_name' => $this->faker->lastName,
                'address' => $this->faker->address,
                'phone' => $this->faker->phoneNumber,
                'email' => $this->faker->email,
                'package_name' => $selectedPackage['package_name'],
                'menu_name' => $selectedMenu['menu_name'],
                'menu_content' => $selectedMenu['foods'],
                'guests_number' => $this->faker->numberBetween(50, 300),
                'sponsors' => $this->faker->numberBetween(1, 5),
                'venue' => $this->faker->company . ' Hall',
                'event_time' => $this->faker->time('H:00'),
                'theme' => $this->faker->randomElement($themes),
                'other_requests' => $this->faker->optional(0.7)->sentence
            ];
        }

        $reference = $this->database->getReference('reservations');
        foreach ($reservations_data as $data) {
            $reference->push($data);
        }
    }
}