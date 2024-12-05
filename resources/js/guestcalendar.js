document.addEventListener('DOMContentLoaded', function() {
    console.log(events);
    var calendarEl = document.getElementById('calendar');
    
    var today = new Date();
    today.setHours(0, 0, 0, 0);
    var calendarEvents = events.map(function(event) {
        var eventDate = new Date(event.Date);
        if (eventDate >= today) {
            return {
                title: event.Event,
                start: event.Date,
                status: event.Status,
            };
        } else {
            return null;
        }
    }).filter(event => event !== null && !['pencil', 'pending', 'cancelled', 'finished'].includes(event.status.toLowerCase()));

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
            let eventDiv = document.createElement('div');
            eventDiv.classList.add('fc-event-content');
            eventDiv.style.textAlign = 'left';  // Force left alignment
            eventDiv.style.paddingLeft = '5px'; // Add some padding
            eventDiv.innerText = arg.event.title;
            let wrapper = document.createElement('div');
            wrapper.style.width = '100%';       // Make wrapper full width
            wrapper.appendChild(eventDiv);
            return { domNodes: [wrapper] };
        },
        height: 'auto',
        dateClick: function(info) {
            const selectedDate = new Date(info.dateStr);
            const today = new Date();
            today.setHours(0, 0, 0, 0);
        
            // Check if selected date is in the past
            if (selectedDate < today) {
                alert("Past dates cannot be selected.");
                return;
            }
        
            // Calculate the minimum allowed date (3 days from today)
            const minAllowedDate = new Date(today);
            minAllowedDate.setDate(today.getDate() + 3);
        
            // Check if selected date is within the 3-day preparation period
            if (selectedDate >= today && selectedDate < minAllowedDate) {
                alert("Please select a date at least 3 days from today for preparation time.");
                return;
            }
        
            // Get the number of reservations on the selected date
            const eventsOnDate = calendarEvents.filter(event => 
                new Date(event.start).toISOString().split('T')[0] === info.dateStr
            ).length;
        
            // Check if the date is fully booked
            if (eventsOnDate >= 5) {
                alert("This date has reached the maximum number of reservations (5). Please select another date.");
                return;
            }
        
            // Use absolute URL to ensure proper redirection
            const baseUrl = window.location.origin;
            window.location.href = `${baseUrl}/reserve?event_date=${info.dateStr}`;
        }
    });
    
    calendar.render();
   
    function highlightPastDates() {
        var today = new Date();
        today.setHours(0, 0, 0, 0);
        
        // Calculate the minimum allowed date (3 days from today)
        const minAllowedDate = new Date(today);
        minAllowedDate.setDate(today.getDate() + 3);
    
        document.querySelectorAll('.fc-daygrid-day').forEach(function(dayCell) {
            var cellDate = new Date(dayCell.getAttribute('data-date'));
            
            // Remove all existing classes first
            dayCell.classList.remove('past-date', 'preparation-period', 'future-date');
            
            if (cellDate < today) {
                // Past dates
                dayCell.classList.add('past-date');
            } else if (cellDate < minAllowedDate) {
                // Today and next 2 days (preparation period)
                dayCell.classList.add('preparation-period');
            } else {
                // Future available dates
                dayCell.classList.add('future-date');
            }
        });
    }

    function updateDateCellsWithCount() {
        document.querySelectorAll('.fc-daygrid-day').forEach(function(dayCell) {
            const cellDate = dayCell.getAttribute('data-date');
            const eventsOnDate = calendarEvents.filter(event => 
                new Date(event.start).toISOString().split('T')[0] === cellDate
            ).length;

            // Debugging log to check reservations count
            console.log(`Date: ${cellDate}, Events Count: ${eventsOnDate}`);

            if (eventsOnDate > 0) {
                let countDiv = dayCell.querySelector('.reservation-count');
                if (!countDiv) {
                    countDiv = document.createElement('div');
                    countDiv.className = 'reservation-count';
                    dayCell.querySelector('.fc-daygrid-day-top').appendChild(countDiv);
                }
                countDiv.textContent = `${eventsOnDate}/5 reserved`;

                if (eventsOnDate >= 5) {
                    dayCell.classList.add('fully-booked');
                } else {
                    dayCell.classList.remove('fully-booked');
                }
            } else {
                // If no events, ensure the fully-booked class is removed
                dayCell.classList.remove('fully-booked');
            }
        });
    }

    highlightPastDates();
    updateDateCellsWithCount();
});
