document.addEventListener('DOMContentLoaded', () => {
    const toggleButton = document.getElementById('toggleSidebar');
    const sidebar = document.getElementById('sidebar');
    const chevron = document.querySelector('.custom-chevron');
    let isExpanded = localStorage.getItem('isExpanded') === 'true';

    // Apply initial state based on saved preference
    if (isExpanded) {
        sidebar.classList.add('expand');
        toggleButton.classList.add('expanded'); // Set expanded color
        chevron.style.display = 'inline-block';
    } else {
        chevron.style.display = 'none';
    }

    // Toggle sidebar on button click
    toggleButton.addEventListener('click', () => {
        isExpanded = !isExpanded;
        sidebar.classList.toggle('expand', isExpanded);
        localStorage.setItem('isExpanded', isExpanded);

        // Toggle the expanded color class based on state
        toggleButton.classList.toggle('expanded', isExpanded);

        // Show or hide the chevron based on the sidebar state
        if (isExpanded) {
            chevron.style.display = 'inline-block';
        } else {
            chevron.style.display = 'none';
        }
    });
});
