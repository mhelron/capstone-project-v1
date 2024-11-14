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
            status: event.Status,  // Status field
        };

    }).filter(event => event !== null);  // Filter out invalid events

    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        aspectRatio: 1.5,
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,listWeek',
        },
        events: calendarEvents,

        eventDidMount: function(info) {
            const status = info.event.extendedProps.status; // Get the event status
        
            // Apply the border and background color based on the event status
            switch (status.toLowerCase()) {
                case 'pending':
                    info.el.style.setProperty('--fc-event-border-color', '#ffa500'); // Set border to orange for pending     --fc-event-text-color:
                    info.el.style.setProperty('--fc-event-bg-color', '#ffecb3'); // Set background to light orange
                    info.el.style.setProperty('--fc-event-text-color', '#ffa500'); // Set background to light orange
                    break;
                case 'confirmed':
                    info.el.style.setProperty('--fc-event-border-color', '#28a745'); // Set border to green for confirmed
                    info.el.style.setProperty('--fc-event-bg-color', '#d4edda'); // Set background to light green
                    info.el.style.setProperty('--fc-event-text-color', '#28a745'); // Set background to light orange
                    break;
                case 'finished':
                    info.el.style.setProperty('--fc-event-border-color', '#007bff'); // Set border to blue for finished
                    info.el.style.setProperty('--fc-event-bg-color', '#cce5ff'); // Set background to light blue
                    info.el.style.setProperty('--fc-event-text-color', '#007bff'); // Set background to light orange
                    break;
                case 'pencil':
                    info.el.style.setProperty('--fc-event-border-color', '#6c757d'); // Set border to gray for pencil
                    info.el.style.setProperty('--fc-event-bg-color', '#e2e3e5'); // Set background to light gray
                    info.el.style.setProperty('--fc-event-text-color', '#6c757d'); // Set background to light orange
                    break;
                default:
                    info.el.style.setProperty('--fc-event-border-color', ''); // Default border color
                    info.el.style.setProperty('--fc-event-bg-color', ''); // Default background color
                    info.el.style.setProperty('--fc-event-text-color', ''); // Set background to light orange
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
