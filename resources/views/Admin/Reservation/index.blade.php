@extends('layouts.adminLayout')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Reservation</h1>
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
                                <button class="nav-link active" id="pen-book" data-bs-toggle="tab" data-bs-target="#penbook" type="button" role="tab" aria-controls="penbook" aria-selected="true">Pencil Reservations</button>
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
 
                                            @forelse ($pendingReservations as $key => $item)
                                                <tr>
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
                                                                            <li class="list-group-item"><strong>Address:</strong> {{ $item['address'] }}</li>
                                                                            <li class="list-group-item"><strong>Phone:</strong> {{ $item['phone'] }}</li>
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
                                                <tr>
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
                                                                            <li class="list-group-item"><strong>Address:</strong> {{ $item['address'] }}</li>
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
                                                <tr>
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
                                                                            <li class="list-group-item"><strong>Address:</strong> {{ $item['address'] }}</li>
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
                                                        <form action="{{url('admin/reservations/cancel-reservation/'.$key)}}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('PUT')
                                                            <button type="submit" class="btn btn-sm btn-secondary">Archive</button>
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
                            </div>
                            <div class="tab-pane fade" id="finished" role="tabpanel" aria-labelledby="finished-tab">
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
                                                <tr>
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
                                                                            <li class="list-group-item"><strong>Address:</strong> {{ $item['address'] }}</li>
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
                                                            <a href="{{ url('admin/services/delete-service/' . $key) }}" class="btn btn-sm btn-secondary">Archive</a>
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
                            </div>
                            <div class="tab-pane fade show active" id="penbook" role="tabpanel" aria-labelledby="penbook-tab">
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
                                                <tr>
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
                                                                            <li class="list-group-item"><strong>Address:</strong> {{ $item['address'] }}</li>
                                                                            <li class="list-group-item"><strong>Phone:</strong> {{ $item['phone'] }}</li>
                                                                            <li class="list-group-item">
                                                                                <strong>Menu Name:</strong> 
                                                                                <span 
                                                                                    data-bs-toggle="tooltip" 
                                                                                    data-bs-html="true"
                                                                                    data-bs-placement="right"
                                                                                    title="{{ isset($item['menu_content']) ? implode('<br>', array_column($item['menu_content'], 'food')) : 'No menu content available' }}"
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

@endsection

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