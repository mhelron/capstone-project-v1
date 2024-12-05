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
                                <a href="{{route('admin.reports.packages')}}" class="sidebar-link">Packages</a>
                            </li>
                            <li class="sidebar-item">
                                <a href="{{route('admin.reports.locations')}}" class="sidebar-link">Locations</a>
                            </li>
                            <li class="sidebar-item">
                                <a href="{{route('admin.reports.logs')}}" class="sidebar-link">Activity Logs</a>
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