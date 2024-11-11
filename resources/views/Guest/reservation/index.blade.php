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
            <div class="col-lg-8 mb-4">

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
                                        <label class="form-label">Is this a Reservation or Pencil Book? <span class="text-danger">*</span></label>
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

                            <!-- Package and Menu Selection -->
                            <div class="row">
                                <div class="form-group col-md-6 mb-3">
                                    <label for="package_name">Package</label>
                                    <select onchange="change()" name="package_name" id="package_name" class="form-control">
                                        <option value="" disabled {{ old('package_name') ? '' : 'selected' }}>Select a Package</option>
                                        @foreach ($packages as $package)
                                            <option value="{{ $package['package_name'] }}" data-persons="{{ $package['persons'] }}"
                                                    {{ old('package_name') == $package['package_name'] ? 'selected' : '' }}>
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
                                    <select name="menu_name" class="form-select" id="menu_name" disabled>
                                        <option value="">Select a Menu</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <!-- Number of Guests -->
                                <div class="form-group col-md-6 mb-3">
                                    <label>Number of Guests</label><span class="text-danger"> *</span>
                                    <input type="number" name="guests_number" class="form-control" id="guests_number" min="1">
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

                            <div class="row">
                                <div class="form-group col-md-6 mb-3">

                                </div>

                                <!-- Color Motif or Theme -->
                                <div class="form-group col-md-6">
                                    <h4>Total Breakdown</h4>
                                    <p class="d-flex justify-content-between">
                                        <strong>Additional Persons:</strong>
                                        <span id="total-additional-persons">0</span> 
                                        <span>x</span> 
                                        <span id="price-per-package-head">₱0.00</span>
                                    </p>
                                    <p class="d-flex justify-content-between">
                                        <strong>Total Additional Person Price: </strong>
                                        <span id="total-additional-person-price">₱0.00</span>
                                    </p>
                                    <p class="d-flex justify-content-between">
                                        <strong>Package Price: </strong>
                                        <span id="total-package-price">₱0.00</span>
                                    </p>
                                    <p class="d-flex justify-content-between">
                                        <strong>Total: </strong>
                                        <span id="total-price">₱0.00</span>
                                    </p>
                                </div>
                            </div>

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
                            <p><h4>Services</h4><span id="preview-package-services"></span></p>
                            <p><h4>Menu</h4></p>
                            <p><span id="preview-menu-items"></span></p>
                        </div>
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
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

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
            // Remove trailing comma and close the foods array
            echo ']},';
        }
        // Remove trailing comma and close the menus array
        echo '], 
        "services": [';

        // Add services to the package
        foreach($package['services'] as $service) {
            echo '{
                "service": "'. addslashes($service['service']) .'"
            },';
        }

        // Remove trailing comma and close the services array
        echo ']
        });';
    }
?>
</script>

@vite('resources/js/guestreservation.js')


<style>
.row .card {
    display: flex;
    flex-direction: column;
    height: 100%;
}

.blank-card {
    background-color: #fff; /* Light background for the blank card */
    display: flex;
    justify-content: center;  /* Horizontally center the content */
    align-items: center;      /* Vertically center the content */
}

.card-body {
    flex-grow: 1; /* Allow the body to take up available space */
    display: flex;
    flex-direction: column;
}

/* Card Border */
.card {
    border: 2px solid darkorange; /* Add darkorange border to the card */
    background-color: #f5f5dc; /* Light beige */
}

/* Input Field Border */
input.form-control,
select.form-control,
select.form-select,
textarea.form-control {
    border: 2px solid darkorange; /* Add orange border to input fields */
}

/* Add focus effect for input fields to enhance visibility */
input.form-control:focus,
select.form-control:focus,
select.form-select:focus,
textarea.form-control:focus {
    border-color: darkorange; /* Change border color to darkorange on focus */
    box-shadow: 0 0 5px darkorange; /* Optional: add a glow effect on focus */
}

input[type="radio"].form-check-input {
    border: 2px solid darkorange; /* Border color for the radio button */
    border-radius: 50%; /* Circular shape */
    width: 20px; /* Adjust the size */
    height: 20px; /* Adjust the size */
    appearance: none; /* Remove default browser styling */
    outline: none; /* Remove outline */
    transition: border-color 0.3s ease, background-color 0.3s ease; /* Smooth transition */
}

/* When the radio button is selected (checked) */
input[type="radio"].form-check-input:checked {
    background-color: darkorange; /* Change background color to darkorange */
    border-color: darkorange; /* Change the border color to darkorange */
}

/* When the radio button is focused */
input[type="radio"].form-check-input:focus {
    box-shadow: 0 0 5px darkorange; /* Optional: add a glowing effect on focus */
}

/* Optional: add a hover effect */
input[type="radio"].form-check-input:hover {
    border-color: darkorange; /* Change border color on hover */
}
</style>


@endsection
