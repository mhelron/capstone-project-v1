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
    const sponsorField = $('#sponsors');
    const formColumn = $('.main-form');
 
    let packageSelect = document.getElementById('package_name');
    let selectedPackage = packageSelect.options[packageSelect.selectedIndex];
    let oldMenuValue = menuDropdown.find('option:selected').val();
 
    let persons = selectedPackage.getAttribute('data-persons');
 
    if (selectedPackage.package_type === 'Wedding') {
        sponsorField.prop('disabled', false);
    } else {
        sponsorField.prop('disabled', true);
        sponsorField.val('');
    }
 
    if (persons) {
        if (!guestsInput.val() || guestsInput.val() < persons) {
            guestsInput.val(persons);
        }
    }
 
    guestsInput.attr('min', persons);
    menuDropdown.empty().append('<option value="" disabled selected>Select a Menu</option>');
    packageServicesList.empty();
 
    if (selectedPackageName) {
        const selectedPackage = packages.find(pkg => pkg.package_name === selectedPackageName);
 
        if (selectedPackage) {
            formColumn.removeClass('col-lg-12').addClass('col-lg-8');
            setTimeout(() => {
                previewSection.show();
                $('.blank-card').addClass('visible');
            }, 100);
 
            menuDropdown.prop('disabled', false);
            selectedPackage.menus.forEach(menu => {
                const option = new Option(menu.menu_name, menu.menu_name);
                if (menu.menu_name === oldMenuValue) {
                    option.selected = true;
                }
                menuDropdown.append(option);
            });
 
            packageNameSpan.text(selectedPackage.package_name);
            const basePrice = parseInt(selectedPackage.price, 10);
            packagePriceSpan.text(basePrice);
            packagePaxSpan.text(selectedPackage.persons);
 
            packageServicesList.empty();
            if (selectedPackage.services?.length > 0) {
                selectedPackage.services.forEach(service => {
                    packageServicesList.append('<li>' + service.service + '</li>');
                });
            } else {
                packageServicesList.append('<li>No services available</li>');
            }
 
            const baseGuests = selectedPackage.persons;
            let pricePerAdditionalPerson = selectedPackage.package_type === 'Wedding' ? 400 : 350;
            
            sponsorField.prop('disabled', selectedPackage.package_type !== 'Wedding');
 
            pricePerPackageHeadSpan.text(pricePerAdditionalPerson);
 
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
        }
    } else {
        $('.blank-card').removeClass('visible');
        setTimeout(() => {
            previewSection.hide();
            formColumn.addClass('col-lg-12').removeClass('col-lg-8');
        }, 800);
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

let eventDatePicker; // Declare the instance globally
let eventTimePicker; // Declare time picker globally too

document.addEventListener('DOMContentLoaded', function () {
    function calculateMinDate() {
        const today = new Date();
        today.setDate(today.getDate() + 3);
        return today.toISOString().split('T')[0];
    }

    function getCurrentTime() {
        const now = new Date();
        let hours = now.getHours();
        let minutes = now.getMinutes();
        hours = (hours < 10 ? '0' : '') + hours;
        minutes = (minutes < 10 ? '0' : '') + minutes;
        return hours + ':' + minutes;
    }

    // Initialize time picker first
    eventTimePicker = flatpickr("#event_time", {
        enableTime: true,
        noCalendar: true,
        dateFormat: "H:i",
        time_24hr: true,
        minuteIncrement: 30,
        minTime: "00:00",
        maxTime: "23:30",
        defaultHour: 12,
        defaultMinute: 0,
        onClose: function(selectedDates, dateStr) {
            const selectedDate = eventDatePicker?.selectedDates[0];
            const today = new Date();
            
            if (selectedDate && selectedDate.toDateString() === today.toDateString()) {
                const currentTime = getCurrentTime();
                const selectedTime = dateStr;
                
                if (selectedTime < currentTime) {
                    this.setDate(currentTime);
                }
            }
        }
    });

    // Then initialize date picker
    eventDatePicker = flatpickr("#event_date", {
        dateFormat: "Y-m-d",
        minDate: calculateMinDate(),
        disable: window.events ? events.map(event => event.Date) : [],
        defaultDate: new URLSearchParams(window.location.search).get('event_date') || null,
        onReady: function(selectedDates, dateStr, instance) {
            const urlParams = new URLSearchParams(window.location.search);
            const selectedDate = urlParams.get('event_date');
            if (selectedDate) {
                instance.setDate(selectedDate, true);
            }
        },
        onChange: function(selectedDates) {
            const selectedDate = selectedDates[0];
            const today = new Date();

            if (selectedDate && eventTimePicker) {
                if (selectedDate.toDateString() === today.toDateString()) {
                    eventTimePicker.set('minTime', getCurrentTime());
                } else {
                    eventTimePicker.set('minTime', "00:00");
                }
            }
        }
    });

    // Handle URL parameters once
    handleUrlParameters();
});

// Remove the duplicate date handling from document.ready
$(document).ready(function() {
    if (!$('#package_name').val()) {
        $('.main-form').addClass('col-lg-12').removeClass('col-lg-8');
    }
    
    $('#package_name').on('change', change);
 
    if ($('#package_name').val()) {
        change();
        if ($('#guests_number').val() === '') {
            $('#guests_number').val($('#package_name option:selected').data('persons'));
        }
        if ($('#guests_number').val()) {
            $('#guests_number').trigger('input');
        }
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

function handleUrlParameters() {
    const urlParams = new URLSearchParams(window.location.search);
    const selectedPackage = urlParams.get('package');
    const selectedMenu = urlParams.get('menu');
    const selectedDate = urlParams.get('event_date');
    
    if (selectedPackage) {
        // Select the package
        const packageSelect = $('#package_name');
        packageSelect.val(selectedPackage);
        
        // Trigger the change event to load package details and menus
        packageSelect.trigger('change');
        
        // Once menus are loaded, select the specific menu
        if (selectedMenu) {
            setTimeout(() => {
                const menuSelect = $('#menu_name');
                menuSelect.val(selectedMenu);
                menuSelect.trigger('change');
            }, 500);
        }
    }

    // Handle the date if it's present
    if (selectedDate) {
        const eventDateInput = document.getElementById('event_date');
        if (eventDateInput && eventDateInput._flatpickr) {
            eventDateInput._flatpickr.setDate(selectedDate);
        }
    }
}

$(document).ready(function() {
    // Your existing code
    if (!$('#package_name').val()) {
        $('.main-form').addClass('col-lg-12').removeClass('col-lg-8');
    }
    
    $('#package_name').on('change', change);
 
    if ($('#package_name').val()) {
        change();
        if ($('#guests_number').val() === '') {
            $('#guests_number').val($('#package_name option:selected').data('persons'));
        }
        if ($('#guests_number').val()) {
            $('#guests_number').trigger('input');
        }
    }

    // Add the URL parameter handling
    handleUrlParameters();

    // Add the date handling from calendar
    const urlParams = new URLSearchParams(window.location.search);
    const selectedDate = urlParams.get('event_date');
    
    if (selectedDate) {
        // Set the date in the date picker
        const eventDateInput = document.getElementById('event_date');
        if (eventDateInput && eventDateInput._flatpickr) {
            eventDateInput._flatpickr.setDate(selectedDate);
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
