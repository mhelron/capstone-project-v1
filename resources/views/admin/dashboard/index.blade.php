@extends('layouts.adminlayout')

@section('content')

<!-- AdminLTE v1 JS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">

<div class="pt-4">
    @if(session('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <h1>Dashboard Page</h1>
    <!-- Add a row to contain the columns -->
    <div class="row">
        <!-- Column 1 -->
        <div class="col-lg-2 col-xs-6">
            <div class="small-box bg-secondary">
                <div class="inner">
                    <h3>{{ $counts['pencil'] }}</h3>
                    <p>Pencil</p>
                </div>
                <div class="icon">
                    <i class="fa-solid fa-pencil"></i>
                </div>
                <a href="{{ route('admin.reservation', ['tab' => 'penbook']) }}" class="small-box-footer">
                    More info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <!-- Column 2 -->
        <div class="col-lg-2 col-xs-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $counts['pending'] }}</h3>
                    <p>Pending</p>
                </div>
                <div class="icon">
                    <i class="fa-solid fa-spinner"></i>
                </div>
                <a href="{{ route('admin.reservation', ['tab' => 'pending']) }}" class="small-box-footer">
                    More info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <!-- Column 3 -->
        <div class="col-lg-2 col-xs-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $counts['confirmed'] }}</h3>
                    <p>Confirmed</p>
                </div>
                <div class="icon">
                    <i class="fa-regular fa-circle-check"></i>
                </div>
                <a href="{{ route('admin.reservation', ['tab' => 'confirmed']) }}" class="small-box-footer">
                    More info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <!-- Column 4 -->
        <div class="col-lg-2 col-xs-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $counts['cancelled'] }}</h3>
                    <p>Cancelled</p>
                </div>
                <div class="icon">
                    <i class="fa-solid fa-xmark"></i>
                </div>
                <a href="{{ route('admin.reservation', ['tab' => 'cancelled']) }}" class="small-box-footer">
                    More info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

         <!-- Column 5 -->
         <div class="col-lg-2 col-xs-6">
            <div class="small-box bg-primary">
                <div class="inner">
                    <h3>{{ $counts['finished'] }}</h3>
                    <p>Finished</p>
                </div>
                <div class="icon">
                    <i class="fa-regular fa-thumbs-up"></i>
                </div>
                <a href="{{ route('admin.reservation', parameters: ['tab' => 'finished']) }}" class="small-box-footer">
                    More info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <!-- Column 6 -->
        <div class="col-lg-2 col-xs-6">
            <div class="small-box bg-dark">
                <div class="inner">
                    <h3>{{ $counts['packages'] }}</h3>
                    <p>Packages</p>
                </div>
                <div class="icon">
                    <i class='bx bx-food-menu'></i>
                </div>
                <a href="{{ route('admin.users') }}" class="small-box-footer">
                    More info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>
    <div class="row">
        <!-- Most Picked Package Chart -->
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Most Picked Package</h3>
                </div>
                <div class="card-body" style="width: 50%; height: 300px;">
                    <canvas id="mostPickedPackageChart" class="chart-size"></canvas>
                </div>
    hg       </div>
        </div>
    </div>
</div>

<!-- AdminLTE v1 JS -->
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
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
