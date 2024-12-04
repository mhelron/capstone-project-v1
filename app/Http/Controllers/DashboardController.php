<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Database;
use Carbon\Carbon;

class DashboardController extends Controller
{
    protected $database, $reservations, $packages;

    public function __construct(Database $database)
    {
        $this->database = $database;
        $this->reservations = 'reservations';
        $this->packages = 'packages';
    }

    public function index()
    {
        // Fetch reservations and packages
        $reservations = $this->database->getReference($this->reservations)->getValue();
        $reservations = is_array($reservations) ? $reservations : [];
        $packages = $this->database->getReference($this->packages)->getValue();
        $packages = is_array($packages) ? $packages : [];

        // Prepare counts for dashboard
        $counts = [
            'pending' => count(array_filter($reservations, fn($r) => $r['status'] === 'Pending')),
            'confirmed' => count(array_filter($reservations, fn($r) => $r['status'] === 'Confirmed')),
            'cancelled' => count(array_filter($reservations, fn($r) => $r['status'] === 'Cancelled')),
            'finished' => count(array_filter($reservations, fn($r) => $r['status'] === 'Finished')),
            'pencil' => count(array_filter($reservations, fn($r) => $r['reserve_type'] === 'Pencil')),
            'packages' => count($packages)
        ];

        $packageCounts = [];
        foreach ($reservations as $reservation) {
            // Only count packages from finished reservations
            if ($reservation['status'] === 'Finished' && isset($reservation['package_name'])) {
                $packageName = $reservation['package_name'];
                if (isset($packageCounts[$packageName])) {
                    $packageCounts[$packageName]++;
                } else {
                    $packageCounts[$packageName] = 1;
                }
            }
        }

        // Sort packages by count and get top 10
        arsort($packageCounts);  // Sort in descending order
        $topPackages = array_slice($packageCounts, 0, 10, true);
        // Filter upcoming confirmed reservations (events)
        $upcomingReservations = array_filter($reservations, function ($reservation) {
            $eventDate = Carbon::parse($reservation['event_date'] . ' ' . $reservation['event_time']);
            // Check if event is confirmed and is in the future
            return $reservation['status'] === 'Confirmed' && $eventDate->isFuture();
        });

        // Sort upcoming confirmed reservations by date and time
        usort($upcomingReservations, function ($a, $b) {
            $dateA = Carbon::parse($a['event_date'] . ' ' . $a['event_time']);
            $dateB = Carbon::parse($b['event_date'] . ' ' . $b['event_time']);
            return $dateA->timestamp - $dateB->timestamp;
        });

        // Limit to the top 5 upcoming confirmed reservations
        $upcomingReservations = array_slice($upcomingReservations, 0, 10);

        $newNotifications = [];
        foreach ($reservations as $key => $reservation) {
            if (!isset($reservation['read']) || $reservation['read'] === false) {
                $newNotifications[] = [
                    'key' => $key,  // Retrieve the key of the reservation
                    'data' => $reservation
                ];
            }
        }

        $newNotifications = array_slice($newNotifications, 0, 7);

        // Passing data to view
        $isExpanded = session()->get('sidebar_is_expanded', true);
        return view('admin.dashboard.index', compact('isExpanded', 'counts', 'topPackages', 'upcomingReservations', 'newNotifications'));
    }

    public function markAsRead($reservationId)
    {
        $reservation = $this->database->getReference($this->reservations . '/' . $reservationId)->getValue();

        if ($reservation) {
            // Mark as read by updating the 'read' field
            $this->database->getReference($this->reservations . '/' . $reservationId)->update(['read' => true]);

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false], 404);
    }

}
