<!DOCTYPE html>
<html lang="en">

@php
    use Illuminate\Support\Facades\Session;
@endphp

<head>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kyla and Kyle</title>

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

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <style>
        #greeting {
            font-size: 16px;
        }
        .user-name {
            color: black;
            font-weight: bold;
            font-size: 16px;
        }
        #current-datetime {
            color: black;
            font-weight: bold;
            text-decoration: underline;
            font-size: 16px;
        }
    </style>


    @vite('resources/css/app.css')
  
</head>
    <body>

        <div class="wrapper">
        @php
            $userRole = session('user_role');
            $userFirstName = session('fname');
        @endphp

        @switch($userRole)
            @case('Super Admin')
                @include('layouts.inc.superadmin')
                @break
            @case('Admin')
                @include('layouts.inc.admin')
                @break
            @case('Manager')
                @include('layouts.inc.manager')
                @break
            @case('Staff')
                @include('layouts.inc.staff')
                @break
            @default
                @include('layouts.inc.staff')
        @endswitch
            <div class="main p-3">
            <nav class="navbar navbar-expand px-4 py-3 admin-nav">
                <div class="d-flex align-items-center">
                    <span class="fs-5">
                        <span id="greeting">Good morning</span>,
                        <span class="user-name">{{ Session::get('firebase_user')->displayName }}</span><span style="font-size: 16px;">! Today is </span>
                        <span id="current-datetime"></span>
                    </span>
                </div>
            </nav>
                <div>
                    @yield('content')
                </div>
            </div>
        </div>

         <!-- Logout Confirmation Modal -->
        <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true" >
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="logoutModalLabel">Confirm Logout</h5>
                    </div>
                    <div class="modal-body">
                        <p class="pt-4 pb-4">Are you sure you want to log out?</p>
                        <!-- Align buttons to the right -->
                        <div class="text-end">
                            <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-danger" id="confirm-logout-btn">Logout</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </body>

    @vite('resources/js/app.js')
    @vite('resources/js/logout.js')
    @vite('resources/js/togglesidebar.js')
    @vite('resources/js/priceinput.js')

    @if (session('status'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var toastEl = document.querySelector('.toast');
                var toast = new bootstrap.Toast(toastEl);
                toast.show();
            });
        </script>
    @endif

    <!-- Bootstrap 5 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- PopperJS -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>

    <!-- Calendar JS -->
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>


    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const toggleButton = document.getElementById('toggleSidebar');
            const sidebar = document.getElementById('sidebar');

            toggleButton.addEventListener('click', function () {
                sidebar.classList.toggle('expand');
            });
        });
    </script>

    
    <script>
        function updateDateTime() {
            const now = new Date();
            const hours = now.getHours();
            const greeting = document.getElementById('greeting');
            
            // Update greeting based on time of day
            if (hours >= 5 && hours < 12) {
                greeting.textContent = 'Good morning';
            } else if (hours >= 12 && hours < 18) {
                greeting.textContent = 'Good afternoon';
            } else {
                greeting.textContent = 'Good evening';
            }

            // Format date and time
            const options = {
                weekday: 'long',
                month: 'long',
                day: 'numeric',
                year: 'numeric',
                hour: 'numeric',
                minute: '2-digit',
                hour12: true
            };
            const dateTimeString = now.toLocaleDateString('en-US', options);
            document.getElementById('current-datetime').textContent = dateTimeString;
        }

        // Update immediately and then every minute
        updateDateTime();
        setInterval(updateDateTime, 60000);
    </script>
</html>