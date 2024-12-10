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
    <h1>Sales Page</h1>
    <a href="{{ route('admin.reports.sales.print') }}" class="btn btn-primary" target="_blank">Print Report</a>
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
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <span>Monthly Sales</span>
                    <select id="yearSelector" class="form-select form-select-sm custom-select-height" style="width: auto;">
                        @foreach($years as $year)
                            <option value="{{ $year }}" {{ $year == date('Y') ? 'selected' : '' }}>{{ $year }}</option>
                        @endforeach
                    </select>
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
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Weekly Sales</span>
                        <div class="d-flex gap-2">
                            <select id="weeklyYearSelector" class="form-select form-select-sm custom-select-height" style="width: auto;">
                                @foreach($years as $year)
                                    <option value="{{ $year }}" {{ $year == date('Y') ? 'selected' : '' }}>{{ $year }}</option>
                                @endforeach
                            </select>
                            <select id="weeklyMonthSelector" class="form-select form-select-sm custom-select-height" style="width: auto;">
                                @foreach($months as $index => $month)
                                    <option value="{{ $index + 1 }}" {{ ($index + 1) == date('n') ? 'selected' : '' }}>{{ $month }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="weeklySalesChart" style="height: 300px;"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="row pt-3">
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Daily Sales</span>
                        <div class="d-flex gap-2">
                            <select id="dailyYearSelector" class="form-select form-select-sm custom-select-height" style="width: auto;">
                                @foreach($years as $year)
                                    <option value="{{ $year }}" {{ $year == date('Y') ? 'selected' : '' }}>{{ $year }}</option>
                                @endforeach
                            </select>
                            <select id="dailyMonthSelector" class="form-select form-select-sm custom-select-height" style="width: auto;">
                                @foreach($months as $index => $month)
                                    <option value="{{ $index + 1 }}" {{ ($index + 1) == date('n') ? 'selected' : '' }}>{{ $month }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="dailySalesChart" style="height: 300px;"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
const years = @json($years);
const yearlyFeesData = @json(array_values($yearlyFees));
const yearlyPricesData = @json(array_values($yearlyPrices));
const weeklyData = @json($weeklyData);
const monthlyData = @json($monthlyData);
const dailyData = @json($dailyData);

let monthlySalesChart;
let weeklySalesChart;
let dailySalesChart;

// Function to update monthly chart
function updateMonthlyChart(selectedYear) {
    const yearData = monthlyData[selectedYear];
    
    if (monthlySalesChart) {
        monthlySalesChart.destroy();
    }

    const ctx2 = document.getElementById('monthlySalesChart').getContext('2d');
    monthlySalesChart = new Chart(ctx2, {
        type: 'bar',
        data: {
            labels: months,
            datasets: [{
                label: 'Reservation Fees',
                data: yearData.reservationFees,
                backgroundColor: 'rgba(54, 162, 235, 0.5)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            },
            {
                label: 'Packages Total',
                data: yearData.totalPrices,
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
    const yearMonthData = weeklyData[selectedYear][selectedMonth];
    
    if (weeklySalesChart) {
        weeklySalesChart.destroy();
    }

    const ctx3 = document.getElementById('weeklySalesChart').getContext('2d');
    weeklySalesChart = new Chart(ctx3, {
        type: 'bar',
        data: {
            labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
            datasets: [{
                label: 'Reservation Fees',
                data: yearMonthData.reservationFees,
                backgroundColor: 'rgba(54, 162, 235, 0.5)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            },
            {
                label: 'Packages Total',
                data: yearMonthData.totalPrices,
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

function updateDailyChart(selectedYear, selectedMonth) {
    const yearMonthData = dailyData[selectedYear][selectedMonth];
    const daysInMonth = new Date(selectedYear, selectedMonth, 0).getDate();
    const labels = Array.from({length: daysInMonth}, (_, i) => i + 1);
    
    if (dailySalesChart) {
        dailySalesChart.destroy();
    }

    const ctx4 = document.getElementById('dailySalesChart').getContext('2d');
    dailySalesChart = new Chart(ctx4, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Reservation Fees',
                data: yearMonthData.reservationFees.slice(1),
                backgroundColor: 'rgba(54, 162, 235, 0.5)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            },
            {
                label: 'Packages Total',
                data: yearMonthData.totalPrices.slice(1),
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

// Event listeners for chart selectors
document.getElementById('yearSelector').addEventListener('change', function(e) {
    updateMonthlyChart(e.target.value);
});

document.getElementById('weeklyYearSelector').addEventListener('change', function(e) {
    updateWeeklyChart(e.target.value, document.getElementById('weeklyMonthSelector').value);
});

document.getElementById('weeklyMonthSelector').addEventListener('change', function(e) {
    updateWeeklyChart(document.getElementById('weeklyYearSelector').value, e.target.value);
});

document.getElementById('dailyYearSelector').addEventListener('change', function(e) {
    updateDailyChart(e.target.value, document.getElementById('dailyMonthSelector').value);
});

document.getElementById('dailyMonthSelector').addEventListener('change', function(e) {
    updateDailyChart(document.getElementById('dailyYearSelector').value, e.target.value);
});

// Initialize charts with default values
const currentYear = new Date().getFullYear().toString();
const currentMonth = (new Date().getMonth() + 1).toString();
const defaultYear = years.includes(currentYear) ? currentYear : years[0];

// Yearly Sales Chart
const ctx1 = document.getElementById('yearlySalesChart').getContext('2d');
new Chart(ctx1, {
    type: 'bar',
    data: {
        labels: years,
        datasets: [
            {
                label: 'Reservation Fees',
                data: yearlyFeesData,
                backgroundColor: 'rgba(54, 162, 235, 0.5)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            },
            {
                label: 'Packages Total',
                data: yearlyPricesData,
                backgroundColor: 'rgba(75, 192, 192, 0.5)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }
        ]
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

updateMonthlyChart(defaultYear);
updateWeeklyChart(defaultYear, currentMonth);
updateDailyChart(defaultYear, currentMonth);
</script>

@endsection