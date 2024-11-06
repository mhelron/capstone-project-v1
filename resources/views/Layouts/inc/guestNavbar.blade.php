@vite('resources/css/guest.css')

<nav class="navbar navbar-expand-lg navbar-light custom-navbar">
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
                    <a class="nav-link dropdown-toggle {{ request()->routeIs('guest.packages.marikina') || request()->routeIs('guest.packages.sanmateo') || request()->routeIs('guest.packages.montalban') ? 'active' : '' }}" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
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
    /* Remove the dropdown arrow */
    .nav-item.dropdown .dropdown-toggle::after {
        display: none;
    }

</style>

