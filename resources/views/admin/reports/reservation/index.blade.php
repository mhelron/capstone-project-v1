@extends('layouts.adminlayout')

@section('content')
<style>
    .custom-select-height {
        height: 30px !important;
        padding: 2px 8px;
    }
    .chart-container {
        position: relative;
        height: 300px;
        width: 100%;
    }
</style>

<div class="d-flex justify-content-between align-items-center" style="padding-top: 35px;">
    <h1>Reservations Report</h1>
    <a href="{{ route('reservation.print') }}" class="btn btn-primary" target="_blank">Print Report</a>
</div>

<div class="row pt-3">
    <!-- Yearly Trends (Monthly View) -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <span>Yearly Trends</span>
                <select id="yearlyTrendSelector" class="form-select form-select-sm custom-select-height">
                    @foreach($years as $year)
                        <option value="{{ $year }}" {{ $year == date('Y') ? 'selected' : '' }}>{{ $year }}</option>
                    @endforeach
                </select>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="yearlyTrendChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Breakdown -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <span>Monthly</span>
                    <div class="d-flex gap-2">
                        <select id="monthlyYearSelector" class="form-select form-select-sm custom-select-height">
                            @foreach($years as $year)
                                <option value="{{ $year }}" {{ $year == date('Y') ? 'selected' : '' }}>{{ $year }}</option>
                            @endforeach
                        </select>
                        <select id="monthlyMonthSelector" class="form-select form-select-sm custom-select-height">
                            @foreach($months as $index => $month)
                                <option value="{{ $index }}" {{ $index == date('n') - 1 ? 'selected' : '' }}>{{ $month }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="monthlyDetailChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Weekly Analysis -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <span>Weekly Analysis</span>
                    <div class="d-flex gap-2">
                        <select id="weeklyYearSelector" class="form-select form-select-sm custom-select-height">
                            @foreach($years as $year)
                                <option value="{{ $year }}" {{ $year == date('Y') ? 'selected' : '' }}>{{ $year }}</option>
                            @endforeach
                        </select>
                        <select id="weeklyMonthSelector" class="form-select form-select-sm custom-select-height">
                            @foreach($months as $index => $month)
                                <option value="{{ $index }}" {{ $index == date('n') - 1 ? 'selected' : '' }}>{{ $month }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="weeklyChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Daily Analysis -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <span>Daily Analysis</span>
                    <div class="d-flex gap-2">
                        <select id="dailyYearSelector" class="form-select form-select-sm custom-select-height">
                            @foreach($years as $year)
                                <option value="{{ $year }}" {{ $year == date('Y') ? 'selected' : '' }}>{{ $year }}</option>
                            @endforeach
                        </select>
                        <select id="dailyMonthSelector" class="form-select form-select-sm custom-select-height">
                            @foreach($months as $index => $month)
                                <option value="{{ $index }}" {{ $index == date('n') - 1 ? 'selected' : '' }}>{{ $month }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="dailyChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Initialize data from PHP
const months = @json($months);
const years = @json($years);
const monthlyTrends = @json($monthlyTrends);
const monthlyData = @json($monthlyData);
const weeklyData = @json($weeklyData);
const dailyData = @json($dailyData);

// Chart instances
let yearlyTrendChart;
let monthlyDetailChart;
let weeklyChart;
let dailyChart;

// Chart Colors
const chartColors = {
    primary: 'rgb(75, 192, 192)',
    secondary: 'rgb(54, 162, 235)',
    tertiary: 'rgb(255, 159, 64)',
    quaternary: 'rgb(153, 102, 255)',
    background: {
        primary: 'rgba(75, 192, 192, 0.2)',
        secondary: 'rgba(54, 162, 235, 0.2)',
        tertiary: 'rgba(255, 159, 64, 0.2)',
        quaternary: 'rgba(153, 102, 255, 0.2)'
    }
};

// Common chart options
const commonOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            position: 'top',
        }
    },
    scales: {
        y: {
            beginAtZero: true,
            ticks: {
                stepSize: 1
            }
        }
    }
};

// Get days in month helper function
function getDaysInMonth(year, month) {
    return new Date(year, month + 1, 0).getDate();
}

// Get week number for a date
function getWeekNumber(date) {
    const firstDay = new Date(date.getFullYear(), date.getMonth(), 1);
    return Math.ceil((date.getDate() + firstDay.getDay() - 1) / 7);
}

// Initialize charts
function initializeCharts() {
    const currentYear = getCurrentYear();
    const currentMonth = getCurrentMonth();
    
    updateYearlyTrendChart(currentYear);
    updateMonthlyDetailChart(currentYear, currentMonth);
    updateWeeklyChart(currentYear, currentMonth);
    updateDailyChart(currentYear, currentMonth);
}

// Yearly Trend Chart (Line Chart)
function updateYearlyTrendChart(year) {
    if (yearlyTrendChart) yearlyTrendChart.destroy();
    
    const ctx = document.getElementById('yearlyTrendChart').getContext('2d');
    yearlyTrendChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: months,
            datasets: [{
                label: `Monthly Trends for ${year}`,
                data: monthlyTrends[year],
                borderColor: chartColors.primary,
                backgroundColor: chartColors.background.primary,
                tension: 0.1,
                fill: true
            }]
        },
        options: {
            ...commonOptions,
            plugins: {
                ...commonOptions.plugins,
                title: {
                    display: true,
                    text: `Monthly Reservation Trends - ${year}`
                }
            }
        }
    });
}

// Monthly Analysis Chart (Bar Chart)
function updateMonthlyDetailChart(year, month) {
    if (monthlyDetailChart) monthlyDetailChart.destroy();
    
    const daysInMonth = getDaysInMonth(year, month);
    const monthName = months[month];
    
    // Create array for daily data
    let dailyValues = [];
    for (let day = 1; day <= daysInMonth; day++) {
        // Access the data correctly from the PHP structure
        const value = dailyData[year]?.[month]?.[day] || 0;
        dailyValues.push({
            x: `${monthName} ${day}`,
            y: value
        });
    }

    // Group data by weeks
    let weeklyDatasets = [];
    const weeksInMonth = Math.ceil(daysInMonth / 7);
    
    for (let week = 0; week < weeksInMonth; week++) {
        const weekData = dailyValues.slice(week * 7, (week + 1) * 7);
        if (weekData.length > 0) {
            weeklyDatasets.push({
                label: `Week ${week + 1}`,
                data: weekData,
                backgroundColor: chartColors.background.secondary,
                borderColor: chartColors.secondary,
                borderWidth: 1
            });
        }
    }

    const ctx = document.getElementById('monthlyDetailChart').getContext('2d');
    monthlyDetailChart = new Chart(ctx, {
        type: 'bar',
        data: {
            datasets: weeklyDatasets
        },
        options: {
            ...commonOptions,
            plugins: {
                ...commonOptions.plugins,
                title: {
                    display: true,
                    text: `Daily Reservations - ${monthName} ${year}`
                },
                tooltip: {
                    callbacks: {
                        title: function(context) {
                            return context[0].raw.x;
                        },
                        label: function(context) {
                            return `Reservations: ${context.raw.y}`;
                        }
                    }
                }
            },
            scales: {
                x: {
                    type: 'category',
                    stacked: true,
                    ticks: {
                        callback: function(value, index) {
                            // Show every 3rd label to prevent overcrowding
                            return index % 3 === 0 ? this.getLabelForValue(value) : '';
                        }
                    }
                },
                y: {
                    stacked: true,
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
}

// Weekly Analysis Chart (Bar Chart)
function updateWeeklyChart(year, month) {
    if (weeklyChart) weeklyChart.destroy();
    
    // Get the weekly data for the selected year and month
    const weekData = weeklyData[year]?.[month] || Array(6).fill(0);
    const weekLabels = ['Week 1', 'Week 2', 'Week 3', 'Week 4', 'Week 5', 'Week 6'];
    
    const ctx = document.getElementById('weeklyChart').getContext('2d');
    weeklyChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: weekLabels,
            datasets: [{
                label: `Weekly Reservations (${months[month]} ${year})`,
                data: weekData,
                backgroundColor: chartColors.background.tertiary,
                borderColor: chartColors.tertiary,
                borderWidth: 1
            }]
        },
        options: {
            ...commonOptions,
            plugins: {
                ...commonOptions.plugins,
                title: {
                    display: true,
                    text: `Weekly Reservation Analysis - ${months[month]} ${year}`
                }
            }
        }
    });
}

// Daily Analysis Chart (Line Chart)
function updateDailyChart(year, month) {
    if (dailyChart) dailyChart.destroy();
    
    const daysInMonth = getDaysInMonth(year, month);
    const dayLabels = Array.from({length: daysInMonth}, (_, i) => `Day ${i + 1}`);
    
    // Get the daily data for the selected year and month
    const dailyValues = Array.from({length: daysInMonth}, (_, i) => 
        dailyData[year]?.[month]?.[i + 1] || 0
    );
    
    const ctx = document.getElementById('dailyChart').getContext('2d');
    dailyChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: dayLabels,
            datasets: [{
                label: `Daily Reservations (${months[month]} ${year})`,
                data: dailyValues,
                borderColor: chartColors.quaternary,
                backgroundColor: chartColors.background.quaternary,
                tension: 0.1,
                fill: true
            }]
        },
        options: {
            ...commonOptions,
            plugins: {
                ...commonOptions.plugins,
                title: {
                    display: true,
                    text: `Daily Reservation Analysis - ${months[month]} ${year}`
                }
            },
            scales: {
                x: {
                    ticks: {
                        callback: function(value, index) {
                            // Show every 5th day label to prevent overcrowding
                            return index % 5 === 0 ? this.getLabelForValue(value) : '';
                        }
                    }
                }
            }
        }
    });
}


// Helper functions
function getCurrentYear() {
    return document.getElementById('yearlyTrendSelector').value;
}

function getCurrentMonth() {
    return parseInt(document.getElementById('weeklyMonthSelector').value);
}

// Event listeners
document.addEventListener('DOMContentLoaded', function() {
    initializeCharts();
    
    // Update all event listeners to use the correct function names
    document.getElementById('yearlyTrendSelector').addEventListener('change', function(e) {
        updateYearlyTrendChart(e.target.value);
    });
    
    document.getElementById('monthlyYearSelector').addEventListener('change', function(e) {
        updateMonthlyDetailChart(e.target.value, getCurrentMonth());
    });
    
    document.getElementById('monthlyMonthSelector').addEventListener('change', function(e) {
        updateMonthlyDetailChart(getCurrentYear(), parseInt(e.target.value));
    });
    
    document.getElementById('weeklyYearSelector').addEventListener('change', function(e) {
        updateWeeklyChart(e.target.value, getCurrentMonth());
    });
    
    document.getElementById('weeklyMonthSelector').addEventListener('change', function(e) {
        updateWeeklyChart(getCurrentYear(), parseInt(e.target.value));
    });
    
    document.getElementById('dailyYearSelector').addEventListener('change', function(e) {
        updateDailyChart(e.target.value, parseInt(document.getElementById('dailyMonthSelector').value));
    });
    
    document.getElementById('dailyMonthSelector').addEventListener('change', function(e) {
        updateDailyChart(getCurrentYear(), parseInt(e.target.value));
    });
});
</script>
@endsection
