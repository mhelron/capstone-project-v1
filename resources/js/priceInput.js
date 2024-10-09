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