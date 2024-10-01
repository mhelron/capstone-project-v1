import './bootstrap';

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