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
                            <p class="text-secondary mt-2 fw-bold">Today's Pencil</p>
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
                            <p class="text-secondary mt-2 fw-bold">Today's Pending</p>
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
                            <p class="text-secondary mt-2 fw-bold">Today's Confirmed</p>
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
                            <p class="text-secondary mt-2 fw-bold">Today's Cancelled</p>
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
                            <p class="text-secondary mt-2 fw-bold">Today's Finished</p>
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
        <!-- Most Picked Package Chart -->
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Most Picked Package</h3>
                </div>
                <div class="card-body" style="width: 50%; height: 300px;">
                    <canvas id="mostPickedPackageChart" class="chart-size"></canvas>
                </div>
             </div>
        </div>
    </div>
</div>

<!-- ChartJS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Function to generate a random color
    function randomColor() {
        const letters = '0123456789ABCDEF';
        let color = '#';
        for (let i = 0; i < 6; i++) {
            color += letters[Math.floor(Math.random() * 16)];
        }
        return color;
    }

    // Get the canvas element for the chart
    var ctx = document.getElementById('mostPickedPackageChart').getContext('2d');

    // Prepare the chart data
    var mostPickedPackageChart = new Chart(ctx, {
        type: 'bar',  // You can change this to 'line', 'pie', etc.
        data: {
            labels:<?php echo json_encode(array_keys($topPackages))?>,  // Labels (Package names)
            datasets: [{
                label: 'Number of Times Picked',
                data: <?php echo json_encode(array_values($topPackages))?>,  // Data (Package counts)
                backgroundColor: function(context) {
                    // Generate a random color for each bar
                    const index = context.dataIndex;
                    return randomColor();  // Apply random color to each bar
                },
                borderColor: function(context) {
                    // Generate a random border color for each bar
                    return randomColor();
                },
                borderWidth: 1
            }]
        },
    });
</script>



@endsection
