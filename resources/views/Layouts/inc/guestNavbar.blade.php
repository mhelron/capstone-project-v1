@vite('resources/css/guest.css')

<nav class="navbar navbar-expand-lg navbar-light custom-navbar" id="navbar">
    <div class="container">
        <a class="navbar-brand" href="{{ route('guest.home') }}">
            <span style="color: darkorange;">Kyla</span> and <span style="color: red;">Kyle</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
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
    /* You can keep the styles you already have here */
    
    /* Add transition to the navbar for smooth hide/show effect */
    #navbar {
        transition: top 0.3s ease-in-out;
        position: fixed;
        width: 100%;
        top: 0;
        z-index: 1000; /* Ensure it's on top of other content */
    }

    /* Optional: Add a shadow when the navbar becomes fixed */
    #navbar.fixed {
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    /* Remove the dropdown arrow */
    .nav-item.dropdown .dropdown-toggle::after {
        display: none;
    }

    /* Custom navbar adjustments */
    @media (max-width: 768px) {

    /* Style the navbar-toggler button */
    .navbar-toggler {
        width: 100%; /* Full width */
        height: auto;
        position: relative;
        background: none; /* Remove default background */
        border: 2px solid black; /* Add border around the hamburger */
        border-radius: 5px; /* Optional: Add rounded corners */
        
    }

    /* Custom hamburger icon lines */
    .navbar-toggler::before,
    .navbar-toggler::after,
    .navbar-toggler div {
        content: '';
        display: block;
        background-color: black; /* Color of the lines */
        margin: 5px auto; /* Space between lines and center align */
    }

    /* Center the brand */
    .navbar-brand {
        text-align: center;
        width: 100%;
    }

        /* Center the hamburger button */
    .navbar-toggler {
            display: block;
            margin: 10px auto;  /* Center and add margin */
        }
        
    /* Ensure navbar items are stacked and centered */
    .navbar-collapse {
        display: flex;
        justify-content: space-between;
        flex-direction: column;
        align-items: center; /* Align items to the center */
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

        /* Adjust dropdown to stay inline */
        .dropdown-menu {
            position: static;
            float: none;
            width: auto;  /* Let dropdown take its content's width */
            padding: 5px 0;  /* Reduce padding for compactness */
            font-size: 0.8rem;  /* Make the font smaller */
            border-radius: 5px;  /* Optional: Add rounded corners */
            box-shadow: none; /* Optional: Remove shadow for cleaner look */
            background-color: white;  /* Set a background color */
        }

        .dropdown-item:hover {
            background-color: #f8f9fa;
        }

        .dropdown-toggle {
            display: inline-block;  /* Make dropdown behave like a regular nav link */
        }
        .dropdown-menu {
    position: right;
    width: 10px;              /* Set to 'auto' or use a max-width */
    max-width: 50px;         /* Restrict the width */
    text-align: right;         /* Align text to the left for a more compact look */
  
}

    /* Center dropdown items */
    .dropdown-item {
        text-align: right;
        font-size: 1rem;
    }

        /* Button adjustments for consistency on mobile */
        .btn-reserve {
            width: 90%;  /* Make the Reserve button slightly smaller */
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
</style>
<script>
    // Variables to store scroll position
    let lastScrollTop = 0;
    const navbar = document.getElementById('navbar');
    
    window.addEventListener('scroll', () => {
        let currentScroll = window.pageYOffset || document.documentElement.scrollTop;
        
        // Check if the user is scrolling up or down
        if (currentScroll > lastScrollTop) {
            // Scroll Down - Hide navbar
            navbar.style.top = "-80px";  // Adjust this value based on navbar height
        } else {
            // Scroll Up - Show navbar
            navbar.style.top = "0";
        }
        
        lastScrollTop = currentScroll <= 0 ? 0 : currentScroll; // Prevent negative scroll
    });
</script>