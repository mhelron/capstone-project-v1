@vite('resources/css/guest.css')
    
    <nav class="navbar navbar-expand-lg navbar-light custom-navbar">
        <div class="container">
            <a class="navbar-brand" href="{{ route('guest.home')}}"><span style="color: darkorange;">Kyla</span> and <span style="color: red;">Kyle</span></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('guest.home')}}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('guest.packages')}}">Packages</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('guest.calendar')}}">Calendar</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('guest.contact')}}">Contact</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('guest.about')}}">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-reserve" href="#">Reserve</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
