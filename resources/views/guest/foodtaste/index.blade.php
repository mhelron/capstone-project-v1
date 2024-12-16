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
                <!-- Add User Button -->
                <div class="d-flex justify-content-end">
                    <a href="{{ route('guest.foodtaste.create') }}" class="btn btn-darkorange mb-2">Create Food Tasting Inquiry</a>
                </div>
            <!-- Check Status Form -->
            <div class="card shadow-sm mb-4">
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <h2 class="fw-bold">Check Food Tasting Inquiry Status</h2>
                        <p class="text-muted small">Enter your reference number to view your food tasting details</p>
                    </div>

                    <form method="POST" action="{{ route('guest.check.submit') }}">
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

            <!-- Food Tasting Details -->
            @if(isset($foodtaste))
                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <h3 class="fw-bold mb-4">Food Tasting Details</h3>

                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tbody>
                                    <!-- Basic Info -->
                                    <tr>
                                        <th colspan="2" class="table-light">Reference Information</th>
                                    </tr>
                                    <tr>
                                        <th width="30%">Reference Number</th>
                                        <td>{{ $foodtaste['reference_number'] }}</td>
                                    </tr>
                                    <tr>
                                        <th>Status</th>
                                        <td><span class="badge status-badge {{ strtolower($foodtaste['status']) }}">{{ $foodtaste['status'] }}</span></td>
                                    </tr>
                                    <tr>
                                        <th>Created At</th>
                                        <td>{{ \Carbon\Carbon::parse($foodtaste['created_at'])->format('M d, Y h:i A') }}</td>
                                    </tr>
                                    
                                    <!-- Personal Information -->
                                    <tr>
                                        <th colspan="2" class="table-light">Personal Information</th>
                                    </tr>
                                    <tr>
                                        <th>Name</th>
                                        <td>{{ $foodtaste['firstname'] }} {{ $foodtaste['lastname'] }}</td>
                                    </tr>
                                    <tr>
                                        <th>Email</th>
                                        <td>{{ $foodtaste['email'] }}</td>
                                    </tr>
                                    <tr>
                                        <th>Phone</th>
                                        <td>{{ $foodtaste['phone'] }}</td>
                                    </tr>

                                    <!-- Delivery Information -->
                                    <tr>
                                        <th colspan="2" class="table-light">Delivery Information</th>
                                    </tr>
                                    <tr>
                                        <th>Delivery Option</th>
                                        <td>{{ ucfirst($foodtaste['delivery_option']) }}</td>
                                    </tr>
                                    @if($foodtaste['delivery_option'] === 'delivery')
                                    <tr>
                                        <th>Delivery Address</th>
                                        <td>
                                            {{ $foodtaste['street_houseno'] }}, 
                                            {{ $foodtaste['barangay'] }}, 
                                            {{ $foodtaste['city'] }}, 
                                            {{ $foodtaste['province'] }}, 
                                            {{ $foodtaste['region'] }}
                                        </td>
                                    </tr>
                                    @endif

                                    <!-- Schedule Information -->
                                    <tr>
                                        <th colspan="2" class="table-light">Schedule Information</th>
                                    </tr>
                                    <tr>
                                        <th>Preferred Date</th>
                                        <td>{{ \Carbon\Carbon::parse($foodtaste['preferred_date'])->format('M d, Y') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Preferred Time</th>
                                        <td>{{ $foodtaste['preferred_time'] }}</td>
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