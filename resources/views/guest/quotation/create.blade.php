@extends('layouts.guestlayout')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<div class="container mt-5" style="padding-top: 50px;">
    <div class="row">
        <div class="col-lg-12">
            <div class="card custom-card">
                <div class="card-body">
                    <h2 class="mb-4 fw-bold">Quotation Request Form</h2>
                    
                    <div class="mb-4">
                        <p>Request a detailed quotation for your upcoming event. Our team will review your requirements
                           and provide you with accurate pricing based on your specifications.</p>

                        <h5 class="text-darkorange mt-4 mb-3 fw-bold">How It Works:</h5>
                        <ol class="mb-4">
                            <li>Fill out the form with your event details and requirements</li>
                            <li>Our team will review your request and prepare a customized quotation</li>
                            <li>We'll contact you with the detailed pricing and package information</li>
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

                    <form action="{{ route('guest.quotation.store') }}" method="POST" id="quotationForm">
                        @csrf
                        
                        <!-- Personal Information -->
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
                                    value="{{ old('lastname') }}" required>
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

                        <!-- Event Details -->
                        <div class="row">
                            <div class="form-group col-md-6 mb-3">
                                <label class="form-label">Event Date <span class="text-danger">*</span></label>
                                <input type="text" name="event_date" id="datePicker" class="form-control custom-select @error('event_date') is-invalid @enderror" 
                                    value="{{ old('event_date') }}" required placeholder="Select date">
                                @error('event_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-6 mb-3">
                                <label class="form-label">Event Time <span class="text-danger">*</span></label>
                                <input type="text" name="event_time" id="timePicker" class="form-control custom-select @error('event_time') is-invalid @enderror" value="{{ old('event_time') }}" required placeholder="Select time">
                                @error('event_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-6 mb-3">
                                <label class="form-label">Venue <span class="text-danger">*</span></label>
                                <input type="text" name="venue" class="form-control custom-select @error('venue') is-invalid @enderror" 
                                    value="{{ old('venue') }}" required>
                                @error('venue')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-6 mb-3">
                                <label class="form-label">Theme <span class="text-danger">*</span></label>
                                <input type="text" name="theme" class="form-control custom-select @error('theme') is-invalid @enderror" 
                                    value="{{ old('theme') }}" required>
                                @error('theme')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-6 mb-3">
                                <label class="form-label">Number of Guests <span class="text-danger">*</span></label>
                                <input type="number" name="guest_count" class="form-control custom-select @error('guest_count') is-invalid @enderror" 
                                    value="{{ old('guest_count') }}" required min="1">
                                @error('guest_count')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-6 mb-3">
                                <label class="form-label">Event <span class="text-danger">*</span></label>
                                <input type="text" name="event" class="form-control custom-select @error('event') is-invalid @enderror" 
                                    value="{{ old('event') }}" required>
                                @error('Event')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Replace the existing menu selection div with this -->
                        <div class="row mb-3">
                            <div class="form-group col-md-12">
                                <label class="form-label">Event Type <span class="text-danger">*</span></label>
                                <div class="d-flex gap-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="is_wedding" id="regular" value="0" 
                                            {{ old('is_wedding') == '0' ? 'checked' : '' }} required>
                                        <label class="form-check-label" for="regular">Regular Event</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="is_wedding" id="wedding" value="1" 
                                            {{ old('is_wedding') == '1' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="wedding">Wedding</label>
                                    </div>
                                </div>
                                @error('is_wedding')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Menu Fields -->
                        <div id="regularMenuFields" class="row" style="display: none;">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Main Course (Beef) <span class="text-danger">*</span></label>
                                <input type="text" name="menu_beef" class="form-control custom-select" value="{{ old('menu_beef') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Main Course (Chicken) <span class="text-danger">*</span></label>
                                <input type="text" name="menu_chicken" class="form-control custom-select" value="{{ old('menu_chicken') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Main Course (Pork) <span class="text-danger">*</span></label>
                                <input type="text" name="menu_pork" class="form-control custom-select" value="{{ old('menu_pork') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Side Dish <span class="text-danger">*</span></label>
                                <input type="text" name="menu_side" class="form-control custom-select" value="{{ old('menu_side') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Pasta <span class="text-danger">*</span></label>
                                <input type="text" name="menu_pasta" class="form-control custom-select" value="{{ old('menu_pasta') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Rice <span class="text-danger">*</span></label>
                                <input type="text" name="menu_rice" class="form-control custom-select" value="{{ old('menu_rice') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Dessert <span class="text-danger">*</span></label>
                                <input type="text" name="menu_dessert" class="form-control custom-select" value="{{ old('menu_dessert') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Drinks <span class="text-danger">*</span></label>
                                <input type="text" name="menu_drinks" class="form-control custom-select" value="{{ old('menu_drinks') }}">
                            </div>
                        </div>

                        <div id="weddingMenuFields" class="row" style="display: none;">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Main Course (Beef) <span class="text-danger">*</span></label>
                                <input type="text" name="menu_beef_wedding" class="form-control custom-select" value="{{ old('menu_beef_wedding') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Main Course (Chicken) <span class="text-danger">*</span></label>
                                <input type="text" name="menu_chicken_wedding" class="form-control custom-select" value="{{ old('menu_chicken_wedding') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Main Course (Pork) <span class="text-danger">*</span></label>
                                <input type="text" name="menu_pork_wedding" class="form-control custom-select" value="{{ old('menu_pork_wedding') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Main Course (Fish) <span class="text-danger">*</span></label>
                                <input type="text" name="menu_fish" class="form-control custom-select" value="{{ old('menu_fish') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Side Dish <span class="text-danger">*</span></label>
                                <input type="text" name="menu_side_wedding" class="form-control custom-select" value="{{ old('menu_side_wedding') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Pasta <span class="text-danger">*</span></label>
                                <input type="text" name="menu_pasta_wedding" class="form-control custom-select" value="{{ old('menu_pasta_wedding') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Rice <span class="text-danger">*</span></label>
                                <input type="text" name="menu_rice_wedding" class="form-control custom-select" value="{{ old('menu_rice_wedding') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Dessert <span class="text-danger">*</span></label>
                                <input type="text" name="menu_dessert_wedding" class="form-control custom-select" value="{{ old('menu_dessert_wedding') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Drinks <span class="text-danger">*</span></label>
                                <input type="text" name="menu_drinks_wedding" class="form-control custom-select" value="{{ old('menu_drinks_wedding') }}">
                            </div>
                        </div>

                        <!-- Address Information -->
                        <div class="row">
                            <!-- Region Dropdown -->
                            <div class="form-group col-md-4 mb-3">
                                <label for="region" class="form-label">Region <span class="text-danger">*</span></label>
                                <select name="region" id="region" class="form-control custom-select @error('region') is-invalid @enderror" required>
                                    <option value="" disabled selected>Select a Region</option>
                                    @foreach ($addressData as $region)
                                        <option value="{{ $region['id'] }}" {{ old('region') == $region['id'] ? 'selected' : '' }}>
                                            {{ $region['name'] }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('region')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Province Dropdown -->
                            <div class="form-group col-md-4 mb-3">
                                <label for="province" class="form-label">Province <span class="text-danger">*</span></label>
                                <select name="province" id="province" class="form-control custom-select @error('province') is-invalid @enderror" disabled required>
                                    <option value="">Select a Province</option>
                                </select>
                                @error('province')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- City Dropdown -->
                            <div class="form-group col-md-4 mb-3">
                                <label for="city" class="form-label">City <span class="text-danger">*</span></label>
                                <select name="city" id="city" class="form-control custom-select @error('city') is-invalid @enderror" disabled required>
                                    <option value="">Select a City</option>
                                </select>
                                @error('city')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <!-- Barangay Dropdown -->
                            <div class="form-group col-md-4 mb-3">
                                <label for="barangay" class="form-label">Barangay <span class="text-danger">*</span></label>
                                <select name="barangay" id="barangay" class="form-control custom-select @error('barangay') is-invalid @enderror" disabled required>
                                    <option value="">Select a Barangay</option>
                                </select>
                                @error('barangay')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Street, Building, House Number -->
                            <div class="form-group col-md-8 mb-3">
                                <label class="form-label">House Number, Building, Street <span class="text-danger">*</span></label>
                                <input type="text" name="street_houseno" value="{{ old('street_houseno') }}" 
                                    class="form-control custom-select @error('street_houseno') is-invalid @enderror" 
                                    style="text-transform: uppercase;" required>
                                @error('street_houseno')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <!-- Understanding and Agreement -->
                        <div class="mb-4">
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" name="understanding" id="understanding" required 
                                    {{ old('understanding') ? 'checked' : '' }}>
                                <label class="form-check-label" for="understanding">
                                    I understand that the quotation is subject to final pricing and package availability.
                                </label>
                                @error('understanding')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="agreement" id="agreement" required 
                                    {{ old('agreement') ? 'checked' : '' }}>
                                <label class="form-check-label" for="agreement">
                                    I agree to the terms and conditions of the Quotation service.
                                </label>
                                @error('agreement')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-4 d-flex justify-content-end gap-2">
                            <a href="{{ route('guest.quotation.index')}}" class="btn btn-secondary" id="cancelBtn">
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
    </div>
</div>

@vite('resources/js/address.js')

<script>
    const addressData = <?php echo json_encode($addressData); ?>;
</script>

<style>
.custom-card {
    border: 2px solid darkorange;
    background-color: #f5f5dc;
    border-radius: 10px;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

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
}

.text-darkorange {
    color: darkorange;
}

/* Add this to your quotation form CSS */
.form-group label,
.form-label {
    text-align: left !important;
    display: block;
    width: 100%;
    font-weight: bold;
    margin-bottom: 0.5rem;
}

.col-md-4 .form-label,
.col-md-6 .form-label,
.col-md-8 .form-label {
    text-align: left !important;
}
</style>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const regularMenuFields = document.getElementById('regularMenuFields');
    const weddingMenuFields = document.getElementById('weddingMenuFields');
    const regularRadio = document.getElementById('regular');
    const weddingRadio = document.getElementById('wedding');

    // Initially hide both menu fields
    regularMenuFields.style.display = 'none';
    weddingMenuFields.style.display = 'none';

    function toggleMenuFields() {
        if (regularRadio.checked) {
            regularMenuFields.style.display = 'flex';
            weddingMenuFields.style.display = 'none';
            // Enable/disable required fields
            toggleRequiredFields(regularMenuFields, true);
            toggleRequiredFields(weddingMenuFields, false);
        } else if (weddingRadio.checked) {
            regularMenuFields.style.display = 'none';
            weddingMenuFields.style.display = 'flex';
            // Enable/disable required fields
            toggleRequiredFields(regularMenuFields, false);
            toggleRequiredFields(weddingMenuFields, true);
        } else {
            // If no radio is selected, hide both
            regularMenuFields.style.display = 'none';
            weddingMenuFields.style.display = 'none';
            // Disable required fields for both
            toggleRequiredFields(regularMenuFields, false);
            toggleRequiredFields(weddingMenuFields, false);
        }
    }

    function toggleRequiredFields(container, required) {
        const inputs = container.getElementsByTagName('input');
        for (let input of inputs) {
            input.required = required;
        }
    }

    regularRadio.addEventListener('change', toggleMenuFields);
    weddingRadio.addEventListener('change', toggleMenuFields);

    // Call toggleMenuFields initially to set the correct state
    toggleMenuFields();
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('quotationForm');
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

    // Initialize time picker
    const timePicker = flatpickr("#timePicker", {
        enableTime: true,
        noCalendar: true,
        dateFormat: "h:i K",
        defaultHour: 12,
        time_24hr: false
    });

    function updateSubmitButton() {
        submitBtn.disabled = !(understandingCheckbox.checked && agreementCheckbox.checked);
    }

    understandingCheckbox.addEventListener('change', updateSubmitButton);
    agreementCheckbox.addEventListener('change', updateSubmitButton);

    updateSubmitButton();

    form.addEventListener('submit', function(e) {
        if (isSubmitting) {
            e.preventDefault();
            return;
        }

        isSubmitting = true;
        submitBtn.disabled = true;
        submitBtn.querySelector('.spinner-border').classList.remove('d-none');
        submitBtn.querySelector('.btn-text').textContent = 'Submitting...';
    });

    cancelBtn.addEventListener('click', function(e) {
        if (isSubmitting) {
            e.preventDefault();
            return;
        }

        cancelBtn.disabled = true;
        cancelBtn.querySelector('.spinner-border').classList.remove('d-none');
        cancelBtn.querySelector('.btn-text').textContent = 'Cancelling...';
    });
});
</script>
@endsection