@extends('layouts.adminlayout')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Add Reservation</h1>
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
                    <a href="{{ route('admin.reservation') }}" class="btn btn-danger">Back</a>
                </div>

                <div class="card">
                    <div class="card-body form-container">
                        <form id="myForm" action="{{ route('admin.reserve.reserve') }}" method="POST">
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
                                <!-- Address -->
                                <div class="form-group col-md-12 mb-3">
                                    <label>Address</label><span class="text-danger"> *</span>
                                    <input type="text" name="address" value="{{ old('address') }}" class="form-control">
                                    @if ($errors->has('address'))
                                        <small class="text-danger">{{ $errors->first('address') }}</small>
                                    @endif
                                </div>
                            </div>

                            <div class="row">
                                <!-- Package -->
                                <div class="form-group col-md-6 mb-3">
                                    <label for="package_name">Package</label>
                                    <select onchange="packageChange()" name="package_name" id="package_name" class="form-control">
                                        <option value="" disabled {{ old('package_name') ? '' : 'selected' }}>Select a Package</option>
                                        @foreach ($packages as $package)
                                            <option value="{{ $package['package_name'] }}" {{ old('package_name') == $package['package_name'] ? 'selected' : '' }}>
                                                {{ $package['package_name'] }}
                                            </option>
                                        @endforeach
                                    </select>

                                    @if ($errors->has('package_name'))
                                        <small class="text-danger">{{ $errors->first('package_name') }}</small>
                                    @endif
                                </div>

                                <div class="form-group col-md-6 mb-3">
                                    <label>Menu</label>
                                    <select name="menu_name" class="form-select" disabled>
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <!-- Number of Guests -->
                                <div class="form-group col-md-6 mb-3">
                                    <label>Number of Guests</label><span class="text-danger"> *</span>
                                    <input type="number" name="guests_number" value="{{ old('guests_number') }}" class="form-control">
                                    @if ($errors->has('guests_number'))
                                        <small class="text-danger">{{ $errors->first('guests_number') }}</small>
                                    @endif
                                </div>

                                <!-- Number of Sponsors -->
                                <div class="form-group col-md-6 mb-3">
                                    <label>Number of Principal Sponsors (Ninong, Ninang & Parents)</label>
                                    <input type="number" id="sponsors" name="sponsors" value="{{ old('sponsors') }}" class="form-control" disabled>
                                    @if ($errors->has('sponsors'))
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
                                    <label>Color Motif or Theme</label><span class="text-danger"> *</span>
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

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary float-end">Add Booking</button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<!-- /.content -->

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

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

    function packageChange() {
        const selectedPackageName = $('#package_name').val();
        const menuDropdown = document.querySelector('select[name="menu_name"]');

        if (selectedPackageName) {
            const selectedPackage = packages.find(pkg => pkg.package_name === selectedPackageName);

            if (selectedPackage) {
                menuDropdown.disabled = false;
                document.getElementById('sponsors').disabled = (selectedPackage.package_type !== 'Wedding');

                // Clear existing options and add options for the selected package's menus
                menuDropdown.innerHTML = '<option value="">Select a Menu</option>';
                selectedPackage.menus.forEach(menu => {
                    const option = document.createElement('option');
                    option.value = menu.menu_name;
                    option.textContent = menu.menu_name;

                    // Get only the food names for the tooltip
                    const foodsList = menu.foods.map(food => food.food).join('\n');
                    option.title = foodsList || "No foods available";

                    menuDropdown.appendChild(option);
                });
            } else {
                console.error("Selected package not found.");
            }
        } else {
            menuDropdown.disabled = true;
        }
    }

    $(document).ready(function() {
        packageChange();
        $('#package_name').on('change', packageChange);

        // Initialize tooltips for the menu dropdown
        $('select[name="menu_name"]').tooltip({
            content: function() {
                return $(this).find('option:selected').attr('title');
            },
            items: "> option",
            track: true,
            tooltipClass: "custom-tooltip"
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Flatpickr for the date input
        flatpickr("#event_date", {
            dateFormat: "Y-m-d", // Format of the date to send to the server
            minDate: "today",    // Prevent past dates
        });

        // Initialize Flatpickr for the time input with AM/PM option
        flatpickr("#event_time", {
            enableTime: true,    // Enable time selection
            noCalendar: true,     // Disable calendar (only time)
            dateFormat: "h:i K",  // Format of the time to send to the server (12-hour format with AM/PM)
            time_24hr: false,     // Use 12-hour format
        });
    });
</script>

@endsection
