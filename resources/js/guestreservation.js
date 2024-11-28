function change() {
    const selectedPackageName = $('#package_name').val();
    const menuDropdown = $('#menu_name');
    const guestsInput = $('#guests_number');
    const previewSection = $('#package-preview');
    const packageNameSpan = $('#preview-package-name');
    const packagePriceSpan = $('#preview-package-price');
    const packagePaxSpan = $('#preview-package-pax');
    const packageServicesList = $('#preview-package-services');
    const menuItemsList = $('#preview-menu-items');
    const additionalPersonsSpan = $("#total-additional-persons");
    const totalPriceSpan = $("#total-price");
    const totalPackagePriceSpan = $("#total-package-price");
    const pricePerPackageHeadSpan = $('#price-per-package-head');
    const sponsorField = $('#sponsors');  // Sponsor field element

    let packageSelect = document.getElementById('package_name');
    let selectedPackage = packageSelect.options[packageSelect.selectedIndex];
    let oldMenuValue = menuDropdown.find('option:selected').val(); // Store the old menu value

    let persons = selectedPackage.getAttribute('data-persons');

    if (selectedPackage.package_type === 'Wedding') {
        sponsorField.prop('disabled', false);  // Enable sponsor field for wedding packages
    } else {
        sponsorField.prop('disabled', true);   // Disable sponsor field for other packages
        sponsorField.val('');  // Optionally clear the value if not enabled
    }

    if (persons) {
        // Set the value if it's not already populated
        if (!guestsInput.val() || guestsInput.val() < persons) {
            guestsInput.val(persons); // Set the value to the default persons number
        }
    }

    guestsInput.attr('min', persons); // Ensure the minimum value is always the default

    menuDropdown.empty().append('<option value="" disabled selected>Select a Menu</option>');
    previewSection.hide();
    packageServicesList.empty();

    if (selectedPackageName) {
        const selectedPackage = packages.find(pkg => pkg.package_name === selectedPackageName);

        if (selectedPackage) {
            menuDropdown.prop('disabled', false);
            selectedPackage.menus.forEach(menu => {
                const option = new Option(menu.menu_name, menu.menu_name);
                if (menu.menu_name === oldMenuValue) {
                    option.selected = true;
                }
                menuDropdown.append(option);
            });

            packageNameSpan.text(selectedPackage.package_name);
            const basePrice = parseInt(selectedPackage.price, 10); // Ensure it's a whole number
            packagePriceSpan.text(basePrice);
            packagePaxSpan.text(selectedPackage.persons);

            packageServicesList.empty();
            if (selectedPackage.services && selectedPackage.services.length > 0) {
                selectedPackage.services.forEach(service => {
                    packageServicesList.append('<li>' + service.service + '</li>');
                });
            } else {
                packageServicesList.append('<li>No services available</li>');
            }

            const baseGuests = selectedPackage.persons;
            let pricePerAdditionalPerson = 350;

            if (selectedPackage.package_type === 'Wedding') {
                pricePerAdditionalPerson = 400;
                sponsorField.prop('disabled', false);  // Enable sponsor field for wedding packages
            } else {
                sponsorField.prop('disabled', true);  // Disable sponsor field for other packages
            }

            pricePerPackageHeadSpan.text(pricePerAdditionalPerson); // Price per head

            let currentGuests = Math.max(baseGuests, guestsInput.val());
            guestsInput.val(currentGuests);

            const additionalGuests = Math.max(0, currentGuests - baseGuests);
            const additionalPrice = additionalGuests * pricePerAdditionalPerson;
            const totalPrice = basePrice + additionalPrice;

            additionalPersonsSpan.text(additionalGuests);
            totalPriceSpan.text(totalPrice);
            totalPackagePriceSpan.text(basePrice);
            menuItemsList.empty();

            if (oldMenuValue) {
                $('#menu_name').trigger('change');
            }

            previewSection.show();
        } else {
            console.error("Selected package not found or has no menus.");
        }
    } else {
        menuDropdown.prop('disabled', true);
        previewSection.hide();
    }
}


$('#menu_name').change(function () {
    const selectedMenuName = $(this).val();
    const selectedPackageName = $('#package_name').val();
    const selectedPackage = packages.find(pkg => pkg.package_name === selectedPackageName);
    const menuItemsList = $('#preview-menu-items');

    if (selectedPackage && selectedMenuName) {
        const selectedMenu = selectedPackage.menus.find(menu => menu.menu_name === selectedMenuName);

        if (selectedMenu) {
            menuItemsList.empty();
            selectedMenu.foods.forEach(food => {
                menuItemsList.append(`<li><span style="font-weight: bold;">${food.category}</span>: ${food.food}</li>`);
            });
        }
    }
});

document.addEventListener('DOMContentLoaded', function () {
    // Function to calculate the minimum date (three days from today)
    function calculateMinDate() {
        const today = new Date();
        today.setDate(today.getDate() + 3); // Add 3 days to the current date
        return today.toISOString().split('T')[0]; // Format as YYYY-MM-DD
    }

    // Initialize Flatpickr for the date input
    const eventDatePicker = flatpickr("#event_date", {
        dateFormat: "Y-m-d", // Format of the date to send to the server
        minDate: calculateMinDate(), // Set minDate to three days from today
        onChange: function (selectedDates) {
            const selectedDate = selectedDates[0];
            const today = new Date();

            // If the selected date is today, update the time picker to not allow past times
            if (selectedDate.toDateString() === today.toDateString()) {
                const currentTime = getCurrentTime();
                // Only update the minTime for the eventTimePicker if it's different from the current value
                if (eventTimePicker.config.minTime !== currentTime) {
                    eventTimePicker.set('minTime', currentTime); // Set minTime to current time if today
                }
            } else {
                // Otherwise, allow any time for future dates
                if (eventTimePicker.config.minTime !== "00:00") {
                    eventTimePicker.set('minTime', "00:00");
                }
            }
        }
    });

    // Initialize Flatpickr for the time input with AM/PM option
    const eventTimePicker = flatpickr("#event_time", {
        enableTime: true,    // Enable time selection
        noCalendar: true,    // Disable calendar (only time)
        dateFormat: "h:i K", // Format of the time to send to the server (12-hour format with AM/PM)
        time_24hr: false,    // Use 12-hour format
        minTime: getCurrentTime(), // Default minTime is set when the page loads (if today)
        onOpen: function () {
            const selectedDate = eventDatePicker.selectedDates[0];
            const today = new Date();

            // If the selected date is today, update minTime dynamically based on the current time
            if (selectedDate && selectedDate.toDateString() === today.toDateString()) {
                this.set('minTime', getCurrentTime()); // Set minTime to current time
            } else {
                this.set('minTime', "00:00"); // Allow any time for future dates
            }
        }
    });

    // Function to get the current time in the correct format (24-hour format)
    function getCurrentTime() {
        const now = new Date();
        let hours = now.getHours();
        let minutes = now.getMinutes();

        // Ensure hours and minutes are two digits
        hours = (hours < 10 ? '0' : '') + hours; // Two-digit hour
        minutes = (minutes < 10 ? '0' : '') + minutes; // Two-digit minutes

        // Return the formatted time string (HH:mm)
        return hours + ':' + minutes;
    }
});

let isUpdatingGuests = false; // Add a flag to prevent infinite loop

$('#guests_number').on('input', function () {
    if (isUpdatingGuests) return; // Prevent nested calls
    isUpdatingGuests = true;

    const selectedPackageName = $('#package_name').val();
    const selectedPackage = packages.find(pkg => pkg.package_name === selectedPackageName);

    if (selectedPackage) {
        const baseGuests = selectedPackage.persons;
        let currentGuests = $(this).val();

        if (currentGuests < baseGuests) {
            $(this).val(baseGuests);
            currentGuests = baseGuests;
        }

        const pricePerAdditionalPerson = selectedPackage?.package_type === 'Wedding' ? 400 : 350;
        const additionalGuests = Math.max(0, currentGuests - baseGuests);
        const additionalPrice = additionalGuests * pricePerAdditionalPerson;
        const basePrice = parseInt(selectedPackage.price, 10); // Ensure it's a whole number
        const totalPrice = basePrice + additionalPrice;

        $('#total-additional-persons').text(additionalGuests);
        $('#price-per-package-head').text(pricePerAdditionalPerson);
        $('#total-price').text(totalPrice);  // Ensure it's a whole number
        $('#total-package-price').text(basePrice);
    }

    isUpdatingGuests = false; // Reset the flag
});

$(document).ready(function () {
    $('#package_name').on('change', change);

    // Run the change function when the page loads, and set default value for guests
    if ($('#package_name').val()) {
        change();

        // Set the default guests value if not already set
        if ($('#guests_number').val() === '') {
            $('#guests_number').val($('#package_name option:selected').data('persons'));
        }

        if ($('#guests_number').val()) {
            $('#guests_number').trigger('input');
        }
    }
});

document.addEventListener("DOMContentLoaded", function() {
    const packageSelect = document.getElementById('package_name'); // Package select element
    const guestsNumberInput = document.getElementById('guests_number'); // Guests input element
    const totalPackagePriceSpan = document.getElementById("preview-package-price"); // Package price span
    const additionalPersonsSpan = document.getElementById("total-additional-persons"); // Additional persons span
    const totalPriceSpan = document.getElementById("total-price"); // Total price span
    const totalPriceInput = document.getElementById("total_price"); // Hidden input for total price
    const pricePerPackageHeadSpan = document.getElementById('price-per-package-head'); // Define pricePerPackageHeadSpan

    let packagePrice = 0;
    let packagePersons = 0;
    let selectedPackage = null; // Add selectedPackage as a global variable

    // Function to update the total breakdown
    let totalPrice = 0; // Store the total price in a variable

    // Function to update the total breakdown
    function updateTotal(selectedPackage) {
        console.log("Updating total...");
        
        // Log the selected package and the current input values
        console.log("Selected Package:", selectedPackage);
        const pricePerAdditionalPerson = (selectedPackage.package_type === 'Wedding') ? 400 : 350;
        console.log("Price per Additional Person:", pricePerAdditionalPerson);

        // Ensure the guests number input is never empty
        const guestsInputValue = guestsNumberInput.value.trim() || packagePersons; // Default to packagePersons if empty
        console.log("Guests Input Value:", guestsInputValue);

        // Parse the value and handle cases for invalid or empty inputs
        const currentGuests = parseInt(guestsInputValue, 10);
        console.log("Parsed Guests Number:", currentGuests);

        // Check for invalid input
        if (isNaN(currentGuests) || currentGuests <= 0) {
            console.error("Invalid guests number input:", guestsInputValue);
            guestsNumberInput.value = packagePersons; // Reset to base number if invalid
            return;
        }

        // The rest of your logic for calculating prices remains the same
        const additionalGuests = Math.max(0, currentGuests - packagePersons);
        const additionalPrice = additionalGuests * pricePerAdditionalPerson;
        totalPrice = (parseInt(packagePrice, 10) || 0) + additionalPrice;

        console.log("Additional Guests:", additionalGuests);
        console.log("Additional Price:", additionalPrice);
        console.log("Total Price:", totalPrice);

        totalPackagePriceSpan.innerText = `${parseInt(packagePrice, 10) || 0}`;
        additionalPersonsSpan.innerText = additionalGuests;
        totalPriceSpan.innerText = `${totalPrice}`;
        pricePerPackageHeadSpan.innerText = pricePerAdditionalPerson;
        totalPriceInput.value = totalPrice;

        document.getElementById('total-additional-person-price').innerText = additionalPrice.toFixed(2);
    }

    // When the package is selected
    packageSelect.addEventListener("change", function() {
        const selectedPackageName = packageSelect.value;
        selectedPackage = packages.find(pkg => pkg.package_name === selectedPackageName);
    
        if (selectedPackage) {
            packagePrice = selectedPackage.price;
            packagePersons = selectedPackage.persons;
            updateTotal(selectedPackage); // Update breakdown and hidden input
        }
    });

    // When the number of guests changes
    guestsNumberInput.addEventListener("input", function() {
        if (selectedPackage) {
            updateTotal(selectedPackage); // Update breakdown and hidden input
        }
    });

    packageForm.addEventListener('submit', function () {
        // Ensure commas are removed from the hidden input field before submission
        totalPriceInput.value = totalPrice; // Use the stored totalPrice
    });
});
