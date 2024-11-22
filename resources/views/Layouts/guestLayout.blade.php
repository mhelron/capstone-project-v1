<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kyla and Kyle</title>

     <!-- Add the canonical link -->
     <link rel="canonical" href="{{ url()->current() }}">

    <!-- Favicon for Browsers -->
    <link rel="icon" href="{{ asset('images/icons/favicon.ico') }}" type="image/x-icon">
    <link rel="icon" href="{{ asset('images/icons/favicon-16x16.png') }}" sizes="16x16">
    <link rel="icon" href="{{ asset('images/icons/favicon-32x32.png') }}" sizes="32x32">

    <!-- Apple Touch Icon -->
    <link rel="apple-touch-icon" href="{{ asset('images/icons/apple-touch-icon.png') }}">

    <!-- Android Chrome Icons -->
    <link rel="icon" href="{{ asset('images/icons/android-chrome-192x192.png') }}" sizes="192x192">
    <link rel="icon" href="{{ asset('images/icons/android-chrome-512x512.png') }}" sizes="512x512">
    
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

    <!-- Boxicons Icons -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    @vite('resources/css/chat.css')
    @vite('resources/css/guest.css')
</head>
<body>

<!-- Wrapper for sticky footer -->
<div class="col-md-12">
    <div class="wrapper d-flex flex-column min-vh-100">
        <!-- Navbar -->
        @include('layouts.inc.guestnavbar')

        <!-- Main content -->
        <div class="main p-3 flex-grow-1" style="background-color: #f4f6f9;"><!-- #FFE9E2 -->
            @yield('content')
        </div>

        <div class="custom-footer">
            @include('layouts.inc.guestfooter')
        </div>
    </div>
</div>


<!-- Bootstrap 5 -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- PopperJS -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>

    @if (session('status'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var toastEl = document.querySelector('.toast');
                var toast = new bootstrap.Toast(toastEl);
                toast.show();
            });
        </script>
    @endif
    <script>
    var botmanWidget = {
        title: 'KKChatbot', 
        introMessage: 'Hello! Type \'help\' to start our conversation!',
        mainColor: 'darkorange',
        bubbleBackground: 'darkorange',
        placeholderText: 'Type your message here...',
        titleColor: '#ff6347',
        aboutText: ' ',
    };
</script>

    <script src='https://cdn.jsdelivr.net/npm/botman-web-widget@latest/build/js/widget.js'></script>
</body>
</html>
