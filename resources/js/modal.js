// JavaScript for auto-showing the modal and enabling Done button when checkbox is checked

document.addEventListener('DOMContentLoaded', () => {
    // Trigger the modal to show automatically on page load
    const termsModal = new bootstrap.Modal(document.getElementById('termsModal'), {
        backdrop: 'static', // Prevent closing by clicking outside
        keyboard: false     // Disable closing with the Esc key
    });
    termsModal.show();

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
        } else {
            // Disable Done button and reset color
            doneButton.disabled = true;
            doneButton.style.backgroundColor = '';  // Reset to default color
            doneButton.style.color = '';
        }
    });
});
