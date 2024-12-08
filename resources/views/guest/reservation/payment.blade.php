@extends('layouts.guestlayout')

@section('content')
<div class="content-header" style="padding-top: 50px;">
    <div class="container">
        <div class="row mb-2">
        </div>
    </div>
</div>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-12">
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

                    <div class="text-center mb-4">
                        <h2 class="fw-bold" style="color: darkorange;">Payment</h2>
                    </div>
                    <p class="text-center mb-2 fw-bold">To pay, scan the QR code below.</p>
                    <p class="text-center mb-4">Or if you're just want to pencil book, just click the 'Proceed to Pencil Reservation'</p>

                    <!-- QR Codes Section -->
                    <div class="container">
                        <div class="row justify-content-center align-items-center g-4">
                            <!-- GCash QR -->
                            <div class="col-12 col-md-4 text-center">
                                <h5 class="mb-3" style="color: #087cf4;">
                                    <img src="{{ asset('/images/icons/gcash_icon.png') }}" alt="GCash Icon" 
                                        class="img-fluid" style="max-width: 50px; border-radius: 10px;">
                                    GCash
                                </h5>
                                <div class="qr-container">
                                    <img src="{{ asset('images/gcash.png') }}" alt="GCash QR Code" 
                                        class="qr-image">
                                </div>
                            </div>

                            <!-- OR Divider -->
                            <div class="col-12 col-md-2 text-center">
                                <h5 class="mb-3 fw-bold d-none d-md-block">or</h5>
                                <h5 class="my-2 fw-bold d-block d-md-none">or</h5>
                            </div>

                            <!-- UnionBank QR -->
                            <div class="col-12 col-md-4 text-center">
                                <h5 class="mb-3" style="color: darkorange;">
                                    <img src="{{ asset('/images/icons/unionbank_icon.png') }}" alt="Unionbank Icon" 
                                        class="img-fluid" style="max-width: 50px; border-radius: 10px;"> 
                                    UnionBank
                                </h5>
                                <div class="qr-container">
                                    <img src="{{ asset('images/unionbank.png') }}" alt="UnionBank QR Code" 
                                        class="qr-image">
                                </div>
                            </div>
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
                        <p class="fw-bold">Attach the receipt here. Make sure the reference number is visible.</p>
                        <div class="row justify-content-center">
                            <div class="col-12">
                                <!-- File input section -->
                                <div class="mb-4">
                                    <input type="file" name="payment_proof" id="payment_proof" class="form-control">
                                    <div class="invalid-feedback">Please provide a receipt image.</div>
                                    @error('payment_proof')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Buttons section -->
                                <div class="d-flex justify-content-center gap-2">
                                    <!-- Payment Submit Button -->
                                    <form id="paymentForm" action="{{ route('guest.payment.proof', ['reservation_id' => $reservation_id]) }}"
                                            method="POST" 
                                            enctype="multipart/form-data"
                                            class="w-50">
                                        @csrf
                                        <input type="hidden" name="payment_proof" id="payment_proof_hidden">
                                        <button type="submit" class="btn btn-success w-100" id="submitBtn">
                                            <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true" id="submitSpinner"></span>
                                            <span id="submitText">Submit Payment</span>
                                        </button>
                                    </form>
                                    
                                    <!-- Pencil Booking Button -->
                                    @if(isset($reservation) && $reservation['status'] === 'New')
                                        <form id="pencilForm" action="{{ route('guest.pencil.booking', ['reservation_id' => $reservation_id]) }}" 
                                            method="POST"
                                            class="w-50">
                                            @csrf
                                            <button type="submit" class="btn btn-secondary w-100">Proceed to Pencil Booking</button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Success Modal -->
                    <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="successModalLabel">Payment Submitted</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body text-center">
                                    <i class="fas fa-check-circle text-success" style="font-size: 48px;"></i>
                                    <p class="mt-3">Your payment has been submitted successfully. We will verify it shortly.</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-primary" id="modalOkButton">Okay</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .qr-container {
        max-width: 300px;
        margin: 0 auto;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .qr-image {
        width: 260px;
        height: 260px;
        object-fit: contain;
    }

    @media (max-width: 768px) {
        .qr-container {
            margin: 0 auto 20px;
        }
        
        .qr-image {
            width: 200px;
            height: 200px;
        }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const paymentForm = document.getElementById('paymentForm');
    const fileInput = document.getElementById('payment_proof');
    const hiddenInput = document.getElementById('payment_proof_hidden');
    const submitBtn = document.getElementById('submitBtn');
    const submitSpinner = document.getElementById('submitSpinner');
    const submitText = document.getElementById('submitText');
    const successModal = new bootstrap.Modal(document.getElementById('successModal'));
    
    // Handle modal okay button
    document.getElementById('modalOkButton').addEventListener('click', function() {
        successModal.hide();
        window.location.href = '{{ route('guest.home') }}';
    });

    // Handle payment form submission
    paymentForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Reset previous validation states
        fileInput.classList.remove('is-invalid');
        
        // Check if file is selected for payment submission
        if (!fileInput.files || !fileInput.files[0]) {
            fileInput.classList.add('is-invalid');
            return false;
        }

        // Show loading state
        submitSpinner.classList.remove('d-none');
        submitText.textContent = 'Processing...';
        submitBtn.disabled = true;

        // If validation passes, copy the file to the hidden input and submit
        const formData = new FormData();
        formData.append('payment_proof', fileInput.files[0]);
        formData.append('_token', document.querySelector('input[name="_token"]').value);
        
        fetch(this.action, {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            // Hide loading state
            submitSpinner.classList.add('d-none');
            submitText.textContent = 'Submit Payment';
            submitBtn.disabled = false;
            
            // Show success modal
            successModal.show();
        })
        .catch(error => {
            // Hide loading state
            submitSpinner.classList.add('d-none');
            submitText.textContent = 'Submit Payment';
            submitBtn.disabled = false;
            
            console.error('Error:', error);
            alert('An error occurred while uploading the payment proof. Please try again.');
        });
    });

    // Add change event listener to show validation in real-time
    fileInput.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            this.classList.remove('is-invalid');
        }
    });
});
</script>
@endsection