<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LogsExport;
use Kreait\Firebase\Contract\Database;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class ReportsController extends Controller
{
    protected $database, $packages, $reservations;

    public function __construct(Database $database){
        $this->database = $database;
        $this->reservations = 'reservations';
        $this->packages = 'packages';
    }

    public function reservation()
    {
        // Get data from Firebase or another source
        $reservations = $this->database->getReference($this->reservations)->getValue();
        $reservations = is_array($reservations) ? $reservations : [];

        // Filter only finished reservations
        $finishedReservations = array_filter($reservations, function ($reservation) {
            return isset($reservation['status']) && $reservation['status'] === 'Finished';
        });

        // Initialize data arrays
        $yearlyReservations = [];
        $monthlyReservations = array_fill(0, 12, 0); // 12 months initialized to 0
        $weeklyReservations = array_fill(0, 4, 0); // 4 weeks initialized to 0

        // Initialize months array (Jan to Dec)
        $months = [
            'January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December'
        ];

        // Loop through the finished reservations to populate the data
        foreach ($finishedReservations as $reservation) {
            if (isset($reservation['event_date'])) {
                $eventDate = strtotime($reservation['event_date']);
                $year = date('Y', $eventDate);
                $month = date('n', $eventDate) - 1; // Zero-indexed month
                $week = ceil(date('j', $eventDate) / 7); // Calculate the week number (1 to 4)

                // Yearly: Count reservations per year
                if (!isset($yearlyReservations[$year])) {
                    $yearlyReservations[$year] = 0;
                }
                $yearlyReservations[$year]++;

                // Monthly: Count reservations per month
                $monthlyReservations[$month]++;

                // Weekly: Count reservations per week
                if ($week >= 1 && $week <= 4) {
                    $weeklyReservations[$week - 1]++;
                }
            }
        }

        $isExpanded = session()->get('sidebar_is_expanded', true);

        // Pass the data to the view
        return view('admin.reports.reservation.index', compact('isExpanded', 'yearlyReservations', 'monthlyReservations', 'weeklyReservations', 'months'));
    }

    public function sales()
    {
        $reservations = $this->database->getReference($this->reservations)->getValue();
        $reservations = is_array($reservations) ? $reservations : [];

        // Filter reservations where status = 'Finished'
        $finishedReservations = array_filter($reservations, function ($reservation) {
            return isset($reservation['status']) && $reservation['status'] === 'Finished';
        });

        // Initialize data arrays
        $years = []; // Store unique years
        $yearlyFees = []; // Group fees by year
        $yearlyPrices = []; // Group prices by year

        foreach ($finishedReservations as $reservation) {
            if (!empty($reservation['event_date'])) {
                $eventDate = strtotime($reservation['event_date']);
                $eventYear = date('Y', $eventDate);

                // Track unique years
                if (!in_array($eventYear, $years)) {
                    $years[] = $eventYear;
                    $yearlyFees[$eventYear] = 0;
                    $yearlyPrices[$eventYear] = 0;
                }

                // Aggregate yearly data
                if (isset($reservation['reserve_fee'])) {
                    $yearlyFees[$eventYear] += floatval($reservation['reserve_fee']);
                }

                if (isset($reservation['total_price'])) {
                    $yearlyPrices[$eventYear] += floatval($reservation['total_price']);
                }
            }
        }

        // Sort years (optional, to ensure chronological order)
        sort($years);

        // Prepare monthly and weekly data (unchanged from original)
        $months = [
            'January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December'
        ];
        $reservationFees = array_fill(0, 12, 0);
        $totalPrices = array_fill(0, 12, 0);
        $weeklyFees = array_fill(0, 4, 0);
        $weeklyPrices = array_fill(0, 4, 0);

        foreach ($finishedReservations as $reservation) {
            if (!empty($reservation['event_date'])) {
                $eventDate = strtotime($reservation['event_date']);
                $eventMonth = date('n', $eventDate) - 1;
                $eventWeek = (int)(date('W', $eventDate) - 1) % 4;

                if (isset($reservation['reserve_fee'])) {
                    $reservationFees[$eventMonth] += floatval($reservation['reserve_fee']);
                    $weeklyFees[$eventWeek] += floatval($reservation['reserve_fee']);
                }

                if (isset($reservation['total_price'])) {
                    $totalPrices[$eventMonth] += floatval($reservation['total_price']);
                    $weeklyPrices[$eventWeek] += floatval($reservation['total_price']);
                }
            }
        }

        $isExpanded = session()->get('sidebar_is_expanded', true);

        return view('admin.reports.sales.index', [
            'years' => $years,
            'yearlyFees' => $yearlyFees,
            'yearlyPrices' => $yearlyPrices,
            'months' => $months,
            'reservationFees' => $reservationFees,
            'totalPrices' => $totalPrices,
            'weeklyFees' => $weeklyFees,
            'weeklyPrices' => $weeklyPrices,
            'isExpanded' => $isExpanded,
        ]);
    }


    public function showLogs(Request $request)
    {
        $logFile = storage_path('logs/laravel.log');
        $logs = [];
        
        if (File::exists($logFile)) {
            $handle = fopen($logFile, 'r');
            if ($handle) {
                $currentLog = '';
                
                while (!feof($handle)) {
                    $line = fgets($handle);
                    
                    if (preg_match('/^\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\]/', $line)) {
                        if (!empty($currentLog)) {
                            // Only match Activity Log format
                            if (preg_match('/\[(.+?)\] local\.INFO: Activity Log ({.+})/', $currentLog, $matches)) {
                                if (isset($matches[1], $matches[2])) {
                                    $logData = json_decode($matches[2], true);
                                    if ($logData && isset($logData['user'], $logData['action'])) {
                                        $logs[] = [
                                            'datetime' => $matches[1],
                                            'user' => $logData['user'],
                                            'message' => $logData['action']
                                        ];
                                    }
                                }
                            }
                        }
                        $currentLog = $line;
                    } else {
                        $currentLog .= $line;
                    }
                    
                    if (count($logs) >= 1000) {
                        break;
                    }
                }
                
                // Process last log entry
                if (!empty($currentLog)) {
                    if (preg_match('/\[(.+?)\] local\.INFO: Activity Log ({.+})/', $currentLog, $matches)) {
                        if (isset($matches[1], $matches[2])) {
                            $logData = json_decode($matches[2], true);
                            if ($logData && isset($logData['user'], $logData['action'])) {
                                $logs[] = [
                                    'datetime' => $matches[1],
                                    'user' => $logData['user'],
                                    'message' => $logData['action']
                                ];
                            }
                        }
                    }
                }
                
                fclose($handle);
                
                usort($logs, function($a, $b) {
                    return strtotime($b['datetime']) - strtotime($a['datetime']);
                });
            }
        }
        
        $isExpanded = session()->get('sidebar_is_expanded', true);
        return view('admin.reports.activitylogs.logs', compact('logs', 'isExpanded'));
    }

    public function download()
    {
        $export = new LogsExport();
        $logsCount = $export->collection()->count();
        
        if ($logsCount === 0) {
            return redirect()->back()->with('error', 'No logs available to export');
        }

        $user = Session::get('firebase_user');
        
        Log::info('Activity Log', [
            'user' => $user->email,
            'action' => 'Successfully download activity logs.'
        ]);
        
        return Excel::download($export, 'Logs (' . date('F-d-Y') . ').xlsx');
    }

    public function removeAll() 
    {
        $logFile = storage_path('logs/laravel.log');
        
        if (File::exists($logFile)) {
            // Clear the file content
            file_put_contents($logFile, '');
            return redirect()->back()->with('success', 'Successfully Removed All Logs');
        }

        $user = Session::get('firebase_user');

        Log::info('Activity Log', [
            'user' => $user->email,
            'action' => 'Successfully removed activity logs.'
        ]);
        
        return redirect()->back()->with('error', 'Log file not found');
    }

    public function packages() {
        // Get data from Firebase or another source
        $reservations = $this->database->getReference($this->reservations)->getValue();
        $reservations = is_array($reservations) ? $reservations : [];

        // Filter only finished reservations
        $finishedReservations = array_filter($reservations, function ($reservation) {
            return isset($reservation['status']) && $reservation['status'] === 'Finished';
        });

        // Initialize an array for counting package occurrences
        $packageCounts = [];

        foreach ($finishedReservations as $reservation) {
            if (isset($reservation['package_name'])) {
                $packageName = $reservation['package_name'];

                // Increment the count for the package
                if (isset($packageCounts[$packageName])) {
                    $packageCounts[$packageName]++;
                } else {
                    $packageCounts[$packageName] = 1;
                }
            }
        }

        // Sort the packages based on their reservation count in descending order
        arsort($packageCounts);
        $isExpanded = session()->get('sidebar_is_expanded', true);
    
        return view('admin.reports.packages.index', compact('packageCounts', 'isExpanded'));
    }

    public function locations() {
        // Get data from Firebase or another source
        $reservations = $this->database->getReference($this->reservations)->getValue();
        $reservations = is_array($reservations) ? $reservations : [];
        
        // Filter only finished reservations
        $finishedReservations = array_filter($reservations, function ($reservation) {
            return isset($reservation['status']) && $reservation['status'] === 'Finished';
        });
        
        // Initialize location arrays for each location
        $locations = ['Marikina', 'San Mateo', 'Montalban'];
        $locationData = [
            'Marikina' => ['monthly' => array_fill(0, 12, 0), 'weekly' => array_fill(0, 4, 0), 'yearly' => []],
            'San Mateo' => ['monthly' => array_fill(0, 12, 0), 'weekly' => array_fill(0, 4, 0), 'yearly' => []],
            'Montalban' => ['monthly' => array_fill(0, 12, 0), 'weekly' => array_fill(0, 4, 0), 'yearly' => []],
        ];
        
        // Collect finished reservations by month, week, and year for each location
        foreach ($finishedReservations as $reservation) {
            $packageName = $reservation['package_name'];
            $eventDate = strtotime($reservation['event_date']); // Convert event date to timestamp
        
            // Get month (1-12), week (1-4), and year for grouping
            $month = (int)date('n', $eventDate) - 1;  // 0-based index for months (Jan = 0)
            $week = (int)date('W', $eventDate) % 4;  // Modulo 4 to fit into 4-week groups
            $year = date('Y', $eventDate); // Extract year
        
            foreach ($locations as $location) {
                if (strpos($packageName, $location) !== false) { // Check if location is part of the package name
                    // Monthly data
                    $locationData[$location]['monthly'][$month]++;
        
                    // Weekly data
                    $locationData[$location]['weekly'][$week]++;
        
                    // Yearly data
                    if (!isset($locationData[$location]['yearly'][$year])) {
                        $locationData[$location]['yearly'][$year] = 0;
                    }
                    $locationData[$location]['yearly'][$year]++;
                }
            }
        }
        
        // Prepare data for the charts
        // Monthly data
        $monthlyCounts = [
            'Marikina' => $locationData['Marikina']['monthly'],
            'San Mateo' => $locationData['San Mateo']['monthly'],
            'Montalban' => $locationData['Montalban']['monthly']
        ];
        
        // Weekly data
        $weeklyCounts = [
            'Marikina' => $locationData['Marikina']['weekly'],
            'San Mateo' => $locationData['San Mateo']['weekly'],
            'Montalban' => $locationData['Montalban']['weekly']
        ];
    
        // Yearly data
        $yearlyCounts = [
            'Marikina' => $locationData['Marikina']['yearly'],
            'San Mateo' => $locationData['San Mateo']['yearly'],
            'Montalban' => $locationData['Montalban']['yearly']
        ];
    
        $isExpanded = session()->get('sidebar_is_expanded', true);
        
        return view('admin.reports.location.index', compact(
            'monthlyCounts', 'weeklyCounts', 'yearlyCounts', 'isExpanded'
        ));
    }

    public function printSales()
    {
        $reservations = $this->database->getReference($this->reservations)->getValue();
        $reservations = is_array($reservations) ? $reservations : [];

        // Filter finished reservations
        $finishedReservations = array_filter($reservations, function ($reservation) {
            return isset($reservation['status']) && $reservation['status'] === 'Finished';
        });

        // Initialize months array
        $months = [
            'January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December'
        ];

        // Initialize data arrays
        $reservationFees = array_fill(0, 12, 0);
        $totalPrices = array_fill(0, 12, 0);
        $weeklyFees = array_fill(0, 4, 0);
        $weeklyPrices = array_fill(0, 4, 0);
        $yearlyFees = 0;
        $yearlyPrices = 0;

        // Process reservations
        foreach ($finishedReservations as $reservation) {
            if (!empty($reservation['event_date'])) {
                $eventDate = strtotime($reservation['event_date']);
                $eventMonth = date('n', $eventDate) - 1;
                $eventWeek = (int)(date('W', $eventDate) - 1) % 4;

                if (isset($reservation['reserve_fee'])) {
                    $reservationFees[$eventMonth] += floatval($reservation['reserve_fee']);
                    $yearlyFees += floatval($reservation['reserve_fee']);
                    $weeklyFees[$eventWeek] += floatval($reservation['reserve_fee']);
                }

                if (isset($reservation['total_price'])) {
                    $totalPrices[$eventMonth] += floatval($reservation['total_price']);
                    $yearlyPrices += floatval($reservation['total_price']);
                    $weeklyPrices[$eventWeek] += floatval($reservation['total_price']);
                }
            }
        }

        return view('admin.reports.sales.print', compact(
            'months',
            'reservationFees',
            'totalPrices',
            'weeklyFees',
            'weeklyPrices',
            'yearlyFees',
            'yearlyPrices'
        ));
    }

    public function printReservation()
    {
        // Get data from Firebase or another source
        $reservations = $this->database->getReference($this->reservations)->getValue();
        $reservations = is_array($reservations) ? $reservations : [];

        // Filter only finished reservations
        $finishedReservations = array_filter($reservations, function ($reservation) {
            return isset($reservation['status']) && $reservation['status'] === 'Finished';
        });

        // Initialize data arrays
        $yearlyReservations = [];
        $monthlyReservations = array_fill(0, 12, 0); // 12 months initialized to 0
        $weeklyReservations = array_fill(0, 4, 0); // 4 weeks initialized to 0

        // Initialize months array (Jan to Dec)
        $months = [
            'January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December'
        ];

        // Loop through the finished reservations to populate the data
        foreach ($finishedReservations as $reservation) {
            if (isset($reservation['event_date'])) {
                $eventDate = strtotime($reservation['event_date']);
                $year = date('Y', $eventDate);
                $month = date('n', $eventDate) - 1; // Zero-indexed month
                $week = ceil(date('j', $eventDate) / 7); // Calculate the week number (1 to 4)

                // Yearly: Count reservations per year
                if (!isset($yearlyReservations[$year])) {
                    $yearlyReservations[$year] = 0;
                }
                $yearlyReservations[$year]++;

                // Monthly: Count reservations per month
                $monthlyReservations[$month]++;

                // Weekly: Count reservations per week
                if ($week >= 1 && $week <= 4) {
                    $weeklyReservations[$week - 1]++;
                }
            }
        }

        // Pass the data to the printable view
        return view('admin.reports.reservation.print', compact('yearlyReservations', 'monthlyReservations', 'weeklyReservations', 'months'));
    }
}
