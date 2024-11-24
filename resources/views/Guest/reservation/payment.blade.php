@extends('layouts.guestlayout')

@section('content')
<div class="content-header" style="padding-top: 100px;">
    <div class="container">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Payment</h1>
            </div>
        </div>
    </div>
</div>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
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

                    <p class="text-center mb-4">To pay, scan the QR code below.</p>

                    <!-- QR Codes Side by Side -->
                    <div class="d-flex justify-content-center align-items-center gap-4 mb-4">
                        <div class="text-center">
                            <h5 class="mb-3">GCash</h5>
                            <img src="{{ asset('images/gcash.png') }}" alt="GCash QR Code" class="img-fluid" style="max-width: 300px;">
                        </div>
                        <div class="text-center">
                            <h5 class="mb-3">UnionBank</h5>
                            <img src="{{ asset('images/unionbank.png') }}" alt="UnionBank QR Code" class="img-fluid" style="max-width: 300px;">
                        </div>
                    </div>

                    <!-- Bank Transfer Details -->
                    <div class="text-center mb-4">
                        <p>Or Bank Transfer:</p>
                        <p><strong>Bank: 123456789</strong></p>
                    </div>

                    <!-- Display Reservation Details -->
                    @if(isset($reservation))
                    <div class="mb-4">
                        <h5 class="text-center">Reservation Details</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tr>
                                    <th>Name</th>
                                    <td>{{ $reservation['first_name'] }} {{ $reservation['last_name'] }}</td>
                                </tr>
                                <tr>
                                    <th>Package</th>
                                    <td>{{ $reservation['package_name'] }}</td>
                                </tr>
                                <tr>
                                    <th>Package Amount</th>
                                    <td>₱{{ number_format($reservation['total_price'], 2) }}</td>
                                </tr>
                                <tr>
                                    <th>Reservation Fee to Pay</th>
                                    <td>₱5,000.00</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    @endif

                    <!-- Receipt Upload Section -->
                    <div class="text-center">
                        <p>Attach the receipt here. Make sure the reference number is visible.</p>
                        <form action="{{ route('guest.payment.proof', ['reservation_id' => $reservation_id]) }}"
                                method="POST" 
                                enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <input type="file" name="payment_proof" class="form-control mb-3" required>
                                @error('payment_proof')
                                    <div class="text-danger mb-3">{{ $message }}</div>
                                @enderror
                                <button type="submit" class="btn btn-success">Submit Payment Proof</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection