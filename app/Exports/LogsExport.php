<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;

class LogsExport implements FromCollection, WithHeadings
{
    public function headings(): array
    {
        return [
            '#',
            'Date',
            'Time',
            'User',
            'Activity'
        ];
    }

    public function collection()
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
                            // Try to match both formats
                            if (preg_match('/\[(.+?)\] local\.INFO: Activity Log ({.+})/', $currentLog, $matches)) {
                                // Format 1: Structured activity log
                                if (isset($matches[1], $matches[2])) {
                                    $logData = json_decode($matches[2], true);
                                    if ($logData && isset($logData['user'], $logData['action'])) {
                                        $datetime = Carbon::parse($matches[1]);
                                        $logs[] = [
                                            'datetime' => $matches[1],
                                            'date' => $datetime->format('M d, Y'),
                                            'time' => $datetime->format('h:i:s A'),
                                            'user' => $logData['user'],
                                            'action' => $logData['action']
                                        ];
                                    }
                                }
                            } elseif (preg_match('/\[(.+?)\] local\.INFO: (.+)/', $currentLog, $matches)) {
                                // Format 2: Simple log message
                                if (isset($matches[1], $matches[2])) {
                                    $datetime = Carbon::parse($matches[1]);
                                    $logs[] = [
                                        'datetime' => $matches[1],
                                        'date' => $datetime->format('M d, Y'),
                                        'time' => $datetime->format('h:i:s A'),
                                        'user' => 'System', // Default for simple logs
                                        'action' => trim($matches[2])
                                    ];
                                }
                            }
                        }
                        $currentLog = $line;
                    } else {
                        $currentLog .= $line;
                    }
                }
                
                // Process the last log entry
                if (!empty($currentLog)) {
                    // Same pattern matching for the last entry
                    if (preg_match('/\[(.+?)\] local\.INFO: Activity Log ({.+})/', $currentLog, $matches)) {
                        if (isset($matches[1], $matches[2])) {
                            $logData = json_decode($matches[2], true);
                            if ($logData && isset($logData['user'], $logData['action'])) {
                                $datetime = Carbon::parse($matches[1]);
                                $logs[] = [
                                    'datetime' => $matches[1],
                                    'date' => $datetime->format('M d, Y'),
                                    'time' => $datetime->format('h:i:s A'),
                                    'user' => $logData['user'],
                                    'action' => $logData['action']
                                ];
                            }
                        }
                    } elseif (preg_match('/\[(.+?)\] local\.INFO: (.+)/', $currentLog, $matches)) {
                        if (isset($matches[1], $matches[2])) {
                            $datetime = Carbon::parse($matches[1]);
                            $logs[] = [
                                'datetime' => $matches[1],
                                'date' => $datetime->format('M d, Y'),
                                'time' => $datetime->format('h:i:s A'),
                                'user' => 'System',
                                'action' => trim($matches[2])
                            ];
                        }
                    }
                }
                
                fclose($handle);
                
                // Sort logs by datetime in descending order
                usort($logs, function($a, $b) {
                    return strtotime($b['datetime']) - strtotime($a['datetime']);
                });
            }
        }
        
        // Format for export
        return collect($logs)->map(function ($log, $index) use ($logs) {
            return [
                count($logs) - $index,
                $log['date'],
                $log['time'],
                $log['user'],
                $log['action']
            ];
        });
    }
}