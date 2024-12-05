@extends('layouts.adminlayout')

@section('content')

<div>
    <h1 style="padding-top: 35px;">Reservation Report</h1>
</div>

<div class="row pt-3">
    <!-- Yearly Reservations Chart -->
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                Yearly Reservations
            </div>
            <div class="card-body">
                <canvas id="yearlyReservationsChart" style="height: 300px;"></canvas>
            </div>
        </div>
    </div>

    <!-- Monthly Reservations Chart -->
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                Monthly Reservations
            </div>
            <div class="card-body">
                <canvas id="monthlyReservationsChart" style="height: 300px;"></canvas>
            </div>
        </div>
    </div>

    <!-- Weekly Reservations Chart -->
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                Weekly Reservations
            </div>
            <div class="card-body">
                <canvas id="weeklyReservationsChart" style="height: 300px;"></canvas>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Prepare the data for the charts from the backend
    const yearlyReservations = @json($yearlyReservations); // Yearly reservation data
    const months = @json($months); // Month names (January to December)
    const monthlyReservations = @json($monthlyReservations); // Monthly reservation data
    const weeklyReservations = @json($weeklyReservations); // Weekly reservation data

    // Yearly Reservations Chart
    const ctx1 = document.getElementById('yearlyReservationsChart').getContext('2d');
    new Chart(ctx1, {
        type: 'bar',
        data: {
            labels: Object.keys(yearlyReservations), // Yearly data (keys)
            datasets: [{
                label: 'Yearly Reservations',
                data: Object.values(yearlyReservations), // Yearly counts
                backgroundColor: 'rgba(54, 162, 235, 0.5)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                },
            },
        },
    });

    // Monthly Reservations Chart
    const ctx2 = document.getElementById('monthlyReservationsChart').getContext('2d');
    new Chart(ctx2, {
        type: 'bar',
        data: {
            labels: months,
            datasets: [{
                label: 'Monthly Reservations',
                data: monthlyReservations,
                backgroundColor: 'rgba(75, 192, 192, 0.5)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                },
            },
        },
    });

    // Weekly Reservations Chart
    const ctx3 = document.getElementById('weeklyReservationsChart').getContext('2d');
    new Chart(ctx3, {
        type: 'bar',
        data: {
            labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
            datasets: [{
                label: 'Weekly Reservations',
                data: weeklyReservations,
                backgroundColor: 'rgba(255, 159, 64, 0.5)',
                borderColor: 'rgba(255, 159, 64, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                },
            },
        },
    });
</script>

@endsection
