function packageChange() {
    const selectedPackageName = $('#package_name').val(); // Get the selected package
    const menuDropdown = document.querySelector('select[name="menu_name"]');
    const sponsorsField = document.getElementById('sponsors');
    const menuError = document.getElementById('menu_name_error'); // Get the error message element

    // Reset the menu select field when changing package
    if (menuDropdown) {
        menuDropdown.value = ''; // Reset the menu dropdown value to empty
    }

    if (selectedPackageName) {
        const selectedPackage = packages.find(pkg => pkg.package_name === selectedPackageName);

        if (selectedPackage) {
            // Enable the menu dropdown if package has menus
            if (selectedPackage.menus && selectedPackage.menus.length > 0) {
                menuDropdown.disabled = false;

                // Clear previous options but keep placeholder
                const placeholderOption = menuDropdown.querySelector('option[value=""]');
                menuDropdown.innerHTML = ''; // Remove all options
                if (placeholderOption) {
                    menuDropdown.appendChild(placeholderOption); // Add back placeholder option
                }

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
            } else {
                // If no menus exist for this package, disable the menu dropdown
                menuDropdown.disabled = true;
                menuDropdown.required = false;
                menuError.style.display = 'none'; // Hide any error message
            }

            // Enable sponsors field if it's a wedding package
            if (sponsorsField) {
                sponsorsField.disabled = (selectedPackage.package_type !== 'Wedding');
            } else {
                console.error("Sponsors field not found in the DOM.");
            }
        } else {
            console.error("Selected package not found.");
        }
    } else {
        // If no package is selected, reset everything
        if (menuDropdown) {
            menuDropdown.disabled = true;
            menuDropdown.required = false; // Remove the required attribute when no package is selected
            menuDropdown.value = ''; // Reset the menu field value to empty
        }
        if (menuError) menuError.style.display = 'none'; // Hide the error message when no package is selected
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
    const datePicker = flatpickr("#event_date", {
        dateFormat: "Y-m-d", // Format of the date to send to the server
        minDate: "today",    // Prevent past dates
        onChange: function(selectedDates) {
            const selectedDate = selectedDates[0];
            const today = new Date();

            // If the selected date is today, update the time picker to prevent past times
            if (selectedDate.toDateString() === today.toDateString()) {
                timePicker.set('minTime', getCurrentTime());  // Update time picker for today
            } else {
                timePicker.set('minTime', "00:00");  // Allow any time for future dates
            }
        }
    });

    // Initialize Flatpickr for the time input with AM/PM option
    const timePicker = flatpickr("#event_time", {
        enableTime: true,    // Enable time selection
        noCalendar: true,    // Disable calendar (only time)
        dateFormat: "h:i K", // Format of the time to send to the server (12-hour format with AM/PM)
        minTime: getCurrentTime(), // Default minTime is set when the page loads (if today)
        onOpen: function() {
            const selectedDate = datePicker.selectedDates[0];
            const today = new Date();

            // If the selected date is today, update minTime based on the current time
            if (selectedDate && selectedDate.toDateString() === today.toDateString()) {
                this.set('minTime', getCurrentTime());  // Update time picker for today
            } else {
                this.set('minTime', "00:00");  // Allow any time for future dates
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