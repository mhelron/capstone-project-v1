function packageChange() {
    const selectedPackageName = $('#package_name').val(); // Get the selected package
    const menuDropdown = document.querySelector('select[name="menu_name"]');
    const sponsorsField = document.getElementById('sponsors');
    const menuError = document.getElementById('menu_name_error'); // Get the error message element

    // Reset the menu select field when changing package
    menuDropdown.value = ''; // Reset the menu dropdown value to empty

    if (selectedPackageName) {
        const selectedPackage = packages.find(pkg => pkg.package_name === selectedPackageName);

        if (selectedPackage) {
            // Enable the menu dropdown if package has menus
            if (selectedPackage.menus && selectedPackage.menus.length > 0) {
                menuDropdown.disabled = false;
                menuDropdown.required = true; // Make the menu field required

                // Clear previous options but keep placeholder
                const placeholderOption = menuDropdown.querySelector('option[value=""]');
                menuDropdown.innerHTML = ''; // Remove all options
                menuDropdown.appendChild(placeholderOption); // Add back placeholder option

                // Add the new menu options for the selected package
                selectedPackage.menus.forEach(menu => {
                    const option = document.createElement('option');
                    option.value = menu.menu_name;
                    option.textContent = menu.menu_name;

                    // Tooltip for food items under each menu
                    const foodsList = menu.foods.map(food => food.food).join('\n');
                    option.title = foodsList || "No foods available";
                    menuDropdown.appendChild(option);
                });

                // Hide the error message when the menu is enabled
                menuError.style.display = 'none';
            } else {
                // If no menus exist for this package, disable the menu dropdown
                menuDropdown.disabled = true;
                menuDropdown.required = false;
                menuError.style.display = 'none'; // Hide any error message
            }

            // Disable sponsors field if it's not a wedding package
            sponsorsField.disabled = (selectedPackage.package_type !== 'Wedding');
        } else {
            console.error("Selected package not found.");
        }
    } else {
        // If no package is selected, reset everything
        menuDropdown.disabled = true;
        menuDropdown.required = false; // Remove the required attribute when no package is selected
        menuDropdown.value = ''; // Reset the menu field value to empty
        menuError.style.display = 'none'; // Hide the error message when no package is selected
    }
}



$(document).ready(function() {
    packageChange();
    $('#package_name').on('change', packageChange);

    // Initialize tooltips for the menu dropdown
    $('select[name="menu_name"]').tooltip({
        content: function() {
            return $(this).find('option:selected').attr('title');
        },
        items: "> option",
        track: true,
        tooltipClass: "custom-tooltip"
    });
});

document.addEventListener('DOMContentLoaded', function() {
    // Initialize Flatpickr for the date input
    flatpickr("#event_date", {
        dateFormat: "Y-m-d", // Format of the date to send to the server
        minDate: "today",    // Prevent past dates
        onChange: function(selectedDates) {
            // When the user selects a date, update the minTime for the time picker
            const selectedDate = selectedDates[0];
            const today = new Date();

            // If the selected date is today, update the time picker to not allow past times
            if (selectedDate.toDateString() === today.toDateString()) {
                flatpickr("#event_time").set('minTime', getCurrentTime());
            } else {
                // Otherwise, allow any time since the date is in the future
                flatpickr("#event_time").set('minTime', "00:00");
            }
        }
    });

    // Initialize Flatpickr for the time input with AM/PM option
    flatpickr("#event_time", {
        enableTime: true,    // Enable time selection
        noCalendar: true,    // Disable calendar (only time)
        dateFormat: "h:i K", // Format of the time to send to the server (12-hour format with AM/PM)
        minTime: getCurrentTime(), // Default minTime is set when the page loads (if today)
        onOpen: function() {
            // Update minTime dynamically each time the time picker is opened
            const selectedDate = flatpickr("#event_date").selectedDates[0];
            const today = new Date();

            // If the selected date is today, update minTime based on the current time
            if (selectedDate && selectedDate.toDateString() === today.toDateString()) {
                this.set('minTime', getCurrentTime());
            } else {
                // Allow any time for future dates
                this.set('minTime', "00:00");
            }
        }
    });

    // Function to get the current time in the correct format (24-hour format)
    function getCurrentTime() {
        var now = new Date();
        var hours = now.getHours();
        var minutes = now.getMinutes();

        // Ensure hours and minutes are two digits
        hours = (hours < 10 ? '0' : '') + hours; // Two-digit hour
        minutes = (minutes < 10 ? '0' : '') + minutes; // Two-digit minutes

        // Return the formatted time string (HH:mm)
        return hours + ':' + minutes;
    }
});

// Ensure `menu_name` gets an empty value when the field is disabled
document.getElementById('package_name').addEventListener('change', function() {
    const menuField = document.getElementById('menu_name');
    
    if (this.value) {
        menuField.disabled = false; // Enable the menu field
    } else {
        menuField.disabled = true; // Disable the menu field
        menuField.value = ''; // Reset the menu field value
    }
});

