<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LogsExport;
use Kreait\Firebase\Contract\Database;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
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
            'yearlyData' => [],      
            'monthlyTrends' => [],    
            'monthlyData' => [],      
            'weeklyData' => [],       
            'dailyData' => []         
        ];

        foreach ($reservations as $reservation) {
            if (!isset($reservation['event_date'])) continue;

            $date = new \DateTime($reservation['event_date']);
            $year = $date->format('Y');
            $month = (int)$date->format('n'); // 1-12 format
            $day = (int)$date->format('j');

            // Calculate the correct week number
            $firstDayOfMonth = new \DateTime($date->format('Y-m-1'));
            $firstDayOfWeek = $firstDayOfMonth->format('w'); // 0 (Sunday) to 6 (Saturday)
            $adjustedDay = $day + $firstDayOfWeek - 1;
            $weekOfMonth = ceil($adjustedDay / 7);

            // Get total weeks in month
            $lastDayOfMonth = new \DateTime($date->format('Y-m-t'));
            $lastDay = (int)$lastDayOfMonth->format('j');
            $lastDayOfWeek = $lastDayOfMonth->format('w');
            $totalWeeks = ceil(($lastDay + $firstDayOfWeek - 1) / 7);

            // Track years and initialize data structures
            if (!in_array($year, $data['years'])) {
                $data['years'][] = $year;
                $data['yearlyData'][$year] = 0;
                $data['monthlyTrends'][$year] = array_fill(0, 12, 0);
                $data['monthlyData'][$year] = array_fill(1, 12, 0);
                $data['weeklyData'][$year] = [];
                $data['dailyData'][$year] = [];
                
                // Initialize data structures with dynamic week counts
                for ($m = 1; $m <= 12; $m++) {
                    $monthDate = new \DateTime("$year-$m-01");
                    $weeksInMonth = ceil((date('t', $monthDate->getTimestamp()) + 
                        (int)$monthDate->format('w') - 1) / 7);
                    $data['weeklyData'][$year][$m] = array_fill(1, $weeksInMonth, 0);
                    $data['dailyData'][$year][$m] = array_fill(1, 31, 0);
                }
            }

            // Update counts
            $data['yearlyData'][$year]++;
            $data['monthlyTrends'][$year][$month-1]++;
            $data['monthlyData'][$year][$month]++;
            
            // Store week count for the month
            if (!isset($data['weeklyData'][$year][$month]['totalWeeks'])) {
                $data['weeklyData'][$year][$month]['totalWeeks'] = $totalWeeks;
            }
            
            // Update weekly data
            if ($weekOfMonth > 0) {
                $data['weeklyData'][$year][$month][$weekOfMonth]++;
            }
            
            $data['dailyData'][$year][$month][$day]++;
        }

        // Sort years in descending order
        rsort($data['years']);

        return $data;
    }

    public function printReservations(Request $request)
    {
        $reservations = $this->getFinishedReservations();
        $originalReportData = $this->generateReportData($reservations); // Store original unfiltered data
        
        // Add filter information
        $reportData = $originalReportData;
        $reportData['filters'] = [
            'report_type' => $request->report_type,
            'year' => $request->year,
            'month' => $request->month,
            'week' => $request->week,
        ];

        // Only filter data if we're not generating "all" reports
        if ($request->report_type !== 'all') {
            // Select appropriate view and filter data based on report type
            switch($request->report_type) {
                case 'yearly':
                    if($request->year) {
                        $filteredReservations = array_filter($reservations, function($reservation) use ($request) {
                            if (!isset($reservation['event_date'])) return false;
                            $date = new \DateTime($reservation['event_date']);
                            return $date->format('Y') == $request->year;
                        });
                        $reportData = $this->generateReportData($filteredReservations);
                        $reportData['filters'] = ['year' => $request->year];
                    }
                    $pdf = PDF::loadView('admin.reports.reservation.yearly-print', $reportData);
                    $filename = 'yearly-report.pdf';
                    break;

                case 'monthly':
                    if($request->year && $request->month) {
                        $filteredReservations = array_filter($reservations, function($reservation) use ($request) {
                            if (!isset($reservation['event_date'])) return false;
                            $date = new \DateTime($reservation['event_date']);
                            return $date->format('Y') == $request->year && 
                                (int)$date->format('n') == $request->month;
                        });
                        $reportData = $this->generateReportData($filteredReservations);
                        $reportData['filters'] = [
                            'year' => $request->year,
                            'month' => $request->month
                        ];
                    }
                    $pdf = PDF::loadView('admin.reports.reservation.monthly-print', $reportData);
                    $filename = 'monthly-report.pdf';
                    break;

                case 'weekly':
                    if($request->year && $request->month && $request->week) {
                        $filteredReservations = array_filter($reservations, function($reservation) use ($request) {
                            if (!isset($reservation['event_date'])) return false;
                            $date = new \DateTime($reservation['event_date']);
                            $firstDayOfMonth = new \DateTime($date->format('Y-m-1'));
                            $firstDayOfWeek = $firstDayOfMonth->format('w'); // 0 (Sunday) to 6 (Saturday)
                            $day = (int)$date->format('j');
                            $adjustedDay = $day + $firstDayOfWeek - 1;
                            $weekOfMonth = ceil($adjustedDay / 7);
                            
                            return $date->format('Y') == $request->year && 
                                (int)$date->format('n') == $request->month &&
                                $weekOfMonth == $request->week;
                        });
                        $reportData = $this->generateReportData($filteredReservations);
                    }
                    $pdf = PDF::loadView('admin.reports.reservation.weekly-print', $reportData);
                    $filename = 'weekly-report.pdf';
                    break;
            }
        } else {
            // For "all" reports, use the original unfiltered data
            $pdf = PDF::loadView('admin.reports.reservation.print', $originalReportData);
            $filename = 'reservation-report.pdf';
        }

        // Configure PDF settings
        $pdf->setPaper('a4', 'portrait');
        
        // Optional: Set more PDF options if needed
        $pdf->setOptions([
            'isRemoteEnabled' => true,
            'isHtml5ParserEnabled' => true,
            'isPhpEnabled' => true
        ]);

        return $pdf->stream($filename);
    }

    public function printYearlyReport(Request $request)
    {
        $reservations = $this->getFinishedReservations();
        
        if($request->year) {
            $reservations = array_filter($reservations, function($reservation) use ($request) {
                if (!isset($reservation['event_date'])) return false;
                $date = new \DateTime($reservation['event_date']);
                return $date->format('Y') == $request->year;
            });
        }
        
        $reportData = $this->generateReportData($reservations);
        $reportData['filters'] = ['year' => $request->year];

        $pdf = PDF::loadView('admin.reports.reservation.yearly-print', $reportData);
        $pdf->setPaper('a4', 'portrait');

        return $pdf->stream('yearly-report.pdf');
    }

    public function printMonthlyReport(Request $request)
    {
        $reservations = $this->getFinishedReservations();
        
        if($request->year && $request->month) {
            $reservations = array_filter($reservations, function($reservation) use ($request) {
                if (!isset($reservation['event_date'])) return false;
                $date = new \DateTime($reservation['event_date']);
                return $date->format('Y') == $request->year && 
                    (int)$date->format('n') == $request->month;
            });
        }
        
        $reportData = $this->generateReportData($reservations);
        $reportData['filters'] = [
            'year' => $request->year,
            'month' => $request->month
        ];
        
        $pdf = PDF::loadView('admin.reports.reservation.monthly-print', $reportData);
        $pdf->setPaper('a4', 'portrait');

        return $pdf->stream('monthly-report.pdf');
    }

    public function printWeeklyReport(Request $request)
    {
        $reservations = $this->getFinishedReservations();
        
        if($request->year && $request->month && $request->week) {
            $filteredReservations = array_filter($reservations, function($reservation) use ($request) {
                if (!isset($reservation['event_date'])) return false;
                $date = new \DateTime($reservation['event_date']);
                $firstDayOfMonth = new \DateTime($date->format('Y-m-1'));
                $firstDayOfWeek = $firstDayOfMonth->format('w'); // 0 (Sunday) to 6 (Saturday)
                $day = (int)$date->format('j');
                $adjustedDay = $day + $firstDayOfWeek - 1;
                $weekOfMonth = ceil($adjustedDay / 7);
                
                return $date->format('Y') == $request->year && 
                    (int)$date->format('n') == $request->month &&
                    $weekOfMonth == $request->week;
            });
            $reportData = $this->generateReportData($filteredReservations);
        }
        
        $reportData = $this->generateReportData($reservations);
        $reportData['filters'] = [
            'year' => $request->year,
            'month' => $request->month,
            'week' => $request->week
        ];
        
        $pdf = PDF::loadView('admin.reports.reservation.weekly-print', $reportData);
        $pdf->setPaper('a4', 'portrait');
        return $pdf->stream('weekly-report.pdf');
    }

    public function sales()
    {
        $reservations = $this->getFinishedReservations();
        
        // Get all the report data
        $reportData = $this->generateSalesData($reservations);
        
        $isExpanded = session()->get('sidebar_is_expanded', true);
        
        return view('admin.reports.sales.index', array_merge(
            ['isExpanded' => $isExpanded],
            $reportData
        ));
    }

    private function generateSalesData($reservations)
    {
        $data = [
            'years' => [],
            'months' => [
                'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
                'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
            ],
            'yearlyFees' => [],
            'yearlyPrices' => [],
            'monthlyData' => [],
            'weeklyData' => [],
            'dailyData' => []
        ];

        foreach ($reservations as $reservation) {
            if (!isset($reservation['event_date'])) continue;

            $eventDate = strtotime($reservation['event_date']);
            $year = date('Y', $eventDate);
            $month = (int)date('n', $eventDate);
            $day = (int)date('j', $eventDate);
            
            // Calculate week number (ensure it's between 1-4)
            $firstDayOfMonth = new \DateTime("$year-$month-01");
            $firstDayOfWeek = $firstDayOfMonth->format('w');
            $adjustedDay = $day + $firstDayOfWeek - 1;
            $weekOfMonth = min(4, ceil($adjustedDay / 7)); // Ensure week is max 4

            // Initialize year data if not exists
            if (!in_array($year, $data['years'])) {
                $data['years'][] = $year;
                $data['yearlyFees'][$year] = 0;
                $data['yearlyPrices'][$year] = 0;
                $data['monthlyData'][$year] = [
                    'reservationFees' => array_fill(0, 12, 0),
                    'totalPrices' => array_fill(0, 12, 0)
                ];
                $data['weeklyData'][$year] = [];
                $data['dailyData'][$year] = [];

                // Initialize weekly and daily data structures
                for ($m = 1; $m <= 12; $m++) {
                    $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $m, $year);
                    $data['weeklyData'][$year][$m] = [
                        'weeklyFees' => array_fill(0, 4, 0),     // Changed from reservationFees
                        'weeklyPrices' => array_fill(0, 4, 0)    // Changed from totalPrices
                    ];
                    $data['dailyData'][$year][$m] = [
                        'reservationFees' => array_fill(1, $daysInMonth, 0),
                        'totalPrices' => array_fill(1, $daysInMonth, 0)
                    ];
                }
            }

            // Update sales data
            if (isset($reservation['reserve_fee'])) {
                $fee = floatval($reservation['reserve_fee']);
                $data['yearlyFees'][$year] += $fee;
                $data['monthlyData'][$year]['reservationFees'][$month - 1] += $fee;
                $data['weeklyData'][$year][$month]['weeklyFees'][$weekOfMonth - 1] += $fee;  // Changed key name
                $data['dailyData'][$year][$month]['reservationFees'][$day] += $fee;
            }

            if (isset($reservation['total_price'])) {
                $price = floatval($reservation['total_price']);
                $data['yearlyPrices'][$year] += $price;
                $data['monthlyData'][$year]['totalPrices'][$month - 1] += $price;
                $data['weeklyData'][$year][$month]['weeklyPrices'][$weekOfMonth - 1] += $price;  // Changed key name
                $data['dailyData'][$year][$month]['totalPrices'][$day] += $price;
            }
        }

        // Sort years in descending order
        rsort($data['years']);

        return $data;
    }

    public function printSales(Request $request)
    {
        $reservations = $this->getFinishedReservations();
        $originalReportData = $this->generateSalesData($reservations);
        
        // Organize the data in the format expected by the view
        $yearlyData = [];
        foreach ($originalReportData['years'] as $year) {
            $yearlyData[$year] = [
                'yearlyFees' => $originalReportData['yearlyFees'][$year] ?? 0,
                'yearlyPrices' => $originalReportData['yearlyPrices'][$year] ?? 0,
                'reservationFees' => $originalReportData['monthlyData'][$year]['reservationFees'] ?? array_fill(0, 12, 0),
                'totalPrices' => $originalReportData['monthlyData'][$year]['totalPrices'] ?? array_fill(0, 12, 0),
                'weeklyData' => $originalReportData['weeklyData'][$year] ?? []
            ];
        }

        // Prepare the view data
        $viewData = [
            'months' => $originalReportData['months'],
            'yearlyData' => $yearlyData,
            'filters' => [
                'report_type' => $request->report_type,
                'year' => $request->year,
                'month' => $request->month,
                'week' => $request->week,
            ]
        ];

        // Initialize variables
        $view = 'admin.reports.sales.print';
        $filename = 'sales-report.pdf';

        // Filter data based on report type
        if ($request->report_type !== 'all') {
            switch($request->report_type) {
                case 'yearly':
                    if($request->year) {
                        $viewData['yearlyData'] = [
                            $request->year => $yearlyData[$request->year] ?? []
                        ];
                    }
                    $view = 'admin.reports.sales.yearly-print';
                    $filename = 'yearly-sales-report.pdf';
                    break;

                case 'monthly':
                    if($request->year && $request->month) {
                        $viewData['yearlyData'] = [
                            $request->year => $yearlyData[$request->year] ?? []
                        ];
                        $viewData['selectedMonth'] = $request->month;
                    }
                    $view = 'admin.reports.sales.monthly-print';
                    $filename = 'monthly-sales-report.pdf';
                    break;

                case 'weekly':
                    if($request->year && $request->month && $request->week) {
                        $viewData['yearlyData'] = [
                            $request->year => $yearlyData[$request->year] ?? []
                        ];
                        $viewData['selectedMonth'] = $request->month;
                        $viewData['selectedWeek'] = $request->week;
                    }
                    $view = 'admin.reports.sales.weekly-print';
                    $filename = 'weekly-sales-report.pdf';
                    break;
            }
        }

        // Generate PDF
        $pdf = PDF::loadView($view, $viewData);

        // Configure PDF settings
        $pdf->setPaper('a4', 'portrait');
        $pdf->setOptions([
            'isRemoteEnabled' => true,
            'isHtml5ParserEnabled' => true,
            'isPhpEnabled' => true
        ]);

        return $pdf->stream($filename);
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

    public function packages(Request $request) {
        // Get data from Firebase
        $reservations = $this->database->getReference($this->reservations)->getValue();
        $reservations = is_array($reservations) ? $reservations : [];
    
        // Filter only finished reservations
        $finishedReservations = array_filter($reservations, function ($reservation) {
            return isset($reservation['status']) && $reservation['status'] === 'Finished';
        });
    
        // Get all available years from the data
        $years = [];
        foreach ($finishedReservations as $reservation) {
            if (isset($reservation['event_date'])) {
                $year = date('Y', strtotime($reservation['event_date']));
                $years[$year] = $year;
            }
        }
        krsort($years); // Sort years in descending order
    
        // Get current date information
        $currentDate = new \DateTime();
        $selectedYear = $request->input('year', $currentDate->format('Y'));
        $selectedMonth = $request->input('month', $currentDate->format('n'));
        $selectedWeek = $request->input('week', $currentDate->format('W'));
    
        // Initialize arrays for different time periods
        $allTimeRankings = [];
        $yearlyRankings = [];
        $monthlyRankings = [];
        $weeklyRankings = [];
    
        foreach ($finishedReservations as $reservation) {
            if (!isset($reservation['package_name']) || !isset($reservation['event_date'])) {
                continue;
            }
    
            $packageName = $reservation['package_name'];
            $eventDate = new \DateTime($reservation['event_date']);
            
            // All-time rankings
            if (!isset($allTimeRankings[$packageName])) {
                $allTimeRankings[$packageName] = 0;
            }
            $allTimeRankings[$packageName]++;
    
            // Yearly rankings (selected year)
            if ($eventDate->format('Y') === $selectedYear) {
                if (!isset($yearlyRankings[$packageName])) {
                    $yearlyRankings[$packageName] = 0;
                }
                $yearlyRankings[$packageName]++;
            }
    
            // Monthly rankings (selected year and month)
            if ($eventDate->format('Y') === $selectedYear && $eventDate->format('n') === $selectedMonth) {
                if (!isset($monthlyRankings[$packageName])) {
                    $monthlyRankings[$packageName] = 0;
                }
                $monthlyRankings[$packageName]++;
            }
    
            // Weekly rankings (selected year, month, and week)
            if ($eventDate->format('Y') === $selectedYear && 
                $eventDate->format('n') === $selectedMonth && 
                $eventDate->format('W') === $selectedWeek) {
                if (!isset($weeklyRankings[$packageName])) {
                    $weeklyRankings[$packageName] = 0;
                }
                $weeklyRankings[$packageName]++;
            }
        }
    
        // Sort all rankings in descending order
        arsort($allTimeRankings);
        arsort($yearlyRankings);
        arsort($monthlyRankings);
        arsort($weeklyRankings);
    
        // Get months array
        $months = [
            1 => 'Jan',
            2 => 'Feb',
            3 => 'Mar',
            4 => 'Apr',
            5 => 'May',
            6 => 'June',
            7 => 'Jul',
            8 => 'Aug',
            9 => 'Sep',
            10 => 'Oct',
            11 => 'Nov',
            12 => 'Dec'
        ];
    
        $isExpanded = session()->get('sidebar_is_expanded', true);
        
        return view('admin.reports.packages.index', compact(
            'allTimeRankings',
            'yearlyRankings',
            'monthlyRankings',
            'weeklyRankings',
            'years',
            'months',
            'selectedYear',
            'selectedMonth',
            'selectedWeek',
            'isExpanded'
        ));
    }

    public function getYearlyRankings(Request $request) {
        $selectedYear = $request->input('year');
        $yearlyRankings = [];
        
        $finishedReservations = $this->getFinishedReservations();
        
        foreach ($finishedReservations as $reservation) {
            if (!isset($reservation['package_name']) || !isset($reservation['event_date'])) {
                continue;
            }
    
            $eventDate = new \DateTime($reservation['event_date']);
            if ($eventDate->format('Y') === $selectedYear) {
                $packageName = $reservation['package_name'];
                if (!isset($yearlyRankings[$packageName])) {
                    $yearlyRankings[$packageName] = 0;
                }
                $yearlyRankings[$packageName]++;
            }
        }
        
        arsort($yearlyRankings);
        
        return response()->json([
            'rankings' => $yearlyRankings
        ]);
    }
    
    public function getMonthlyRankings(Request $request) {
        $selectedYear = $request->input('year');
        $selectedMonth = $request->input('month');
        $monthlyRankings = [];
        
        $finishedReservations = $this->getFinishedReservations();
        
        foreach ($finishedReservations as $reservation) {
            if (!isset($reservation['package_name']) || !isset($reservation['event_date'])) {
                continue;
            }
    
            $eventDate = new \DateTime($reservation['event_date']);
            if ($eventDate->format('Y') === $selectedYear && 
                $eventDate->format('n') === $selectedMonth) {
                $packageName = $reservation['package_name'];
                if (!isset($monthlyRankings[$packageName])) {
                    $monthlyRankings[$packageName] = 0;
                }
                $monthlyRankings[$packageName]++;
            }
        }
        
        arsort($monthlyRankings);
        
        return response()->json([
            'rankings' => $monthlyRankings
        ]);
    }
    
    public function getWeeklyRankings(Request $request) {
        $selectedYear = $request->input('year');
        $selectedMonth = $request->input('month');
        $selectedWeek = $request->input('week');
        $weeklyRankings = [];
        
        $finishedReservations = $this->getFinishedReservations();
        
        foreach ($finishedReservations as $reservation) {
            if (!isset($reservation['package_name']) || !isset($reservation['event_date'])) {
                continue;
            }
    
            $eventDate = new \DateTime($reservation['event_date']);
            $firstDayOfMonth = new \DateTime($eventDate->format('Y-m-1'));
            $firstDayOfWeek = $firstDayOfMonth->format('w');
            $day = (int)$eventDate->format('j');
            $adjustedDay = $day + $firstDayOfWeek - 1;
            $weekOfMonth = ceil($adjustedDay / 7);
            
            if ($eventDate->format('Y') === $selectedYear && 
                $eventDate->format('n') === $selectedMonth &&
                $weekOfMonth == $selectedWeek) {
                $packageName = $reservation['package_name'];
                if (!isset($weeklyRankings[$packageName])) {
                    $weeklyRankings[$packageName] = 0;
                }
                $weeklyRankings[$packageName]++;
            }
        }
        
        arsort($weeklyRankings);
        
        return response()->json([
            'rankings' => $weeklyRankings
        ]);
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
}
