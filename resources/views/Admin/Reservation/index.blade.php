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
                    <a href="#" class="btn btn-secondary">Add Pencil Reservation</a>
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
                                <button class="nav-link active" id="pending-reservation" data-bs-toggle="tab" data-bs-target="#pending" type="button" role="tab" aria-controls="pending" aria-selected="true">Pending Reservations</button>
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

                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="pen-book" data-bs-toggle="tab" data-bs-target="#penbook" type="button" role="tab" aria-controls="penbook" aria-selected="true">Pencil Reservations</button>
                            </li>

                        </ul>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="pending" role="tabpanel" aria-labelledby="pending-tab">
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
                                                            <div class="modal-dialog">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="reservationModalLabel{{ $key }}">Reservation Details for {{ $item['first_name'] }}</h5>
                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <ul>
                                                                            <li><strong>Address:</strong> {{ $item['address'] }}</li>
                                                                            <li><strong>Phone:</strong> {{ $item['phone'] }}</li>
                                                                            <li><strong>Venue:</strong> {{ $item['venue'] }}</li>
                                                                            <li><strong>Event Time:</strong> {{ \Carbon\Carbon::parse($item['event_time'])->format('g:i A') }}</li>
                                                                            <li><strong>Theme:</strong> {{ $item['theme'] }}</li>
                                                                            <li><strong>Other Requests:</strong> {{ $item['other_requests'] ?? 'No requests' }}</li>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex">
                                                            <form action="{{url('admin/reservations/confirm-reservation/'.$key)}}" method="POST">
                                                            @csrf
                                                            @method('PUT')
                                                                <button type="submit" class="btn btn-sm btn-success me-2">Confirm</button>
                                                            </form>
                                                            <a href="#" class="btn btn-sm btn-secondary">Cancel</a>
                                                        </div>
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
                                                            <div class="modal-dialog">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="reservationModalLabel{{ $key }}">Reservation Details for {{ $item['first_name'] }}</h5>
                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <ul>
                                                                            <li><strong>Address:</strong> {{ $item['address'] }}</li>
                                                                            <li><strong>Phone:</strong> {{ $item['phone'] }}</li>
                                                                            <li><strong>Venue:</strong> {{ $item['venue'] }}</li>
                                                                            <li><strong>Event Time:</strong> {{ \Carbon\Carbon::parse($item['event_time'])->format('g:i A') }}</li>
                                                                            <li><strong>Theme:</strong> {{ $item['theme'] }}</li>
                                                                            <li><strong>Other Requests:</strong> {{ $item['other_requests'] }}</li>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex">
                                                            <form action="{{url('admin/reservations/finish-reservation/'.$key)}}" method="POST">
                                                            @csrf
                                                            @method('PUT')
                                                                <button type="submit" class="btn btn-sm btn-success me-2">Finish</button>
                                                            </form>
                                                            <a href="#" class="btn btn-sm btn-secondary">Cancel</a>
                                                        </div>
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
                                                    <td>{{ $item['email'] }}</td>
                                                    <td>
                                                        <span class="status-badge {{ strtolower($item['status']) }}">
                                                            {{ ucfirst($item['status']) }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex">
                                                            <a href="{{ url('admin/services/edit-service/' . $key) }}" class="btn btn-sm btn-success mr-2">Edit</a>
                                                            <a href="{{ url('admin/services/delete-service/' . $key) }}" class="btn btn-sm btn-secondary">Archive</a>
                                                        </div>
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
                                                    <td>{{ $item['email'] }}</td>
                                                    <td>
                                                        <span class="status-badge {{ strtolower($item['status']) }}">
                                                            {{ ucfirst($item['status']) }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex">
                                                            <a href="{{ url('admin/services/edit-service/' . $key) }}" class="btn btn-sm btn-success me-2">Edit</a>
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
                            <div class="tab-pane fade" id="penbook" role="tabpanel" aria-labelledby="penbook-tab">
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
                                                    <td>{{ $item['email'] }}</td>
                                                    <td>{{ $item['reserve_type'] }}</td>
                                                    <td>
                                                        <div class="d-flex">
                                                            <a href="{{ url('admin/services/edit-service/' . $key) }}" class="btn btn-sm btn-success mr-2">Edit</a>
                                                            <a href="{{ url('admin/services/delete-service/' . $key) }}" class="btn btn-sm btn-secondary">Archive</a>
                                                        </div>
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