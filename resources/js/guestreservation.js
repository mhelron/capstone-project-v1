function formatPriceCustom(price) {
    // Add currency symbol, e.g., PHP or USD
    // Format number with commas (e.g., 1000000 becomes 1,000,000)
    return price.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

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
    const additionalPersonsSpan = $("#total-additional-persons"); // Additional persons
    const totalPriceSpan = $("#total-price"); // Total price
    const totalPackagePriceSpan = $("#total-package-price"); // Total package price (for the first section)
    const pricePerPackageHeadSpan = $('#price-per-package-head'); // Price per head element

    let packageSelect = document.getElementById('package_name');
    let selectedPackage = packageSelect.options[packageSelect.selectedIndex];

    // Get the persons value from the selected option's data-persons attribute
    let persons = selectedPackage.getAttribute('data-persons');

    // Update the guests_number input with the persons value
    if (persons) {
        guestsInput.val(persons);  // Set the value of the guests input field
    }

    // Restrict the guest input to not go below the persons value
    guestsInput.attr('min', persons);  // Set the minimum value of the input

    // Reset menu dropdown and hide the preview by default
    menuDropdown.empty().append('<option value="" disabled selected>Select a Menu</option>');
    previewSection.hide();
    packageServicesList.empty();

    // Ensure the selected package name is not empty
    if (selectedPackageName) {
        // Find the selected package
        const selectedPackage = packages.find(pkg => pkg.package_name === selectedPackageName);

        // Ensure the selected package exists and has menus
        if (selectedPackage) {
            // Enable the menu dropdown and populate it with options
            menuDropdown.prop('disabled', false);
            selectedPackage.menus.forEach(menu => {
                menuDropdown.append(new Option(menu.menu_name, menu.menu_name));
            });

            // Populate preview section with package details
            packageNameSpan.text(selectedPackage.package_name);
            const formattedPrice = formatPriceCustom(parseFloat(selectedPackage.price));
            packagePriceSpan.text(formattedPrice);
            packagePaxSpan.text(selectedPackage.persons);

            // Handle services
            packageServicesList.empty();
            if (selectedPackage.services && selectedPackage.services.length > 0) {
                selectedPackage.services.forEach(service => {
                    packageServicesList.append('<li>' + service.service + '</li>');
                });
            } else {
                packageServicesList.append('<li>No services available</li>');
            }

            // Calculate and update the total price
            const basePrice = parseFloat(selectedPackage.price); // Base price for the selected package
            const baseGuests = selectedPackage.persons; // Base number of guests
            let pricePerAdditionalPerson = 350; // Default price per additional guest

            // Check the package type and set the price per head accordingly
            if (selectedPackage.package_type === 'Wedding') {
                pricePerAdditionalPerson = 400; // Wedding package has a higher price per person
            }

            // Display price per head
            pricePerPackageHeadSpan.text(formatPriceCustom(pricePerAdditionalPerson)); // Show price per additional person

            // Ensure guests input doesn't go below the base number of guests
            let currentGuests = Math.max(baseGuests, guestsInput.val()); // Capture the updated number of guests
            guestsInput.val(currentGuests); // Update the value in the input field

            // Calculate additional guests and total price
            const additionalGuests = Math.max(0, currentGuests - baseGuests);
            const additionalPrice = additionalGuests * pricePerAdditionalPerson;
            const totalPrice = basePrice + additionalPrice;

            // Update price breakdown on the UI
            additionalPersonsSpan.text(additionalGuests); // Show number of additional guests
            totalPriceSpan.text(formatPriceCustom(totalPrice)); // Update the total price

            // Update the total package price in the first section
            totalPackagePriceSpan.text(formatPriceCustom(basePrice)); // Show base package price

            // Clear menu details
            menuItemsList.empty();

            // Show the preview section
            previewSection.show();
        } else {
            console.error("Selected package not found or has no menus.");
        }
    } else {
        // If no package is selected, hide the preview
        menuDropdown.prop('disabled', true);
        previewSection.hide();
    }
}


// Update preview content based on menu selection
$('#menu_name').change(function() {
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

document.addEventListener('DOMContentLoaded', function() {
    // Initialize Flatpickr for the date input
    flatpickr("#event_date", {
        dateFormat: "Y-m-d", // Format of the date to send to the server
        minDate: "today",    // Prevent past dates
    });

    // Initialize Flatpickr for the time input with AM/PM option
    flatpickr("#event_time", {
        enableTime: true,    // Enable time selection
        noCalendar: true,     // Disable calendar (only time)
        dateFormat: "h:i K",  // Format of the time to send to the server (12-hour format with AM/PM)
        time_24hr: false,     // Use 12-hour format
    });
});

$('#guests_number').on('input', function() {
    const selectedPackageName = $('#package_name').val();
    const selectedPackage = packages.find(pkg => pkg.package_name === selectedPackageName);

    if (selectedPackage) {
        const baseGuests = selectedPackage.persons;
        let currentGuests = $(this).val();

        // Ensure that the number of guests does not go below the base guests
        if (currentGuests < baseGuests) {
            $(this).val(baseGuests);
            currentGuests = baseGuests; // Set to the base number of guests if less than base
        }

        // Determine the price per additional person based on the package type
        const pricePerAdditionalPerson = selectedPackage?.package_type === 'Wedding' ? 400 : 350;

        // Calculate additional guests and price
        const additionalGuests = Math.max(0, currentGuests - baseGuests);
        const additionalPrice = additionalGuests * pricePerAdditionalPerson;

        // Calculate the total additional person price (1 x price per person)
        const totalAdditionalPersonPrice = additionalGuests * pricePerAdditionalPerson; // Here is the 1 x per person price calculation

        // Calculate the base price and the total price (base + additional persons)
        const basePrice = parseFloat(selectedPackage.price);
        const totalPrice = basePrice + additionalPrice;

        // Update the UI elements
        $('#total-additional-persons').text(additionalGuests); // Show number of additional guests
        $('#price-per-package-head').text(formatPriceCustom(pricePerAdditionalPerson)); // Show price per additional person
        $('#total-additional-person-price').text(formatPriceCustom(totalAdditionalPersonPrice)); // Show the total additional person price
        $('#total-price').text(formatPriceCustom(totalPrice)); // Show the total price with additional guests
        $('#total-package-price').text(formatPriceCustom(basePrice)); // Show the base package price
    }
});



$(document).ready(function() {
    $('#package_name').on('change', change); // Ensures change() is bound after DOM is ready
});

document.addEventListener("DOMContentLoaded", function() {
    const packageSelect = document.getElementById('package_name'); // Package select element
    const guestsNumberInput = document.getElementById('guests_number'); // Guests input element
    const totalPackagePriceSpan = document.getElementById("preview-package-price"); // Package price span
    const additionalPersonsSpan = document.getElementById("total-additional-persons"); // Additional persons span
    const totalPriceSpan = document.getElementById("total-price"); // Total price span
    const totalPriceInput = document.getElementById("total_price"); // Hidden input for total price

    let packagePrice = 0;
    let packagePersons = 0;
    const pricePerAdditionalPerson = 500; // Price for each additional person

    // Function to update the total breakdown
    function updateTotal() {
        const additionalGuests = Math.max(0, parseInt(guestsNumberInput.value, 10) - packagePersons);
        const additionalPrice = additionalGuests * pricePerAdditionalPerson;
        const totalPrice = (parseFloat(packagePrice) || 0) + additionalPrice;
    
        // Update visible breakdown
        totalPackagePriceSpan.innerText = `₱${(parseFloat(packagePrice) || 0).toFixed(2)}`;
        additionalPersonsSpan.innerText = additionalGuests;
        totalPriceSpan.innerText = `₱${totalPrice.toFixed(2)}`;
    
        // Update hidden input field for total price without formatting
        totalPriceInput.value = totalPrice.toFixed(2);
    }

    // When the package is selected
    packageSelect.addEventListener("change", function() {
        const selectedPackageName = packageSelect.value;
        const selectedPackage = packages.find(pkg => pkg.package_name === selectedPackageName);
    
        if (selectedPackage) {
            packagePrice = selectedPackage.price;
            packagePersons = selectedPackage.persons;
            updateTotal(); // Update breakdown and hidden input
        }
    });

    // When the number of guests changes
    guestsNumberInput.addEventListener("input", function() {
        updateTotal(); // Update breakdown and hidden input
    });

    packageForm.addEventListener('submit', function () {
        // Ensure commas are removed from the hidden input field before submission
        totalPriceInput.value = totalPriceInput.value.replace(/,/g, '');
    });

    // Initialize the total when the page is loaded, in case a default package is selected
    const initialPackageName = packageSelect.value;
    if (initialPackageName) {
        const selectedPackage = packages.find(pkg => pkg.package_name === initialPackageName);
        if (selectedPackage) {
            packagePrice = selectedPackage.price;
            packagePersons = selectedPackage.persons;
            updateTotal();
        }
    }
});

console.log("Total Price:", totalPrice);
console.log("Hidden Input Value:", totalPriceInput.value);