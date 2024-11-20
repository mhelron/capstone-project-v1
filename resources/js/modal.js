document.addEventListener('DOMContentLoaded', () => {
    // Trigger the modal to show automatically on page load if no validation errors
    const termsModal = new bootstrap.Modal(document.getElementById('termsModal'), {
        backdrop: 'static', // Prevent closing by clicking outside
        keyboard: false     // Disable closing with the Esc key
    });

    // Check if the modal has already been shown before (using sessionStorage)
    if (!sessionStorage.getItem('modalShown')) {
        termsModal.show();
        sessionStorage.setItem('modalShown', 'true'); // Mark that the modal has been shown
    }

    // Get the checkbox and Done button
    const agreeCheckboxModal = document.getElementById('agreeCheckboxModal');
    const doneButton = document.getElementById('modalDoneBtn');

    // Initially disable the Done button
    doneButton.disabled = true;

    // Add event listener to the checkbox
    agreeCheckboxModal.addEventListener('change', () => {
        if (agreeCheckboxModal.checked) {
            // Enable Done button
            doneButton.disabled = false;
            doneButton.style.backgroundColor = '#ff8c00';  // Dark Orange
            doneButton.style.color = 'white';
            doneButton.style.outline = 'none';  // Remove outline when checked
        } else {
            // Disable Done button and reset color
            doneButton.disabled = true;
            doneButton.style.backgroundColor = '';  // Reset to default color
            doneButton.style.color = '';
            doneButton.style.outline = '';  // Reset outline
        }
    });
});
