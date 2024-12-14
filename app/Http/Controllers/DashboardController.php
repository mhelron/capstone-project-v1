<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Database;
use Kreait\Firebase\Contract\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    protected $database, $reservations, $packages, $auth;

    public function __construct(Database $database, Auth $auth)
    {
        $this->database = $database;
        $this->auth = $auth;
        $this->reservations = 'reservations';
        $this->packages = 'packages';
    }

    private function getCurrentUserData()
    {
        $user = Session::get('firebase_user');
        $userRole = Session::get('user_role');
        
        if (!$user || !$userRole) {
            Log::warning('Missing user data in session', [
                'user' => $user ? 'exists' : 'missing',
                'role' => $userRole
            ]);
            return null;
        }

        return [
            'uid' => $user->uid,
            'email' => $user->email,
            'role' => $userRole
        ];
    }

    public function markAsRead($reservationId)
    {
        try {
            $userData = $this->getCurrentUserData();
            
            if (!$userData) {
                return response()->json([
                    'success' => false,
                    'message' => 'Session expired. Please login again.'
                ], 401);
            }

            $reservationRef = $this->database->getReference($this->reservations . '/' . $reservationId);
            $reservation = $reservationRef->getValue();

            if ($reservation) {
                // Get existing readBy data or initialize empty array
                $readBy = $reservation['readBy'] ?? [];
                
                // Add or update current user's read status
                $readBy[$userData['uid']] = [
                    'timestamp' => Carbon::now()->toDateTimeString(),
                    'userRole' => $userData['role'],
                    'email' => $userData['email']
                ];

                // Update the reservation
                $reservationRef->update([
                    'readBy' => $readBy
                ]);

                Log::info('Notification marked as read', [
                    'reservation_id' => $reservationId,
                    'user' => $userData['email'],
                    'role' => $userData['role']
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Notification marked as read'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Reservation not found'
            ], 404);

        } catch (\Exception $e) {
            Log::error('Error marking notification as read:', [
                'error' => $e->getMessage(),
                'reservation_id' => $reservationId,
                'user' => Session::get('firebase_user.email') ?? 'unknown'
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error occurred. Please try again.'
            ], 500);
        }
    }

    public function index()
    {
        $userData = $this->getCurrentUserData();
        if (!$userData) {
            return redirect()->route('login')
                        ->with('error', 'Your session has expired. Please login again.');
        }
        
        // Fetch reservations and packages
        $reservations = $this->database->getReference($this->reservations)->getValue();
        $reservations = is_array($reservations) ? $reservations : [];
        $packages = $this->database->getReference($this->packages)->getValue();
        $packages = is_array($packages) ? $packages : [];

        // Initialize packageCounts as an empty array
        $packageCounts = [];

        // Count packages from finished reservations
        if (!empty($reservations)) {
            foreach ($reservations as $reservation) {
                if (isset($reservation['status']) && 
                    $reservation['status'] === 'Finished' && 
                    isset($reservation['package_name'])) {
                    $packageName = $reservation['package_name'];
                    $packageCounts[$packageName] = ($packageCounts[$packageName] ?? 0) + 1;
                }
            }
        }

        // Only sort if packageCounts is not empty
        if (!empty($packageCounts)) {
            arsort($packageCounts);
        }

        // Get top 10 packages or all if less than 10
        $topPackages = array_slice($packageCounts, 0, 10, true);

        // Prepare counts for dashboard
        $counts = [
            'pending' => count(array_filter($reservations, fn($r) => isset($r['status']) && $r['status'] === 'Pending')),
            'confirmed' => count(array_filter($reservations, fn($r) => isset($r['status']) && $r['status'] === 'Confirmed')),
            'cancelled' => count(array_filter($reservations, fn($r) => isset($r['status']) && $r['status'] === 'Cancelled')),
            'finished' => count(array_filter($reservations, fn($r) => isset($r['status']) && $r['status'] === 'Finished')),
            'pencil' => count(array_filter($reservations, fn($r) => isset($r['reserve_type']) && $r['reserve_type'] === 'Pencil')),
            'packages' => count($packages)
        ];
        
        // Filter upcoming confirmed reservations
        $upcomingReservations = array_filter($reservations, function ($reservation) {
            if (!isset($reservation['event_date']) || !isset($reservation['event_time']) || !isset($reservation['status'])) {
                return false;
            }
            $eventDate = Carbon::parse($reservation['event_date'] . ' ' . $reservation['event_time']);
            return $reservation['status'] === 'Confirmed' && $eventDate->isFuture();
        });

        // Sort upcoming reservations
        if (!empty($upcomingReservations)) {
            usort($upcomingReservations, function ($a, $b) {
                $dateA = Carbon::parse($a['event_date'] . ' ' . $a['event_time']);
                $dateB = Carbon::parse($b['event_date'] . ' ' . $b['event_time']);
                return $dateA->timestamp - $dateB->timestamp;
            });
        }

        // Get top 10 upcoming events
        $upcomingReservations = array_slice($upcomingReservations, 0, 10);

        // Get unread notifications for current user
        $newNotifications = [];
        foreach ($reservations as $key => $reservation) {
            if (isset($reservation['status']) && 
                isset($reservation['reserve_type']) && 
                ($reservation['status'] === 'Pending' || $reservation['reserve_type'] === 'Pencil') && 
                (!isset($reservation['readBy']) || !isset($reservation['readBy'][$userData['uid']]))) {
                $newNotifications[] = [
                    'key' => $key,
                    'data' => $reservation
                ];
            }
        }

        // Sort notifications by creation date
        if (!empty($newNotifications)) {
            usort($newNotifications, function ($a, $b) {
                $dateA = Carbon::parse($a['data']['created_at'] ?? '');
                $dateB = Carbon::parse($b['data']['created_at'] ?? '');
                return $dateB->timestamp - $dateA->timestamp;
            });
        }

        // Limit to 7 notifications
        $newNotifications = array_slice($newNotifications, 0, 7);

        $isExpanded = session()->get('sidebar_is_expanded', true);

        return view('admin.dashboard.index', compact(
            'isExpanded', 
            'counts', 
            'topPackages', 
            'upcomingReservations', 
            'newNotifications'
        ));
    }
}