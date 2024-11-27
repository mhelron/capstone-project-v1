document.addEventListener('DOMContentLoaded', function() {
    console.log(events);

    var calendarEl = document.getElementById('calendar');
    
    // Get today's date in the correct format for comparison
    var today = new Date();
    today.setHours(0, 0, 0, 0); // Set time to midnight to ignore time during comparison

    var calendarEvents = events.map(function(event) {
        // Convert event date to a Date object
        var eventDate = new Date(event.Date);

        // Only include events that are on or after today
        if (eventDate >= today) {
            return {
                title: event.Event,
                start: event.Date,
                status: event.Status,
            };
        } else {
            return null; // Exclude past events
        }
    }).filter(event => event !== null && !['pencil', 'pending', 'cancelled', 'finished'].includes(event.status.toLowerCase())); // Exclude cancelled events

    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        aspectRatio: 1.5,
        headerToolbar: {
            left: 'title',
            right: 'prev,next today',
        },
        events: calendarEvents,
        eventColor: '#ff8c00',
        eventContent: function(arg) {
            // Custom rendering for events
            let eventDiv = document.createElement('div');
            eventDiv.classList.add('fc-event-content');
            eventDiv.innerText = arg.event.title;

            // Wrap in a container for proper positioning
            let wrapper = document.createElement('div');
            wrapper.appendChild(eventDiv);
            return { domNodes: [wrapper] };
        },
        height: 'auto', // Let FullCalendar adjust height automatically

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

    // Function to highlight past dates
    function highlightPastDates() {
        var today = new Date();
        today.setHours(0, 0, 0, 0); // Normalize time to midnight for comparison

        document.querySelectorAll('.fc-daygrid-day').forEach(function(dayCell) {
            var cellDate = new Date(dayCell.getAttribute('data-date'));
            if (cellDate < today) {
                dayCell.classList.add('past-date');
            } else {
                dayCell.classList.remove('past-date');
            }
        });
    }

    highlightPastDates(); // Initial call for the current month
});