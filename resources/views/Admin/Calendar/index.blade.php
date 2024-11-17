@extends('layouts.adminlayout')

@section('content')

@vite('resources/css/calendar.css')

<div>
    <div class="content pt-2">
        <div class="container-fluid">
            <div class="row">
                <!-- Left column for calendar -->
                <div class="col-lg-10">
                    <div class="calendar-container">
                        <div id='calendar'></div>
                    </div>
                </div>

                <!-- Right column for legend -->
                <div class="col-lg-2" style="padding-top:86px;">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Legend</h5>
                            <ul class="list-unstyled">
                                <li>
                                    <span class="status-dot" style="background-color: #ffa500;"></span> Pending
                                </li>
                                <li>
                                    <span class="status-dot" style="background-color: #28a745;"></span> Confirmed
                                </li>
                                <li>
                                    <span class="status-dot" style="background-color: #007bff;"></span> Finished
                                </li>
                                <li>
                                    <span class="status-dot" style="background-color: #6c757d;"></span> Pencil
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Event Form -->
    <div id="eventModal" class="modal fade" tabindex="-1" aria-labelledby="eventModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="eventModalLabel">Add Event</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="eventForm">
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
                                    <label for="menu_name">Menu</label>
                                    <select name="menu_name" class="form-control" id="menu_name" {{ old('menu_name') ? '' : 'disabled' }}>
                                        <option value="" disabled {{ old('menu_name') ? '' : 'selected' }}>Select a Menu</option>
                                        <!-- Dynamic options will be inserted here by JavaScript -->
                                    </select>

                                    <small id="menu_name_error" class="text-danger" style="display:none;"></small>
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
                                    <input type="text" id="event_date" name="event_date" value="{{ old('event_date') }}" class="form-control" placeholder="Select date" style="pointer-events: none;">
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

@vite('resources/js/calendar.js')

<script>
    // Define the events array
    var events = [];

    <?php 
        foreach($reservations as $reservation){
            echo 'events.push({"Event": "'. addslashes($reservation['package_name']) .'", 
            "Date": "'. addslashes($reservation['event_date']) .'",
            "Time": "'. addslashes($reservation['event_time']) .'", 
            "Status": "'. addslashes($reservation['status']) .'"});';
        }
    ?>

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
    
</script>

@vite('resources/js/calendarreservation.js')

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://unpkg.com/tippy.js@6.3.1/dist/tippy.all.min.js"></script>
<link rel="stylesheet" href="https://unpkg.com/tippy.js@6.3.1/dist/tippy.css" />

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<!-- Firebase SDK Scripts -->
<script src="https://www.gstatic.com/firebasejs/9.1.3/firebase-app-compat.js"></script>
<script src="https://www.gstatic.com/firebasejs/9.1.3/firebase-database-compat.js"></script>

<script>
    // Firebase configuration
    const firebaseConfig = {
        apiKey: "AIzaSyARhHx8V5F3mILZujmFXVEwZtJbUMvrHVQ",
        authDomain: "capstone-project-v1-ddf72.firebaseapp.com",
        databaseURL: "https://capstone-project-v1-ddf72-default-rtdb.firebaseio.com",
        projectId: "capstone-project-v1-ddf72",
        storageBucket: "capstone-project-v1-ddf72.appspot.com",
        messagingSenderId: "831745544682",  
        appId: "1:831745544682:web:c2d48183143aaff9682293",
        measurementId: "G-GWDDD0KKBH"
    };

    // Initialize Firebase
    const app = firebase.initializeApp(firebaseConfig);
    const database = firebase.database(app);

    // Form submission handler
    document.getElementById('eventForm').addEventListener('submit', function(event) {
        event.preventDefault();  // Prevent default form submission

        // Get form data
        const firstName = document.getElementById('first_name').value;
        const lastName = document.getElementById('last_name').value;
        const phone = document.getElementById('phone').value;
        const email = document.getElementById('email').value;
        const address = document.getElementById('address').value;
        const packageName = document.getElementById('package_name').value;
        const menuName = document.getElementById('menu_name').value;
        const guestsNumber = document.getElementById('guests_number').value;
        const sponsors = document.getElementById('sponsors').value;
        const eventDate = document.getElementById('event_date').value;
        const eventTime = document.getElementById('event_time').value;
        const venue = document.getElementById('venue').value;
        const theme = document.getElementById('theme').value;
        const specialRequests = document.getElementById('other_requests').value;

        // Create an object to store form data
        const reservationData = {
            first_name: firstName,
            last_name: lastName,
            phone: phone,
            email: email,
            address: address,
            package_name: packageName,
            menu_name: menuName,
            guests_number: guestsNumber,
            sponsors: sponsors,
            event_date: eventDate,
            event_time: eventTime,
            venue: venue,
            theme: theme,
            other_requests: specialRequests
        };

        // Push the form data to Firebase Realtime Database
        const reservationRef = database.ref('reservations');  // Reference to the reservations node
        reservationRef.push(reservationData)
            .then(() => {
                alert("Reservation added successfully!");
                document.getElementById('eventForm').reset(); // Reset form
            })
            .catch((error) => {
                console.error("Error adding reservation: ", error);
                alert("Error adding reservation, please try again.");
            });
    });
</script>

@endsection