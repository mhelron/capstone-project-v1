@extends('layouts.adminlayout')

@section('content')
<style>
    .calendar-container {
        display: flex;
        justify-content: center;  /* Centers horizontally */
        align-items: center;      /* Centers vertically */
        height: 87vh;            /* Ensure full viewport height */
        transition: width 0.3s ease, margin-left 0.3s ease; /* Adjust timing as desired */
    }
    
    #calendar .fc-daygrid-day-number,
    #calendar .fc-daygrid-day-top,
    #calendar .fc-daygrid-day-frame {
        text-decoration: none;
    }

    .fc .fc-button {
        border-radius: .375rem;
        padding: .375rem .75rem;
        font-size: 1rem;
        text-align: center;
        color: #fff;
    }

    .fc .fc-button-primary {
        background-color: #0d6efd;
        border: 1px solid #0d6efd;
    }

    .fc .fc-button:not(.fc-button-primary) {
        background-color: #e9ecef;
        border: 1px solid #ced4da;
        color: #495057;
    }

    .fc .fc-button-primary:hover {
        background-color: #0b5ed7;
        border-color: #0b5ed7;
    }
    
    .fc .fc-button-primary:focus {
        box-shadow: 0 0 0 .2rem rgba(38, 143, 255, .5);
    }

    .fc-event-title,
    .fc-daygrid-event .fc-event-title {
        white-space: normal !important; /* Allow line breaks in the title */
        word-wrap: break-word;          /* Wrap long words if needed */
    }

    .fc-daygrid-event {
        overflow: hidden;               /* Hide overflow for cleaner appearance */
        max-height: 100px;              /* Optional: Limit the height if there are multiple lines */
    }

    #calendar {
        width: 100%;
        max-width: 1200px;  /* Optional: Set a max-width to prevent the calendar from becoming too large */
    }
</style>
<div>
    <h1 style="padding-top: 35px;">Calendar</h1>

    <div class="content pt-2">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="calendar-container">
                        <div id='calendar'></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        
        // Prepare the events array in FullCalendar format
        var calendarEvents = events.map(function(event) {
            return {
                title: `${event.Event} - ${event.Time}`,       // Display package name as the event title
                start: event.Date,        // Use the event date as the start date
                // Optionally add a time if needed, e.g., `${event.Date}T${event.Time}`
            };
        });

        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            aspectRatio: 1.5,
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek',
            },
            events: calendarEvents  // Pass the formatted events to FullCalendar
        });

        calendar.render();

        // Resize calendar on window resize
        window.addEventListener('resize', function() {
            calendar.updateSize();
        });

        // Resize calendar on sidebar toggle
        document.querySelector('#toggleSidebar').addEventListener('click', function() {
            setTimeout(function() {
                calendar.updateSize();
            }, 300);
        });
    });

    // Define the events array
    var events = [];

    <?php 
        foreach($reservations as $reservation){
            echo 'events.push({"Event": "'. addslashes($reservation['package_name']) .'", "Date": "'. addslashes($reservation['event_date']) .'", "Time": "'. addslashes($reservation['event_time']) .'"});';
        }
    ?>
</script>
@endsection