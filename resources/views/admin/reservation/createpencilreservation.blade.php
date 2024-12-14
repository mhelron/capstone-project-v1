@extends('layouts.adminlayout')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 pt-4">Add Pencil Reservation</h1>
            </div>
        </div>
    </div>
</div>
<!-- /.content-header -->

<!-- Main content -->
<div class="content">
    <div class="container-fluid">
        <div class="row d-flex justify-content-center">
            <div class="col-lg-12">

                @if (session('status'))
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        {{ session('status') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="d-flex justify-content-end mb-2">
                    <a href="{{ route('admin.reservation', ['tab' => 'pencil']) }}" class="btn btn-danger">Back</a>
                </div>

                <div class="card">
                    <div class="card-body form-container">
                        <form id="myForm" action="{{ route('admin.reserve.pencil') }}" method="POST">
                            @csrf

                            <div class="row">
                                <!-- First Name -->
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
                                <div class="form-group col-md-6 mb-3">
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
                                    <label>Menu</label>
                                    <select name="menu_name" class="form-select" id="menu_name" {{ old('package_name') ? '' : 'disabled' }}>
                                        <option value="">Select a Menu</option>
                                        @if(old('menu_name'))
                                            <option value="{{ old('menu_name') }}" selected>{{ old('menu_name') }}</option>
                                        @endif
                                    </select>
                                    @if ($errors->has('menu_name') && !is_null(old('package_name')))
                                        <small class="text-danger">{{ $errors->first('menu_name') }}</small>
                                    @endif
                                </div>
                            </div>

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
                                <button type="submit" class="btn btn-primary float-end">Add Pencil Reservation</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-lg-4 mb-4" style="display:none;">
                <div class="card blank-card" style="display:none;">
                    <div class="card-body" style="display:none;">
                        <div style="display:none;">
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
        </div>
    </div>
</div>
<!-- /.content -->

<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
 var packages = [];

<?php 
    foreach($packages as $package) {
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
            foreach($menu['foods'] as $food) {
                echo '{
                    "category": "'. addslashes($food['category']) .'",
                    "food": "'. addslashes($food['food']) .'"
                },';
            }                
            echo ']
            },';
        }
        echo ']});';
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
console.log(packages)
</script>

@vite('resources/js/address.js')
@vite('resources/js/adminreservation.js')

@endsection
