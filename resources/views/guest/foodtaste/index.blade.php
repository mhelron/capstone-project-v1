@extends('layouts.guestlayout')

@section('content')

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<div class="container mt-5" style="padding-top: 50px;">
    <div class="card custom-card">
        <div class="card-body">
            <h2 class="mb-4">Food Tasting Form</h2>
            
            <div class="mb-4">
                <p>Experience the quality and taste of our dishes before booking your event. Our Food Tasting
                service allows you to sample a selection of menu items prepared for the day. Please note
                that personalized requests for specific dishes are not available, as the tasting menu is
                based on the items scheduled for that day.</p>

                <h5 class="text-darkorange mt-4 mb-3">How It Works:</h5>
                <ol class="mb-4">
                    <li>Request a Tasting – Schedule a tasting appointment by filling out this form or contacting us directly.</li>
                    <li>Choose Your Option – Select between pick-up or delivery via Lalamove.</li>
                    <li>Taste the Experience – Receive a curated selection of menu items available on the chosen date.</li>
                </ol>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form action="#" method="POST" id="tastingForm">
                @csrf
                
                <div class="mb-4">
                    <label class="form-label text-darkorange">Client's Name <span class="text-danger">*</span></label>
                    <input type="text" name="client_name" class="form-control custom-select @error('client_name') is-invalid @enderror" 
                           value="{{ old('client_name') }}" required>
                    @error('client_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label text-darkorange">Email Address <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control custom-select @error('email') is-invalid @enderror" 
                           value="{{ old('email') }}" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label text-darkorange">Phone Number <span class="text-danger">*</span></label>
                    <input type="tel" name="phone" class="form-control custom-select @error('phone') is-invalid @enderror" 
                           value="{{ old('phone') }}" required>
                    @error('phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label text-darkorange">Pickup or Delivery? <span class="text-danger">*</span></label>
                    <div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="delivery_option" id="pickup" value="pickup" 
                                   {{ old('delivery_option') == 'pickup' ? 'checked' : '' }} required>
                            <label class="form-check-label" for="pickup">Pickup</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="delivery_option" id="delivery" value="delivery" 
                                   {{ old('delivery_option') == 'delivery' ? 'checked' : '' }}>
                            <label class="form-check-label" for="delivery">Delivery</label>
                        </div>
                    </div>
                    @error('delivery_option')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label text-darkorange">Preferred Time for Pick-Up/Delivery <span class="text-danger">*</span></label>
                    <input type="text" name="preferred_time" id="timePicker" class="form-control custom-select @error('preferred_time') is-invalid @enderror" 
                        value="{{ old('preferred_time') }}" required placeholder="Select time">
                    @error('preferred_time')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label text-darkorange">Preferred Date for Food Tasting <span class="text-danger">*</span></label>
                    <input type="text" name="preferred_date" id="datePicker" class="form-control custom-select @error('preferred_date') is-invalid @enderror" 
                        value="{{ old('preferred_date') }}" required placeholder="Select date">
                    @error('preferred_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label text-darkorange">Delivery Information: Address</label>
                    <textarea name="delivery_address" class="form-control custom-select @error('delivery_address') is-invalid @enderror" 
                              rows="3">{{ old('delivery_address') }}</textarea>
                    @error('delivery_address')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="understanding" id="understanding" required 
                               {{ old('understanding') ? 'checked' : '' }}>
                        <label class="form-check-label" for="understanding">
                            I understand that food tasting is subject to menu availability and specific dish requests are not allowed.
                        </label>
                        @error('understanding')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="agreement" id="agreement" required 
                               {{ old('agreement') ? 'checked' : '' }}>
                        <label class="form-check-label" for="agreement">
                            I agree to the terms and conditions of the Food Tasting service.
                        </label>
                        @error('agreement')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mt-4 d-flex justify-content-end gap-2">
                    <a href="{{ route('guest.home')}}" class="btn btn-secondary" id="cancelBtn">
                        <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                        <span class="btn-text">Cancel</span>
                    </a>
                    <button type="submit" class="btn btn-darkorange" id="submitBtn">
                        <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                        <span class="btn-text">Submit</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
/* Card Styles */
.custom-card {
    border: 2px solid darkorange;
    background-color: #f5f5dc;
    border-radius: 10px;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.custom-card:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    transform: translateY(-2px);
}

/* Form Select Styles */
.custom-select {
    border: 2px solid darkorange;
    border-radius: 5px;
    padding: 8px 12px;
    transition: all 0.3s ease;
}

.custom-select:focus {
    border-color: darkorange;
    box-shadow: 0 0 5px rgba(255, 140, 0, 0.3);
    outline: none;
}

/* Button Styles */
.btn-darkorange {
    background-color: darkorange;
    color: white;
    border: none;
    padding: 8px 20px;
    border-radius: 5px;
    transition: all 0.3s ease;
}

.btn-darkorange:hover {
    background-color: #ff8c00;
    color: white;
    transform: translateY(-1px);
}

.btn-darkorange:active {
    transform: translateY(1px);
}

/* Text Colors */
.text-darkorange {
    color: darkorange;
}

/* Form Label Styles */
.form-label {
    font-weight: bold;
    margin-bottom: 0.5rem;
}
</style>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Get required elements
    const form = document.getElementById('tastingForm');
    const submitBtn = document.getElementById('submitBtn');
    const cancelBtn = document.getElementById('cancelBtn');
    const understandingCheckbox = document.getElementById('understanding');
    const agreementCheckbox = document.getElementById('agreement');
    let isSubmitting = false;

    // Initialize date picker
    const datePicker = flatpickr("#datePicker", {
        dateFormat: "Y-m-d",
        minDate: "today",
        locale: {
            firstDayOfWeek: 1 
        }
    });

    // Initialize time picker with AM/PM
    const timePicker = flatpickr("#timePicker", {
        enableTime: true,
        noCalendar: true,
        dateFormat: "h:i K", 
        minTime: "09:00",
        maxTime: "17:00",
        defaultHour: 9,
        time_24hr: false 
    });

    // Function to check both checkboxes and update submit button
    function updateSubmitButton() {
        if(understandingCheckbox.checked && agreementCheckbox.checked) {
            submitBtn.disabled = false;
        } else {
            submitBtn.disabled = true;
        }
    }

    // Add event listeners to checkboxes
    understandingCheckbox.addEventListener('change', updateSubmitButton);
    agreementCheckbox.addEventListener('change', updateSubmitButton);

    // Call initially to set initial state
    updateSubmitButton();

    // Form submission handling
    form.addEventListener('submit', function(e) {
        if (isSubmitting) {
            e.preventDefault();
            return;
        }

        isSubmitting = true;
        submitBtn.disabled = true;
        
        // Show spinner, hide text
        submitBtn.querySelector('.spinner-border').classList.remove('d-none');
        submitBtn.querySelector('.btn-text').textContent = 'Submitting...';
    });

    // Cancel button handling
    cancelBtn.addEventListener('click', function(e) {
        if (isSubmitting) {
            e.preventDefault();
            return;
        }

        cancelBtn.disabled = true;
        
        // Show spinner, hide text
        cancelBtn.querySelector('.spinner-border').classList.remove('d-none');
        cancelBtn.querySelector('.btn-text').textContent = 'Cancelling...';
    });
});
</script>

<style>
/* Flatpickr custom styles */
.flatpickr-calendar.material_orange {
    box-shadow: 0 3px 13px rgba(255, 140, 0, 0.08);
}

.flatpickr-day.selected, 
.flatpickr-day.selected:hover {
    background: darkorange !important;
    border-color: darkorange !important;
}

.flatpickr-day.today {
    border-color: darkorange !important;
}

.flatpickr-day.today:hover {
    background: rgba(255, 140, 0, 0.15);
}

.flatpickr-time input:hover,
.flatpickr-time .flatpickr-am-pm:hover,
.flatpickr-time input:focus,
.flatpickr-time .flatpickr-am-pm:focus {
    background: rgba(255, 140, 0, 0.05);
}

.flatpickr-calendar.material_orange .flatpickr-day.selected {
    box-shadow: 0 3px 13px rgba(255, 140, 0, 0.2);
}
</style>
@endsection