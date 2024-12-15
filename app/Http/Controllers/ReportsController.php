<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LogsExport;
use Kreait\Firebase\Contract\Database;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

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
        $reservations = $this->getFinishedReservations();
        
        // Get all the report data
        $reportData = $this->generateReportData($reservations);
        
        $isExpanded = session()->get('sidebar_is_expanded', true);
        
        return view('admin.reports.reservation.index', array_merge(
            ['isExpanded' => $isExpanded],
            $reportData
        ));
    }

    private function getFinishedReservations()
    {
        $reservations = $this->database->getReference($this->reservations)->getValue() ?: [];
        
        return array_filter($reservations, function ($reservation) {
            return isset($reservation['status']) && $reservation['status'] === 'Finished';
        });
    }

    private function generateReportData($reservations)
    {
        $data = [
            'years' => [],
            'months' => [
                'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
                'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
            ],
            'yearlyData' => [],      // Yearly total counts
            'monthlyTrends' => [],    // Monthly trends for each year
            'monthlyData' => [],      // Monthly breakdown for selected year
            'weeklyData' => [],       // Weekly breakdown for selected month
            'dailyData' => []         // Daily breakdown for selected week
        ];

        foreach ($reservations as $reservation) {
            if (!isset($reservation['event_date'])) continue;

            $date = new \DateTime($reservation['event_date']);
            $year = $date->format('Y');
            $month = $date->format('n') - 1; // 0-11
            $week = ceil($date->format('j') / 7);
            $day = $date->format('j');

            // Track years
            if (!in_array($year, $data['years'])) {
                $data['years'][] = $year;
                $data['yearlyData'][$year] = 0;
                $data['monthlyTrends'][$year] = array_fill(0, 12, 0);
                $data['monthlyData'][$year] = array_fill(0, 12, 0);
                $data['weeklyData'][$year] = [];
                $data['dailyData'][$year] = [];
                
                // Initialize weekly and daily data structures
                for ($m = 0; $m < 12; $m++) {
                    $data['weeklyData'][$year][$m] = array_fill(0, 6, 0); // Up to 6 weeks
                    $data['dailyData'][$year][$m] = array_fill(1, 31, 0); // Days 1-31
                }
            }

            // Update counts
            $data['yearlyData'][$year]++;
            $data['monthlyTrends'][$year][$month]++;
            $data['monthlyData'][$year][$month]++;
            
            if ($week >= 1 && $week <= 6) {
                $data['weeklyData'][$year][$month][$week - 1]++;
            }
            
            $data['dailyData'][$year][$month][$day]++;
        }

        // Sort years in descending order
        rsort($data['years']);

        return $data;
    }

    public function printReservations()
    {
        $reservations = $this->getFinishedReservations();
        $reportData = $this->generateReportData($reservations);

        // Convert month abbreviations to full names for print view
        $reportData['months'] = [
            'January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December'
        ];

        return view('admin.reports.reservation.print', $reportData);
    }

    public function sales()
    {
        $reservations = $this->database->getReference($this->reservations)->getValue();
        $reservations = is_array($reservations) ? $reservations : [];
        
        $finishedReservations = array_filter($reservations, function ($reservation) {
            return isset($reservation['status']) && $reservation['status'] === 'Finished';
        });

        // Initialize all data arrays
        $years = [];
        $yearlyFees = [];
        $yearlyPrices = [];
        $monthlyData = [];
        $weeklyData = [];
        $dailyData = []; // New array for daily data
        $currentWeekData = []; // New array for current week

        foreach ($finishedReservations as $reservation) {
            if (!empty($reservation['event_date'])) {
                $eventDate = strtotime($reservation['event_date']);
                $eventYear = date('Y', $eventDate);
                $eventMonth = date('n', $eventDate);
                $eventDay = date('j', $eventDate);
                
                // Initialize year if not exists
                if (!in_array($eventYear, $years)) {
                    $years[] = $eventYear;
                    $yearlyFees[$eventYear] = 0;
                    $yearlyPrices[$eventYear] = 0;
                    $monthlyData[$eventYear] = [
                        'reservationFees' => array_fill(0, 12, 0),
                        'totalPrices' => array_fill(0, 12, 0)
                    ];
                    $weeklyData[$eventYear] = [];
                    $dailyData[$eventYear] = []; // Initialize daily data structure
                    
                    for ($month = 1; $month <= 12; $month++) {
                        $weeklyData[$eventYear][$month] = [
                            'reservationFees' => array_fill(0, 4, 0),
                            'totalPrices' => array_fill(0, 4, 0)
                        ];
                        
                        // Initialize daily data for each month
                        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $eventYear);
                        $dailyData[$eventYear][$month] = [
                            'reservationFees' => array_fill(1, $daysInMonth, 0),
                            'totalPrices' => array_fill(1, $daysInMonth, 0)
                        ];
                    }
                }

                // Existing yearly and monthly aggregation
                if (isset($reservation['reserve_fee'])) {
                    $yearlyFees[$eventYear] += floatval($reservation['reserve_fee']);
                    $monthlyData[$eventYear]['reservationFees'][$eventMonth - 1] += floatval($reservation['reserve_fee']);
                    $dailyData[$eventYear][$eventMonth]['reservationFees'][$eventDay] += floatval($reservation['reserve_fee']);
                }
                
                if (isset($reservation['total_price'])) {
                    $yearlyPrices[$eventYear] += floatval($reservation['total_price']);
                    $monthlyData[$eventYear]['totalPrices'][$eventMonth - 1] += floatval($reservation['total_price']);
                    $dailyData[$eventYear][$eventMonth]['totalPrices'][$eventDay] += floatval($reservation['total_price']);
                }

                // Weekly data aggregation
                $eventWeek = (int)(date('W', $eventDate) - 1) % 4;
                if (isset($reservation['reserve_fee'])) {
                    $weeklyData[$eventYear][$eventMonth]['reservationFees'][$eventWeek] += floatval($reservation['reserve_fee']);
                }
                if (isset($reservation['total_price'])) {
                    $weeklyData[$eventYear][$eventMonth]['totalPrices'][$eventWeek] += floatval($reservation['total_price']);
                }

                // Current week data
                $currentWeekStart = strtotime('monday this week');
                $currentWeekEnd = strtotime('sunday this week');
                
                if ($eventDate >= $currentWeekStart && $eventDate <= $currentWeekEnd) {
                    $weekDay = date('w', $eventDate); // 0 (Sunday) to 6 (Saturday)
                    if (!isset($currentWeekData[$weekDay])) {
                        $currentWeekData[$weekDay] = [
                            'date' => date('Y-m-d', $eventDate),
                            'reservationFees' => 0,
                            'totalPrices' => 0
                        ];
                    }
                    if (isset($reservation['reserve_fee'])) {
                        $currentWeekData[$weekDay]['reservationFees'] += floatval($reservation['reserve_fee']);
                    }
                    if (isset($reservation['total_price'])) {
                        $currentWeekData[$weekDay]['totalPrices'] += floatval($reservation['total_price']);
                    }
                }
            }
        }

        // Sort years in descending order
        rsort($years);
        
        $months = [
            'January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December'
        ];

        $isExpanded = session()->get('sidebar_is_expanded', true);

        return view('admin.reports.sales.index', [
            'years' => $years,
            'yearlyFees' => $yearlyFees,
            'yearlyPrices' => $yearlyPrices,
            'months' => $months,
            'monthlyData' => $monthlyData,
            'weeklyData' => $weeklyData,
            'dailyData' => $dailyData,
            'currentWeekData' => $currentWeekData,
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

        // Initialize yearly data structure
        $yearlyData = [];

        // Process reservations
        foreach ($finishedReservations as $reservation) {
            if (!empty($reservation['event_date'])) {
                $eventDate = strtotime($reservation['event_date']);
                $eventYear = date('Y', $eventDate);
                $eventMonth = date('n', $eventDate); // 1-12 format
                $week = ceil(date('j', $eventDate) / 7); // 1-4 format

                // Initialize data structure for the year if not already set
                if (!isset($yearlyData[$eventYear])) {
                    $yearlyData[$eventYear] = [
                        'reservationFees' => array_fill(0, 12, 0),
                        'totalPrices' => array_fill(0, 12, 0),
                        'yearlyFees' => 0,
                        'yearlyPrices' => 0,
                        'weeklyData' => [] // New structure for weekly data by month
                    ];
                    
                    // Initialize weekly data for each month
                    for ($month = 1; $month <= 12; $month++) {
                        $yearlyData[$eventYear]['weeklyData'][$month] = [
                            'weeklyFees' => array_fill(0, 4, 0),
                            'weeklyPrices' => array_fill(0, 4, 0)
                        ];
                    }
                }

                // Add data to the appropriate year and month
                if (isset($reservation['reserve_fee'])) {
                    $fee = floatval($reservation['reserve_fee']);
                    $yearlyData[$eventYear]['reservationFees'][$eventMonth - 1] += $fee;
                    $yearlyData[$eventYear]['yearlyFees'] += $fee;
                    if ($week >= 1 && $week <= 4) {
                        $yearlyData[$eventYear]['weeklyData'][$eventMonth]['weeklyFees'][$week - 1] += $fee;
                    }
                }

                if (isset($reservation['total_price'])) {
                    $price = floatval($reservation['total_price']);
                    $yearlyData[$eventYear]['totalPrices'][$eventMonth - 1] += $price;
                    $yearlyData[$eventYear]['yearlyPrices'] += $price;
                    if ($week >= 1 && $week <= 4) {
                        $yearlyData[$eventYear]['weeklyData'][$eventMonth]['weeklyPrices'][$week - 1] += $price;
                    }
                }
            }
        }

        return view('admin.reports.sales.print', compact('months', 'yearlyData'));
    }
}
