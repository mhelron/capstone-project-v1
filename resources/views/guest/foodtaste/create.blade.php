@extends('layouts.guestlayout')

@section('content')

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<div class="container mt-5" style="padding-top: 50px;">
    <div class="card custom-card">
        <div class="card-body">
            <h2 class="mb-4 fw-bold">Food Tasting Form</h2>
            
            <div class="mb-4">
                <p>Experience the quality and taste of our dishes before booking your event. Our Food Tasting
                service allows you to sample a selection of menu items prepared for the day. Please note
                that personalized requests for specific dishes are not available, as the tasting menu is
                based on the items scheduled for that day.</p>

                <h5 class="text-darkorange mt-4 mb-3 fw-bold">How It Works:</h5>
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
                
                <div class="row">
                    <div class="form-group col-md-6 mb-3">
                        <label class="form-label">Firstname: <span class="text-danger">*</span></label>
                        <input type="text" name="firstname" class="form-control custom-select @error('firstname') is-invalid @enderror" 
                            value="{{ old('firstname') }}" required>
                        @error('firstname')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group col-md-6 mb-3">
                        <label class="form-label">Lastname: <span class="text-danger">*</span></label>
                        <input type="text" name="lastname" class="form-control custom-select @error('lastname') is-invalid @enderror" 
                            value="{{ old('client_name') }}" required>
                        @error('lastname')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-md-6 mb-3">
                        <label class="form-label">Email Address <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control custom-select @error('email') is-invalid @enderror" 
                            value="{{ old('email') }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group col-md-6 mb-3">
                        <label class="form-label">Phone Number <span class="text-danger">*</span></label>
                        <input type="tel" name="phone" class="form-control custom-select @error('phone') is-invalid @enderror" 
                            value="{{ old('phone') }}" required>
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label">Pickup or Delivery? <span class="text-danger">*</span></label>
                    <div class="d-flex gap-4">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="delivery_option" id="pickup" value="pickup" 
                                {{ old('delivery_option') == 'pickup' ? 'checked' : '' }} required>
                            <label for="pickup">Pickup</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="delivery_option" id="delivery" value="delivery" 
                                {{ old('delivery_option') == 'delivery' ? 'checked' : '' }}>
                            <label for="delivery">Delivery</label>
                        </div>
                    </div>
                    @error('delivery_option')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <!-- Region Dropdown -->
                    <div class="form-group col-md-4 mb-3">
                        <label for="region" class="form-label">Region</label>
                        <select name="region" id="region" class="form-control custom-select">
                            <option value="" disabled selected>Select a Region</option>
                            @foreach ($addressData as $region)
                                <option value="{{ $region['id'] }}" {{ old('region') == $region['id'] ? 'selected' : '' }}>
                                    {{ $region['name'] }}
                                </option>
                            @endforeach
                        </select>
                        @if ($errors->has('region'))
                            <small class="text-danger">{{ $errors->first('region') }}</small>
                        @endif
                    </div>

                    <!-- Province Dropdown -->
                    <div class="form-group col-md-4 mb-3">
                        <label for="province" class="form-label">Province</label>
                        <select name="province" id="province" class="form-control custom-select" disabled>
                            <option value="">Select a Province</option>
                            @if(old('province'))
                                <option value="{{ old('province') }}" selected>{{ old('province') }}</option>
                            @endif
                        </select>
                        @if ($errors->has('province') && !is_null(old('region')))
                            <small class="text-danger">{{ $errors->first('province') }}</small>
                        @endif
                    </div>

                    <!-- City Dropdown -->
                    <div class="form-group col-md-4 mb-3">
                        <label for="city" class="form-label">City</label>
                        <select name="city" id="city" class="form-control custom-select" disabled>
                            <option value="">Select a City</option>
                            @if(old('city'))
                                <option value="{{ old('city') }}" selected>{{ old('city') }}</option>
                            @endif
                        </select>
                        @if ($errors->has('city') && !is_null(old('province')))
                            <small class="text-danger">{{ $errors->first('city') }}</small>
                        @endif
                    </div>
                </div>

                <div class="row">
                    <!-- Barangay Dropdown -->
                    <div class="form-group col-md-4 mb-3">
                        <label for="barangay" class="form-label">Barangay</label>
                        <select name="barangay" id="barangay" class="form-control custom-select" disabled>
                            <option value="">Select a Barangay</option>
                            @if(old('barangay'))
                                <option value="{{ old('barangay') }}" selected>{{ old('barangay') }}</option>
                            @endif
                        </select>
                        @if ($errors->has('barangay') && !is_null(old('city')))
                            <small class="text-danger">{{ $errors->first('barangay') }}</small>
                        @endif
                    </div>

                    <!-- Street, Building, House Number -->
                    <div class="form-group col-md-8 mb-3">
                        <label class="form-label text-center">House Number, Building, Street<span class="text-danger"> *</span></label>
                        <input type="text" name="street_houseno" value="{{ old('street_houseno') }}" class="form-control custom-select" style="text-transform: uppercase;">
                        @if ($errors->has('street_houseno'))
                            <small class="text-danger">{{ $errors->first('street_houseno') }}</small>
                        @endif
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-md-6 mb-3">
                        <label class="form-label">Preferred Time for Pick-Up/Delivery <span class="text-danger">*</span></label>
                        <input type="text" name="preferred_time" id="timePicker" class="form-control custom-select @error('preferred_time') is-invalid @enderror" 
                            value="{{ old('preferred_time') }}" required placeholder="Select time">
                        @error('preferred_time')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group col-md-6 mb-3">
                        <label class="form-label">Preferred Date for Food Tasting <span class="text-danger">*</span></label>
                        <input type="text" name="preferred_date" id="datePicker" class="form-control custom-select @error('preferred_date') is-invalid @enderror" 
                            value="{{ old('preferred_date') }}" required placeholder="Select date">
                        @error('preferred_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
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

@vite('resources/js/address.js')

<script>
    const addressData = <?php echo json_encode($addressData); ?>;
</script>

<style>
/* Card Styles */
.custom-card {
    border: 2px solid darkorange;
    background-color: #f5f5dc;
    border-radius: 10px;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
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

.btn-darkorange:disabled {
    background-color: darkorange !important; /* Force keep the color */
    color: white;
    cursor: not-allowed;
    opacity: 0.65;
}

.btn-darkorange:not(:disabled):hover {
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

.col-md-4 .form-label,
.col-md-6 .form-label,
.col-md-8 .form-label {
    text-align: left !important;
}

/* Form Label Styles */
.form-label {
    font-weight: bold;
    margin-bottom: 0.5rem;
    text-align: left !important;
    display: block;
    width: 100%;
}

.form-group label {
    text-align: left !important;
    display: block;
    width: 100%;
}

.form-check-input {
    border-color: darkorange;
    background-color: white;
    transition: all 0.3s ease;
}

/* Change border color when radio button is focused */
.form-check-input:focus {
    border-color: darkorange;
    box-shadow: 0 0 5px rgba(255, 140, 0, 0.3);
}

/* Change the appearance when radio button is selected */
.form-check-input:checked {
    background-color: darkorange;
    border-color: darkorange;
    outline: none;
}

/* Customize the inner circle when radio is checked */
.form-check-input:checked::before {
    background-color: white;
    border-radius: 50%;
}

/* Adjust label color */
.form-check-label {
    color: #333;
    font-weight: bold;
    margin-left: 8px;
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
    const deliveryAddress = document.querySelector('textarea[name="delivery_address"]');
    const pickupRadio = document.getElementById('pickup');
    const deliveryRadio = document.getElementById('delivery');
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
        submitBtn.disabled = !(understandingCheckbox.checked && agreementCheckbox.checked);
    }

    // Add event listeners to checkboxes
    understandingCheckbox.addEventListener('change', updateSubmitButton);
    agreementCheckbox.addEventListener('change', updateSubmitButton);

    // Call initially to set initial state
    updateSubmitButton();

    // Function to toggle the delivery address field based on selection
    function toggleDeliveryAddress() {
        if (pickupRadio.checked) {
            deliveryAddress.disabled = true;
            deliveryAddress.value = ''; // Clear the delivery address when disabled
        } else if (deliveryRadio.checked) {
            deliveryAddress.disabled = false;
        }
    }

    // Disable delivery address field by default (for the Pickup option)
    deliveryAddress.disabled = true;

    // Event listeners for delivery options
    pickupRadio.addEventListener('change', toggleDeliveryAddress);
    deliveryRadio.addEventListener('change', toggleDeliveryAddress);

    // Form submission handling
    form.addEventListener('submit', function(e) {
        if (isSubmitting) {
            e.preventDefault();
            return;
        }

        isSubmitting = true;
        submitBtn.classList.add('btn-disabled');
        
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