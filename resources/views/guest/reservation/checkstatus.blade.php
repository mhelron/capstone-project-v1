@extends('layouts.guestlayout')

@section('content')
<div class="content-header" style="padding-top: 80px;">
    <div class="container">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0"></h1>
            </div>
        </div>
    </div>
</div>

<div class="container py-4" style="padding-top: 80px;">
    <div class="row justify-content-center">
        <div class="col-12">
            <!-- Check Status Form -->
            <div class="card shadow-sm mb-4">
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <h2 class="fw-bold">Check Reservation Status</h2>
                        <p class="text-muted small">Enter your reference number to view your reservation details</p>
                    </div>

                    <form method="POST" action="/check-status">
                        @csrf
                        <div class="row justify-content-center">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="reference_number" class="form-label">Reference Number</label>
                                    <input type="text" 
                                        class="form-control" 
                                        id="reference_number" 
                                        name="reference_number" 
                                        value="{{ old('reference_number', request('reference_number')) }}" 
                                        placeholder="Enter your 12-digit reference number">
                                    @if ($errors->has('reference_number'))
                                        <small class="text-danger">{{ $errors->first('reference_number') }}</small>
                                    @endif
                                </div>

                                <button type="submit" class="btn btn-darkorange w-100">
                                    Check Status
                                </button>
                            </div>
                        </div>
                    </form>

                    @if(session('error'))
                    <div class="alert alert-danger mt-3" role="alert">
                        {{ session('error') }}
                    </div>
                    @endif
                </div>
            </div>

            <!-- Reservation Details -->
            @if(isset($reservation))
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <h3 class="fw-bold mb-4">Reservation Details</h3>

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tbody>
                                <!-- Basic Info -->
                                <tr>
                                    <th colspan="2" class="table-light">Personal Information</th>
                                </tr>
                                <tr>
                                    <th width="30%">Reference Number</th>
                                    <td>{{ $reservation['reference_number'] }}</td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td><span class="badge status-badge {{ strtolower($reservation['status']) }}">{{ $reservation['status'] }}</span></td>
                                </tr>
                                <tr>
                                    <th>Created At</th>
                                    <td>{{ \Carbon\Carbon::parse($reservation['created_at'])->format('M d, Y h:i A') }}</td>
                                </tr>
                                
                                <!-- Personal Information -->
                                <tr>
                                    <th colspan="2" class="table-light">Personal Information</th>
                                </tr>
                                <tr>
                                    <th>Name</th>
                                    <td>{{ $reservation['first_name'] }} {{ $reservation['last_name'] }}</td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td>{{ $reservation['email'] }}</td>
                                </tr>
                                <tr>
                                    <th>Phone</th>
                                    <td>{{ $reservation['phone'] }}</td>
                                </tr>
                                <tr>
                                    <th>Address</th>
                                    <td>{{ $reservation['street_houseno'] }}, {{ $reservation['barangay'] }}, {{ $reservation['city'] }}, {{ $reservation['province'] }}, {{ $reservation['region'] }}</td>
                                </tr>
                                
                                <!-- Event Details -->
                                <tr>
                                    <th colspan="2" class="table-light">Event Details</th>
                                </tr>
                                <tr>
                                    <th>Event Title</th>
                                    <td>{{ $reservation['event_title'] }}</td>
                                </tr>
                                <tr>
                                    <th>Date</th>
                                    <td>{{ $reservation['event_date'] }}</td>
                                </tr>
                                <tr>
                                    <th>Time</th>
                                    <td>{{ $reservation['event_time'] }}</td>
                                </tr>
                                <tr>
                                    <th>Venue</th>
                                    <td>{{ $reservation['venue'] }}</td>
                                </tr>
                                <tr>
                                    <th>Theme</th>
                                    <td>{{ $reservation['theme'] }}</td>
                                </tr>
                                <tr>
                                    <th>Number of Guests</th>
                                    <td>{{ $reservation['guests_number'] }}</td>
                                </tr>
                                
                                <!-- Package and Menu Details -->
                                <tr>
                                    <th colspan="2" class="table-light">Package and Menu Details</th>
                                </tr>
                                <tr>
                                    <th>Package</th>
                                    <td>{{ $reservation['package_name'] }}</td>
                                </tr>
                                <tr>
                                    <th>Menu</th>
                                    <td>{{ $reservation['menu_name'] }}</td>
                                </tr>
                                <tr>
                                    <th>Menu Content:</th>
                                    <td>
                                        @if(isset($reservation['menu_content']) && count($reservation['menu_content']) > 0)
                                            <table class="table table-sm table-borderless mb-0">
                                                @foreach ($reservation['menu_content'] as $item)
                                                    <tr>
                                                        <td><strong>{{ $item['category'] }}:</strong></td>
                                                        <td>{{ $item['food'] }}</td>
                                                    </tr>
                                                @endforeach
                                            </table>
                                        @else
                                            <p>No menu content available.</p>
                                        @endif
                                    </td>
                                </tr>
                                
                                <!-- Additional Requests -->
                                <tr>
                                    <th colspan="2" class="table-light">Additional Requests</th>
                                </tr>
                                <tr>
                                    <td colspan="2">{{ $reservation->other_requests ?? 'No Request' }}</td>
                                </tr>

                                
                                <!-- Payment Information -->
                                <tr>
                                    <th colspan="2" class="table-light">Payment Information</th>
                                </tr>
                                <tr>
                                    <th>Reservation Fee</th>
                                    <td>₱5,000.00</td>
                                </tr>
                                <tr>
                                    <th>Payment Status of Reservation Fee</th>
                                    <td>
                                    <span class="badge 
                                        {{ 
                                            $reservation['payment_status'] === 'Paid' ? 'bg-success' : 
                                            ($reservation['payment_status'] === 'Pending' ? 'bg-warning' : 'bg-danger') 
                                        }}">
                                        {{ $reservation['payment_status'] }}
                                    </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Payment Date of Reservation Fee</th>
                                    <td>{{ $reservation['payment_submitted_at'] ? \Carbon\Carbon::parse($reservation['payment_submitted_at'])->format('M d, Y h:i A') : 'None' }}</td>
                                </tr>
                                <tr>
                                    <th>Reservation Price</th>
                                    <td>₱{{ number_format($reservation['total_price'], 2) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex gap-2 justify-content-center mt-4">
                        @if(!in_array($reservation['status'], ['Cancelled', 'Finished']))
                            @if($reservation['payment_status'] !== 'Paid' && $reservation['payment_status'] !== 'Pending')
                                <a href="{{ route('guest.payment', ['reservation_id' => $reservation['reservation_id']]) }}" 
                                    class="btn btn-success">
                                    Make Payment
                                </a>
                            @endif

                            @if(in_array($reservation['status'], ['Pencil', 'Pending', 'Confirmed']))
                                <a href="{{ route('guest.reserve.edit', ['reservation_id' => $reservation['reservation_id']]) }}" 
                                    class="btn btn-primary">
                                    Edit Details
                                </a>
                            @endif

                            @if(in_array($reservation['status'], ['Pencil', 'Pending', 'Confirmed']))
                                <form action="{{ route('reservation.cancel', ['reservation_id' => $reservation['reservation_id']]) }}" 
                                    method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#cancelModal">
                                        Cancel Reservation
                                    </button>
                                </form>
                            @endif
                        @endif
                    </div>
                </div>
            </div>

            <!-- Cancel Confirmation Modal -->
            <div class="modal fade" id="cancelModal" tabindex="-1" aria-labelledby="cancelModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="cancelModalLabel">Confirm Cancellation</h5>
                        </div>
                        <form action="{{ route('reservation.cancel', ['reservation_id' => $reservation['reservation_id']]) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <div class="modal-body">
                                <p>Are you sure you want to cancel this reservation? This action cannot be undone.</p>
                                
                                <div class="mb-3">
                                    <label for="cancellation_reason" class="form-label">Reason for Cancellation</label>
                                    <select class="form-select mb-3" id="cancellation_reason" name="cancellation_reason" required>
                                        <option value="" disabled selected>Select a reason...</option>
                                        <option value="Change in Event Plans">Change in Event Plans</option>
                                        <option value="Budget Issues">Budget Issues</option>
                                        <option value="Found Another Caterer">Found Another Caterer</option>
                                        <option value="Event Postponed">Event Postponed</option>
                                        <option value="other">Other (Please specify)</option>
                                    </select>
                                    
                                    <!-- Other reason textarea - hidden by default -->
                                    <div id="otherReasonDiv" style="display: none;">
                                        <textarea 
                                            class="form-control" 
                                            id="other_reason" 
                                            name="other_reason" 
                                            rows="3" 
                                            placeholder="Please specify your reason..."></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-danger">Confirm Cancellation</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<script>
document.getElementById('cancellation_reason').addEventListener('change', function() {
    const otherReasonDiv = document.getElementById('otherReasonDiv');
    const otherReasonInput = document.getElementById('other_reason');
    
    if (this.value === 'other') {
        otherReasonDiv.style.display = 'block';
        otherReasonInput.required = true;
    } else {
        otherReasonDiv.style.display = 'none';
        otherReasonInput.required = false;
    }
});
</script>

<style>
    .status-badge {
        padding: 0.5em 0.75em;
        border: 1px solid;
        border-radius: 0.25rem;
        font-weight: 500;
    }

    .status-badge.pending {
        color: #ffa500;
        background-color: rgba(255, 165, 0, 0.2);
        border-color: #ffa500;
    }

    .status-badge.confirmed {
        color: #28a745;
        background-color: rgba(40, 167, 69, 0.2);
        border-color: #28a745;
    }

    .status-badge.cancelled {
        color: #dc3545;
        background-color: rgba(220, 53, 69, 0.2);
        border-color: #dc3545;
    }

    .status-badge.finished {
        color: #007bff;
        background-color: rgba(0, 123, 255, 0.2);
        border-color: #007bff;
    }

    .status-badge.pencil {
        color: #6c757d;
        background-color: rgba(108, 117, 125, 0.2);
        border-color: #6c757d;
    }

    .table th {
    background-color: #f8f9fa;
    vertical-align: middle;
    
    }
    .table-light {
        background-color: #e9ecef !important;
        font-weight: bold;
    }
</style>
@endsection