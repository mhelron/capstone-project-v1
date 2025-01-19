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
            <!-- Add Quotation Button -->
            <div class="d-flex justify-content-end">
                <a href="{{ route('guest.quotation.create') }}" class="btn btn-darkorange mb-2">Create Quotation Request</a>
            </div>

            <!-- Check Status Form -->
            <div class="card shadow-sm mb-4">
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <h2 class="fw-bold">Check Quotation Status</h2>
                        <p class="text-muted small">Enter your reference number to view your quotation details</p>
                    </div>

                    <form method="POST" action="{{ route('guest.quotation.check.submit') }}">
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

            <!-- Quotation Details -->
            @if(isset($quotation))
                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <h3 class="fw-bold mb-4">Quotation Details</h3>

                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tbody>
                                    <!-- Reference Information -->
                                    <tr>
                                        <th colspan="2" class="table-light">Reference Information</th>
                                    </tr>
                                    <tr>
                                        <th width="30%">Reference Number</th>
                                        <td>{{ $quotation['reference_number'] }}</td>
                                    </tr>
                                    <tr>
                                        <th>Status</th>
                                        <td><span class="badge status-badge {{ strtolower($quotation['status']) }}">{{ $quotation['status'] }}</span></td>
                                    </tr>
                                    <tr>
                                        <th>Created At</th>
                                        <td>{{ \Carbon\Carbon::parse($quotation['created_at'])->format('M d, Y h:i A') }}</td>
                                    </tr>
                                    
                                    <!-- Personal Information -->
                                    <tr>
                                        <th colspan="2" class="table-light">Personal Information</th>
                                    </tr>
                                    <tr>
                                        <th>Name</th>
                                        <td>{{ $quotation['firstname'] }} {{ $quotation['lastname'] }}</td>
                                    </tr>
                                    <tr>
                                        <th>Email</th>
                                        <td>{{ $quotation['email'] }}</td>
                                    </tr>
                                    <tr>
                                        <th>Phone</th>
                                        <td>{{ $quotation['phone'] }}</td>
                                    </tr>

                                    <!-- Event Details -->
                                    <tr>
                                        <th colspan="2" class="table-light">Event Details</th>
                                    </tr>
                                    <tr>
                                        <th>Event</th>
                                        <td>{{ $quotation['event'] }}</td>
                                    </tr>
                                    <tr>
                                        <th>Event Date</th>
                                        <td>{{ \Carbon\Carbon::parse($quotation['event_date'])->format('M d, Y') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Event Time</th>
                                        <td>{{ $quotation['event_time'] }}</td>
                                    </tr>
                                    <tr>
                                        <th>Venue</th>
                                        <td>{{ $quotation['venue'] }}</td>
                                    </tr>
                                    <tr>
                                        <th>Theme</th>
                                        <td>{{ $quotation['theme'] }}</td>
                                    </tr>
                                    <tr>
                                        <th>Number of Guests</th>
                                        <td>{{ $quotation['guest_count'] }}</td>
                                    </tr>

                                    <!-- Package Details -->
                                    <tr>
                                        <th colspan="2" class="table-light">Package Details</th>
                                    </tr>
                                    <tr>
                                        <th>Total Price</th>
                                        <td>₱{{ number_format($quotation['total_price'], 2) }}</td>
                                    </tr>
                                    <tr>
                                        <th>Payment Status</th>
                                        <td>{{ $quotation['payment_status'] }}</td>
                                    </tr>

                                    <!-- Menu Selections -->
                                    <tr>
                                        <th colspan="2" class="table-light">Menu Selections</th>
                                    </tr>
                                    @if(isset($quotation['menu_content']) && is_array($quotation['menu_content']))
                                        @foreach($quotation['menu_content'] as $menu)
                                            <tr>
                                                <th>{{ $menu['category'] }}</th>
                                                <td>{{ $menu['food'] }}</td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="2" class="text-muted text-center">No menu details available</td>
                                        </tr>
                                    @endif

                                    <!-- Address Information -->
                                    <tr>
                                        <th colspan="2" class="table-light">Address Information</th>
                                    </tr>
                                    <tr>
                                        <th>Complete Address</th>
                                        <td>
                                            {{ $quotation['street_houseno'] }}, 
                                            {{ $quotation['barangay'] }}, 
                                            {{ $quotation['city'] }}, 
                                            {{ $quotation['province'] }}, 
                                            {{ $quotation['region'] }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

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

    .status-badge.approved {
        color: #28a745;
        background-color: rgba(40, 167, 69, 0.2);
        border-color: #28a745;
    }

    .status-badge.rejected {
        color: #dc3545;
        background-color: rgba(220, 53, 69, 0.2);
        border-color: #dc3545;
    }

    .table th {
        background-color: #f8f9fa;
        vertical-align: middle;
    }
    
    .table-light {
        background-color: #e9ecef !important;
        font-weight: bold;
    }

    .btn-darkorange {
        background-color: darkorange;
        color: white;
    }

    .btn-darkorange:hover {
        background-color: #ff8c00;
        color: white;
    }
</style>
@endsection