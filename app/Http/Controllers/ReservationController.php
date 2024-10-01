<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Kreait\Firebase\Contract\Database;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    protected $database, $area, $packages, $menu, $events, $reservations, $archived_reservations;

    public function __construct(Database $database){
        $this->database = $database;
        $this->reservations = 'reservations';
        $this->area = 'area';
        $this->packages = 'packages';
        $this->menu = 'menu';       
        $this->events = 'events';
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
        $packages = is_array($packages) ? $packages : [];

        $isExpanded = session()->get('sidebar_is_expanded', true);    
        return view('admin.reservation.createReservation', compact('packages', 'isExpanded'));
    }

    public function create_pen(){
        $areas = $this->database->getReference($this->area)->getValue();
        $areas = is_array($areas) ? array_map(fn($area) => $area['area_name'], $areas) : [];
        
        $packages = $this->database->getReference($this->packages)->getValue();
        $packages = is_array($packages) ? $packages : [];

        $menus = $this->database->getReference($this->menu)->getValue();
        $menus = is_array($menus) ? $menus : [];

        $events = $this->database->getReference($this->events)->getValue();
        $events = is_array($events) ? $events : [];
    
        return view('firebase.admin.reservation.create_pen', compact('areas', 'packages', 'menus', 'events'));
    }

    public function reservation(Request $request){

        $validatedData = $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'address' => 'required',
            'phone' => 'required',
            'email' => 'required',
            'package_name' => 'nullable',
            'event_date' => 'required',
            'guests_number' => 'required',
            'sponsors' => 'nullable|integer',
            'venue' => 'required',
            'event_time' => 'required',
            'theme' => 'required',
            'other_requests' => 'required',
        ]);

        $reserveData = [
            'status' => 'Pending',
            'reserve_type' => 'Reserve',
            'first_name' => $validatedData['first_name'],
            'last_name' => $validatedData['last_name'],
            'address' => $validatedData['address'],
            'phone' => $validatedData['phone'],
            'email' => $validatedData['email'],
            'package_name' => $validatedData['package_name'] ?? null,
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
            return redirect('admin/reservations')->with('status', 'Reservation Added Successfully');
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
            'area_name' => 'required',
            'package_name' => 'required',
            'menu_name' => 'required',
            'event_date' => 'required',
            'guests_number' => 'required',
            'sponsors' => 'nullable|integer',
            'venue' => 'required',
            'event_time' => 'required',
            'theme' => 'required',
            'other_requests' => 'required',
        ]);

        $penData = [
            'status' => '',
            'reserve_type' => 'Pencil',
            'first_name' => $validatedData['first_name'],
            'last_name' => $validatedData['last_name'],
            'address' => $validatedData['address'],
            'phone' => $validatedData['phone'],
            'email' => $validatedData['email'],
            'area_name' => $validatedData['area_name'],
            'package_name' => $validatedData['package_name'],
            'menu_name' => $validatedData['menu_name'],
            'event_date' => $validatedData['event_date'],
            'guests_number' => $validatedData['guests_number'],
            'sponsors' => $validatedData['sponsors'] ?? null,
            'venue' => $validatedData['venue'],
            'event_time' => $validatedData['event_time'],
            'theme' => $validatedData['theme'],
            'other_requests' => $validatedData['other_requests'],
        ];

        $postRef = $this->database->getReference($this->reservations)->push($penData);

        if ($postRef) {
            return redirect('admin/calendar')->with('status', 'Reservation Added Successfully');
        } else {
            return redirect('admin/calendar')->with('status', 'Reservation Not Added');
        }
    }

    public function confirmReservation(Request $request, $id){
        $key = $id;

        $reserveData = [
            'status' => 'Confirmed'
        ];

        $res_updated = $this->database->getReference($this->reservations. '/'.$key)->update($reserveData);

        if ($res_updated) {
            return redirect('admin/reservations')->with('status', 'Category Updated Successfully');
        } else {
            return redirect('admin/reservations')->with('status', 'Category Not Updated');
        }
    }
}