document.addEventListener('DOMContentLoaded', function() {
    console.log(events);

    var calendarEl = document.getElementById('calendar');
    var today = new Date();
    today.setHours(0, 0, 0, 0); // Normalize the time to midnight for accurate comparison

    var calendarEvents = events
        .map(function(event) {
            let startDateTimeStr = `${event.Date}T${convertTo24Hour(event.Time)}`;
            let startDateTime = new Date(startDateTimeStr);

            if (isNaN(startDateTime.getTime())) {
                console.error(`Invalid date for event: ${event.Event} on ${event.Date} at ${event.Time}`);
                return null;
            }

            // Calculate end time
            let endDateTime = new Date(startDateTime);
            endDateTime.setHours(endDateTime.getHours() + 5); // Add 5 hours as an example duration

            // Ensure end time doesn't extend to the next day
            let maxEndDateTime = new Date(startDateTime);
            maxEndDateTime.setHours(23, 59, 59); // Set to 11:59:59 PM of the same day

            if (endDateTime > maxEndDateTime) {
                endDateTime = maxEndDateTime; // Clamp the end time to the same day
            }

            return {
                title: event.Event,
                start: startDateTime.toISOString(),
                end: endDateTime.toISOString(),
                status: event.Status,
            };
        })
        .filter(event => event !== null && event.status.toLowerCase() !== 'cancelled'); // Exclude cancelled events

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
            const status = info.event.extendedProps.status;

            switch (status.toLowerCase()) {
                case 'pending':
                    info.el.style.setProperty('--fc-event-border-color', '#ffa500');
                    info.el.style.setProperty('--fc-event-bg-color', '#ffecb3');
                    info.el.style.setProperty('--fc-event-text-color', '#ffa500');
                    break;
                case 'confirmed':
                    info.el.style.setProperty('--fc-event-border-color', '#28a745');
                    info.el.style.setProperty('--fc-event-bg-color', '#d4edda');
                    info.el.style.setProperty('--fc-event-text-color', '#28a745');
                    break;
                case 'finished':
                    info.el.style.setProperty('--fc-event-border-color', '#007bff');
                    info.el.style.setProperty('--fc-event-bg-color', '#cce5ff');
                    info.el.style.setProperty('--fc-event-text-color', '#007bff');
                    break;
                case 'pencil':
                    info.el.style.setProperty('--fc-event-border-color', '#6c757d');
                    info.el.style.setProperty('--fc-event-bg-color', '#e2e3e5');
                    info.el.style.setProperty('--fc-event-text-color', '#6c757d');
                    break;
                default:
                    info.el.style.setProperty('--fc-event-border-color', '');
                    info.el.style.setProperty('--fc-event-bg-color', '');
                    info.el.style.setProperty('--fc-event-text-color', '');
            }
        },

        dateClick: function(info) {
            // Store selected date in localStorage
            localStorage.setItem('selectedDate', info.dateStr);

            // Show the reserve type modal
            var modalElement = document.getElementById('reserveTypeModal');
            if (modalElement) {
                var modal = new bootstrap.Modal(modalElement);
                modal.show();
            } else {
                console.error('Modal element not found.');
            }
        },

        datesSet: function() {
            highlightPastDates();
        }
    });

    calendar.render();

    // Function to convert 12-hour format to 24-hour format
    function convertTo24Hour(timeStr) {
        const [time, modifier] = timeStr.split(' ');
        let [hours, minutes] = time.split(':');

        if (modifier === 'PM' && hours !== '12') hours = (parseInt(hours, 10) + 12).toString();
        if (modifier === 'AM' && hours === '12') hours = '00';
        if (hours.length === 1) hours = '0' + hours;
        return `${hours}:${minutes}:00`;
    }

    // Function to highlight past dates
    function highlightPastDates() {
        document.querySelectorAll('.fc-daygrid-day').forEach(function(dayCell) {
            var cellDate = new Date(dayCell.getAttribute('data-date'));
            if (cellDate < today) {
                dayCell.classList.add('past-date');
            } else {
                dayCell.classList.remove('past-date');
            }
        });
    }

    highlightPastDates(); // Initial call for current month
});
