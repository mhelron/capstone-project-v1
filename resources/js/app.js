import './bootstrap';

// Script for Logout

// ---------------------------------------------------------------------------------- //
document.getElementById('logout-link').addEventListener('click', function(e) {
    e.preventDefault();
    try {
        var myModal = new bootstrap.Modal(document.getElementById('logoutModal'));
        myModal.show();
    } catch (error) {
        console.error('Error initializing modal: ', error);
    }
});

document.getElementById('confirm-logout-btn').addEventListener('click', function() {
    document.getElementById('logout-form').submit();
});

document.addEventListener('hidden.bs.modal', function (event) {
    const backdrop = document.querySelector('.modal-backdrop');
    if (backdrop) {
        backdrop.remove();
    }
});
// ---------------------------------------------------------------------------------- //


// Script for Chevron Icon in Dropdown

// ---------------------------------------------------------------------------------- //
document.addEventListener('DOMContentLoaded', () => {
    const toggleButton = document.getElementById('toggleSidebar');
    const sidebar = document.getElementById('sidebar');
    const chevron = document.querySelector('.custom-chevron');
    let isExpanded = localStorage.getItem('isExpanded') === 'true';

    if (isExpanded) {
        sidebar.classList.add('expand');
        chevron.style.display = 'inline-block'; // Show the chevron when expanded
    }

    toggleButton.addEventListener('click', () => {
        isExpanded = !isExpanded;
        sidebar.classList.toggle('expand', isExpanded);
        localStorage.setItem('isExpanded', isExpanded);

        // Show or hide the chevron based on the sidebar state
        if (isExpanded) {
            chevron.style.display = 'inline-block';
        } else {
            chevron.style.display = 'none';
        }
    });
});
// ---------------------------------------------------------------------------------- //


// Script sa Add Package, Add input field for Services and Food

// ------------------------------------------------------------------------------------------------------------ //
document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('add-service').addEventListener('click', function () {
        const servicesList = document.getElementById('services-list');
        const serviceCount = servicesList.children.length; // Bilangin yung index ng Services
        const newInputGroup = document.createElement('div');
        newInputGroup.classList.add('row', 'mb-2'); // Gagawa ng bagong input field

        if (serviceCount === 0) {
            // input field 1 column
            newInputGroup.innerHTML = `
                <div class="col-md-12">
                    <input type="text" name="services[]" class="form-control" placeholder="Enter service">
                </div>
            `;
        } else {
            // intput field 2 column
            newInputGroup.innerHTML = `
                <div class="col-md-10">
                    <input type="text" name="services[]" class="form-control" placeholder="Enter service">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button class="btn btn-danger remove-item" type="button">Remove</button>
                </div>
            `;
        }
        servicesList.appendChild(newInputGroup);
    });

    document.getElementById('add-more-food').addEventListener('click', function () {
        const foodList = document.getElementById('food-list');
        const foodCount = foodList.children.length; // Bilangin yung index ng Foods
        const newInputGroup = document.createElement('div');
        newInputGroup.classList.add('row', 'mb-2'); // Gagawa ng bagong input field
        newInputGroup.innerHTML = `
            <div class="col-md-5">
                <input type="text" name="foods[${foodCount}][food]" class="form-control" placeholder="Enter food item">
            </div>
            <div class="col-md-5">
                <input type="text" name="foods[${foodCount}][category]" class="form-control" placeholder="Enter category">
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button class="btn btn-danger remove-item" type="button">Remove</button>
            </div>
        `;
        foodList.appendChild(newInputGroup);
    });

    document.addEventListener('click', function (e) {
        if (e.target && e.target.classList.contains('remove-item')) {
            e.target.closest('.row').remove(); // Remove yung added input field
        }
    });

    document.getElementById('package-form').addEventListener('keypress', function (event) {
        if (event.key === 'Enter' && event.target.tagName === 'INPUT') {
            event.preventDefault();
            event.target.form.submit();
        }
    });
});
// ------------------------------------------------------------------------------------------------------------ //


// Script sa formating ng price

// ----------------------------------------------------------------------------------------------------------------------------------------- //
document.addEventListener('DOMContentLoaded', function () {
    const priceInput = document.querySelector('input[name="price"]');

    // Lalagyan ng comma yung price every 3 digits
    function formatWithCommas(value) {
        return value.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }

    // Reretain yung old value para pag nag refresh page
    if (priceInput.value) {
        let value = priceInput.value.replace(/,/g, ''); // Alis comma
        priceInput.value = formatWithCommas(value); // Add commas
    }

    // Pag na hit yung every 3 digit mag add comma, pag nag delete ng digit aalisin din yung comma pag hinde 3 digit yung every digit
    priceInput.addEventListener('input', function () {
        let value = this.value.replace(/,/g, ''); // Aalsin comma
        if (!isNaN(value)) {
            // i-foformat yung digits ng comma every 3 digits
            this.value = formatWithCommas(value);
        }
    });

    // Pag i-submit na yung form aalisin na yung comma
    const packageForm = document.getElementById('package-form');
    packageForm.addEventListener('submit', function () {
        priceInput.value = priceInput.value.replace(/,/g, ''); // Alis comma sa pag submit
    });
});
// ----------------------------------------------------------------------------------------------------------------------------------------- //


// Script ng pag add field service and food sa edit package

// ----------------------------------------------------------------------------------------------------------------------------------------- //

// Remove added field
document.addEventListener('click', function (e) {
    if (e.target && e.target.classList.contains('remove-item')) {
        e.target.closest('.row').remove(); // Remove the closest row for both services and foods
    }
});

// Function to add a new food item
document.getElementById('add-food').addEventListener('click', function () {
    const foodList = document.getElementById('food-list');
    const foodCount = foodList.children.length; // Get the current count of foods
    const newInputGroup = document.createElement('div');
    newInputGroup.classList.add('row', 'mb-2'); // Create a new row
        
    // Create the new food input group
    newInputGroup.innerHTML = `
        <div class="col-md-5">
            <input type="text" name="foods[${foodCount}][food]" class="form-control" placeholder="Enter food item">
        </div>
        <div class="col-md-5">
            <input type="text" name="foods[${foodCount}][category]" class="form-control" placeholder="Enter category">
        </div>
        <div class="col-md-2 d-flex align-items-end">
            <button class="btn btn-danger remove-item" type="button">Remove</button>
        </div>
    `;
    foodList.appendChild(newInputGroup);
});

// ----------------------------------------------------------------------------------------------------------------------------------------- //