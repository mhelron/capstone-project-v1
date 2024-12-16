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
                        <a href="{{ route('admin.reservation', ['tab' => 'penbook']) }}" class="sidebar-link">
                            <i class='bx bx-edit-alt'></i>
                            <span>Reservation</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="{{route('admin.foodtaste.index')}}" class="sidebar-link">
                            <i class='bx bx-fork'></i>
                            <span>Food Tasting</span>
                        </a>
                    </li>
                </ul>

                <div class="sidebar-footer">
                    <a href="#" class="sidebar-link" id="logout-link" data-bs-toggle="modal" data-bs-target="#logoutModal">
                        <i class='bx bx-log-out'></i>
                        <span>Logout</span>
                    </a>
                </div>
            </aside>