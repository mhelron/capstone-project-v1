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
            
            // Add regular menus
            selectedPackage.menus.forEach(menu => {
                const option = new Option(menu.menu_name, menu.menu_name);
                if (menu.menu_name === oldMenuValue) {
                    option.selected = true;
                }
                menuDropdown.append(option);
            });

            // Check for customized menu in URL and session
            const urlParams = new URLSearchParams(window.location.search);
            const menuId = urlParams.get('menu_id');
            
            // If there's a customized menu in the session, add it to the dropdown
            if (menuId && window.customizedMenu) {
                const option = new Option(window.customizedMenu.menu_name, window.customizedMenu.menu_name);
                if (window.customizedMenu.menu_name === oldMenuValue) {
                    option.selected = true;
                }
                menuDropdown.append(option);
            }
 
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
    const menuItemsList = $('#preview-menu-items');

    // Check if this is a customized menu
    const urlParams = new URLSearchParams(window.location.search);
    const menuId = urlParams.get('menu_id');

    if (window.customizedMenu && selectedMenuName === window.customizedMenu.menu_name) {
        // Display customized menu items
        menuItemsList.empty();
        window.customizedMenu.foods.forEach(food => {
            menuItemsList.append(`<li><span style="font-weight: bold;">${food.category}</span>: ${food.food}</li>`);
        });
    } else {
        // Handle regular menu display
        const selectedPackage = packages.find(pkg => pkg.package_name === selectedPackageName);
        if (selectedPackage && selectedMenuName) {
            const selectedMenu = selectedPackage.menus.find(menu => menu.menu_name === selectedMenuName);
            if (selectedMenu) {
                menuItemsList.empty();
                selectedMenu.foods.forEach(food => {
                    menuItemsList.append(`<li><span style="font-weight: bold;">${food.category}</span>: ${food.food}</li>`);
                });
            }
        }
    }
});

let eventDatePicker; // Declare the instance globally
let eventTimePicker; // Declare time picker globally too

document.addEventListener('DOMContentLoaded', function () {
    function calculateMinDate() {
        const today = new Date();
        today.setDate(today.getDate() + 4);
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

    function formatDate(date) {
        return date.toISOString().split('T')[0];
    }

    function countReservationsOnDate(date) {
        if (!window.events) return 0;
        
        // Format the check date consistently
        const checkDate = new Date(date);
        checkDate.setHours(0, 0, 0, 0);
        const dateStr = formatDate(checkDate);
        
        // Filter events to count only confirmed reservations
        const confirmedReservations = events.filter(event => {
            // Normalize event date
            const eventDate = new Date(event.Date);
            eventDate.setHours(0, 0, 0, 0);
            const eventDateStr = formatDate(eventDate);
            const isConfirmed = event.Status.toLowerCase() === 'confirmed';
            
            // Debug logging
            console.log('Comparing dates:', {
                checkingDate: dateStr,
                eventDate: eventDateStr,
                status: event.Status,
                isConfirmed: isConfirmed,
                isMatch: eventDateStr === dateStr && isConfirmed
            });
            
            return eventDateStr === dateStr && isConfirmed;
        });

        console.log(`Date ${dateStr} has ${confirmedReservations.length} confirmed reservations:`, confirmedReservations);
        return confirmedReservations.length;
    }

    function isDateFullyBooked(date) {
        const MAX_RESERVATIONS = 5;
        const count = countReservationsOnDate(date);
        const isBooked = count >= MAX_RESERVATIONS;
        
        if (isBooked) {
            console.log(`Date ${formatDate(date)} is BLOCKED with ${count} reservations`);
        }
        
        return isBooked;
    }

    // Initialize time picker
    eventTimePicker = flatpickr("#event_time", {
        enableTime: true,
        noCalendar: true,
        dateFormat: "h:i K",
        time_24hr: false,
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

    // Initialize date picker
    eventDatePicker = flatpickr("#event_date", {
        dateFormat: "Y-m-d",
        minDate: calculateMinDate(),
        disable: [
            function(date) {
                return isDateFullyBooked(date);
            }
        ],
        defaultDate: new URLSearchParams(window.location.search).get('event_date') || null,
        onChange: function(selectedDates) {
            const selectedDate = selectedDates[0];
            const today = new Date();

            if (selectedDate && eventTimePicker) {
                if (selectedDate.toDateString() === today.toDateString()) {
                    eventTimePicker.set('minTime', getCurrentTime());
                } else {
                    eventTimePicker.set('minTime', "00:00");
                }

                // Debug log for selected date
                console.log('Selected date:', {
                    date: formatDate(selectedDate),
                    reservations: countReservationsOnDate(selectedDate)
                });
            }
        },
        onReady: function(selectedDates, dateStr, instance) {
            // Log all events on initialization
            console.log('Available events:', events);
            
            const urlParams = new URLSearchParams(window.location.search);
            const selectedDate = urlParams.get('event_date');
            if (selectedDate) {
                instance.setDate(selectedDate, true);
            }
        }
    });

    // Handle URL parameters
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
    const selectedPrice = urlParams.get('price');
    const selectedDate = urlParams.get('event_date');
    const menuId = urlParams.get('menu_id');
    
    // If there's a menu_id, try to get the customized menu from session
    if (menuId && typeof sessionStorage !== 'undefined') {
        const customizedMenu = sessionStorage.getItem('customized_menu_' + menuId);
        if (customizedMenu) {
            window.customizedMenu = JSON.parse(customizedMenu);
        }
    }

    if (selectedPackage) {
        const packageSelect = $('#package_name');
        packageSelect.val(selectedPackage);
        
        if (selectedPrice) {
            const totalPackagePriceSpan = document.getElementById("total-package-price");
            const totalPriceSpan = document.getElementById("total-price");
            const totalPriceInput = document.getElementById("total_price");
            
            if (totalPackagePriceSpan) totalPackagePriceSpan.innerText = selectedPrice;
            if (totalPriceSpan) totalPriceSpan.innerText = selectedPrice;
            if (totalPriceInput) totalPriceInput.value = selectedPrice;
        }
        
        packageSelect.trigger('change');
        
        if (selectedMenu) {
            setTimeout(() => {
                const menuSelect = $('#menu_name');
                menuSelect.val(selectedMenu);
                menuSelect.trigger('change');
            }, 500);
        }
    }

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
    const packageSelect = document.getElementById('package_name');
    const guestsNumberInput = document.getElementById('guests_number');
    const totalPackagePriceSpan = document.getElementById("preview-package-price");
    const additionalPersonsSpan = document.getElementById("total-additional-persons");
    const totalPriceSpan = document.getElementById("total-price");
    const totalPriceInput = document.getElementById("total_price");
    const pricePerPackageHeadSpan = document.getElementById('price-per-package-head');

    let packagePrice = 0;
    let packagePersons = 0;
    let selectedPackage = null;
    let totalPrice = 0;

    // Add custom menu initialization
    const urlParams = new URLSearchParams(window.location.search);
    const menuId = urlParams.get('menu_id');
    
    // Initialize customized menu if it exists in session
    if (menuId) {
        try {
            const customizedMenuData = sessionStorage.getItem('customized_menu_' + menuId);
            if (customizedMenuData) {
                window.customizedMenu = JSON.parse(customizedMenuData);
            }
        } catch (error) {
            console.error('Error loading customized menu:', error);
        }
    }

    function updateTotal(selectedPackage) {
        console.log("Updating total...");
        
        console.log("Selected Package:", selectedPackage);
        const pricePerAdditionalPerson = (selectedPackage.package_type === 'Wedding') ? 400 : 350;
        console.log("Price per Additional Person:", pricePerAdditionalPerson);

        const guestsInputValue = guestsNumberInput.value.trim() || packagePersons;
        console.log("Guests Input Value:", guestsInputValue);

        const currentGuests = parseInt(guestsInputValue, 10);
        console.log("Parsed Guests Number:", currentGuests);

        if (isNaN(currentGuests) || currentGuests <= 0) {
            console.error("Invalid guests number input:", guestsInputValue);
            guestsNumberInput.value = packagePersons;
            return;
        }

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
        
        // If there's a customized menu, update its price in session
        if (window.customizedMenu) {
            try {
                const updatedMenu = { ...window.customizedMenu, totalPrice: totalPrice };
                sessionStorage.setItem('customized_menu_' + menuId, JSON.stringify(updatedMenu));
            } catch (error) {
                console.error('Error updating customized menu price:', error);
            }
        }
    }

    packageSelect.addEventListener("change", function() {
        const selectedPackageName = packageSelect.value;
        selectedPackage = packages.find(pkg => pkg.package_name === selectedPackageName);
    
        if (selectedPackage) {
            packagePrice = selectedPackage.price;
            packagePersons = selectedPackage.persons;
            
            // Check if we have a customized menu selected
            const menuDropdown = document.getElementById('menu_name');
            const selectedMenuName = menuDropdown.value;
            
            if (window.customizedMenu && selectedMenuName === window.customizedMenu.menu_name) {
                // If using customized menu, ensure any special pricing is handled
                console.log("Using customized menu:", window.customizedMenu.menu_name);
            }
            
            updateTotal(selectedPackage);
        }
    });

    guestsNumberInput.addEventListener("input", function() {
        if (selectedPackage) {
            updateTotal(selectedPackage);
        }
    });

    // Modified form submission handler
    const packageForm = document.querySelector('form');
    if (packageForm) {
        packageForm.addEventListener('submit', function(event) {
            // Include custom menu data in form submission if it exists
            if (window.customizedMenu && menuId) {
                const menuInput = document.createElement('input');
                menuInput.type = 'hidden';
                menuInput.name = 'customized_menu_data';
                menuInput.value = JSON.stringify(window.customizedMenu);
                this.appendChild(menuInput);
            }
            
            totalPriceInput.value = totalPrice;
        });
    }

    // Initialize form with URL parameters if they exist
    if (urlParams.get('package')) {
        const packageName = urlParams.get('package');
        if (packageSelect) {
            packageSelect.value = packageName;
            packageSelect.dispatchEvent(new Event('change'));
        }
    }
});