@extends('layouts.guestlayout')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
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
            <div class="col-lg-8">

                @if (session('status'))
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        {{ session('status') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="card">
                    <div class="card-body form-container">
                        <form id="myForm" action="{{ route('admin.reserve.reserve') }}" method="POST">
                            @csrf

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                        <label class="form-label">Is this a Reservation or Pencil Book?<span class="text-danger">*</span></label>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="reserve_type" id="reservation" value="Reserve" {{ old('reserve_type') == 'Reserve' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="reservation">
                                                Reservation
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="reserve_type" id="pencilbook" value="Pencil" {{ old('reserve_type') == 'Pencil' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="pencilbook">
                                                Pencil Book
                                            </label>
                                        </div>
                                        @error('package_type')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                            </div>

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
                                <button type="submit" class="btn btn-primary float-end">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card" id="package-preview" style="display: none;">
                        <div class="card-body form-container">
                            <h5>Package Details</h5>
                            <p><strong>Package Name:</strong> <span id="preview-package-name"></span></p>
                            <p><strong>Package Type:</strong> <span id="preview-package-type"></span></p>
                            <h6>Menus:</h6>
                            <ul id="preview-menus"></ul>
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

<!-- HTML for the Number of Guests Field -->
<div class="form-group col-md-6 mb-3">
    <label>Number of Guests</label><span class="text-danger"> *</span>
    <input type="text" name="guests" id="guests" value="{{ old('guests') }}" class="form-control" readonly>
</div>

<script>
    var packages = [];

    <?php 
        foreach($packages as $package) {
            echo 'packages.push({
                "package_name": "'. addslashes($package['package_name']) .'",
                "package_type": "'. addslashes($package['package_type']) .'",
                "persons": "'. addslashes($package['persons']) .'",
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
        const guestsInput = document.getElementById('guests');
        const previewSection = $('#package-preview');
        const packageNameSpan = $('#preview-package-name');
        const packageTypeSpan = $('#preview-package-type');
        const menusList = $('#preview-menus');

        // Reset the menu dropdown
        menuDropdown.innerHTML = '<option value="">Select a Menu</option>';
        guestsInput.value = '';  // Reset the guests field

        if (selectedPackageName) {
            const selectedPackage = packages.find(pkg => pkg.package_name === selectedPackageName);

            if (selectedPackage) {
                // Enable the menu dropdown
                menuDropdown.disabled = false;
                
                // Populate the menu dropdown options
                selectedPackage.menus.forEach(menu => {
                    const option = document.createElement('option');
                    option.value = menu.menu_name;
                    option.textContent = menu.menu_name;
                    menuDropdown.appendChild(option);
                });

                // Populate guests input with the package's persons value
                guestsInput.value = selectedPackage.persons;

                // Populate package preview details
                packageNameSpan.text(selectedPackage.package_name);
                packageTypeSpan.text(selectedPackage.package_type);
                menusList.empty();

                selectedPackage.menus.forEach(menu => {
                    const menuItem = $('<li>').text(menu.menu_name);

                    // Add foods under each menu item
                    const foodList = $('<ul>');
                    menu.foods.forEach(food => {
                        foodList.append($('<li>').text(`${food.category}: ${food.food}`));
                    });

                    menuItem.append(foodList);
                    menusList.append(menuItem);
                });

                // Show the preview section
                previewSection.show();
            } else {
                console.error("Selected package not found.");
                previewSection.hide();
            }
        } else {
            // Hide the preview if no package is selected
            menuDropdown.disabled = true;
            previewSection.hide();
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