<!-- Google Font: Source Sans Pro -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

<!-- Boxicons Icons -->
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

<!-- Bootstrap 5 -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<nav class="navbar bg-dark navbar-expand-lg navbar-dark">
  <div class="container">
    <a class="navbar-brand" href="#">laravel + Firebase</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link" href="{{ route('guest.home')}}">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{ route('guest.reservation')}}">Reservation</a>
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
      </ul>
    </div>
  </div>
</nav>

<div class="main p-3">
    @yield('content')
</div>

    <!-- Bootstrap 5 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- PopperJS -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>