@extends('layouts.adminlayout')

@section('content')
<style>
    .custom-select-height {
        height: 30px !important;
        padding: 2px 25px 2px 8px !important;
        width: 120px !important;
        max-width: 120px !important;
    }
    .chart-container {
        position: relative;
        height: 350px;
        width: 100%;
    }
</style>

<div class="d-flex justify-content-between align-items-center" style="padding-top: 35px;">
    <h1>Sales Report</h1>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#printModal">
        Print Report
    </button>
</div>

<!-- Print Options Modal -->
<div class="modal fade" id="printModal" tabindex="-1" aria-labelledby="printModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="printModalLabel">Print Options</h5>
            </div>
            <form action="{{ route('admin.reports.sales.print') }}" method="GET" target="_blank">
                <div class="modal-body">
                    <!-- Report Type Selection -->
                    <div class="mb-3">
                        <label class="form-label">Report Type</label>
                        <select name="report_type" id="printReportType" class="form-select">
                            <option value="all">All Reports</option>
                            <option value="yearly">Yearly Report</option>
                            <option value="monthly">Monthly Report</option>
                            <option value="weekly">Weekly Report</option>
                        </select>
                    </div>

                    <!-- Year Selection -->
                    <div class="mb-3">
                        <label class="form-label">Year</label>
                        <select name="year" id="printYear" class="form-select">
                            <option value="">All Years</option>
                            @foreach($years as $year)
                                <option value="{{ $year }}">{{ $year }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Month Selection (Initially hidden) -->
                    <div class="mb-3 d-none" id="monthSection">
                        <label class="form-label">Month</label>
                        <select name="month" id="printMonth" class="form-select">
                            <option value="">All Months</option>
                            @foreach($months as $index => $month)
                                <option value="{{ $index + 1 }}">{{ $month }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Week Selection (Initially hidden) -->
                    <div class="mb-3 d-none" id="weekSection">
                        <label class="form-label">Week</label>
                        <select name="week" id="modalWeekSelector" class="form-select">
                            <option value="">All Weeks</option>
                            @for($i = 1; $i <= 4; $i++)
                                <option value="{{ $i }}">Week {{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Print Report</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="row pt-3">
    <!-- Yearly Sales -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <span>Yearly Report</span>
                <select id="yearlyTrendSelector" class="form-select form-select-sm custom-select-height">
                    @foreach($years as $year)
                        <option value="{{ $year }}" {{ $year == date('Y') ? 'selected' : '' }}>{{ $year }}</option>
                    @endforeach
                </select>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="yearlySalesChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Sales -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <span>Monthly Report</span>
                    <div class="d-flex gap-2">
                        <select id="monthlyYearSelector" class="form-select form-select-sm custom-select-height">
                            @foreach($years as $year)
                                <option value="{{ $year }}" {{ $year == date('Y') ? 'selected' : '' }}>{{ $year }}</option>
                            @endforeach
                        </select>
                        <select id="monthlyMonthSelector" class="form-select form-select-sm custom-select-height">
                            @foreach($months as $index => $month)
                                <option value="{{ $index + 1 }}" {{ ($index + 1) == date('n') ? 'selected' : '' }}>{{ $month }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="monthlySalesChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Weekly Sales -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <span>Weekly Report</span>
                    <div class="d-flex gap-2">
                        <select id="weeklyYearSelector" class="form-select form-select-sm custom-select-height">
                            @foreach($years as $year)
                                <option value="{{ $year }}" {{ $year == date('Y') ? 'selected' : '' }}>{{ $year }}</option>
                            @endforeach
                        </select>
                        <select id="weeklyMonthSelector" class="form-select form-select-sm custom-select-height">
                            @foreach($months as $index => $month)
                                <option value="{{ $index + 1 }}" {{ ($index + 1) == date('n') ? 'selected' : '' }}>{{ $month }}</option>
                            @endforeach
                        </select>
                        <select id="weekSelector" class="form-select form-select-sm custom-select-height">
                            @php
                                $currentDate = new DateTime(date('Y-m-01'));
                                $weeksInMonth = ceil((date('t', $currentDate->getTimestamp()) + 
                                    (int)$currentDate->format('w') - 1) / 7);
                            @endphp
                            @for($i = 1; $i <= $weeksInMonth; $i++)
                                <option value="{{ $i }}">Week {{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="weeklySalesChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Daily Sales -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <span>Daily Report</span>
                    <div class="d-flex gap-2">
                        <select id="dailyYearSelector" class="form-select form-select-sm custom-select-height">
                            @foreach($years as $year)
                                <option value="{{ $year }}" {{ $year == date('Y') ? 'selected' : '' }}>{{ $year }}</option>
                            @endforeach
                        </select>
                        <select id="dailyMonthSelector" class="form-select form-select-sm custom-select-height">
                            @foreach($months as $index => $month)
                                <option value="{{ $index + 1 }}" {{ ($index + 1) == date('n') ? 'selected' : '' }}>{{ $month }}</option>
                            @endforeach
                        </select>
                        <select id="daySelector" class="form-select form-select-sm custom-select-height">
                            @for($day = 1; $day <= 31; $day++)
                                <option value="{{ $day }}" {{ $day == date('j') ? 'selected' : '' }}>Day {{ $day }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="dailySalesChart"></canvas>
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
const yearlyFees = @json($yearlyFees);
const yearlyPrices = @json($yearlyPrices);
const monthlyData = @json($monthlyData);
const weeklyData = @json($weeklyData);
const dailyData = @json($dailyData);

// Chart instances
let yearlySalesChart;
let monthlySalesChart;
let weeklySalesChart;
let dailySalesChart;

// Chart Colors
const chartColors = {
    fees: {
        background: 'rgba(54, 162, 235, 0.5)',
        border: 'rgba(54, 162, 235, 1)'
    },
    sales: {
        background: 'rgba(75, 192, 192, 0.5)',
        border: 'rgba(75, 192, 192, 1)'
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
                callback: function(value) {
                    return '₱' + value.toLocaleString();
                }
            }
        }
    }
};

// Helper functions
function getElement(id) {
    const element = document.getElementById(id);
    if (!element) {
        console.error(`Element with id "${id}" not found`);
    }
    return element;
}

function getChartContext(id) {
    const canvas = getElement(id);
    if (!canvas) return null;
    return canvas.getContext('2d');
}

// Update yearly chart to show monthly data for selected year
function updateYearlyChart(selectedYear) {
    if (yearlySalesChart) {
        yearlySalesChart.destroy();
    }

    const ctx = getChartContext('yearlySalesChart');
    if (!ctx) return;

    const yearData = monthlyData[selectedYear] || {
        reservationFees: Array(12).fill(0),
        totalPrices: Array(12).fill(0)
    };

    yearlySalesChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: months,
            datasets: [{
                label: 'Reservation Fees',
                data: yearData.reservationFees,
                backgroundColor: chartColors.fees.background,
                borderColor: chartColors.fees.border,
                borderWidth: 1,
                fill: true
            },
            {
                label: 'Package Sales',
                data: yearData.totalPrices,
                backgroundColor: chartColors.sales.background,
                borderColor: chartColors.sales.border,
                borderWidth: 1,
                fill: true
            }]
        },
        options: {
            ...commonOptions,
            plugins: {
                ...commonOptions.plugins,
                title: {
                    display: true,
                    text: `Monthly Sales in ${selectedYear}`
                }
            }
        }
    });
}

// Update monthly chart to show daily data for selected month
function updateMonthlyChart(selectedYear, selectedMonth) {
    if (monthlySalesChart) {
        monthlySalesChart.destroy();
    }

    const ctx = getChartContext('monthlySalesChart');
    if (!ctx) return;

    const daysInMonth = new Date(selectedYear, selectedMonth, 0).getDate();
    const dailyFees = [];
    const dailyPrices = [];

    for (let day = 1; day <= daysInMonth; day++) {
        dailyFees.push(dailyData[selectedYear]?.[selectedMonth]?.reservationFees?.[day] || 0);
        dailyPrices.push(dailyData[selectedYear]?.[selectedMonth]?.totalPrices?.[day] || 0);
    }

    monthlySalesChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: Array.from({length: daysInMonth}, (_, i) => `${i + 1}`),
            datasets: [{
                label: 'Reservation Fees',
                data: dailyFees,
                backgroundColor: chartColors.fees.background,
                borderColor: chartColors.fees.border,
                borderWidth: 1,
                fill: true
            },
            {
                label: 'Package Sales',
                data: dailyPrices,
                backgroundColor: chartColors.sales.background,
                borderColor: chartColors.sales.border,
                borderWidth: 1,
                fill: true
            }]
        },
        options: {
            ...commonOptions,
            plugins: {
                ...commonOptions.plugins,
                title: {
                    display: true,
                    text: `Daily Sales for ${months[selectedMonth - 1]} ${selectedYear}`
                }
            }
        }
    });
}

// Update weekly chart to show daily data for selected week
function updateWeeklyChart(selectedYear, selectedMonth, selectedWeek) {
    if (weeklySalesChart) {
        weeklySalesChart.destroy();
    }

    const ctx = getChartContext('weeklySalesChart');
    if (!ctx) return;

    // Calculate week dates
    const firstDayOfMonth = new Date(selectedYear, selectedMonth - 1, 1);
    const firstDayOfWeekOffset = firstDayOfMonth.getDay();
    const startDateOffset = ((selectedWeek - 1) * 7) - firstDayOfWeekOffset;
    const startDate = new Date(selectedYear, selectedMonth - 1, startDateOffset + 1);

    const labels = [];
    const weeklyFees = [];
    const weeklyPrices = [];

    for (let i = 0; i < 7; i++) {
        const currentDate = new Date(startDate);
        currentDate.setDate(startDate.getDate() + i);
        
        labels.push(currentDate.toLocaleDateString('en-US', {
            weekday: 'short',
            month: 'short',
            day: 'numeric'
        }));

        if (currentDate.getMonth() === selectedMonth - 1) {
            const dayOfMonth = currentDate.getDate();
            weeklyFees.push(dailyData[selectedYear]?.[selectedMonth]?.reservationFees?.[dayOfMonth] || 0);
            weeklyPrices.push(dailyData[selectedYear]?.[selectedMonth]?.totalPrices?.[dayOfMonth] || 0);
        } else {
            weeklyFees.push(0);
            weeklyPrices.push(0);
        }
    }

    weeklySalesChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Reservation Fees',
                data: weeklyFees,
                backgroundColor: chartColors.fees.background,
                borderColor: chartColors.fees.border,
                borderWidth: 1
            },
            {
                label: 'Package Sales',
                data: weeklyPrices,
                backgroundColor: chartColors.sales.background,
                borderColor: chartColors.sales.border,
                borderWidth: 1
            }]
        },
        options: {
            ...commonOptions,
            plugins: {
                ...commonOptions.plugins,
                title: {
                    display: true,
                    text: `Week ${selectedWeek} of ${months[selectedMonth - 1]} ${selectedYear}`
                }
            }
        }
    });
}

// Update daily chart
function updateDailyChart(selectedYear, selectedMonth, selectedDay) {
    if (dailySalesChart) {
        dailySalesChart.destroy();
    }

    const ctx = getChartContext('dailySalesChart');
    if (!ctx) return;

    const fees = dailyData[selectedYear]?.[selectedMonth]?.reservationFees?.[selectedDay] || 0;
    const sales = dailyData[selectedYear]?.[selectedMonth]?.totalPrices?.[selectedDay] || 0;

    dailySalesChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: [`${months[selectedMonth - 1]} ${selectedDay}, ${selectedYear}`],
            datasets: [{
                label: 'Reservation Fees',
                data: [fees],
                backgroundColor: chartColors.fees.background,
                borderColor: chartColors.fees.border,
                borderWidth: 1
            },
            {
                label: 'Package Sales',
                data: [sales],
                backgroundColor: chartColors.sales.background,
                borderColor: chartColors.sales.border,
                borderWidth: 1
            }]
        },
        options: {
            ...commonOptions,
            plugins: {
                ...commonOptions.plugins,
                title: {
                    display: true,
                    text: 'Daily Sales'
                }
            }
        }
    });
}

function getWeekOfMonth(date) {
    // Get the first day of the month
    const firstDay = new Date(date.getFullYear(), date.getMonth(), 1);
    // Get day of the month (1-31)
    const dayOfMonth = date.getDate();
    
    // Get day of week of the first day (0-6)
    const firstDayOfWeek = firstDay.getDay();
    
    // Calculate the adjusted day number
    const adjustedDay = dayOfMonth + firstDayOfWeek - 1;
    
    // Get week number (1-based)
    return Math.ceil(adjustedDay / 7);
}

// Update selectors
function updateWeekSelector(year, month) {
    const weekSelector = document.getElementById('weekSelector');
    if (!weekSelector) return;

    const now = new Date();
    const currentYear = now.getFullYear();
    const currentMonth = now.getMonth() + 1;
    
    // Get current week if we're in the selected month/year
    const currentWeek = getWeekOfMonth(now);
    
    // Calculate weeks in the selected month
    const date = new Date(year, month - 1, 1);
    const lastDay = new Date(year, month, 0);
    const weeksInMonth = Math.ceil((lastDay.getDate() + date.getDay()) / 7);

    // Clear and populate options
    weekSelector.innerHTML = '';
    for (let i = 1; i <= weeksInMonth; i++) {
        const option = document.createElement('option');
        option.value = i;
        option.text = `Week ${i}`;
        weekSelector.appendChild(option);
    }

    // Set the appropriate week
    if (year === currentYear && month === currentMonth) {
        weekSelector.value = currentWeek;
    } else {
        weekSelector.value = "1";
    }

    // Trigger update
    weekSelector.dispatchEvent(new Event('change'));
    
    // Force chart update
    const selectedWeek = parseInt(weekSelector.value);
    updateWeeklyChart(year, month, selectedWeek);
}

// Initialize everything when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    const now = new Date();
    const currentYear = now.getFullYear();
    const currentMonth = now.getMonth() + 1;
    const currentDay = now.getDate();
    const currentWeek = getWeekOfMonth(now);
    
    const weeklyYearSelector = document.getElementById('weeklyYearSelector');
    const weeklyMonthSelector = document.getElementById('weeklyMonthSelector');
    
    if (weeklyYearSelector) weeklyYearSelector.value = currentYear.toString();
    if (weeklyMonthSelector) weeklyMonthSelector.value = currentMonth.toString();
    
    // Initialize week selector first
    updateWeekSelector(currentYear, currentMonth);
    
    // Then initialize all charts
    updateYearlyChart(currentYear);
    updateMonthlyChart(currentYear, currentMonth);
    updateDailyChart(currentYear, currentMonth, currentDay);

    // Set up event listeners
    // Yearly chart
    getElement('yearlyTrendSelector')?.addEventListener('change', (e) => {
        updateYearlyChart(parseInt(e.target.value));
    });

    // Monthly chart
    ['monthlyYearSelector', 'monthlyMonthSelector'].forEach(id => {
        getElement(id)?.addEventListener('change', () => {
            const year = parseInt(getElement('monthlyYearSelector').value);
            const month = parseInt(getElement('monthlyMonthSelector').value);
            updateMonthlyChart(year, month);
        });
    });

    ['weeklyYearSelector', 'weeklyMonthSelector', 'weekSelector'].forEach(id => {
        getElement(id)?.addEventListener('change', () => {
            const year = parseInt(getElement('weeklyYearSelector').value);
            const month = parseInt(getElement('weeklyMonthSelector').value);
            const week = parseInt(getElement('weekSelector').value) || 1;
            updateWeeklyChart(year, month, week);
        });
    });

    // Daily chart
    ['dailyYearSelector', 'dailyMonthSelector', 'daySelector'].forEach(id => {
        getElement(id)?.addEventListener('change', () => {
            const year = parseInt(getElement('dailyYearSelector').value);
            const month = parseInt(getElement('dailyMonthSelector').value);
            const day = parseInt(getElement('daySelector').value);
            updateDailyChart(year, month, day);
        });
    });

    ['weeklyMonthSelector', 'weeklyYearSelector'].forEach(id => {
        getElement(id)?.addEventListener('change', () => {
            const year = parseInt(getElement('weeklyYearSelector').value);
            const month = parseInt(getElement('weeklyMonthSelector').value);
            updateWeekSelector(year, month);
        });
    });

    ['dailyMonthSelector', 'dailyYearSelector'].forEach(id => {
        getElement(id)?.addEventListener('change', () => {
            const year = parseInt(getElement('dailyYearSelector').value);
            const month = parseInt(getElement('dailyMonthSelector').value);
            updateDaySelector(year, month);
        });
    });
});
</script>

<!-- Print route handling script -->
<script>
document.getElementById('printReportType').addEventListener('change', function() {
    const monthSection = document.getElementById('monthSection');
    const weekSection = document.getElementById('weekSection');
    
    // Hide all sections first
    monthSection.classList.add('d-none');
    weekSection.classList.add('d-none');
    
    // Show relevant sections based on selection
    switch(this.value) {
        case 'monthly':
            monthSection.classList.remove('d-none');
            break;
        case 'weekly':
            monthSection.classList.remove('d-none');
            weekSection.classList.remove('d-none');
            break;
    }
});
document.querySelector('#printModal form').addEventListener('submit', function(e) {
    e.preventDefault();
    const reportType = getElement('printReportType').value;
    let route = '{{ route("admin.reports.sales.print") }}';
    
    switch(reportType) {
        case 'yearly':
            route = '{{ route("admin.reports.sales.print") }}?report_type=yearly';
            break;
        case 'monthly':
            route = '{{ route("admin.reports.sales.print") }}?report_type=monthly';
            break;
        case 'weekly':
            route = '{{ route("admin.reports.sales.print") }}?report_type=weekly';
            break;
    }
    
    this.action = route;
    this.submit();

    setTimeout(() => {
        this.reset();
        getElement('monthSection').classList.add('d-none');
        getElement('weekSection').classList.add('d-none');
    }, 1000);
});
</script>
@endsection