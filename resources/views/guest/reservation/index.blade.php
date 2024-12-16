@extends('layouts.guestlayout')

@section('content')

@vite('resources/css/guestreservation.css')

<meta charset="UTF-8">

<!-- Content Header (Page header) -->
<div class="content-header" style="padding-top: 100px;">
    <div class="container">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Reserve</h1>
            </div>
        </div>
    </div>
</div>
<!-- /.content-header -->

<!-- Main content -->
<div class="content pt-2">
    <div class="container">
        <div class="row d-flex justify-content-center">
            <div class="col-lg-8 mb-4 main-form">

                <div class="card">
                    <div class="card-body form-container">
                        <form id="myForm" action="{{ route('guest.reserve.add') }}" method="POST">
                            @csrf

                            <div class="row">
                                <!-- First Name -->
                                <div class="form-group col-md-6 mb-3">
                                    <label>First Name</label><span class="text-danger"> *</span>
                                    <input type="text" name="first_name" value="{{ old('first_name') }}" class="form-control">
                                    @if ($errors->has('first_name'))
                                        <small class="text-danger">{{ $errors->first('first_name') }}</small>
                                    @endif
                                </div>

                                <!-- Last Name -->
                                <div class="form-group col-md-6 mb-3">
                                    <label>Last Name</label><span class="text-danger"> *</span>
                                    <input type="text" name="last_name" value="{{ old('last_name') }}" class="form-control">
                                    @if ($errors->has('last_name'))
                                        <small class="text-danger">{{ $errors->first('last_name') }}</small>
                                    @endif
                                </div>
                            </div>

                            <div class="row">
                                <!-- Phone -->
                                <div class="form-group col-md-6 mb-3">
                                    <label>Phone</label><span class="text-danger"> *</span>
                                    <input type="text" name="phone" value="{{ old('phone') }}" class="form-control">
                                    @if ($errors->has('phone'))
                                        <small class="text-danger">{{ $errors->first('phone') }}</small>
                                    @endif
                                </div>

                                <!-- Email -->
                                <div class="form-group col-md-6 mb-3">
                                    <label>Email</label><span class="text-danger"> *</span>
                                    <input type="email" name="email" value="{{ old('email') }}" class="form-control">
                                    @if ($errors->has('email'))
                                        <small class="text-danger">{{ $errors->first('email') }}</small>
                                    @endif
                                </div>
                            </div>

                            <div class="row">
                                <!-- Region Dropdown -->
                                <div class="col-md-6 mb-3 text-start">
                                    <label for="region">Region</label>
                                    <select name="region" id="region" class="form-control">
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
                                <div class="form-group col-md-6 mb-3">
                                    <label for="province">Province</label>
                                    <select name="province" id="province" class="form-control" disabled>
                                    <option value="">Select a Province</option>
                                        @if(old('province'))
                                            <option value="{{ old('province') }}" selected>{{ old('province') }}</option>
                                        @endif
                                    </select>
                                    @if ($errors->has('province') && !is_null(old('region')))
                                        <small class="text-danger">{{ $errors->first('province') }}</small>
                                    @endif
                                </div>
                            </div>

                            <div class="row">
                                <!-- City Dropdown -->
                                <div class="form-group col-md-6 mb-3">
                                    <label for="city">City</label>
                                    <select name="city" id="city" class="form-control" disabled>
                                    <option value="">Select a City</option>
                                        @if(old('city'))
                                            <option value="{{ old('city') }}" selected>{{ old('city') }}</option>
                                        @endif
                                    </select>
                                    @if ($errors->has('city') && !is_null(old('province')))
                                        <small class="text-danger">{{ $errors->first('city') }}</small>
                                    @endif
                                </div>

                                <!-- Barangay Dropdown -->
                                <div class="form-group col-md-6 mb-3">
                                    <label for="barangay">Barangay</label>
                                    <select name="barangay" id="barangay" class="form-control" disabled>
                                        <option value="">Select a Barangay</option>
                                        @if(old('barangay'))
                                            <option value="{{ old('barangay') }}" selected>{{ old('barangay') }}</option>
                                        @endif
                                    </select>
                                    @if ($errors->has('barangay') && !is_null(old('city')))
                                        <small class="text-danger">{{ $errors->first('barangay') }}</small>
                                    @endif
                                </div>
                            </div>

                            <div class="row">
                                <!-- Street, Building, House Number -->
                                <div class="form-group col-md-6 mb-3">
                                    <label>House Number, Building, Street</label><span class="text-danger"> *</span>
                                    <input type="text" name="street_houseno" value="{{ old('street_houseno') }}" class="form-control" style="text-transform: uppercase;">
                                    @if ($errors->has('street_houseno'))
                                        <small class="text-danger">{{ $errors->first('street_houseno') }}</small>
                                    @endif
                                </div>

                                <!-- Package Selection -->
                                <div class="form-group col-md-6 mb-3">
                                    <label for="package_name">Package</label>
                                    <select name="package_name" id="package_name" class="form-control">
                                        <option value="" disabled {{ old('package_name') ? '' : 'selected' }}>Select a Package</option>
                                        @foreach ($packages ?? [] as $package)
                                            @if(isset($package['is_displayed']) && $package['is_displayed'] === true)
                                                <option value="{{ $package['package_name'] ?? '' }}" 
                                                        data-persons="{{ $package['persons'] ?? '' }}"
                                                        {{ old('package_name') == ($package['package_name'] ?? '') ? 'selected' : '' }}>
                                                    {{ $package['package_name'] ?? 'Unknown Package' }}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                    @if ($errors->has('package_name'))
                                        <small class="text-danger">{{ $errors->first('package_name') }}</small>
                                    @endif
                                </div>
                            </div>

                            <div class="row">
                                <!-- Title of the Event -->
                                <div class="form-group col-md-6 mb-3">
                                    <label>Title of the Event</label><span class="text-danger"> *</span>
                                    <input type="text" name="event_title" value="{{ old('event_title') }}" class="form-control">
                                    @if ($errors->has('event_title'))
                                        <small class="text-danger">{{ $errors->first('event_title') }}</small>
                                    @endif
                                </div>
                                
                                 <!-- Menu Selection -->
                                 <div class="form-group col-md-6 mb-3">
                                    <label for="menu_name">Menu</label>
                                    <select name="menu_name" class="form-select" id="menu_name" {{ old('package_name') ? '' : 'disabled' }}>
                                        <option value="">Select a Menu</option>
                                        @if(isset($menuId) && session()->has('customized_menu_' . $menuId))
                                            @php $customMenu = session('customized_menu_' . $menuId); @endphp
                                            <option value="{{ $customMenu['menu_name'] }}" 
                                                    {{ old('menu_name') == $customMenu['menu_name'] ? 'selected' : '' }}>
                                                {{ $customMenu['menu_name'] }}
                                            </option>
                                        @endif
                                        @if(old('menu_name'))
                                            <option value="{{ old('menu_name') }}" selected>{{ old('menu_name') }}</option>
                                        @endif
                                    </select>
                                    @if ($errors->has('menu_name') && !is_null(old('package_name')))
                                        <small class="text-danger">{{ $errors->first('menu_name') }}</small>
                                    @endif
                                </div>
                            </div>

                            <!-- Add a hidden input for menu_id if it exists -->
                            @if(isset($menuId))
                                <input type="hidden" name="menu_id" value="{{ $menuId }}">
                            @endif

                            <div class="row">
                                <!-- Number of Guests -->
                                <div class="form-group col-md-6 mb-3">
                                    <label>Number of Guests</label><span class="text-danger"> *</span>
                                    <input type="number" name="guests_number" value="{{ old('guests_number') }}" class="form-control" id="guests_number" min="1" max="500">
                                    @if ($errors->has('guests_number'))
                                        <small class="text-danger">{{ $errors->first('guests_number') }}</small>
                                    @endif
                                </div>

                                <div class="form-group col-md-6 mb-3">
                                    <label>Number of Principal Sponsors (Ninong, Ninang & Parents)</label>
                                    <input 
                                        type="number" 
                                        id="sponsors" 
                                        name="sponsors" 
                                        value="{{ old('sponsors') }}" 
                                        class="form-control"
                                        min="1"
                                        max="500"
                                        @if(old('package_type', $packageType ?? '') !== 'Wedding') disabled @endif>

                                    <input type="hidden" id="package_type" name="package_type" value="{{ old('package_type', $packageType ?? '') }}">
                                    
                                    @if ($errors->has('sponsors') && old('package_name', $packageType ?? '') === 'Wedding')
                                        <small class="text-danger">{{ $errors->first('sponsors') }}</small>
                                    @endif
                                </div>
                            </div>

                            <div class="row">
                                <!-- Date of Event -->
                                <div class="form-group col-md-6 mb-3">
                                    <label>Date of Event</label><span class="text-danger"> *</span>
                                    <input type="text" id="event_date" name="event_date" value="{{ old('event_date') }}" class="form-control" placeholder="Select date">
                                    @if ($errors->has('event_date'))
                                        <small class="text-danger">{{ $errors->first('event_date') }}</small>
                                    @endif
                                </div>

                                <!-- Serving Time / Time of Event -->
                                <div class="form-group col-md-6 mb-3">
                                    <label>Serving Time / Time of Event</label><span class="text-danger"> *</span>
                                    <input type="text" id="event_time" name="event_time" value="{{ old('event_time') }}" class="form-control" placeholder="Select time">
                                    @if ($errors->has('event_time'))
                                        <small class="text-danger">{{ $errors->first('event_time') }}</small>
                                    @endif
                                </div>
                            </div>

                            <div class="row">
                                 <!-- Location or Venue -->
                                <div class="form-group col-md-6 mb-3">
                                    <label>Location or Venue</label><span class="text-danger"> *</span>
                                    <input type="text" name="venue" value="{{ old('venue') }}" class="form-control">
                                    @if ($errors->has('venue'))
                                        <small class="text-danger">{{ $errors->first('venue') }}</small>
                                    @endif
                                </div>

                                <!-- Color Motif or Theme -->
                                <div class="form-group col-md-6 mb-3">
                                    <label>Color Motif or/and Theme</label><span class="text-danger"> *</span>
                                    <input type="text" name="theme" value="{{ old('theme') }}" class="form-control">
                                    @if ($errors->has('theme'))
                                        <small class="text-danger">{{ $errors->first('theme') }}</small>
                                    @endif
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-12 mb-3">
                                    <label>Others (Special Request)</label>
                                    <textarea name="other_requests" class="form-control">{{ old('other_requests') }}</textarea>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-6 mb-3">

                                </div>

                                <!-- Color Motif or Theme -->
                             <div class="form-group col-md-6">
                                    <h4>Total Breakdown</h4>
                                    <p class="d-flex justify-content-between">
                                        <strong>Additional Persons: </strong>
                                        <span id="total-additional-persons">0</span> 
                                        <span>x</span> 
                                        <span id="price-per-package-head">0.00</span>
                                    </p>
                                    <p class="d-flex justify-content-between">
                                        <strong>Total Additional Person Price: </strong>
                                        <span id="total-additional-person-price">0.00</span>
                                    </p>
                                    <p class="d-flex justify-content-between">
                                        <strong>Package Price: </strong>
                                        <span id="total-package-price">0.00</span>
                                    </p>
                                    <p class="d-flex justify-content-between">
                                        <strong>Total:</strong>
                                        <span id="total-price">0.00</span>
                                    </p>
                                </div>
                            </div>

                            <input type="hidden" id="total_price" name="total_price" value="{{ old('total_price') }}">

                            <div class="form-group">
                                <button type="submit" class="btn btn-darkorange float-end">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 mb-4">
                <div class="card blank-card">
                    <div class="card-body">
                        <div id="package-preview" style="display:none;">
                            <h4>Package Preview</h4>
                            <p><strong>Package Name: </strong><span id="preview-package-name"></span></p>
                            <p><strong>Package Price: </strong><span id="preview-package-price"></span></p>
                            <p><strong>Package Pax: </strong><span id="preview-package-pax"></span></p>
                            <p><h4>Menu</h4></p>
                            <p><span id="preview-menu-items"></span></p>
                            <p><h4>Services</h4><span id="preview-package-services"></span></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true" >
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <!-- Modal Title centered -->
                        <h4 class="modal-title" id="termsModalLabel"><strong>Terms and Conditions Policy</strong></h4>
                    </div>
                    <div class="modal-body">
                        
                        {!! $content['terms'] !!}

                        <!-- Agreement Checkbox -->
                        <div class="mt-3">
                            <input type="checkbox" id="agreeCheckboxModal">
                            <label for="agreeCheckboxModal" class="text-muted"> I have read and agree to the Terms and Conditions.</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="modalDoneBtn" disabled>Done</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Summary Modal -->
        <div class="modal fade" id="summaryModal" tabindex="-1" aria-labelledby="summaryModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="summaryModalLabel"><strong>Reservation Summary</strong></h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Personal Information Table -->
                        <h5>Personal Information</h5>
                        <table class="table table-hover">
                            <tbody>
                                <tr>
                                    <th style="width: 30%">Name</th>
                                    <td><span id="summary-name"></span></td>
                                </tr>
                                <tr>
                                    <th>Phone</th>
                                    <td><span id="summary-phone"></span></td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td><span id="summary-email"></span></td>
                                </tr>
                                <tr>
                                    <th>Address</th>
                                    <td><span id="summary-address"></span></td>
                                </tr>
                            </tbody>
                        </table>

                        <!-- Event Details Table -->
                        <h5 class="mt-4">Event Details</h5>
                        <table class="table table-hover">
                            <tbody>
                                <tr>
                                    <th style="width: 30%">Event Title</th>
                                    <td><span id="summary-event-title"></span></td>
                                </tr>
                                <tr>
                                    <th>Package</th>
                                    <td><span id="summary-package"></span></td>
                                </tr>
                                <tr>
                                    <th>Menu</th>
                                    <td><span id="summary-menu"></span></td>
                                </tr>
                                <tr>
                                    <th>Number of Guests</th>
                                    <td><span id="summary-guests"></span></td>
                                </tr>
                                <tr id="summary-sponsors-container" style="display: none;">
                                    <th>Number of Sponsors</th>
                                    <td><span id="summary-sponsors"></span></td>
                                </tr>
                                <tr>
                                    <th>Date & Time</th>
                                    <td><span id="summary-datetime"></span></td>
                                </tr>
                                <tr>
                                    <th>Venue</th>
                                    <td><span id="summary-venue"></span></td>
                                </tr>
                                <tr>
                                    <th>Theme</th>
                                    <td><span id="summary-theme"></span></td>
                                </tr>
                            </tbody>
                        </table>

                        <!-- Special Requests Table -->
                        <h5 class="mt-4">Special Requests</h5>
                        <table class="table table-hover">
                            <tbody>
                                <tr>
                                    <td><span id="summary-requests"></span></td>
                                </tr>
                            </tbody>
                        </table>

                        <!-- Price Breakdown Table -->
                        <h5 class="mt-4">Price Breakdown</h5>
                        <table class="table table-hover">
                            <tbody>
                                <tr>
                                    <th style="width: 30%">Package Price</th>
                                    <td>₱<span id="summary-package-price"></span></td>
                                </tr>
                                <tr>
                                    <th>Additional Persons Cost</th>
                                    <td>₱<span id="summary-additional-cost"></span></td>
                                </tr>
                                <tr class="table-active">
                                    <th>Total Price</th>
                                    <td><strong>₱<span id="summary-total-price"></span></strong></td>
                                </tr>
                                <tr class="table-active">
                                    <th>Reservation Fee</th>
                                    <td><strong>₱<span>5,000.00</span></strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Edit</button>
                        <button type="button" class="btn btn-darkorange" id="confirmSubmit">Confirm Reservation</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /.content -->

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

<!-- Load jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Load jQuery UI -->
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
    document.getElementById('guests_number').addEventListener('input', function() {
        // Get the current value
        let value = parseInt(this.value);
        
        // Check if the value exceeds the max value
        if (value > 500) {
            this.value = 500;
        } else if (value < 1) {
            this.value = 1;
        }
    });
</script>


<script>
    var packages = [];

    <?php 
    foreach($packages as $package) {
        if (isset($package['is_displayed']) && $package['is_displayed'] === true) {
            echo 'packages.push({
                "package_name": "'. addslashes($package['package_name']) .'", 
                "package_type": "'. addslashes($package['package_type']) .'", 
                "persons": "'. addslashes($package['persons']) .'", 
                "price": "'. addslashes($package['price']) .'", 
                "menus": [';

            foreach($package['menus'] as $menu) {
                echo '{
                        "menu_name": "'. addslashes($menu['menu_name']) .'", 
                        "foods": [';

                foreach($menu['foods'] ?? [] as $food) {
                    if (is_array($food)) {
                        echo '{
                            "category": "'. addslashes($food['category'] ?? '') .'", 
                            "food": "'. addslashes($food['food'] ?? '') .'"
                        },';
                    }
                }
                echo ']},';
            }
            echo '], 
            "services": [';

            foreach($package['services'] as $service) {
                echo '{
                    "service": "'. addslashes($service['service']) .'"
                },';
            }

            echo ']
            });';
        }
    }
    ?>

    var events = [];
    <?php 
        foreach($reservations as $reservation) {
            // Ensure consistent date format
            $formattedDate = date('Y-m-d', strtotime($reservation['event_date']));
            echo 'events.push({
                "Event": "Reserved", 
                "Date": "'. addslashes($formattedDate) .'",
                "Status": "'. addslashes($reservation['status']) .'"
            });';
        }
    ?>

const addressData = <?php echo json_encode($addressData); ?>;
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('myForm');
    const submitBtn = form.querySelector('button[type="submit"]');
    const summaryModal = new bootstrap.Modal(document.getElementById('summaryModal'));

    // Create error message elements for all fields
    function createErrorElements() {
        const fields = form.querySelectorAll('input, select, textarea');
        fields.forEach(field => {
            const parent = field.parentElement;
            const existingError = parent.querySelector('.error-message');
            if (!existingError) {
                const errorDiv = document.createElement('div');
                errorDiv.className = 'error-message text-danger small mt-1';
                errorDiv.style.display = 'none';
                parent.appendChild(errorDiv);
            }
        });
    }

    // Initialize error elements
    createErrorElements();

    // Clear all error messages
    function clearErrors() {
        form.querySelectorAll('.error-message').forEach(errorDiv => {
            errorDiv.style.display = 'none';
            errorDiv.textContent = '';
        });
    }

    // Show error message for a specific field
    function showError(field, message) {
        const parent = field.parentElement;
        const errorDiv = parent.querySelector('.error-message');
        if (errorDiv) {
            errorDiv.textContent = message;
            errorDiv.style.display = 'block';
        }
    }

    submitBtn.addEventListener('click', function(e) {
        e.preventDefault();
        clearErrors();
        
        if (!validateForm()) {
            return;
        }

        updateSummaryModal();
        summaryModal.show();
    });

    document.getElementById('confirmSubmit').addEventListener('click', function() {
        form.submit();
    });

    function validateForm() {
        let isValid = true;

        // Validate required fields
        const requiredFields = {
            'first_name': 'First Name',
            'last_name': 'Last Name',
            'phone': 'Phone',
            'email': 'Email',
            'street_houseno': 'House Number, Building, Street',
            'event_title': 'Event Title',
            'guests_number': 'Number of Guests',
            'event_date': 'Date of Event',
            'event_time': 'Time of Event',
            'venue': 'Venue',
            'theme': 'Theme'
        };

        // Check each required field
        for (const [fieldName, label] of Object.entries(requiredFields)) {
            const field = document.getElementsByName(fieldName)[0];
            if (!field.value.trim()) {
                showError(field, `${label} is required`);
                isValid = false;
            }
        }

        // Validate dropdowns
        const requiredDropdowns = {
            'region': 'Region',
            'province': 'Province',
            'city': 'City',
            'barangay': 'Barangay',
            'package_name': 'Package',
            'menu_name': 'Menu'
        };

        for (const [dropdownId, label] of Object.entries(requiredDropdowns)) {
            const dropdown = document.getElementById(dropdownId);
            if (!dropdown.value || dropdown.value === '' || dropdown.value === 'Select a ' + label) {
                showError(dropdown, `Please select a ${label}`);
                isValid = false;
            }
        }

        // Special validation for wedding package sponsors
        const packageType = document.getElementById('package_type').value;
        const sponsorsInput = document.getElementById('sponsors');
        if (packageType === 'Wedding' && (!sponsorsInput.value || sponsorsInput.value <= 0)) {
            showError(sponsorsInput, 'Please enter the number of sponsors');
            isValid = false;
        }

        // Phone number format validation
        const phoneInput = document.getElementsByName('phone')[0];
        const phonePattern = /^(09|\+639)\d{9}$/;
        if (phoneInput.value && !phonePattern.test(phoneInput.value)) {
            showError(phoneInput, 'Please enter a valid Philippine mobile number');
            isValid = false;
        }

        const emailInput = document.getElementsByName('email')[0];
        if (emailInput.value) {
            // First check basic email format
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailPattern.test(emailInput.value)) {
                showError(emailInput, 'Please enter a valid email address');
                isValid = false;
            } else {
                // Then check allowed domains
                const allowedDomains = [
                    'gmail.com',
                    'yahoo.com',
                    'outlook.com',
                    'hotmail.com',
                    'icloud.com',
                    'aol.com',
                    'proton.me',
                    'protonmail.com',
                    'yahoo.co.uk',
                    'msn.com'
                ];
                
                const domain = emailInput.value.split('@')[1].toLowerCase();
                if (!allowedDomains.includes(domain)) {
                    showError(emailInput, 'Please use a common email provider (Gmail, Yahoo, Outlook, etc.)');
                    isValid = false;
                }
            }
        }

        // Number of guests validation
        const guestsInput = document.getElementById('guests_number');
        if (guestsInput.value && (guestsInput.value < 1 || guestsInput.value > 500)) {
            showError(guestsInput, 'Number of guests must be between 1 and 500');
            isValid = false;
        }

        return isValid;
    }

    function updateSummaryModal() {
    // Personal Information
    document.getElementById('summary-name').textContent = 
        `${document.getElementsByName('first_name')[0].value} ${document.getElementsByName('last_name')[0].value}`;
    document.getElementById('summary-phone').textContent = document.getElementsByName('phone')[0].value;
    document.getElementById('summary-email').textContent = document.getElementsByName('email')[0].value;
    
    // Address
    const address = [
        document.getElementsByName('street_houseno')[0].value = document.getElementsByName('street_houseno')[0].value.toUpperCase(),
        document.getElementById('barangay').options[document.getElementById('barangay').selectedIndex]?.text,
        document.getElementById('city').options[document.getElementById('city').selectedIndex]?.text,
        document.getElementById('province').options[document.getElementById('province').selectedIndex]?.text,
        document.getElementById('region').options[document.getElementById('region').selectedIndex]?.text
    ].filter(Boolean).join(', ');
    document.getElementById('summary-address').textContent = address;
    
    // Event Details
    document.getElementById('summary-event-title').textContent = document.getElementsByName('event_title')[0].value;
    document.getElementById('summary-package').textContent = document.getElementById('package_name').value;
    document.getElementById('summary-menu').textContent = document.getElementById('menu_name').value;
    document.getElementById('summary-guests').textContent = document.getElementById('guests_number').value;
    document.getElementById('summary-datetime').textContent = 
        `${document.getElementById('event_date').value} ${document.getElementById('event_time').value}`;
    document.getElementById('summary-venue').textContent = document.getElementsByName('venue')[0].value;
    document.getElementById('summary-theme').textContent = document.getElementsByName('theme')[0].value;
    
    // Sponsors (only for wedding packages)
    const sponsorsContainer = document.getElementById('summary-sponsors-container');
    if (document.getElementById('package_type').value === 'Wedding') {
        sponsorsContainer.style.display = 'table-row';
        document.getElementById('summary-sponsors').textContent = document.getElementById('sponsors').value;
    } else {
        sponsorsContainer.style.display = 'none';
    }
    
    // Special Requests
    document.getElementById('summary-requests').textContent = 
        document.getElementsByName('other_requests')[0].value || 'None';
    
    // Price Breakdown
    document.getElementById('summary-package-price').textContent = 
        document.getElementById('total-package-price').textContent;
    document.getElementById('summary-additional-cost').textContent = 
        document.getElementById('total-additional-person-price').textContent;
    document.getElementById('summary-total-price').textContent = 
        document.getElementById('total-price').textContent;
}

    // Add input event listeners to clear errors when user starts typing
    form.querySelectorAll('input, select, textarea').forEach(field => {
        field.addEventListener('input', function() {
            const errorDiv = this.parentElement.querySelector('.error-message');
            if (errorDiv) {
                errorDiv.style.display = 'none';
                errorDiv.textContent = '';
            }
        });
    });
});
</script>

@vite('resources/js/address.js')
@vite('resources/js/guestreservation.js')
@vite('resources/js/modal.js')

@endsection
