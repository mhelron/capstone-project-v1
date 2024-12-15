@extends('layouts.adminlayout')

@section('content')

<style>
    /* Hover effect for the cards inside the columns */
    .card1 {
        transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
    }

    /* Hover effect when user hovers over a card */
    .card1:hover {
        transform: scale(1.05);  /* Zoom effect: enlarge the card slightly */
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);  /* Adds a larger shadow for the zoom effect */
    }

    .card.pending {
        border: 2px solid #ffa500;  /* Main color */
        background-color: rgba(255, 165, 0, 0.1);  /* Light background */
    }

    .card.pending.i{
        color: #ffa500;
    }

    .card.confirmed {
        border: 2px solid #28a745;  /* Main color */
        background-color: rgba(40, 167, 69, 0.1);  /* Light background */
    }

    .card.cancelled {
        border: 2px solid #dc3545;  /* Main color */
        background-color: rgba(220, 53, 69, 0.1);  /* Light background */
    }

    .card.finished {
        border: 2px solid #007bff;  /* Main color */
        background-color: rgba(0, 123, 255, 0.1);  /* Light background */
    }

    .card.pencil {
        border: 2px solid #6c757d;  /* Main color */
        background-color: rgba(108, 117, 125, 0.1);  /* Light background */
    }

    .card.packages {
        border: 2px solid black;  /* Main color */
        background-color: white;  /* Light background */
    }

    /* Change icon color based on card status */
    .icon-pencil {
        color: #6c757d;  /* Color for pencil icon */
    }

    .icon-pending {
        color: #ffa500;  /* Color for pending icon (orange) */
    }

    .icon-confirmed {
        color: #28a745;  /* Color for confirmed icon (green) */
    }

    .icon-cancelled {
        color: #dc3545;  /* Color for cancelled icon (red) */
    }

    .icon-finished {
        color: #007bff;  /* Color for finished icon (blue) */
    }

    .notification-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255, 255, 255, 0.8);
        display: none;
        justify-content: center;
        align-items: center;
        z-index: 1000;
    }

    .notification-item {
        position: relative;
    }

    .spinner-border {
        width: 1.5rem;
        height: 1.5rem;
    }

    /* Add styles for the view button */
    .notification-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        width: 100%;
    }

    .notification-info {
        flex-grow: 1;
    }

    .btn-view {
        min-width: 120px;
        margin-left: 10px;
    }

    .fixed-height-card {
        height: 400px;
        overflow-y: auto;
    }

    .fixed-height-card .list-group {
        max-height: 300px;
        overflow-y: auto;
    }

    /* Custom scrollbar for better appearance */
    .fixed-height-card .list-group::-webkit-scrollbar {
        width: 6px;
    }

    .fixed-height-card .list-group::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 3px;
    }

    .fixed-height-card .list-group::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 3px;
    }

    .fixed-height-card .list-group::-webkit-scrollbar-thumb:hover {
        background: #555;
    }

    /* Center content vertically when empty */
    .empty-card-content {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 300px;
    }
</style>

<div class="pt-4">
    @if(session('status'))
        <div class="alert alert-success alert-dismissible fade show custom-alert" role="alert">
            {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <h1>Dashboard</h1>

    <!-- Add a row to contain the columns -->
    <div class="row mb-3">
        <!-- Column 1 -->
        <div class="col-lg-2 col-xs-6">
            <a href="{{ route('admin.reservation', ['tab' => 'penbook']) }}" class="card-link">
                <div class="card pencil card1 p-3 box-shadow">
                    <div class="row mb-2">
                        <div class="col-md-12 d-flex justify-content-md-end">
                            <i class="fa-solid fa-pencil icon-pencil"></i>
                        </div>
                    </div>
                    
                    <!-- Title (optional) -->
                    <h1 class="text-start">{{ $counts['pencil'] }}</h1>

                    <!-- Content in the Card -->
                    <div class="row d-flex align-items-lg-center justify-content-lg-between">
                        <div class="col-md-6">
                            <p class="text-secondary mt-2 fw-bold">Total</br> Pencil</p>
                        </div>
                        <div class="col-md-6 d-flex justify-content-md-end">
                            <i class="bx bx-right-arrow-alt fs-3"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Column 2 -->
        <div class="col-lg-2 col-xs-6">
            <a href="{{ route('admin.reservation', parameters: ['tab' => 'pencil']) }}" class="card-link">
                <div class="card pending card1 p-3 box-shadow">
                    <div class="row mb-2">
                        <div class="col-md-12 d-flex justify-content-md-end">
                            <i class="fa-solid fa-spinner icon-pending"></i>
                        </div>
                    </div>
                    
                    <!-- Title (optional) -->
                    <h1 class="text-start">{{ $counts['pending'] }}</h1>

                    <!-- Content in the Card -->
                    <div class="row d-flex align-items-lg-center justify-content-lg-between">
                        <div class="col-md-6">
                            <p class="text-secondary mt-2 fw-bold">Total Pending</p>
                        </div>
                        <div class="col-md-6 d-flex justify-content-md-end">
                            <i class="bx bx-right-arrow-alt fs-3"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Column 3 -->
        <div class="col-lg-2 col-xs-6">
            <a href="{{ route('admin.reservation', ['tab' => 'confirmed']) }}" class="card-link">
                <div class="card confirmed card1 p-3 box-shadow">
                    <div class="row mb-2">
                        <div class="col-md-12 d-flex justify-content-md-end">
                            <i class='bx bx-check-circle icon-confirmed'></i>
                        </div>
                    </div>
                    
                    <!-- Title (optional) -->
                    <h1 class="text-start">{{ $counts['confirmed'] }}</h1>

                    <!-- Content in the Card -->
                    <div class="row d-flex align-items-lg-center justify-content-lg-between">
                        <div class="col-md-6">
                            <p class="text-secondary mt-2 fw-bold">Total Confirmed</p>
                        </div>
                        <div class="col-md-6 d-flex justify-content-md-end">
                            <i class="bx bx-right-arrow-alt fs-3"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Column 4 -->
        <div class="col-lg-2 col-xs-6">
            <a href="{{ route('admin.reservation', ['tab' => 'cancelled']) }}" class="card-link">
                <div class="card cancelled card1 p-3 box-shadow">
                    <div class="row mb-2">
                        <div class="col-md-12 d-flex justify-content-md-end">
                            <i class='bx bx-x-circle icon-cancelled'></i>
                        </div>
                    </div>
                    
                    <!-- Title (optional) -->
                    <h1 class="text-start">{{ $counts['cancelled'] }}</h1>

                    <!-- Content in the Card -->
                    <div class="row d-flex align-items-lg-center justify-content-lg-between">
                        <div class="col-md-6">
                            <p class="text-secondary mt-2 fw-bold">Total Cancelled</p>
                        </div>
                        <div class="col-md-6 d-flex justify-content-md-end">
                            <i class="bx bx-right-arrow-alt fs-3"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>

         <!-- Column 5 -->
        <div class="col-lg-2 col-xs-6">
            <a href="{{ route('admin.reservation', ['tab' => 'finished']) }}" class="card-link">
                <div class="card finished card1 p-3 box-shadow">
                    <div class="row mb-2">
                        <div class="col-md-12 d-flex justify-content-md-end">
                            <i class='bx bxs-flag-checkered icon-finished' ></i>
                        </div>
                    </div>
                    
                    <!-- Title (optional) -->
                    <h1 class="text-start">{{ $counts['finished'] }}</h1>

                    <!-- Content in the Card -->
                    <div class="row d-flex align-items-lg-center justify-content-lg-between">
                        <div class="col-md-6">
                            <p class="text-secondary mt-2 fw-bold">Total Finished</p>
                        </div>
                        <div class="col-md-6 d-flex justify-content-md-end">
                            <i class="bx bx-right-arrow-alt fs-3"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>

         <!-- Column 6 -->
         <div class="col-lg-2 col-xs-6">
            <a href="{{ route('admin.packages') }}" class="card-link">
                <div class="card packages card1 p-3 box-shadow">
                    <div class="row mb-2">
                        <div class="col-md-12 d-flex justify-content-md-end">
                            <i class='bx bx-food-menu' ></i>
                        </div>
                    </div>
                    
                    <!-- Title (optional) -->
                    <h1 class="text-start">{{ $counts['packages'] }}</h1>

                    <!-- Content in the Card -->
                    <div class="row d-flex align-items-lg-center justify-content-lg-between">
                        <div class="col-md-6">
                            <p class="text-secondary mt-2 fw-bold">Total Packages</p>
                        </div>
                        <div class="col-md-6 d-flex justify-content-md-end">
                            <i class="bx bx-right-arrow-alt fs-3"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <div class="row pt-3">
        <div class="col-lg-4 col-xs-6">
            <div class="card upcoming-events p-3 box-shadow fixed-height-card">
                <div class="row mb-2">
                    <div class="col-md-12 d-flex justify-content-md-end">
                        <i class="bx bx-calendar"></i>
                    </div>
                </div>
                <h1 class="text-start">Upcoming Events</h1>

                @if(!empty($upcomingReservations))
                    <div class="list-group">
                        @foreach($upcomingReservations as $reservation)
                            <a href="#" class="list-group-item list-group-item-action">
                                <div class="d-flex justify-content-between">
                                    <span>{{ $reservation['package_name'] }}</span>
                                    <span class="text-muted">{{ \Carbon\Carbon::parse($reservation['event_date'] . ' ' . $reservation['event_time'])->format('M d, Y - h:i A') }}</span>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="empty-card-content">
                        <p class="text-center text-muted">No upcoming events.</p>
                    </div>
                @endif
            </div>
        </div>

         <!-- Top 10 Packages Card -->
        <div class="col-lg-4 col-xs-6">
            <div class="card p-3 box-shadow fixed-height-card">
                <div class="row mb-2">
                    <div class="col-md-12 d-flex justify-content-md-end">
                        <i class="bx bx-trophy"></i>
                    </div>
                </div>
                <h1 class="text-start">Top 10 Packages</h1>

                @if(!empty($topPackages))
                    <div class="list-group">
                        @foreach($topPackages as $packageName => $count)
                            <div class="list-group-item list-group-item-action">
                                <div class="d-flex justify-content-between">
                                    <span>{{ $packageName }}</span>
                                    <span class="badge bg-primary">{{ $count }} times</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-card-content">
                        <p class="text-center text-muted">No package data available.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Notifications Card -->
        <div class="col-lg-4 col-xs-6">
            <div class="card notification-card p-3 box-shadow fixed-height-card">
                <div class="row mb-2">
                    <div class="col-md-12 d-flex justify-content-md-end">
                        <i class="bx bx-bell"></i>
                    </div>
                </div>
                <h1 class="text-start">New Notifications</h1>

                @if(!empty($newNotifications))
                    <div class="list-group">
                        @foreach($newNotifications as $notification)
                            <div class="list-group-item notification-item" data-id="{{ $notification['key'] }}"
                                data-package="{{ $notification['data']['package_name'] }}"
                                data-date="{{ \Carbon\Carbon::parse($notification['data']['event_date'] . ' ' . $notification['data']['event_time'])->format('M d, Y - h:i A') }}">
                                <!-- Add spinner overlay -->
                                <div class="notification-overlay">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </div>
                                <div class="notification-content">
                                    <div class="notification-info">
                                        <div class="d-flex justify-content-between">
                                            <span>{{ $notification['data']['package_name'] }}</span>
                                            <span class="badge bg-danger">New</span>
                                        </div>
                                        <small class="text-muted d-block">
                                            {{ \Carbon\Carbon::parse($notification['data']['event_date'] . ' ' . $notification['data']['event_time'])->format('M d, Y - h:i A') }}
                                        </small>
                                    </div>
                                    <div class="ms-3">
                                        <button class="btn btn-primary btn-sm btn-view" 
                                                data-id="{{ $notification['key'] }}"
                                                data-redirect="{{ route('admin.reservation', ['tab' => 'penbook']) }}">
                                            View
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-card-content">
                        <p class="text-center text-muted">No new notifications.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="confirmReadModal" tabindex="-1" aria-labelledby="confirmReadModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmReadModalLabel">Mark as Read</h5>
            </div>
            <div class="modal-body">
                <p class="fw-bold">Are you sure you want to mark this notification as read?</p>
                <p id="notificationDetails"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirmMarkAsRead">Mark as Read</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentNotificationElement = null;
    const modalElement = document.getElementById('confirmReadModal');
    const modal = new bootstrap.Modal(modalElement);

    // Handle View button clicks
    document.querySelectorAll('.btn-view').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const notificationItem = this.closest('.notification-item');
            const redirectUrl = this.getAttribute('data-redirect');
            const reservationId = this.getAttribute('data-id');
            
            // Show spinner
            const overlay = notificationItem.querySelector('.notification-overlay');
            overlay.style.display = 'flex';

            // Mark as read and then redirect
            fetch(`/admin/reservations/mark-as-read/${reservationId}`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = redirectUrl;
                } else {
                    console.error('Failed to mark notification as read:', data.message);
                    window.location.href = redirectUrl;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                window.location.href = redirectUrl;
            });
        });
    });

    // Regular notification click handling (for mark as read modal)
    document.querySelectorAll('.notification-item').forEach(item => {
        item.addEventListener('click', function(e) {
            // Check if click was on the View button or its container
            if (e.target.closest('.btn-view') || e.target.closest('.ms-3')) {
                return;
            }
            
            e.preventDefault();
            currentNotificationElement = this;
            
            // Update modal with notification details
            const packageName = this.dataset.package;
            const date = this.dataset.date;
            document.getElementById('notificationDetails').textContent = 
                `Package: ${packageName}\nDate: ${date}`;
            
            // Show the modal
            modal.show();
        });
    });

    // Original markAsRead function for modal confirmation
    document.getElementById('confirmMarkAsRead').addEventListener('click', function() {
        if (currentNotificationElement) {
            markAsRead(currentNotificationElement);
            modal.hide();
        }
    });

    function markAsRead(element) {
        const reservationId = element.getAttribute('data-id');
        
        // Show spinner
        const overlay = element.querySelector('.notification-overlay');
        overlay.style.display = 'flex';
        
        fetch(`/admin/reservations/mark-as-read/${reservationId}`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Hide spinner
                overlay.style.display = 'none';
                
                // Add fade-out animation
                element.style.transition = 'opacity 0.5s';
                element.style.opacity = '0';
                
                // Remove the element after animation
                setTimeout(() => {
                    element.remove();
                    const remainingNotifications = document.querySelectorAll('.notification-item');
                    const listGroup = document.querySelector('.list-group');
                    if (remainingNotifications.length === 0 && listGroup) {
                        const noNotificationsMessage = document.createElement('div');
                        noNotificationsMessage.className = 'notification-content';
                        noNotificationsMessage.innerHTML = `
                            <div class="notification-info">
                                <small class="text-center text-muted">No new notifications.</small>
                            </div>
                        `;
                        listGroup.appendChild(noNotificationsMessage);
                    }
                }, 500);
            } else {
                // Hide spinner and show error
                overlay.style.display = 'none';
                if (data.message === 'Please login again to continue.') {
                    alert('Your session has expired. Please login again.');
                    window.location.href = '{{ route("login") }}';
                } else {
                    alert(data.message || 'Unable to mark notification as read.');
                }
            }
        })
        .catch(error => {
            // Hide spinner and show error
            overlay.style.display = 'none';
            console.error('Error:', error);
            alert('Network error. Please try again.');
        });
    }
});
</script>

@endsection
