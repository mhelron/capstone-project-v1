document.addEventListener('DOMContentLoaded', function() {
    console.log(events);

    var calendarEl = document.getElementById('calendar');
    var calendarEvents = events.map(function(event) {
        let startDateTimeStr = `${event.Date}T${convertTo24Hour(event.Time)}`;
        let startDateTime = new Date(startDateTimeStr);

        if (isNaN(startDateTime.getTime())) {
            console.error(`Invalid date for event: ${event.Event} on ${event.Date} at ${event.Time}`);
            return null;
        }

        let endDateTime = new Date(startDateTime);
        endDateTime.setHours(endDateTime.getHours() + 5);

        return {
            title: event.Event,
            start: startDateTime.toISOString(),
            end: endDateTime.toISOString(),
            status: event.Status,
        };
    }).filter(event => event !== null);

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
            const selectedDate = new Date(info.dateStr);
            const today = new Date();
            today.setHours(0, 0, 0, 0);

            if (selectedDate >= today) {
                $('#eventModal').modal('show');
                $('#event_date').val(info.dateStr); 
                $('#eventTitle, #eventDescription, #eventTime').val('');
            } else {
                alert("Cannot select past dates.");
            }
        },

        datesSet: function() {
            highlightPastDates();
        }
    });

    calendar.render();

    function convertTo24Hour(timeStr) {
        const [time, modifier] = timeStr.split(' ');
        let [hours, minutes] = time.split(':');

        if (modifier === 'PM' && hours !== '12') hours = (parseInt(hours, 10) + 12).toString();
        if (modifier === 'AM' && hours === '12') hours = '00';
        if (hours.length === 1) hours = '0' + hours;
        return `${hours}:${minutes}:00`;
    }

    function highlightPastDates() {
        var today = new Date();
        today.setHours(0, 0, 0, 0);

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
