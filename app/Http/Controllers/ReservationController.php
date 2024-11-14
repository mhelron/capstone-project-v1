<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Kreait\Firebase\Contract\Database;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    protected $database, $packages, $reservations, $archived_reservations;

    public function __construct(Database $database){
        $this->database = $database;
        $this->reservations = 'reservations';
        $this->packages = 'packages';
        $this->archived_reservations = 'archived_reservations';
    }
    public function index(Request $request){
        $reservations = $this->database->getReference($this->reservations)->getValue();
        $reservations = is_array($reservations) ? $reservations : [];

        $pendingReservations = array_filter($reservations, fn($reservation) => $reservation['status'] === 'Pending');
        $confirmedReservations = array_filter($reservations, fn($reservation) => $reservation['status'] === 'Confirmed');
        $cancelledReservations = array_filter($reservations, fn($reservation) => $reservation['status'] === 'Cancelled');
        $finishedReservations = array_filter($reservations, fn($reservation) => $reservation['status'] === 'Finished');
        $pencilReservations = array_filter($reservations, fn($reservation) => $reservation['reserve_type'] === 'Pencil');

        $perPage = 10;
        $currentPage = $request->input('page', 1);
        $totalItems = count($reservations);
        $totalPages = ceil($totalItems / $perPage);
        $offset = ($currentPage - 1) * $perPage;

        $pagedService = array_slice($reservations, $offset, $perPage);

        $isExpanded = session()->get('sidebar_is_expanded', true);
        return view('admin.reservation.index', [
            'pendingReservations' => $pendingReservations,
            'confirmedReservations' => $confirmedReservations,
            'cancelledReservations' => $cancelledReservations,
            'finishedReservations' => $finishedReservations,
            'pencilReservations' => $pencilReservations,
            'totalPages' => $totalPages,
            'currentPage' => $currentPage,
            'totalItems' => $totalItems,
            'reservations' => $pagedService,
            'isExpanded' => $isExpanded
        ]);
    }

    public function createReservation(){
        $packages = $this->database->getReference($this->packages)->getValue();
        $packages = is_array($packages) ? array_map(fn($package) => $package, $packages) : [];

        $isExpanded = session()->get('sidebar_is_expanded', true);    
        return view('admin.reservation.createreservation', compact('packages', 'isExpanded'));
    }

    public function createPencil(){   
        $packages = $this->database->getReference($this->packages)->getValue();
        $packages = is_array($packages) ? array_map(fn($package) => $package, $packages) : [];

        $isExpanded = session()->get('sidebar_is_expanded', true);    
        return view('admin.reservation.createpencilreservation', compact('packages', 'isExpanded'));
    }

    public function reservation(Request $request)
    {
    // Validation rules
    $validatedData = $request->validate([
        'first_name' => 'required',
        'last_name' => 'required',
        'address' => 'required',
        'phone' => 'required',
        'email' => 'required',
        'package_name' => 'required', // Ensure package is selected
        'menu_name' => 'required',
        'event_date' => 'required',
        'guests_number' => 'required',
        'sponsors' => 'nullable|integer',
        'venue' => 'required',
        'event_time' => 'required',
        'theme' => 'required',
        'other_requests' => 'nullable',
    ],[
        'menu_name.required_if' => 'You must select a menu when a package is selected.',
    ]);

    // Fetch packages from Firebase
    $packages = $this->database->getReference($this->packages)->getValue();
    $packages = is_array($packages) ? $packages : [];

    $menuContent = [];
    // Only process menu if both package_name and menu_name are provided
    if (!empty($validatedData['package_name']) && !empty($validatedData['menu_name'])) {
        foreach ($packages as $package) {
            if ($package['package_name'] === $validatedData['package_name']) {
                foreach ($package['menus'] as $menu) {
                    if ($menu['menu_name'] === $validatedData['menu_name']) {
                        $menuContent = $menu['foods']; // Get the foods from the selected menu
                        break 2; // Exit both loops once found
                    }
                }
            }
        }
    }

    // Prepare reservation data
    $reserveData = [
        'status' => 'Pending',
        'reserve_type' => 'Reserve',
        'first_name' => $validatedData['first_name'],
        'last_name' => $validatedData['last_name'],
        'address' => $validatedData['address'],
        'phone' => $validatedData['phone'],
        'email' => $validatedData['email'],
        'package_name' => $validatedData['package_name'] ?? null,
        'menu_name' => $validatedData['menu_name'] ?? null,
        'menu_content' => $menuContent,
        'event_date' => $validatedData['event_date'],
        'guests_number' => $validatedData['guests_number'],
        'sponsors' => $validatedData['sponsors'] ?? null,
        'venue' => $validatedData['venue'],
        'event_time' => $validatedData['event_time'],
        'theme' => $validatedData['theme'],
        'other_requests' => $validatedData['other_requests'],
    ];

    // Push reservation data to Firebase
    $postRef = $this->database->getReference($this->reservations)->push($reserveData);

    if ($postRef) {
        return redirect('admin/reservations')->with('status', 'Reservation Added');
    } else {
        return redirect('admin/reservations')->with('status', 'Reservation Not Added');
    }
}

    public function pencilReservation(Request $request){

        $validatedData = $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'address' => 'required',
            'phone' => 'required',
            'email' => 'required',
            'package_name' => 'required',
            'menu_name' => 'required',
            'event_date' => 'required',
            'guests_number' => 'required',
            'sponsors' => 'nullable|integer',
            'venue' => 'required',
            'event_time' => 'required',
            'theme' => 'required',
            'other_requests' => 'nullable',
        ]);

        $packages = $this->database->getReference($this->packages)->getValue();
        $packages = is_array($packages) ? $packages : [];

        $menuContent = [];
        if (!empty($validatedData['package_name']) && !empty($validatedData['menu_name'])) {
            foreach ($packages as $package) {
                if ($package['package_name'] === $validatedData['package_name']) {
                    foreach ($package['menus'] as $menu) {
                        if ($menu['menu_name'] === $validatedData['menu_name']) {
                            $menuContent = $menu['foods']; // Get the foods from the selected menu
                            break 2; // Exit both loops once found
                        }
                    }
                }
            }
        }

        $reserveData = [
            'status' => 'Pencil',
            'reserve_type' => 'Pencil',
            'first_name' => $validatedData['first_name'],
            'last_name' => $validatedData['last_name'],
            'address' => $validatedData['address'],
            'phone' => $validatedData['phone'],
            'email' => $validatedData['email'],
            'package_name' => $validatedData['package_name'] ?? null,
            'menu_name' => $validatedData['menu_name'] ?? null,
            'menu_content' => $menuContent,
            'event_date' => $validatedData['event_date'],
            'guests_number' => $validatedData['guests_number'],
            'sponsors' => $validatedData['sponsors'] ?? null,
            'venue' => $validatedData['venue'],
            'event_time' => $validatedData['event_time'],
            'theme' => $validatedData['theme'],
            'other_requests' => $validatedData['other_requests'],
        ];

        $postRef = $this->database->getReference($this->reservations)->push($reserveData);

        if ($postRef) {
            return redirect('admin/reservations')->with('status', 'Reservation Added');
        } else {
            return redirect('admin/reservations')->with('status', 'Reservation Not Added');
        }
    }

    public function confirmReservation($id){
        $key = $id;

        $reserveData = [
            'status' => 'Confirmed'
        ];

        $res_updated = $this->database->getReference($this->reservations. '/'.$key)->update($reserveData);

        if ($res_updated) {
            return redirect('admin/reservations')->with('status', 'Reservation Confirmed');
        } else {
            return redirect('admin/reservations')->with('status', 'Reservation Not Confirmed');
        }
    }

    public function finishReservation($id){
        $key = $id;

        $reserveData = [
            'status' => 'Finished'
        ];

        $res_updated = $this->database->getReference($this->reservations. '/'.$key)->update($reserveData);

        if ($res_updated) {
            return redirect('admin/reservations')->with('status', 'Reservation Finished');
        } else {
            return redirect('admin/reservations')->with('status', 'Reservation Not Finished');
        }
    }

    public function cancelReservation($id){
        $key = $id;

        $reserveData = [
            'status' => 'Cancelled'
        ];

        $res_updated = $this->database->getReference($this->reservations. '/'.$key)->update($reserveData);

        if ($res_updated) {
            return redirect('admin/reservations')->with('status', 'Reservation Cancelled');
        } else {
            return redirect('admin/reservations')->with('status', 'Reservation Not Cancelled');
        }
    }
}