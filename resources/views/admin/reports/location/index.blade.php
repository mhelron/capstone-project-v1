@extends('layouts.adminlayout')

@section('content')

<div>
    <h1 style="padding-top: 35px;">Locations Report</h1>
</div>

<div>
    <div class="row pt-3">
        <!-- Monthly Line Chart -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    Finished Reservations by Month
                </div>
                <div class="card-body">
                    <canvas id="monthlyLineChart" style="height: 300px;"></canvas>
                </div>
            </div>
        </div>

        <!-- Weekly Line Chart -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    Finished Reservations by Week
                </div>
                <div class="card-body">
                    <canvas id="weeklyLineChart" style="height: 300px;"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const monthlyCounts = @json($monthlyCounts);
    const weeklyCounts = @json($weeklyCounts);
    const yearlyCounts = @json($yearlyCounts); // Add yearly data

    // Monthly Line Chart Data
    const monthlyLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    
    const marikinaMonthlyCounts = monthlyCounts['Marikina'];
    const sanMateoMonthlyCounts = monthlyCounts['San Mateo'];
    const montalbanMonthlyCounts = monthlyCounts['Montalban'];

    // Weekly Line Chart Data
    const weeklyLabels = ['Week 1', 'Week 2', 'Week 3', 'Week 4'];

    const marikinaWeeklyCounts = weeklyCounts['Marikina'];
    const sanMateoWeeklyCounts = weeklyCounts['San Mateo'];
    const montalbanWeeklyCounts = weeklyCounts['Montalban'];

    // Yearly Line Chart Data (Assuming yearly data is in the form of an array)
    const yearlyLabels = Object.keys(yearlyCounts); // This should give you years like ['2024', '2023', etc.]

    // You can then adjust your labels by stripping any additional values if needed
    const adjustedYearlyLabels = yearlyLabels.map(label => label); // This just uses the year as the label directly

    // Monthly Line Chart
    const monthlyCtx = document.getElementById('monthlyLineChart').getContext('2d');
    new Chart(monthlyCtx, {
        type: 'line',
        data: {
            labels: monthlyLabels,
            datasets: [
                {
                    label: 'Marikina',
                    data: marikinaMonthlyCounts,
                    borderColor: 'rgba(54, 162, 235, 1)',
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 2
                },
                {
                    label: 'San Mateo',
                    data: sanMateoMonthlyCounts,
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 2
                },
                {
                    label: 'Montalban',
                    data: montalbanMonthlyCounts,
                    borderColor: 'rgba(153, 102, 255, 1)',
                    backgroundColor: 'rgba(153, 102, 255, 0.2)',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 2
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'top' },
                tooltip: { mode: 'index', intersect: false }
            },
            scales: { y: { beginAtZero: true } }
        }
    });

    // Weekly Line Chart
    const weeklyCtx = document.getElementById('weeklyLineChart').getContext('2d');
    new Chart(weeklyCtx, {
        type: 'line',
        data: {
            labels: weeklyLabels,
            datasets: [
                {
                    label: 'Marikina',
                    data: marikinaWeeklyCounts,
                    borderColor: 'rgba(54, 162, 235, 1)',
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 2
                },
                {
                    label: 'San Mateo',
                    data: sanMateoWeeklyCounts,
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 2
                },
                {
                    label: 'Montalban',
                    data: montalbanWeeklyCounts,
                    borderColor: 'rgba(153, 102, 255, 1)',
                    backgroundColor: 'rgba(153, 102, 255, 0.2)',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 2
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'top' },
                tooltip: { mode: 'index', intersect: false }
            },
            scales: { y: { beginAtZero: true } }
        }
    });

    // Yearly Line Chart
const yearlyCtx = document.getElementById('yearlyLineChart').getContext('2d');
new Chart(yearlyCtx, {
    type: 'line',
    data: {
        labels: adjustedYearlyLabels, // Use the adjusted labels here
        datasets: [
            {
                label: 'Marikina',
                data: marikinaYearlyCounts,
                borderColor: 'rgba(54, 162, 235, 1)',
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                fill: true,
                tension: 0.4,
                borderWidth: 2
            },
            {
                label: 'San Mateo',
                data: sanMateoYearlyCounts,
                borderColor: 'rgba(75, 192, 192, 1)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                fill: true,
                tension: 0.4,
                borderWidth: 2
            },
            {
                label: 'Montalban',
                data: montalbanYearlyCounts,
                borderColor: 'rgba(153, 102, 255, 1)',
                backgroundColor: 'rgba(153, 102, 255, 0.2)',
                fill: true,
                tension: 0.4,
                borderWidth: 2
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { position: 'top' },
            tooltip: { mode: 'index', intersect: false }
        },
        scales: { y: { beginAtZero: true } }
    }
});
</script>

@endsection
