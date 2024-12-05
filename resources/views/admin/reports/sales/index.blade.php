@extends('layouts.adminlayout')

@section('content')

<div>
    <h1 style="padding-top: 35px;">Sales Page</h1>
</div>

<div>
    <div class="row pt-3">

<!-- Yearly Sales Chart -->
<div class="col-md-4 mb-4">
    <div class="card">
        <div class="card-header bg-primary text-white">
            Yearly Sales
        </div>
        <div class="card-body">
            <canvas id="yearlySalesChart" style="height: 300px;"></canvas>
        </div>
    </div>
</div>

<!-- Monthly Sales Chart -->
<div class="col-md-4 mb-4">
    <div class="card">
        <div class="card-header bg-primary text-white">
            Monthly Sales
        </div>
        <div class="card-body">
            <canvas id="monthlySalesChart" style="height: 300px;"></canvas>
        </div>
    </div>
</div>

<!-- Weekly Sales Chart -->
<div class="col-md-4 mb-4">
    <div class="card">
        <div class="card-header bg-primary text-white">
            Weekly Sales
        </div>
        <div class="card-body">
            <canvas id="weeklySalesChart" style="height: 300px;"></canvas>
        </div>
    </div>
</div>

</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const months = @json($months);
    const reservationFeesYearly = @json([$yearlyFees]); // Pass yearly data
    const totalPricesYearly = @json([$yearlyPrices]); // Pass yearly total price data
    const reservationFeesMonthly = @json($reservationFees);
    const totalPricesMonthly = @json($totalPrices); // Monthly total price data
    const reservationFeesWeekly = @json($weeklyFees); // Weekly data
    const totalPricesWeekly = @json($weeklyPrices); // Weekly total price data

    // Yearly Sales Chart
    const ctx1 = document.getElementById('yearlySalesChart').getContext('2d');
    new Chart(ctx1, {
        type: 'bar',
        data: {
            labels: ['2024'], // Only one label for yearly sales
            datasets: [{
                label: 'Reservation Fees',
                data: reservationFeesYearly,
                backgroundColor: 'rgba(54, 162, 235, 0.5)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            },
            {
                label: 'Packages Total',
                data: totalPricesYearly,
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

    // Monthly Sales Chart
    const ctx2 = document.getElementById('monthlySalesChart').getContext('2d');
    new Chart(ctx2, {
        type: 'bar',
        data: {
            labels: months,
            datasets: [{
                label: 'Reservation Fees',
                data: reservationFeesMonthly,
                backgroundColor: 'rgba(54, 162, 235, 0.5)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            },
            {
                label: 'Packages Total',
                data: totalPricesMonthly,
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

    // Weekly Sales Chart
    const ctx3 = document.getElementById('weeklySalesChart').getContext('2d');
    new Chart(ctx3, {
        type: 'bar',
        data: {
            labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
            datasets: [{
                label: 'Reservation Fees',
                data: reservationFeesWeekly,
                backgroundColor: 'rgba(54, 162, 235, 0.5)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            },
            {
                label: 'Packages Total',
                data: totalPricesWeekly,
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
</script>

@endsection
