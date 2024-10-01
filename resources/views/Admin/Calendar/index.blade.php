@extends('layouts.adminLayout')

@section('content')
<style>
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

    #calendar {
        width: 100%;
        /* Optional: You can set a min-height if desired */
    }
</style>
<div>
    <h1>Calendar Page</h1>

    <div class="card">
                    <div class="card-body">
                        <div id='calendar'></div>
                    </div>
                </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            aspectRatio: 1.5, // Maintain a good aspect ratio
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek',
            }
        });

        calendar.render();

        window.addEventListener('resize', function() {
            calendar.updateSize(); // Ensure the calendar resizes
        });

        document.querySelector('toogleSidebar').addEventListener('click', function() {
            setTimeout(function() {
                calendar.updateSize();
            }, 300); 
        });
    });
</script>
@endsection