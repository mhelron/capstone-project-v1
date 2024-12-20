@extends('layouts.adminlayout')

@section('content')
<style>
    .custom-select-height {
        height: 30px !important;
        padding: 2px 25px 2px 8px !important;
        width: 120px !important;
        max-width: 120px !important;
    }
    .table-container {
        height: 350px;
        overflow-y: auto;
    }
    .rank-1 {
        background-color: rgba(255, 215, 0, 0.2) !important;
    }
    .rank-2 {
        background-color: rgba(192, 192, 192, 0.2) !important;
    }
    .rank-3 {
        background-color: rgba(205, 127, 50, 0.2) !important;
    }
    .empty-message {
        text-align: center;
        padding: 20px;
        font-style: italic;
        color: #6c757d;
    }
    .card {
        margin-bottom: 20px;
    }
    .card-header {
        background-color: #0d6efd !important;
        color: white !important;
        padding: 0.75rem !important;
    }
    .table th {
        background-color: #f8f9fa;
        position: sticky;
        top: 0;
        z-index: 1;
    }
    .table-responsive {
        margin: 0;
    }
</style>

<div class="d-flex justify-content-between align-items-center" style="padding-top: 35px;">
    <h1>Package Rankings</h1>
</div>

<div class="row pt-3">
    <!-- All Time Rankings -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <span>All Time Rankings</span>
            </div>
            <div class="card-body p-0">
                <div class="table-container">
                    <table class="table table-bordered mb-0">
                        <thead>
                            <tr>
                                <th>Rank</th>
                                <th>Package Name</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($allTimeRankings as $packageName => $count)
                                <tr class="rank-{{ $loop->iteration <= 3 ? $loop->iteration : '' }}">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $packageName }}</td>
                                    <td>{{ $count }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="empty-message">No packages found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Yearly Rankings -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <span>Yearly Rankings</span>
                    <select id="yearSelector" class="form-select form-select-sm custom-select-height" onchange="updateRankings('yearly')">
                        @foreach($years as $year)
                            <option value="{{ $year }}" {{ $year == $selectedYear ? 'selected' : '' }}>
                                {{ $year }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-container">
                    <table class="table table-bordered mb-0">
                        <thead>
                            <tr>
                                <th>Rank</th>
                                <th>Package Name</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <!-- In the Yearly Rankings table -->
                        <tbody id="yearlyTableBody">
                            @forelse($yearlyRankings as $packageName => $count)
                                <tr class="rank-{{ $loop->iteration <= 3 ? $loop->iteration : '' }}">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $packageName }}</td>
                                    <td>{{ $count }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="empty-message">No packages found for selected year</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Rankings -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <span>Monthly Rankings</span>
                    <div class="d-flex gap-2">
                        <select id="monthlyYearSelector" class="form-select form-select-sm custom-select-height" onchange="updateRankings('monthly')">
                            @foreach($years as $year)
                                <option value="{{ $year }}" {{ $year == $selectedYear ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @endforeach
                        </select>
                        <select id="monthSelector" class="form-select form-select-sm custom-select-height" onchange="updateRankings('monthly')">
                            @foreach($months as $key => $month)
                                <option value="{{ $key }}" {{ $key == $selectedMonth ? 'selected' : '' }}>
                                    {{ $month }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-container">
                    <table class="table table-bordered mb-0">
                        <thead>
                            <tr>
                                <th>Rank</th>
                                <th>Package Name</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody id="monthlyTableBody">
                            @forelse($monthlyRankings as $packageName => $count)
                                <tr class="rank-{{ $loop->iteration <= 3 ? $loop->iteration : '' }}">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $packageName }}</td>
                                    <td>{{ $count }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="empty-message">No packages found for selected month</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Weekly Rankings -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <span>Weekly Rankings</span>
                    <div class="d-flex gap-2">
                        <select id="weeklyYearSelector" class="form-select form-select-sm custom-select-height" onchange="updateRankings('weekly')">
                            @foreach($years as $year)
                                <option value="{{ $year }}" {{ $year == $selectedYear ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @endforeach
                        </select>
                        <select id="weeklyMonthSelector" class="form-select form-select-sm custom-select-height" onchange="updateRankings('weekly')">
                            @foreach($months as $key => $month)
                                <option value="{{ $key }}" {{ $key == $selectedMonth ? 'selected' : '' }}>
                                    {{ $month }}
                                </option>
                            @endforeach
                        </select>
                        <select id="weekSelector" class="form-select form-select-sm custom-select-height" onchange="updateRankings('weekly')">
                            @for($i = 1; $i <= 5; $i++)
                                <option value="{{ $i }}" {{ $i == $selectedWeek ? 'selected' : '' }}>
                                    Week {{ $i }}
                                </option>
                            @endfor
                        </select>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-container">
                    <table class="table table-bordered mb-0">
                        <thead>
                            <tr>
                                <th>Rank</th>
                                <th>Package Name</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody id="weeklyTableBody">
                            @forelse($weeklyRankings as $packageName => $count)
                                <tr class="rank-{{ $loop->iteration <= 3 ? $loop->iteration : '' }}">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $packageName }}</td>
                                    <td>{{ $count }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="empty-message">No packages found for selected week</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function updateRankings(type) {
    let url;
    const params = new URLSearchParams();
    let tableBody;
    
    if (type === 'yearly') {
        url = '/admin/reports/packages/yearly';
        params.append('year', document.getElementById('yearSelector').value);
        tableBody = document.querySelector('#yearlyTableBody');
    } else if (type === 'monthly') {
        url = '/admin/reports/packages/monthly';
        params.append('year', document.getElementById('monthlyYearSelector').value);
        params.append('month', document.getElementById('monthSelector').value);
        tableBody = document.querySelector('#monthlyTableBody');
    } else if (type === 'weekly') {
        url = '/admin/reports/packages/weekly';
        params.append('year', document.getElementById('weeklyYearSelector').value);
        params.append('month', document.getElementById('weeklyMonthSelector').value);
        params.append('week', document.getElementById('weekSelector').value);
        tableBody = document.querySelector('#weeklyTableBody');
    }

    // Show loading state
    tableBody.innerHTML = '<tr><td colspan="3" class="text-center">Loading...</td></tr>';

    // Make AJAX request
    fetch(`${url}?${params.toString()}`)
        .then(response => response.json())
        .then(data => {
            let html = '';
            let rank = 1;
            
            if (Object.keys(data.rankings).length === 0) {
                html = `<tr><td colspan="3" class="empty-message">No packages found</td></tr>`;
            } else {
                for (const [packageName, count] of Object.entries(data.rankings)) {
                    html += `
                        <tr class="rank-${rank <= 3 ? rank : ''}">
                            <td>${rank}</td>
                            <td>${packageName}</td>
                            <td>${count}</td>
                        </tr>
                    `;
                    rank++;
                }
            }
            
            tableBody.innerHTML = html;
        })
        .catch(error => {
            console.error('Error:', error);
            tableBody.innerHTML = '<tr><td colspan="3" class="text-center text-danger">Error loading data. Please try again.</td></tr>';
        });
}

// Update number of weeks based on selected month
function updateWeeks(year, month) {
    const weekSelector = document.getElementById('weekSelector');
    if (!weekSelector) return;

    const date = new Date(year, month - 1, 1);
    const lastDate = new Date(year, month, 0);
    const firstDayOfWeek = date.getDay();
    const numberOfWeeks = Math.ceil((lastDate.getDate() + firstDayOfWeek) / 7);

    const currentSelection = weekSelector.value;
    weekSelector.innerHTML = '';

    for (let i = 1; i <= numberOfWeeks; i++) {
        const option = document.createElement('option');
        option.value = i;
        option.text = `Week ${i}`;
        weekSelector.appendChild(option);
    }

    if (currentSelection <= numberOfWeeks) {
        weekSelector.value = currentSelection;
    } else {
        weekSelector.value = 1;
    }
    
    // Update the weekly rankings after changing weeks
    updateRankings('weekly');
}
</script>
@endsection