<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LogsExport;

class ReportsController extends Controller
{
    public function reservation(){

        $isExpanded = session()->get('sidebar_is_expanded', true);
        return view('admin.reports.reservation.index', compact('isExpanded'));
    }

    public function sales(){

        $isExpanded = session()->get('sidebar_is_expanded', true);
        return view('admin.reports.sales.index', compact('isExpanded'));
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
        return Excel::download(new LogsExport, 'Logs (' . date('F-d-Y') . ').xlsx');
    }

    public function removeAll() 
    {
        $logFile = storage_path('logs/laravel.log');
        
        if (File::exists($logFile)) {
            // Clear the file content
            file_put_contents($logFile, '');
            return redirect()->back()->with('success', 'Successfully Removed All Logs');
        }
        
        return redirect()->back()->with('error', 'Log file not found');
    }
}
