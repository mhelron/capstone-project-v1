@extends('layouts.adminLayout')

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
                    <a href="{{route('admin.calendar')}}" class="btn btn-danger">Back</a>
                </div>

                <div class="card">
                    <div class="card-body form-container">
                        <form id="myForm" action="{{ route('admin.reserve.reserve') }}" method="POST">
                            @csrf

                            <div class="form-row">
                                <div class="col-md-6 mb-3">
                                    <label>First Name</label>
                                    <input type="text" name="first_name" value="{{ old('first_name') }}" class="form-control">
                                    @if ($errors->has('first_name'))
                                        <small class="text-danger">{{ $errors->first('first_name') }}</small>
                                    @endif
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label>Last Name</label>
                                    <input type="text" name="last_name" value="{{ old('last_name') }}" class="form-control">
                                    @if ($errors->has('last_name'))
                                        <small class="text-danger">{{ $errors->first('last_name') }}</small>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <label>Address</label>
                                <input type="text" name="address" value="{{ old('address') }}" class="form-control">
                                @if ($errors->has('address'))
                                    <small class="text-danger">{{ $errors->first('address') }}</small>
                                @endif
                            </div>

                            <div class="form-row">
                                <div class="col-md-6 mb-3">
                                    <label>Phone</label>
                                    <input type="text" name="phone" value="{{ old('phone') }}" class="form-control">
                                    @if ($errors->has('phone'))
                                        <small class="text-danger">{{ $errors->first('phone') }}</small>
                                    @endif
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label>Email</label>
                                    <input type="email" name="email" value="{{ old('email') }}" class="form-control">
                                    @if ($errors->has('email'))
                                        <small class="text-danger">{{ $errors->first('email') }}</small>
                                    @endif
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-md-6 form-group mb-3">
                                    <label for="area_name">Area</label>
                                        <select onchange="areaChange()" name="area_name" id="area_name" class="form-control">
                                            <option value="" disabled selected>Select an Area</option>
                                        @foreach ($areas as $area)
                                            <option>{{ $area }}</option>
                                        @endforeach
                                        </select>

                                    @if ($errors->has('area_name'))
                                        <small class="text-danger">{{ $errors->first('area_name') }}</small>
                                    @endif
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="package_name">Package</label>
                                    <select onchange="packageChange()" name="package_name" id="package_name" class="form-control" disabled>
                                        <option value="" disabled selected>Select a Package</option>
                                        
                                    </select>

                                    @if ($errors->has('package_name'))
                                        <small class="text-danger">{{ $errors->first('package_name') }}</small>
                                    @endif
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-md-6 form-group mb-3">
                                    <label for="menu_name">Menu</label>
                                        <select name="menu_name" id="menu_name" class="form-control" disabled>
                                            <option value="" disabled selected>Select a Menu</option>

                                    </select>

                                    @if ($errors->has('menu_name'))
                                        <small class="text-danger">{{ $errors->first('menu_name') }}</small>
                                    @endif
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label>Date of Event</label>
                                    <input type="date" name="event_date" value="{{ old('event_date') }}" class="form-control">
                                    @if ($errors->has('event_date'))
                                        <small class="text-danger">{{ $errors->first('event_date') }}</small>
                                    @endif
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-md-6 mb-3">
                                    <label>Number of Guests</label>
                                    <input type="number" name="guests_number" value="{{ old('guests_number') }}" class="form-control">
                                    @if ($errors->has('guests_number'))
                                        <small class="text-danger">{{ $errors->first('guests_number') }}</small>
                                    @endif
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label>Number of principal sponsors (ninong, ninang & parents)</label>
                                    <input type="number" id="sponsors" name="sponsors" value="{{ old('sponsors') }}" class="form-control" disabled>
                                    @if ($errors->has('sponsors'))
                                        <small class="text-danger">{{ $errors->first('sponsors') }}</small>
                                    @endif
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-md-6 mb-3">
                                    <label>Location or Venue</label>
                                    <input type="text" name="venue" value="{{ old('venue') }}" class="form-control">
                                    @if ($errors->has('venue'))
                                        <small class="text-danger">{{ $errors->first('venue') }}</small>
                                    @endif
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label>Serving Time / Time of Event</label>
                                    <input type="time" name="event_time" value="{{ old('event_time') }}" class="form-control">
                                    @if ($errors->has('event_time'))
                                        <small class="text-danger">{{ $errors->first('event_time') }}</small>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <label>Color Motif or Theme</label>
                                <input type="text" name="theme" value="{{ old('theme') }}" class="form-control">
                                @if ($errors->has('theme'))
                                    <small class="text-danger">{{ $errors->first('theme') }}</small>
                                @endif
                            </div>

                            <div class="form-group mb-3">
                                <label>Others (Request)</label>
                                <textarea name="other_requests" class="form-control">{{ old('other_requests') }}</textarea>
                            </div>

                            <div class="form-group mb-3">
                                <button type="submit" class="btn btn-primary">Add Booking</button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<!-- /.content -->

<!-- Script para sa dependedent dropdown para sa Packages base sa selected area ni user-->
<script>

// container ng packages na i-pupush natin
var packages = [];

// php syntax para ma push ang package data sa package var
<?php 
    foreach($packages as $package){
        echo 'packages.push({"area_name": "'. $package['area_name'] .'", "package_name": "'. $package['package_name'] .'", "event_name": "'. $package['event_name'] .'"});';
    }
?>

// container ng events na i-pupush natin
var events = [];

// php syntax para ma push ang event data sa events var
<?php 
    foreach($events as $event_id => $event){
        echo 'events.push({"event_id": "'. $event_id .'", "event_name": "'. $event['event_name'] .'"});';
    }
?>

// container ng menus na i-pupush natin
var menus = [];

// php syntax para ma push ang menu data sa menus var
<?php 
    foreach($menus as $menu_id => $menu) {
        // kunin natin event_ids
        $event_ids = isset($menu['event_ids']) ? $menu['event_ids'] : [];
        
        // convert sa string
        $event_ids_string = implode(', ', $event_ids);
        
        // push data
        echo 'menus.push({"menu_name": "'. $menu['menu_name'] .'", "event_ids": "'. $event_ids_string .'"});';
    }
?>

// pang map sa event_ids into event_name
var eventMap = {};
events.forEach(function(event) {
    eventMap[event.event_id] = event.event_name;
});

// mapping
menus.forEach(function(menu) {
    var eventIds = menu.event_ids.split(', ');
    var eventNames = eventIds.map(function(eventId) {
        return eventMap[eventId] || eventId;
    });
    menu.event_name = eventNames.join(', ');
});

// i-filter yung values ng package dropdown base sa area selected
function areaChange() {
    document.getElementById('package_name').disabled = false;
    $('#package_name').html('<option value="" disabled selected>Select a Package</option>');
    packages.forEach(package => {
        if(package['area_name'] == $('#area_name :selected').text()){
            $('#package_name').append('<option value="' + package['package_name'] + '">' + package['package_name'] + '</option>');
        }
    });
}


// i-filter yung values ng menu dropdown base sa package selected
function packageChange() {
    document.getElementById('menu_name').disabled = false;
    $('#menu_name').html('<option value="" disabled selected>Select a Menu</option>');
    
    // Get the selected package name
    var selectedPackageName = $('#package_name :selected').text();
    
    // Find event_names related to the selected package
    var selectedEventNames = [];
    packages.forEach(package => {
        if (package.package_name === selectedPackageName) {
            selectedEventNames = package.event_name.split(', ');
        }
    });
    
    // Filter menus based on event_names
    menus.forEach(menu => {
        var menuEventNames = menu.event_ids.split(', ').map(id => eventMap[id] || id);
        var hasMatchingEventName = menuEventNames.some(name => selectedEventNames.includes(name));
        
        if (hasMatchingEventName) {
            $('#menu_name').append('<option value="' + menu['menu_name'] + '">' + menu['menu_name'] + '</option>');
        }
    });

    var wed = "Wedding Package";

    if ($('#package_name :selected').text() == wed) {
        document.getElementById('sponsors').disabled = false;
    } else {
        document.getElementById('sponsors').disabled = true;
    }

}

document.getElementById('myForm').addEventListener('submit', function(event) {
        const sponsorsField = document.getElementById('sponsors');
        sponsorsField.disabled = false;
        
        if (sponsorsField.value === '') {
            sponsorsField.value = '0';
        }
    });
</script>
 
@endsection