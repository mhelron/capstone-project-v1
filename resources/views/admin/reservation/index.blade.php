@extends('layouts.adminlayout')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0" style="padding-top: 35px;">Reservation</h1>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="d-flex justify-content-end mb-2">
                    <a href="{{route('admin.reserve.addRes')}}" class="btn btn-primary me-2">Add Reservation</a>
                    <a href="{{route('admin.reserve.addPen')}}" class="btn btn-secondary">Add Pencil Reservation</a>
                </div>

                <!-- Bootstrap Toast -->
                @if (session('status'))
                    <div class="toast-container position-fixed top-0 end-0 p-3">
                        <div class="toast text-bg-light border border-dark custom-toast-size" role="alert" aria-live="assertive" aria-atomic="true">
                            <div class="d-flex">
                                <div class="toast-body">
                                    {{ session('status') }}
                                </div>
                                <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="card">
                    <div class="card-body">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">

                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="pen-book" data-bs-toggle="tab" data-bs-target="#penbook" type="button" role="tab" aria-controls="penbook" aria-selected="false">Pencil Reservations</button>
                            </li>

                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="pending-reservation" data-bs-toggle="tab" data-bs-target="#pending" type="button" role="tab" aria-controls="pending" aria-selected="false">Pending Reservations</button>
                            </li>

                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="confirmed-reservation" data-bs-toggle="tab" data-bs-target="#confirmed" type="button" role="tab" aria-controls="confirmed" aria-selected="false">Confirmed Reservations</button>
                            </li>

                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="cancelled-reservation" data-bs-toggle="tab" data-bs-target="#cancelled" type="button" role="tab" aria-controls="cancelled" aria-selected="false">Cancelled Reservations</button>
                            </li>

                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="finished-reservation" data-bs-toggle="tab" data-bs-target="#finished" type="button" role="tab" aria-controls="finished" aria-selected="false">Finished Reservations</button>
                            </li>

                        </ul>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade" id="pending" role="tabpanel" aria-labelledby="pending-tab">
                                <!-- Filter Controls Template - Will be duplicated for each tab -->
                                <div class="filter-controls mb-3 mt-3">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label class="form-label">Sort By</label>
                                            <select class="form-select date-filter">
                                                <option value="all">All Events</option>
                                                <option value="upcoming">Upcoming Events</option>
                                                <option value="newest">Newly Added</option>
                                                <option value="ascending">Date (Ascending)</option>
                                                <option value="descending">Date (Descending)</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Filter by Location</label>
                                            <select class="form-select venue-filter">
                                                <option value="all">All Locations</option>
                                                <option value="marikina">Marikina</option>
                                                <option value="san mateo">San Mateo</option>
                                                <option value="montalban">Montalban</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">First name</th>
                                                <th scope="col">Last name</th>
                                                <th scope="col">Event Date</th>
                                                <th scope="col">Package</th>
                                                <th scope="col">Pax</th>
                                                <th scope="col">Status</th>
                                                <th scope="col">Details</th>
                                                <th scope="col">Payment</th>
                                                <th scope="col">Options</th>
                                            </tr>
                                        </thead>
                                        <tbody>
 
                                            @forelse ($pendingReservations as $key => $item)
                                                <tr data-location="{{ \Illuminate\Support\Str::contains($item['package_name'], 'Marikina') ? 'marikina' : 
                                                    (\Illuminate\Support\Str::contains($item['package_name'], 'San Mateo') ? 'san mateo' : 
                                                    (\Illuminate\Support\Str::contains($item['package_name'], 'Montalban') ? 'montalban' : '')) }}"
                                                    data-event-date="{{ \Carbon\Carbon::parse($item['event_date'])->format('Y-m-d') }}"
                                                    data-created="{{ isset($item['created_at']) ? \Carbon\Carbon::parse($item['created_at'])->format('Y-m-d H:i:s') : '' }}">
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $item['first_name'] }}</td>
                                                    <td>{{ $item['last_name'] }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($item['event_date'])->format('F j, Y') }}</td>
                                                    <td>{{ $item['package_name'] }}</td>
                                                    <td>{{ $item['guests_number'] }}</td>
                                                    <td>
                                                        <span class="status-badge {{ strtolower($item['status']) }}">
                                                            {{ ucfirst($item['status']) }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#reservationModal{{ $key }}">
                                                            View Details
                                                        </button>

                                                        <div class="modal fade" id="reservationModal{{ $key }}" tabindex="-1" aria-labelledby="reservationModalLabel{{ $key }}" aria-hidden="true">
                                                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="reservationModalLabel{{ $key }}">Reservation Details for {{ $item['first_name'] }}</h5>
                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <ul class="list-group">
                                                                            <li class="list-group-item"><strong>Address:</strong> 
                                                                                {{ $item['street_houseno'] ?? 'No Street/House No.' }}, 
                                                                                {{ $item['barangay'] ?? 'No Barangay' }}, 
                                                                                {{ $item['city'] ?? 'No City' }}, 
                                                                                {{ $item['province'] ?? 'No Province' }}, 
                                                                                {{ $item['region'] ?? 'No Region' }}
                                                                            </li>
                                                                            <li class="list-group-item">
                                                                                <strong>Menu Name:</strong> 
                                                                                <span 
                                                                                    data-bs-toggle="tooltip" 
                                                                                    data-bs-html="true"
                                                                                    data-bs-placement="right"
                                                                                    title="{{ isset($item['menu_content']) ? implode(',<br>', array_column($item['menu_content'], 'food')) : 'No menu content available' }}"
                                                                                    class="tooltip-text"
                                                                                    style="cursor: pointer;">
                                                                                    {{ $item['menu_name'] ?? 'No Menu Selected' }}
                                                                                </span>
                                                                            </li>
                                                                            <li class="list-group-item"><strong>Venue:</strong> {{ $item['venue'] }}</li>
                                                                            @if(isset($item['package_name']) && \Illuminate\Support\Str::contains($item['package_name'], 'Wedding'))
                                                                                <li class="list-group-item"><strong>Sponsors:</strong> {{ $item['sponsors'] ?? 'No sponsors' }}</li>
                                                                            @endif
                                                                            <li class="list-group-item"><strong>Event Time:</strong> {{ \Carbon\Carbon::parse($item['event_time'])->format('g:i A') }}</li>
                                                                            <li class="list-group-item"><strong>Theme:</strong> {{ $item['theme'] }}</li>
                                                                            <li class="list-group-item"><strong>Other Requests:</strong> {{ $item['other_requests'] ?? 'No requests' }}</li>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <!-- Separate Image Modal Trigger -->
                                                    <td>
                                                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#imageModal{{ $key }}">
                                                            View Payment
                                                        </button>

                                                        <!-- Image Modal -->
                                                        <div class="modal fade" id="imageModal{{ $key }}" tabindex="-1" aria-labelledby="imageModalLabel{{ $key }}" aria-hidden="true">
                                                            <div class="modal-dialog modal-dialog-centered">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="imageModalLabel{{ $key }}">Payment Details for {{ $item['first_name'] }}</h5>
                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <!-- Display Payment Proof Image -->
                                                                        @if(isset($item['payment_proof']) && !empty($item['payment_proof']))
                                                                            <img src="{{ asset('storage/'.$item['payment_proof']) }}" alt="Payment Proof" class="img-fluid mb-3">
                                                                        @else
                                                                            <p>No payment details available.</p>
                                                                        @endif

                                                                        <!-- Check if 'payment_submitted_at' exists and display it -->
                                                                        @if(isset($item['payment_submitted_at']) && !empty($item['payment_submitted_at']))
                                                                            <p><strong>Submitted At:</strong> {{ \Carbon\Carbon::parse($item['payment_submitted_at'])->format('F j, Y, g:i A') }}</p>
                                                                        @else
                                                                            <p><strong>Submitted At:</strong> Not Available</p>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </td>
                                                    <td>
                                                        <form action="{{url('admin/reservations/confirm-reservation/'.$key)}}" method="POST" class="me-2 d-inline">
                                                            @csrf
                                                            @method('PUT')
                                                            <button type="submit" class="btn btn-sm btn-success">Confirm</button>
                                                        </form>
                                                        <form action="{{url('admin/reservations/cancel-reservation/'.$key)}}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('PUT')
                                                            <button type="submit" class="btn btn-sm btn-secondary">Cancel</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="3">No Pending Reservation Found</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                            </div>
                            <div class="tab-pane fade" id="confirmed" role="tabpanel" aria-labelledby="confirmed-tab">
                                <!-- Filter Controls Template - Will be duplicated for each tab -->
                                <div class="filter-controls mb-3 mt-3">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label class="form-label">Sort By</label>
                                            <select class="form-select date-filter">
                                                <option value="all">All Events</option>
                                                <option value="upcoming">Upcoming Events</option>
                                                <option value="newest">Newly Added</option>
                                                <option value="ascending">Date (Ascending)</option>
                                                <option value="descending">Date (Descending)</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Filter by Location</label>
                                            <select class="form-select venue-filter">
                                                <option value="all">All Locations</option>
                                                <option value="marikina">Marikina</option>
                                                <option value="san mateo">San Mateo</option>
                                                <option value="montalban">Montalban</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">First name</th>
                                                <th scope="col">Last name</th>
                                                <th scope="col">Event Date</th>
                                                <th scope="col">Package</th>
                                                <th scope="col">Pax</th>
                                                <th scope="col">Status</th>
                                                <th scope="col">Details</th>
                                                <th scope="col">Options</th>
                                            </tr>
                                        </thead>
                                        <tbody>
 
                                            @forelse ($confirmedReservations as $key => $item)
                                                <tr data-location="{{ \Illuminate\Support\Str::contains($item['package_name'], 'Marikina') ? 'marikina' : 
                                                    (\Illuminate\Support\Str::contains($item['package_name'], 'San Mateo') ? 'san mateo' : 
                                                    (\Illuminate\Support\Str::contains($item['package_name'], 'Montalban') ? 'montalban' : '')) }}"
                                                    data-event-date="{{ \Carbon\Carbon::parse($item['event_date'])->format('Y-m-d') }}"
                                                   data-created="{{ isset($item['created_at']) ? \Carbon\Carbon::parse($item['created_at'])->format('Y-m-d H:i:s') : '' }}">
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $item['first_name'] }}</td>
                                                    <td>{{ $item['last_name'] }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($item['event_date'])->format('F j, Y') }}</td>
                                                    <td>{{ $item['package_name'] }}</td>
                                                    <td>{{ $item['guests_number'] }}</td>
                                                    <td>
                                                        <span class="status-badge {{ strtolower($item['status']) }}">
                                                            {{ ucfirst($item['status']) }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#reservationModal{{ $key }}">
                                                            View Details
                                                        </button>

                                                        <div class="modal fade" id="reservationModal{{ $key }}" tabindex="-1" aria-labelledby="reservationModalLabel{{ $key }}" aria-hidden="true">
                                                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="reservationModalLabel{{ $key }}">Reservation Details for {{ $item['first_name'] }}</h5>
                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <ul class="list-group">
                                                                            <li class="list-group-item"><strong>Address:</strong> 
                                                                                {{ $item['street_houseno'] ?? 'No Street/House No.' }}, 
                                                                                {{ $item['barangay'] ?? 'No Barangay' }}, 
                                                                                {{ $item['city'] ?? 'No City' }}, 
                                                                                {{ $item['province'] ?? 'No Province' }}, 
                                                                                {{ $item['region'] ?? 'No Region' }}
                                                                            </li>
                                                                            <li class="list-group-item"><strong>Phone:</strong> {{ $item['phone'] }}</li>
                                                                            <li class="list-group-item">
                                                                                <strong>Menu Name:</strong> 
                                                                                <span 
                                                                                    data-bs-toggle="tooltip" 
                                                                                    data-bs-html="true"
                                                                                    data-bs-placement="right"
                                                                                    title="{{ isset($item['menu_content']) ? implode(',<br>', array_column($item['menu_content'], 'food')) : 'No menu content available' }}"
                                                                                    style="cursor: pointer;">
                                                                                    {{ $item['menu_name'] ?? 'No Menu Selected' }}
                                                                                </span>
                                                                            </li>
                                                                            <li class="list-group-item"><strong>Venue:</strong> {{ $item['venue'] }}</li>
                                                                            @if(isset($item['package_name']) && \Illuminate\Support\Str::contains($item['package_name'], 'Wedding'))
                                                                                <li class="list-group-item"><strong>Sponsors:</strong> {{ $item['sponsors'] ?? 'No sponsors' }}</li>
                                                                            @endif
                                                                            <li class="list-group-item"><strong>Event Time:</strong> {{ \Carbon\Carbon::parse($item['event_time'])->format('g:i A') }}</li>
                                                                            <li class="list-group-item"><strong>Theme:</strong> {{ $item['theme'] }}</li>
                                                                            <li class="list-group-item"><strong>Other Requests:</strong> {{ $item['other_requests'] ?? 'No requests' }}</li>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <form action="{{url('admin/reservations/finish-reservation/'.$key)}}" method="POST" class="me-2 d-inline">
                                                            @csrf
                                                            @method('PUT')
                                                            <button type="submit" class="btn btn-sm btn-success">Finish</button>
                                                        </form>
                                                        <form action="{{url('admin/reservations/cancel-reservation/'.$key)}}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('PUT')
                                                            <button type="submit" class="btn btn-sm btn-secondary">Cancel</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="3">No Confirmed Reservation Found</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                            </div>
                            <div class="tab-pane fade" id="cancelled" role="tabpanel" aria-labelledby="cancelled-tab">
                                <!-- Filter Controls Template - Will be duplicated for each tab -->
                                <div class="filter-controls mb-3 mt-3">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label class="form-label">Sort By</label>
                                            <select class="form-select date-filter">
                                                <option value="all">All Events</option>
                                                <option value="ascending">Date (Ascending)</option>
                                                <option value="descending">Date (Descending)</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Filter by Location</label>
                                            <select class="form-select venue-filter">
                                                <option value="all">All Locations</option>
                                                <option value="marikina">Marikina</option>
                                                <option value="san mateo">San Mateo</option>
                                                <option value="montalban">Montalban</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">First name</th>
                                                <th scope="col">Last name</th>
                                                <th scope="col">Event Date</th>
                                                <th scope="col">Package</th>
                                                <th scope="col">Pax</th>
                                                <th scope="col">Status</th>
                                                <th scope="col">Details</th>
                                                <th scope="col">Options</th>
                                            </tr>
                                        </thead>
                                        <tbody>
 
                                            @forelse ($cancelledReservations as $key => $item)
                                                <tr data-location="{{ \Illuminate\Support\Str::contains($item['package_name'], 'Marikina') ? 'marikina' : 
                                                    (\Illuminate\Support\Str::contains($item['package_name'], 'San Mateo') ? 'san mateo' : 
                                                    (\Illuminate\Support\Str::contains($item['package_name'], 'Montalban') ? 'montalban' : '')) }}"
                                                    data-event-date="{{ \Carbon\Carbon::parse($item['event_date'])->format('Y-m-d') }}"
                                                    data-created="{{ isset($item['created_at']) ? \Carbon\Carbon::parse($item['created_at'])->format('Y-m-d H:i:s') : '' }}">
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $item['first_name'] }}</td>
                                                    <td>{{ $item['last_name'] }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($item['event_date'])->format('F j, Y') }}</td>
                                                    <td>{{ $item['package_name'] }}</td>
                                                    <td>{{ $item['guests_number'] }}</td>
                                                    <td>
                                                        <span class="status-badge {{ strtolower($item['status']) }}">
                                                            {{ ucfirst($item['status']) }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#reservationModal{{ $key }}">
                                                            View Details
                                                        </button>

                                                        <div class="modal fade" id="reservationModal{{ $key }}" tabindex="-1" aria-labelledby="reservationModalLabel{{ $key }}" aria-hidden="true">
                                                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="reservationModalLabel{{ $key }}">Reservation Details for {{ $item['first_name'] }}</h5>
                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <ul class="list-group">
                                                                            <li class="list-group-item"><strong>Address:</strong> 
                                                                                {{ $item['street_houseno'] ?? 'No Street/House No.' }}, 
                                                                                {{ $item['barangay'] ?? 'No Barangay' }}, 
                                                                                {{ $item['city'] ?? 'No City' }}, 
                                                                                {{ $item['province'] ?? 'No Province' }}, 
                                                                                {{ $item['region'] ?? 'No Region' }}
                                                                            </li>
                                                                            <li class="list-group-item"><strong>Phone:</strong> {{ $item['phone'] }}</li>
                                                                            <li class="list-group-item">
                                                                                <strong>Menu Name:</strong> 
                                                                                <span 
                                                                                    data-bs-toggle="tooltip" 
                                                                                    data-bs-html="true"
                                                                                    data-bs-placement="right"
                                                                                    title="{{ isset($item['menu_content']) ? implode(',<br>', array_column($item['menu_content'], 'food')) : 'No menu content available' }}"
                                                                                    style="cursor: pointer;">
                                                                                    {{ $item['menu_name'] ?? 'No Menu Selected' }}
                                                                                </span>
                                                                            </li>
                                                                            <li class="list-group-item"><strong>Venue:</strong> {{ $item['venue'] }}</li>
                                                                            @if(isset($item['package_name']) && \Illuminate\Support\Str::contains($item['package_name'], 'Wedding'))
                                                                                <li class="list-group-item"><strong>Sponsors:</strong> {{ $item['sponsors'] ?? 'No sponsors' }}</li>
                                                                            @endif
                                                                            <li class="list-group-item"><strong>Event Time:</strong> {{ \Carbon\Carbon::parse($item['event_time'])->format('g:i A') }}</li>
                                                                            <li class="list-group-item"><strong>Theme:</strong> {{ $item['theme'] }}</li>
                                                                            <li class="list-group-item"><strong>Other Requests:</strong> {{ $item['other_requests'] ?? 'No requests' }}</li>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <!-- Form -->
                                                        <form id="archiveForm" action="{{ url('admin/reservations/archive-reservation'. $key) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('PUT')
                                                            <button type="button" class="btn btn-sm btn-secondary" 
                                                                    data-bs-toggle="modal" 
                                                                    data-bs-target="#archiveModal" 
                                                                    data-id="{{ $key }}" 
                                                                    data-name="{{ $item['package_name'] }}">
                                                                Archive
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="3">No Cancelled Reservation Found</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>

                           <!-- Modal -->
                            <div class="modal fade" id="archiveModal" tabindex="-1" aria-labelledby="archiveModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="archiveModalLabel">Confirm Archive</h5>
                                        </div>
                                        <div class="modal-body">
                                            Are you sure you want to archive <strong id="userName"></strong>?
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <!-- Confirm button with explicit form submission -->
                                            <button type="button" class="btn btn-danger" id="confirmArchiveButton">Archive</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            </div>
                            <div class="tab-pane fade" id="finished" role="tabpanel" aria-labelledby="finished-tab">
                                <!-- Filter Controls Template - Will be duplicated for each tab -->
                                <div class="filter-controls mb-3 mt-3">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label class="form-label">Sort By</label>
                                            <select class="form-select date-filter">
                                                <option value="all">All Events</option>
                                                <option value="ascending">Date (Ascending)</option>
                                                <option value="descending">Date (Descending)</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Filter by Location</label>
                                            <select class="form-select venue-filter">
                                                <option value="all">All Locations</option>
                                                <option value="marikina">Marikina</option>
                                                <option value="san mateo">San Mateo</option>
                                                <option value="montalban">Montalban</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">First name</th>
                                                <th scope="col">Last name</th>
                                                <th scope="col">Event Date</th>
                                                <th scope="col">Package</th>
                                                <th scope="col">Pax</th>
                                                <th scope="col">Status</th>
                                                <th scope="col">Details</th>
                                                <th scope="col">Options</th>
                                            </tr>
                                        </thead>
                                        <tbody>
 
                                            @forelse ($finishedReservations as $key => $item)
                                                <tr data-location="{{ \Illuminate\Support\Str::contains($item['package_name'], 'Marikina') ? 'marikina' : 
                                                    (\Illuminate\Support\Str::contains($item['package_name'], 'San Mateo') ? 'san mateo' : 
                                                    (\Illuminate\Support\Str::contains($item['package_name'], 'Montalban') ? 'montalban' : '')) }}"
                                                    data-event-date="{{ \Carbon\Carbon::parse($item['event_date'])->format('Y-m-d') }}"
                                                    data-created="{{ isset($item['created_at']) ? \Carbon\Carbon::parse($item['created_at'])->format('Y-m-d H:i:s') : '' }}">
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $item['first_name'] }}</td>
                                                    <td>{{ $item['last_name'] }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($item['event_date'])->format('F j, Y') }}</td>
                                                    <td>{{ $item['package_name'] }}</td>
                                                    <td>{{ $item['guests_number'] }}</td>
                                                    <td>
                                                        <span class="status-badge {{ strtolower($item['status']) }}">
                                                            {{ ucfirst($item['status']) }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#reservationModal{{ $key }}">
                                                            View Details
                                                        </button>

                                                        <div class="modal fade" id="reservationModal{{ $key }}" tabindex="-1" aria-labelledby="reservationModalLabel{{ $key }}" aria-hidden="true">
                                                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="reservationModalLabel{{ $key }}">Reservation Details for {{ $item['first_name'] }}</h5>
                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <ul class="list-group">
                                                                            <li class="list-group-item"><strong>Address:</strong> 
                                                                                {{ $item['street_houseno'] ?? 'No Street/House No.' }}, 
                                                                                {{ $item['barangay'] ?? 'No Barangay' }}, 
                                                                                {{ $item['city'] ?? 'No City' }}, 
                                                                                {{ $item['province'] ?? 'No Province' }}, 
                                                                                {{ $item['region'] ?? 'No Region' }}
                                                                            </li>
                                                                            <li class="list-group-item"><strong>Phone:</strong> {{ $item['phone'] }}</li>
                                                                            <li class="list-group-item">
                                                                                <strong>Menu Name:</strong> 
                                                                                <span 
                                                                                    data-bs-toggle="tooltip" 
                                                                                    data-bs-html="true"
                                                                                    data-bs-placement="right"
                                                                                    title="{{ isset($item['menu_content']) ? implode(',<br>', array_column($item['menu_content'], 'food')) : 'No menu content available' }}"
                                                                                    style="cursor: pointer;">
                                                                                    {{ $item['menu_name'] ?? 'No Menu Selected' }}
                                                                                </span>
                                                                            </li>
                                                                            <li class="list-group-item"><strong>Venue:</strong> {{ $item['venue'] }}</li>
                                                                            @if(isset($item['package_name']) && \Illuminate\Support\Str::contains($item['package_name'], 'Wedding'))
                                                                                <li class="list-group-item"><strong>Sponsors:</strong> {{ $item['sponsors'] ?? 'No sponsors' }}</li>
                                                                            @endif
                                                                            <li class="list-group-item"><strong>Event Time:</strong> {{ \Carbon\Carbon::parse($item['event_time'])->format('g:i A') }}</li>
                                                                            <li class="list-group-item"><strong>Theme:</strong> {{ $item['theme'] }}</li>
                                                                            <li class="list-group-item"><strong>Other Requests:</strong> {{ $item['other_requests'] ?? 'No requests' }}</li>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex">
                                                        <form id="archiveFormFinished" action="{{ url('admin/reservations/archive-reservation/' . $key) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('PUT')
                                                            <button type="button" class="btn btn-sm btn-secondary" 
                                                                    data-bs-toggle="modal" 
                                                                    data-bs-target="#archiveModalFinished" 
                                                                    data-id="{{ $key }}" 
                                                                    data-name="{{ $item['package_name'] }}">
                                                                Archive
                                                            </button>
                                                        </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="3">No Finished Reservation Found</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                        <!-- Modal for Finished Reservation -->
                                    <div class="modal fade" id="archiveModalFinished" tabindex="-1" aria-labelledby="archiveModalLabelFinished" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="archiveModalLabelFinished">Confirm Archive</h5>
                                                </div>
                                                <div class="modal-body">
                                                    Are you sure you want to archive <strong id="userNameFinished"></strong>?
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <!-- Confirm button with explicit form submission -->
                                                    <button type="button" class="btn btn-danger" id="confirmArchiveButtonFinished">Archive</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <div class="tab-pane fade" id="penbook" role="tabpanel" aria-labelledby="penbook-tab">
                                <!-- Filter Controls Template - Will be duplicated for each tab -->
                                <div class="filter-controls mb-3 mt-3">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label class="form-label">Sort By</label>
                                            <select class="form-select date-filter">
                                                <option value="all">All Events</option>
                                                <option value="upcoming">Upcoming Events</option>
                                                <option value="newest">Newly Added</option>
                                                <option value="ascending">Date (Ascending)</option>
                                                <option value="descending">Date (Descending)</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Filter by Location</label>
                                            <select class="form-select venue-filter">
                                                <option value="all">All Locations</option>
                                                <option value="marikina">Marikina</option>
                                                <option value="san mateo">San Mateo</option>
                                                <option value="montalban">Montalban</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">First name</th>
                                                <th scope="col">Last name</th>
                                                <th scope="col">Event Date</th>
                                                <th scope="col">Package</th>
                                                <th scope="col">Pax</th>
                                                <th scope="col">Status</th>
                                                <th scope="col">Details</th>
                                                <th scope="col">Options</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($pencilReservations as $key => $item)
                                                <tr data-location="{{ \Illuminate\Support\Str::contains($item['package_name'], 'Marikina') ? 'marikina' : 
                                                    (\Illuminate\Support\Str::contains($item['package_name'], 'San Mateo') ? 'san mateo' : 
                                                    (\Illuminate\Support\Str::contains($item['package_name'], 'Montalban') ? 'montalban' : '')) }}"
                                                    data-event-date="{{ \Carbon\Carbon::parse($item['event_date'])->format('Y-m-d') }}"
                                                    data-created="{{ isset($item['created_at']) ? \Carbon\Carbon::parse($item['created_at'])->format('Y-m-d H:i:s') : '' }}">
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $item['first_name'] }}</td>
                                                    <td>{{ $item['last_name'] }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($item['event_date'])->format('F j, Y') }}</td>
                                                    <td>{{ $item['package_name'] }}</td>
                                                    <td>{{ $item['guests_number'] }}</td>
                                                    <td>
                                                        <span class="status-badge {{ strtolower($item['status']) }}">
                                                            {{ ucfirst($item['status']) }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <!-- Reservation Modal Trigger -->
                                                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#reservationModal{{ $key }}">
                                                            View Details
                                                        </button>

                                                        <!-- Reservation Modal -->
                                                        <div class="modal fade" id="reservationModal{{ $key }}" tabindex="-1" aria-labelledby="reservationModalLabel{{ $key }}" aria-hidden="true">
                                                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="reservationModalLabel{{ $key }}">Reservation Details for {{ $item['first_name'] }}</h5>
                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <ul class="list-group">
                                                                            <li class="list-group-item"><strong>Address:</strong> 
                                                                                {{ $item['street_houseno'] ?? 'No Street/House No.' }}, 
                                                                                {{ $item['barangay'] ?? 'No Barangay' }}, 
                                                                                {{ $item['city'] ?? 'No City' }}, 
                                                                                {{ $item['province'] ?? 'No Province' }}, 
                                                                                {{ $item['region'] ?? 'No Region' }}
                                                                            </li>
                                                                            <li class="list-group-item"><strong>Phone:</strong> {{ $item['phone'] }}</li>
                                                                            <li class="list-group-item"><strong>Menu Name:</strong> {{ $item['menu_name'] ?? 'No Menu Selected' }}</li>
                                                                            <li class="list-group-item"><strong>Venue:</strong> {{ $item['venue'] }}</li>
                                                                            <li class="list-group-item"><strong>Event Time:</strong> {{ \Carbon\Carbon::parse($item['event_time'])->format('g:i A') }}</li>
                                                                            <li class="list-group-item"><strong>Theme:</strong> {{ $item['theme'] }}</li>
                                                                            <li class="list-group-item"><strong>Other Requests:</strong> {{ $item['other_requests'] ?? 'No requests' }}</li>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <form action="{{url('admin/reservations/confirm-pencil/'.$key)}}" method="POST" class="me-2 d-inline">
                                                            @csrf
                                                            @method('PUT')
                                                            <button type="submit" class="btn btn-sm btn-success">Confirm</button>
                                                        </form>
                                                        <form action="{{url('admin/reservations/cancel-pencil/'.$key)}}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('PUT')
                                                            <button type="submit" class="btn btn-sm btn-secondary">Cancel</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="3">No Pencil Reservation Found</td>
                                                </tr>
                                            @endforelse
                                        </tbody>

                                        </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .tooltip-text[data-bs-toggle="tooltip"] {
        white-space: nowrap; /* Prevents text from wrapping */
        overflow: hidden;    /* Ensures content overflows are hidden */
        text-overflow: ellipsis; /* Adds ellipsis if text is too long */
        max-width: 200px; /* Set max width for tooltip content */
    }
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<script>
    $(function () {
        $('[data-toggle="tooltip"]').tooltip();
    });

    document.addEventListener('DOMContentLoaded', function () {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    });
</script>

<!-- Script for the Archive Button -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const archiveButtons = document.querySelectorAll('[data-bs-target="#archiveModal"]');
    const userNameField = document.getElementById('userName');
    const archiveForm = document.getElementById('archiveForm');
    const confirmArchiveButton = document.getElementById('confirmArchiveButton');

    // Update modal data when clicking the Archive button
    archiveButtons.forEach(button => {
        button.addEventListener('click', function () {
            const userName = this.getAttribute('data-name'); // Get package name
            const reservationID = this.getAttribute('data-id'); // Get reservation ID
            
            // Populate modal with package name
            userNameField.textContent = userName;

            // Update form action URL
            archiveForm.action = `{{ url('admin/reservations/archive-reservation') }}/${reservationID}`;
        });
    });

    // Submit form when Confirm button is clicked
    confirmArchiveButton.addEventListener('click', function () {
        archiveForm.submit();
    });
});
</script>

<!-- Script for the Archive Button -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const archiveButtonsFinished = document.querySelectorAll('[data-bs-target="#archiveModalFinished"]');
    const userNameFieldFinished = document.getElementById('userNameFinished');
    const archiveFormFinished = document.getElementById('archiveFormFinished');
    const confirmArchiveButtonFinished = document.getElementById('confirmArchiveButtonFinished');

    // Update modal data when clicking the Archive button
    archiveButtonsFinished.forEach(button => {
        button.addEventListener('click', function () {
            const userName = this.getAttribute('data-name'); // Get package name
            const reservationID = this.getAttribute('data-id'); // Get reservation ID
            
            // Populate modal with package name
            userNameFieldFinished.textContent = userName;

            // Update form action URL
            archiveFormFinished.action = `{{ url('admin/reservations/archive-reservation') }}/${reservationID}`;
        });
    });

    // Submit form when Confirm button is clicked
    confirmArchiveButtonFinished.addEventListener('click', function () {
        archiveFormFinished.submit();
    });
});

</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Get the tab parameter from URL
    const urlParams = new URLSearchParams(window.location.search);
    const activeTab = urlParams.get('tab');

    if (activeTab) {
        // Remove active class from all tabs
        document.querySelectorAll('.nav-link').forEach(tab => {
            tab.classList.remove('active');
        });
        document.querySelectorAll('.tab-pane').forEach(pane => {
            pane.classList.remove('active', 'show');
        });

        // Activate the correct tab
        let tabButton;
        let tabContent;
        
        switch(activeTab) {
            case 'pending':
                tabButton = document.querySelector('#pending-reservation');
                tabContent = document.querySelector('#pending');
                break;
            case 'confirmed':
                tabButton = document.querySelector('#confirmed-reservation');
                tabContent = document.querySelector('#confirmed');
                break;
            case 'cancelled':
                tabButton = document.querySelector('#cancelled-reservation');
                tabContent = document.querySelector('#cancelled');
                break;
            case 'finished':
                tabButton = document.querySelector('#finished-reservation');
                tabContent = document.querySelector('#finished');
                break;
            case 'penbook':
                tabButton = document.querySelector('#pen-book');
                tabContent = document.querySelector('#penbook');
                break;
            default:
                tabButton = document.querySelector('#pen-book');
                tabContent = document.querySelector('#penbook');
        }

        if (tabButton && tabContent) {
            tabButton.classList.add('active');
            tabContent.classList.add('active', 'show');
        }
    }

    // Keep your existing event listeners for tab clicks
    document.querySelectorAll('.nav-link').forEach(tab => {
        tab.addEventListener('click', function(e) {
            const tabId = this.getAttribute('data-bs-target').replace('#', '');
            const params = new URLSearchParams(window.location.search);
            params.set('tab', tabId);
            history.pushState(null, '', `?${params.toString()}`);
        });
    });
});yt
</script>

<style>
.filter-controls {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 5px;
    margin-bottom: 20px;
}

.filter-controls select {
    width: 100%;
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 4px;
}

.tooltip-text[data-bs-toggle="tooltip"] {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 200px;
}

.status-badge {
    padding: 5px 10px;
    border-radius: 15px;
    font-size: 0.875rem;
}
</style>

@vite('resources/js/filter.js')

@endsection