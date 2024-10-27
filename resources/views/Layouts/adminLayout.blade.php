<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kyla and Kyle</title>
    
	<!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

    <!-- Boxicons Icons -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    @vite('resources/css/app.css')
  
</head>
    <body>
        <div class="wrapper">
            <aside class="{{ $isExpanded ? 'expand' : '' }}" id="sidebar">
                <div class="d-flex">
                    <button class="toggle-btn" type="button" id="toggleSidebar">
                        <i class='bx bx-menu'></i>
                    </button>
                    <div class="sidebar-logo">
                        <a href="#">Kyla and Kyle</a>
                    </div>
                </div>
                <ul class="sidebar-nav">
                    <li class="sidebar-item">
                        <a href="{{route('admin.dashboard')}}" class="sidebar-link">
                            <i class='bx bxs-dashboard'></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="{{route('admin.calendar')}}" class="sidebar-link">
                            <i class='bx bxs-calendar'></i>
                            <span>Calendar</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="{{route('admin.reservation')}}" class="sidebar-link">
                            <i class='bx bx-edit-alt'></i>
                            <span>Reservation</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="{{route('admin.packages')}}" class="sidebar-link">
                            <i class='bx bxs-food-menu'></i>
                            <span>Packages</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="{{route('admin.users')}}" class="sidebar-link">
                            <i class='bx bx-user'></i>
                            <span>User</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="#" class="sidebar-link collapsed has-dropdown" data-bs-toggle="collapse"
                            data-bs-target="#auth" aria-expanded="false" aria-controls="auth">
                            <i class='bx bx-line-chart'></i>
                            <span>Reports</span>
                            @if ($isExpanded)
                                <i class="bx bx-chevron-left custom-chevron ms-auto"></i>
                            @endif
                        </a>
                        <ul id="auth" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                            <li class="sidebar-item">
                                <a href="{{route('admin.reports.reservation')}}" class="sidebar-link">Reservation</a>
                            </li>
                            <li class="sidebar-item">
                                <a href="{{route('admin.reports.sales')}}" class="sidebar-link">Sales</a>
                            </li>
                            <li class="sidebar-item">
                                <a href="#" class="sidebar-link">Activity Logs</a>
                            </li>
                        </ul>
                    </li>
                </ul>
                <div class="sidebar-footer">
                    <a href="#" class="sidebar-link" id="logout-link" data-bs-toggle="modal" data-bs-target="#logoutModal">
                        <i class='bx bx-log-out'></i>
                        <span>Logout</span>
                    </a>
                </div>
            </aside>
            <div class="main p-3">
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
    @vite('resources/js/toggleSidebar.js')
    @vite('resources/js/priceInput.js')

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
</html>