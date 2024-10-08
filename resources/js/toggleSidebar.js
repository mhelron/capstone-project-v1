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