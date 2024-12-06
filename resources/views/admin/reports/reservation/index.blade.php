@extends('layouts.adminlayout')

@section('content')

<style>
    .custom-select-height {
        height: 23px !important;  /* Adjust this value as needed */
        padding-top: 0.3px;
        padding-bottom: 0.3px;
    }
</style>

<div class="d-flex justify-content-between align-items-center" style="padding-top: 35px;">
    <h1>Reservations Report Page</h1>
    <a href="{{ route('reservation.print') }}" class="btn btn-primary" target="_blank">Print Report</a>
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
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <span>Monthly Reservations</span>
                <select id="monthlyYearSelector" class="form-select form-select-sm custom-select-height " style="width: auto; min-width: 100px; height: 30px;">
                    @foreach($years as $year)
                        <option value="{{ $year }}" {{ $year == date('Y') ? 'selected' : '' }}>{{ $year }}</option>
                    @endforeach
                </select>
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
                <div class="d-flex justify-content-between align-items-center">
                    <span>Weekly Reservations</span>
                    <div class="d-flex gap-2">
                        <select id="weeklyYearSelector" class="form-select form-select-sm custom-select-height " style="width: auto; min-width: 100px; height: 30px;">
                            @foreach($years as $year)
                                <option value="{{ $year }}" {{ $year == date('Y') ? 'selected' : '' }}>{{ $year }}</option>
                            @endforeach
                        </select>
                        <select id="weeklyMonthSelector" class="form-select form-select-sm custom-select-height " style="width: auto; min-width: 120px; height: 30px;">
                            @foreach($months as $index => $month)
                                <option value="{{ $index + 1 }}" {{ ($index + 1) == date('n') ? 'selected' : '' }}>{{ $month }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <canvas id="weeklyReservationsChart" style="height: 300px;"></canvas>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const months = @json($months);
    const years = @json($years);
    const yearlyReservations = @json($yearlyReservations);
    const monthlyData = @json($monthlyData);
    const weeklyData = @json($weeklyData);

    let monthlyChart;
    let weeklyChart;

    // Function to update monthly chart
    function updateMonthlyChart(selectedYear) {
        if (monthlyChart) {
            monthlyChart.destroy();
        }

        const ctx2 = document.getElementById('monthlyReservationsChart').getContext('2d');
        monthlyChart = new Chart(ctx2, {
            type: 'bar',
            data: {
                labels: months,
                datasets: [{
                    label: 'Monthly Reservations',
                    data: monthlyData[selectedYear],
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
    }

    // Function to update weekly chart
    function updateWeeklyChart(selectedYear, selectedMonth) {
        if (weeklyChart) {
            weeklyChart.destroy();
        }

        const ctx3 = document.getElementById('weeklyReservationsChart').getContext('2d');
        weeklyChart = new Chart(ctx3, {
            type: 'bar',
            data: {
                labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
                datasets: [{
                    label: 'Weekly Reservations',
                    data: weeklyData[selectedYear][selectedMonth],
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
                scales: {
                    y: {
                        ticks: {
                            // This ensures no decimals in the y-axis
                            callback: function(value) {
                                return Math.floor(value); // Show as integer
                            }
                        },
                    }
                }
            }
        });
    }

    // Yearly Reservations Chart
    const ctx1 = document.getElementById('yearlyReservationsChart').getContext('2d');
    new Chart(ctx1, {
        type: 'bar',
        data: {
            labels: Object.keys(yearlyReservations),
            datasets: [{
                label: 'Yearly Reservations',
                data: Object.values(yearlyReservations),
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

    // Event listeners
    document.getElementById('monthlyYearSelector').addEventListener('change', function(e) {
        updateMonthlyChart(e.target.value);
    });

    document.getElementById('weeklyYearSelector').addEventListener('change', function(e) {
        updateWeeklyChart(e.target.value, document.getElementById('weeklyMonthSelector').value);
    });

    document.getElementById('weeklyMonthSelector').addEventListener('change', function(e) {
        updateWeeklyChart(document.getElementById('weeklyYearSelector').value, e.target.value);
    });

    // Initialize charts with current year and month
    const currentYear = new Date().getFullYear().toString();
    const currentMonth = (new Date().getMonth() + 1).toString();
    const defaultYear = years.includes(currentYear) ? currentYear : years[0];

    updateMonthlyChart(defaultYear);
    updateWeeklyChart(defaultYear, currentMonth);
</script>
@endsection