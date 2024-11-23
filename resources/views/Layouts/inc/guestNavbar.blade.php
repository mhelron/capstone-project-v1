@vite('resources/css/guest.css')

<nav class="navbar navbar-expand-lg navbar-light custom-navbar" id="navbar">
    <div class="container">
        <!-- Navbar Brand (Kyla and Kyle) -->
        <a class="navbar-brand" href="{{ route('guest.home') }}">
            <span style="color: darkorange;">Kyla</span> and <span style="color: red;">Kyle</span>
        </a>

        <!-- Navbar Toggler (Hamburger Icon) -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navbar Items -->
        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('guest.home') ? 'active' : '' }}" href="{{ route('guest.home') }}">Home</a>
                </li>
                
                <!-- Packages Dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ request()->routeIs('guest.packages.marikina') || request()->routeIs('guest.packages.sanmateo') || request()->routeIs('guest.packages.montalban') || request()->routeIs('package.show')? 'active' : '' }}" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Packages
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="{{ route('guest.packages.marikina') }}">Marikina</a></li>
                        <li><a class="dropdown-item" href="{{ route('guest.packages.sanmateo') }}">San Mateo</a></li>
                        <li><a class="dropdown-item" href="{{ route('guest.packages.montalban') }}">Montalban</a></li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('guest.gallery') ? 'active' : '' }}" href="{{ route('guest.gallery') }}">Gallery</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('guest.calendar') ? 'active' : '' }}" href="{{ route('guest.calendar') }}">Calendar</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('guest.contact') ? 'active' : '' }}" href="{{ route('guest.contact') }}">Contact</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('guest.about') ? 'active' : '' }}" href="{{ route('guest.about') }}">About</a>
                </li>
                <li class="nav-item">
                    <a class="btn btn-reserve" href="#">Reserve</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<style>
    /* Existing CSS for navbar and responsive styles */
    #navbar {
    position: fixed;
    width: 100%;
    top: 0;
    z-index: 1000; /* Ensure it stays on top of other content */
}

/* Optional: Add a shadow when the navbar becomes fixed */
#navbar.fixed {
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.nav-item.dropdown .dropdown-toggle::after {
    display: none; /* Remove the default arrow */
}

/* Custom navbar adjustments */
@media (max-width: 768px) {
    /* Flexbox layout to arrange navbar brand and hamburger icon */
    .navbar-nav {
        display: flex;
        justify-content: space-between;
        width: 100%;
        flex-direction: row;
        padding-left: 0; /* Remove default padding */
    }

    /* Navbar Brand alignment */
    .navbar-brand {
        flex: 1;
        text-align: center; /* Align to the center */
    }

    /* Move the hamburger icon to the left */
    .navbar-toggler {
        order: 0; /* Ensure the hamburger stays on the left */
        background: none;
        border: 2px solid black;
        border-radius: 5px;
    }

    /* Center align navbar items in collapsed state */
    .navbar-collapse {
        display: flex;
        justify-content: space-between;
        flex-direction: column;
        align-items: center;
        width: 100%;
    }

    .navbar-nav {
        display: flex;
        flex-direction: column;
        width: 100%;
    }

    .nav-item {
        text-align: center;
        width: 100%;
    }

    .dropdown-menu {
        position: static; /* Ensure it stays within the container */
        float: none;
        width: 250%; /* Make the dropdown menu full width */
        padding: 5px 0;
        font-size: 0.8rem;
        border-radius: 5px;
        box-shadow: none;
        background-color: white;
    }

    /* Dropdown items should be full width */
    .dropdown-item {
        width: 140%; /* Make each dropdown item fill the container */
        text-align: center; /* Center the text of each item */
        padding: 10px 20px; /* More padding for easier tapping */
    }

    /* Button styling for mobile */
    .btn-reserve {
        width: 90%;
        text-align: center;
        font-size: 1rem;
        padding: 10px 15px;
        background-color: darkorange;
        color: white;
        border-radius: 5px;
        border: none;
        cursor: pointer;
        text-decoration: none;
        margin: 0 auto;
    }

    .btn-reserve:hover {
        background-color: #0056b3;
    }
}

/* Additional styling for larger screens (optional) */
@media (min-width: 769px) {
    .dropdown-menu {
        width: auto; /* Ensure the dropdown is not full width on larger screens */
        background-color: #f8f9fa;
    }

    .dropdown-item {
        text-align: left; /* Align text to the left for larger screens */
    }
}
</style>

<script>
    let lastScrollTop = 0;
const navbar = document.getElementById('navbar');

// Add a smooth transition to the navbar's position in CSS
navbar.style.transition = "top 0.3s ease"; // Smooth transition for hiding/revealing

window.addEventListener('scroll', () => {
    let currentScroll = window.pageYOffset || document.documentElement.scrollTop;
    
    // Hide navbar when scrolling down
    if (currentScroll > lastScrollTop) {
        navbar.style.top = "-1000px"; // Adjust this to the height of your navbar
    } else {
        navbar.style.top = "0"; // Show the navbar when scrolling up
    }
    
    lastScrollTop = currentScroll <= 0 ? 0 : currentScroll;
});
</script>
