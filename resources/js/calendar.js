document.addEventListener('DOMContentLoaded', function() {
    console.log(events);

    var calendarEl = document.getElementById('calendar');
    var calendarEvents = events.map(function(event) {
        // Ensure proper time format (24-hour) for the start and end times
        let startDateTimeStr = `${event.Date}T${convertTo24Hour(event.Time)}`;
        let startDateTime = new Date(startDateTimeStr);

        // Check if the start date-time is valid
        if (isNaN(startDateTime.getTime())) {
            console.error(`Invalid date for event: ${event.Event} on ${event.Date} at ${event.Time}`);
            return null;  // Skip this event if invalid date
        }

        // Calculate the end date-time (start time + 5 hours)
        let endDateTime = new Date(startDateTime);
        endDateTime.setHours(endDateTime.getHours() + 5);

        return {
            title: event.Event,
            start: startDateTime.toISOString(),
            end: endDateTime.toISOString(),
            status: event.Status,
        };
    }).filter(event => event !== null);  // Filter out invalid events

    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        aspectRatio: 1.5,
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek',
        },
        events: calendarEvents,

        eventDidMount: function(info) {
            // Get the event status
            const status = info.event.extendedProps.status;
            const dot = info.el.querySelector('.fc-event-dot');  // Get the event dot

            // Change the event dot color based on the event status
            if (dot) {
                switch (status.toLowerCase()) {
                    case 'pending':
                        dot.style.backgroundColor = '#ffcc00'; // Yellow for Pending
                        break;
                    case 'confirmed':
                        dot.style.backgroundColor = '#28a745'; // Green for Confirmed
                        break;
                    case 'finished':
                        dot.style.backgroundColor = '#6c757d'; // Gray for Finished
                        break;
                    case 'pencil':
                        dot.style.backgroundColor = '#ffc107'; // Orange for Pencil
                        break;
                    default:
                        dot.style.backgroundColor = ''; // Default color
                }
            }
        },

        dateClick: function(info) {
            const selectedDate = new Date(info.dateStr);
            const today = new Date();
            today.setHours(0, 0, 0, 0);  // Set to midnight for accurate comparison

            if (selectedDate >= today) {
                $('#eventModal').modal('show');
                $('#event_date').val(info.dateStr); 
                $('#eventTitle, #eventDescription, #eventTime').val('');
            } else {
                alert("Cannot select past dates.");
            }
        },

        dayCellDidMount: function(info) {
            var today = new Date();
            today.setHours(0, 0, 0, 0);

            var cellDate = new Date(info.date);
            if (cellDate.toDateString() === today.toDateString()) {
                var dateElement = info.el.querySelector('.fc-day-number');
                if (dateElement) {
                    var circle = document.createElement('span');
                    circle.classList.add('today-circle');
                    dateElement.appendChild(circle);
                }
            }
        },

        dayCellDidMount: function(info) {
            var today = new Date();
            today.setHours(0, 0, 0, 0);

            var cellDate = new Date(info.date);
            if (cellDate < today) {
                info.el.classList.add('past-date');
            }
        },
    });

    calendar.render();

    // Helper function to convert 12-hour format time to 24-hour format
    function convertTo24Hour(timeStr) {
        const [time, modifier] = timeStr.split(' ');
        let [hours, minutes] = time.split(':');
        
        if (modifier === 'PM' && hours !== '12') hours = (parseInt(hours, 10) + 12).toString();
        if (modifier === 'AM' && hours === '12') hours = '00';
        if (hours.length === 1) hours = '0' + hours;
        return `${hours}:${minutes}:00`;
    }
});
