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

    <style>

        ::after,
        ::before {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        a {
            text-decoration: none;
        }

        li {
            list-style: none;
        }

h1 {
    font-weight: 600;
    font-size: 1.5rem;
}

.header {
    display: flex; /* Create a flex container */
    align-items: center; /* Align the items (button and logo) vertically centered */
    padding: 0.5rem; /* Optional: Add padding to space out items */
}

.toggle-btn {
    background-color: transparent;
    cursor: pointer;
    border: none;
    padding: 0.5rem 1rem;
    display: inline-flex;
    align-items: center; /* Ensure the icon inside the button is aligned */
}



body {
    font-family: 'Poppins', sans-serif;
}

.wrapper {
    display: flex;
}

.main {
    min-height: 100vh;
    width: 100%;
    overflow: hidden;
    transition: all 0.35s ease-in-out;
    background-color: #fafbfe;
}

#sidebar {
    width: 70px;
    min-width: 70px;
    z-index: 1000;
    transition: all .25s ease-in-out;
    background-color: #0e2238;
    display: flex;
    flex-direction: column;
}

#sidebar.expand {
    width: 260px;
    min-width: 260px;
}

.toggle-btn {
    background-color: transparent;
    cursor: pointer;
    border: 0;
    padding: 1rem 1.5rem;
}

.toggle-btn i {
    font-size: 1.5rem;
    color: #FFF;
}

.sidebar-logo {
    margin: auto 0;
}

.sidebar-logo a {
    color: #FFF;
    font-size: 1.15rem;
    font-weight: 600;
}

#sidebar:not(.expand) .sidebar-logo,
#sidebar:not(.expand) a.sidebar-link span {
    display: none;
}

.sidebar-nav {
    padding: 2rem 0;
    flex: 1 1 auto;
}

a.sidebar-link {
    padding: .625rem 1.625rem;
    color: #FFF;
    display: flex; /* Use flexbox */
    align-items: center; /* Aligns items vertically centered */
    font-size: 0.9rem;
    white-space: nowrap;
    border-left: 3px solid transparent;
    font-size: 16px;
}

.sidebar-link::after {
    content: none !important;
    display: none !important;
}

.custom-chevron {
    transition: transform 0.3s ease; 
}

.sidebar-link.collapsed .custom-chevron {
    transform: rotate(0deg); 
}

.sidebar-link[aria-expanded="true"] .custom-chevron {
    transform: rotate(-90deg);
}

.sidebar-link i {
    font-size: 1.1rem;
    margin-right: .75rem;
    display: inline-block;
}

.sidebar-link span {
    display: inline-block;
    line-height: 1.1rem; /* Adjust if necessary */
    font-size: 16px;
}

a.sidebar-link:hover {
    background-color: rgba(255, 255, 255, .075);
    border-left: 3px solid #3b7ddd;
}

.sidebar-item {
    position: relative;
}

#sidebar:not(.expand) .sidebar-item .sidebar-dropdown {
    position: absolute;
    top: 0;
    left: 70px;
    background-color: #0e2238;
    padding: 0;
    min-width: 15rem;
    display: none;
}

#sidebar:not(.expand) .sidebar-item:hover .has-dropdown+.sidebar-dropdown {
    display: block;
    max-height: 15em;
    width: 100%;
    opacity: 1;
}

#sidebar.expand .sidebar-link[data-bs-toggle="collapse"]::after {
    border: solid;
    border-width: 0 .075rem .075rem 0;
    content: "";
    display: inline-block;
    padding: 2px;
    position: absolute;
    right: 1.5rem;
    top: 1.4rem;
    transform: rotate(-135deg);
    transition: all .2s ease-out;
}

#sidebar.expand .sidebar-link[data-bs-toggle="collapse"].collapsed::after {
    transform: rotate(45deg);
    transition: all .2s ease-out;
}

/* Added Dropdown Icon sa Select */
select.form-control {
    appearance: auto;
    -webkit-appearance: auto;
    -moz-appearance: auto;
}
    </style>
  
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
         <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true" style="z-index: 9999;">
            <div class="modal-dialog modal-dialog-centered modal-sm ">
                <di v class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="logoutModalLabel">Confirm Logout</h5>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to log out?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-danger" id="confirm-logout-btn">Logout</button>
                    </div>
                </div>
            </div>
        </div>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </body>

    <!-- Script sa ToggleMenu -->
    <script>
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
    </script>

<script>
            document.getElementById('logout-link').addEventListener('click', function(e) {
                e.preventDefault(); // Prevent default link behavior

                try {
                    // Ensure the modal is initialized properly
                    var myModal = new bootstrap.Modal(document.getElementById('logoutModal'));
                    myModal.show();
                } catch (error) {
                    console.error('Error initializing modal: ', error);
                }
            });

            // When the user clicks "Logout" in the modal, submit the form
            document.getElementById('confirm-logout-btn').addEventListener('click', function() {
                document.getElementById('logout-form').submit();
            });

            document.addEventListener('hidden.bs.modal', function (event) {
                const backdrop = document.querySelector('.modal-backdrop');
                if (backdrop) {
                    backdrop.remove();
                }
            });
        </script>

    <!-- Script sa Toast -->
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