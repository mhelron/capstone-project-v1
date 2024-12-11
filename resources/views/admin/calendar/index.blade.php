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

    <!-- Modal for Reserve Type Selection -->
    <div class="modal fade" id="reserveTypeModal" tabindex="-1" aria-labelledby="reserveTypeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="reserveTypeModalLabel">Select Reservation Type</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Please select the type of reservation you would like to make:</p>
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-primary" onclick="redirectToReservation('reserve')">
                            Reserve
                        </button>
                        <button type="button" class="btn btn-secondary" onclick="redirectToReservation('pencil')">
                            Pencil Reserve
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Add Bootstrap JS CDN for modal functionality -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Define the events array
    var events = [];

    <?php 
        foreach($reservations as $reservation){
            $uniqueKey = $reservation['event_date'] . "_" . $reservation['event_time'] . "_" . $reservation['package_name'];
            
            echo 'events.push({
                "id": "'. addslashes($uniqueKey) .'",
                "Event": "'. addslashes($reservation['package_name']) .'", 
                "Date": "'. addslashes($reservation['event_date']) .'", 
                "Time": "'. addslashes($reservation['event_time']) .'", 
                "Status": "'. addslashes($reservation['status']) .'"
            });';
        }
    ?>

    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            selectable: true,
            select: function(info) {
                // Store selected date in localStorage
                localStorage.setItem('selectedDate', info.startStr);
                
                // Show the reserve type modal
                var modalElement = document.getElementById('reserveTypeModal');
                if (modalElement) {
                    var modal = new bootstrap.Modal(modalElement);
                    modal.show();
                } else {
                    console.error('Modal element not found.');
                }
            },
            // Add event rendering and other calendar settings here
        });
        calendar.render();
    });

    // Function for handling redirection based on reservation type
    function redirectToReservation(type) {
        const selectedDate = localStorage.getItem('selectedDate');
        const baseUrl = type === 'reserve' ? 
            '{{ route("admin.reserve.reserve") }}' : 
            '{{ route("admin.reserve.pencil") }}';
        
        // Redirect to the appropriate form with the date as a query parameter
        window.location.href = `${baseUrl}?selected_date=${selectedDate}`;
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

@vite('resources/js/calendar.js')
@endsection
