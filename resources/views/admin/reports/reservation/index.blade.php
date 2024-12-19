@extends('layouts.adminlayout')

@section('content')
<style>
    .custom-select-height {
        height: 30px !important;
        padding: 2px 25px 2px 8px !important;
        width: 120px !important; /* Add specific width */
        /* or */
        max-width: 120px !important; /* Alternative approach */
    }
    .chart-container {
        position: relative;
        height: 350px; /* Increased from 300px */
        width: 100%;
    }
</style>

<div class="d-flex justify-content-between align-items-center" style="padding-top: 35px;">
    <h1>Reservations Report</h1>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#printModal">
        Print Report
    </button>
</div>

<!-- Add this modal code after your charts section -->
<div class="modal fade" id="printModal" tabindex="-1" aria-labelledby="printModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="printModalLabel">Print Options</h5>
            </div>
            <form action="{{ route('reservation.print') }}" method="GET" target="_blank">
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
                        <select name="week" id="modalWeekSelector" class="form-select"> <!-- Changed from printWeek -->
                            <option value="">All Weeks</option>
                            @for($i = 1; $i <= 5; $i++)
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
    <!-- Yearly Trends (Monthly View) -->
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
                        <select id="weekSelector" class="form-select form-select-sm custom-select-height"> <!-- Changed from printWeek -->
                            <option value="">All Weeks</option>
                            @php
                                $currentDate = new DateTime("$year-$month-01");
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

// Helper function to safely get DOM elements
function getElement(id) {
    const element = document.getElementById(id);
    if (!element) {
        console.error(`Element with id "${id}" not found`);
    }
    return element;
}

// Helper function to safely get canvas context
function getChartContext(id) {
    const canvas = getElement(id);
    if (!canvas) return null;
    return canvas.getContext('2d');
}

// Yearly Trend Chart
function updateYearlyTrendChart(year) {
    try {
        const ctx = getChartContext('yearlyTrendChart');
        if (!ctx) return;

        if (yearlyTrendChart) {
            yearlyTrendChart.destroy();
        }

        console.log('Updating yearly trend chart:', { year, data: monthlyTrends[year] });
        
        yearlyTrendChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: months,
                datasets: [{
                    label: `Reservations in ${year}`,
                    data: monthlyTrends[year] || Array(12).fill(0),
                    borderColor: chartColors.primary,
                    backgroundColor: chartColors.background.primary,
                    borderColor: chartColors.primary,
                    borderWidth: 1
                }]
            },
            options: commonOptions
        });
    } catch (error) {
        console.error('Error updating yearly trend chart:', error);
    }
}

// Monthly Detail Chart
function updateMonthlyDetailChart(year, month) {
    try {
        const ctx = getChartContext('monthlyDetailChart');
        if (!ctx) return;

        if (monthlyDetailChart) {
            monthlyDetailChart.destroy();
        }

        const daysInMonth = new Date(year, month + 1, 0).getDate();
        const dayLabels = Array.from({length: daysInMonth}, (_, i) => `${i + 1}`);
        
        const dailyValues = [];
        for (let day = 1; day <= daysInMonth; day++) {
            dailyValues.push(dailyData[year]?.[month + 1]?.[day] || 0);
        }

        console.log('Updating monthly detail chart:', { year, month, data: dailyValues });

        monthlyDetailChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: dayLabels,
                datasets: [{
                    label: `Daily Reservations`,
                    data: dailyValues,
                    backgroundColor: chartColors.background.secondary,
                    borderColor: chartColors.secondary,
                    borderWidth: 1
                }]
            },
            options: {
                ...commonOptions,
                plugins: {
                    ...commonOptions.plugins,
                    title: {
                        display: true,
                        text: `${months[month]} ${year}`
                    }
                }
            }
        });
    } catch (error) {
        console.error('Error updating monthly detail chart:', error);
    }
}

// Weekly Chart
function updateWeeklyChart(year, month, week) { // Add week parameter
    try {
        const ctx = getChartContext('weeklyChart');
        if (!ctx) return;

        if (weeklyChart) {
            weeklyChart.destroy();
        }

        const selectedWeek = week || 1;
        
        // Calculate first day of month
        const firstDayOfMonth = new Date(year, month, 1);
        const firstDayOfWeekOffset = firstDayOfMonth.getDay(); // 0 (Sunday) to 6 (Saturday)
        
        // Calculate the start and end dates for the selected week
        const startDateOffset = ((selectedWeek - 1) * 7) - firstDayOfWeekOffset;
        const startDate = new Date(year, month, startDateOffset + 1);
        
        const dateLabels = [];
        const weekData = [];
        
        // Generate data for each day of the week
        for (let i = 0; i < 7; i++) {
            const currentDate = new Date(startDate);
            currentDate.setDate(startDate.getDate() + i);
            
            // Format the date label
            dateLabels.push(currentDate.toLocaleDateString('en-US', {
                weekday: 'short',
                month: 'short',
                day: 'numeric'
            }));
            
            // Only count reservations if the date is within the selected month
            if (currentDate.getMonth() === month) {
                const dayOfMonth = currentDate.getDate();
                weekData.push(dailyData[year]?.[month + 1]?.[dayOfMonth] || 0);
            } else {
                weekData.push(0);
            }
        }

        console.log('Weekly chart data:', {
            year,
            month,
            week: selectedWeek,
            data: weekData,
            labels: dateLabels
        });

        weeklyChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: dateLabels,
                datasets: [{
                    label: `Week ${selectedWeek} Reservations`,
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
                        text: `Week ${selectedWeek} of ${months[month]} ${year}`
                    }
                }
            }
        });
    } catch (error) {
        console.error('Error updating weekly chart:', error);
    }
}

// Daily Chart
function updateDailyChart(year, month) {
    try {
        const ctx = getChartContext('dailyChart');
        if (!ctx) return;

        if (dailyChart) {
            dailyChart.destroy();
        }

        const daySelector = getElement('daySelector');
        if (!daySelector) return;

        const selectedDay = parseInt(daySelector.value) || 1;
        const dayValue = dailyData[year]?.[month + 1]?.[selectedDay] || 0;

        console.log('Updating daily chart:', { year, month, day: selectedDay, value: dayValue });

        dailyChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: [`${months[month]} ${selectedDay}, ${year}`],
                datasets: [{
                    label: 'Reservations',
                    data: [dayValue],
                    backgroundColor: chartColors.background.quaternary,
                    borderColor: chartColors.quaternary,
                    borderWidth: 1
                }]
            },
            options: {
                ...commonOptions,
                plugins: {
                    ...commonOptions.plugins,
                    title: {
                        display: true,
                        text: 'Daily Reservations'
                    }
                }
            }
        });
    } catch (error) {
        console.error('Error updating daily chart:', error);
    }
}

// Initialize everything when the DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    const now = new Date();
    const currentYear = now.getFullYear();
    const currentMonth = now.getMonth();
    const currentDay = now.getDate();
    const currentWeek = getWeekOfMonth(now);
    
    console.log('Initialization with:', {
        currentYear,
        currentMonth,
        currentDay,
        currentWeek
    });
    
    // Initialize selectors first
    const weeklyYearSelector = getElement('weeklyYearSelector');
    const weeklyMonthSelector = getElement('weeklyMonthSelector');
    
    if (weeklyYearSelector) {
        weeklyYearSelector.value = currentYear.toString();
    }
    if (weeklyMonthSelector) {
        weeklyMonthSelector.value = currentMonth.toString();
    }

    // Initialize selectors before charts
    updateWeekSelector(currentYear, currentMonth);
    updateDaySelector(currentYear, currentMonth, currentDay);

    // Now initialize charts
    updateYearlyTrendChart(currentYear);
    updateMonthlyDetailChart(currentYear, currentMonth);
    updateWeeklyChart(currentYear, currentMonth, currentWeek); // Pass current week
    updateDailyChart(currentYear, currentMonth); 

    // Set up event listeners
    const yearlyTrendSelector = getElement('yearlyTrendSelector');
    if (yearlyTrendSelector) {
        yearlyTrendSelector.addEventListener('change', (e) => {
            updateYearlyTrendChart(parseInt(e.target.value));
        });
    }

    // Monthly chart listeners
    const monthlySelectors = ['monthlyYearSelector', 'monthlyMonthSelector'];
    monthlySelectors.forEach(id => {
        const element = getElement(id);
        if (element) {
            element.addEventListener('change', () => {
                const year = parseInt(getElement('monthlyYearSelector')?.value || currentYear);
                const month = parseInt(getElement('monthlyMonthSelector')?.value || currentMonth);
                updateMonthlyDetailChart(year, month);
            });
        }
    });

    function debugChartData(year, month, week) {
        console.log('Chart data debug:', {
            year,
            month,
            week,
            hasYearData: !!dailyData[year],
            hasMonthData: dailyData[year] ? !!dailyData[year][month + 1] : false,
            weekData: weeklyData[year]?.[month + 1]?.[week],
            dailyData: dailyData[year]?.[month + 1]
        });
    }

    // Modify the weekly chart event listeners
    const weeklySelectors = ['weeklyYearSelector', 'weeklyMonthSelector', 'weekSelector'];
    weeklySelectors.forEach(id => {
        const element = getElement(id);
        if (element) {
            element.addEventListener('change', () => {
                const year = parseInt(getElement('weeklyYearSelector')?.value || currentYear);
                const month = parseInt(getElement('weeklyMonthSelector')?.value || currentMonth);
                const week = parseInt(getElement('weekSelector')?.value || 1);
                
                console.log('Weekly selector changed:', { id, year, month, week });
                debugChartData(year, month, week);
                
                updateWeeklyChart(year, month, week);
            });
        }
    });

    // Daily chart listeners
    const dailySelectors = ['dailyYearSelector', 'dailyMonthSelector', 'daySelector'];
    dailySelectors.forEach(id => {
        const element = getElement(id);
        if (element) {
            element.addEventListener('change', () => {
                const year = parseInt(getElement('dailyYearSelector')?.value || currentYear);
                const month = parseInt(getElement('dailyMonthSelector')?.value || currentMonth);
                updateDailyChart(year, month);
            });
        }
    });

    // Initialize selectors
    updateWeekSelector(currentYear, currentMonth);
    updateDaySelector(currentYear, currentMonth);
});

function getWeekOfMonth(date) {
    const firstDay = new Date(date.getFullYear(), date.getMonth(), 1);
    const firstDayWeek = firstDay.getDay();
    const offset = firstDayWeek;
    
    return Math.ceil((date.getDate() + offset) / 7);
}

// Update dynamic selectors
function updateWeekSelector(year, month) {
    const weekSelector = getElement('weekSelector');
    if (!weekSelector) {
        console.error('Week selector not found');
        return;
    }

    const date = new Date(year, month, 1);
    const weeksInMonth = Math.ceil((date.getDay() + new Date(year, month + 1, 0).getDate()) / 7);
    
    // Get current date and its week number
    const now = new Date();
    const currentYear = now.getFullYear();
    const currentMonth = now.getMonth();
    const currentWeek = getWeekOfMonth(now);
    
    console.log('Updating week selector:', { year, month, weeksInMonth, currentWeek });
    
    weekSelector.innerHTML = '';
    for (let i = 1; i <= weeksInMonth; i++) {
        const option = document.createElement('option');
        option.value = i;
        option.text = `Week ${i}`;
        
        if (year === currentYear && month === currentMonth && i === currentWeek) {
            option.selected = true;
        }
        weekSelector.appendChild(option);
    }
    
    if (year !== currentYear || month !== currentMonth) {
        weekSelector.value = "1";
    }
    
    // Get the selected week value after setting options
    const selectedWeek = parseInt(weekSelector.value) || 1;
    
    // Update the weekly chart with the current values
    updateWeeklyChart(year, month, selectedWeek);
}

function updateDaySelector(year, month, selectedDay = null) {
    const daySelector = getElement('daySelector');
    if (!daySelector) {
        console.error('Day selector not found');
        return;
    }

    // Get the current date and current day
    const now = new Date();
    const currentYear = now.getFullYear();
    const currentMonth = now.getMonth();
    const currentDay = now.getDate();

    const daysInMonth = new Date(year, month + 1, 0).getDate();
    daySelector.innerHTML = '';

    for (let day = 1; day <= daysInMonth; day++) {
        const option = document.createElement('option');
        option.value = day;
        option.text = `Day ${day}`;

        // Select the current day if year/month match, or use the provided selectedDay
        if (year === currentYear && month === currentMonth && day === currentDay) {
            option.selected = true;
        } else if (selectedDay && day === selectedDay) {
            option.selected = true;
        }

        daySelector.appendChild(option);
    }

    // Set the default selected day if not already selected
    if (!daySelector.value) {
        daySelector.value = selectedDay || 1;
    }

    // Trigger change event to update chart
    daySelector.dispatchEvent(new Event('change'));
}


// Add listeners for selector updates
['weeklyMonthSelector', 'weeklyYearSelector'].forEach(id => {
    const element = getElement(id);
    if (element) {
        element.addEventListener('change', () => {
            const year = parseInt(getElement('weeklyYearSelector')?.value || new Date().getFullYear());
            const month = parseInt(getElement('weeklyMonthSelector')?.value || new Date().getMonth());
            updateWeekSelector(year, month);
            const week = parseInt(getElement('weekSelector')?.value || 1);
            updateWeeklyChart(year, month, week);
        });
    }
});

['dailyMonthSelector', 'dailyYearSelector'].forEach(id => {
    const element = getElement(id);
    if (element) {
        element.addEventListener('change', () => {
            const year = parseInt(getElement('dailyYearSelector')?.value || new Date().getFullYear());
            const month = parseInt(getElement('dailyMonthSelector')?.value || new Date().getMonth());
            updateDaySelector(year, month);
        });
    }
});
</script>

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
    const reportType = document.getElementById('printReportType').value;
    let route;
    
    switch(reportType) {
        case 'yearly':
            route = '{{ route("reservation.print.yearly") }}';
            break;
        case 'monthly':
            route = '{{ route("reservation.print.monthly") }}';
            break;
        case 'weekly':
            route = '{{ route("reservation.print.weekly") }}';
            break;
        default:
            route = '{{ route("reservation.print") }}';
    }
    
    this.action = route;
    this.submit();

    setTimeout(() => {
        this.reset();
        // Reset any hidden sections
        document.getElementById('monthSection').classList.add('d-none');
        document.getElementById('weekSection').classList.add('d-none');
    }, 1000);
});
</script>
@endsection
