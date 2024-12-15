    @extends('layouts.adminlayout')

    @section('content')

    <div class="content-header pt-4">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Activity Logs</h1>
                </div>
                <div class="col-sm-6">
                    <div class="float-end">
                        @if(count($logs) > 0)
                            <a href="{{ route('admin.logs.download') }}" class="btn btn-success me-2">
                                Export
                            </a>
                        @else
                            <button class="btn btn-success me-2" disabled title="No logs available">
                                Export
                            </button>
                        @endif
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#removeAllModal">
                            Remove All
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add this modal for confirmation -->
<div class="modal fade" id="removeAllModal" tabindex="-1" aria-labelledby="removeAllModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="removeAllModalLabel">Confirm Removal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to remove all logs? This action cannot be undone.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('admin.logs.removeAll') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-danger">Remove All</button>
                </form>
            </div>
        </div>
    </div>
</div>

 <!-- Filters and Table inside the card -->
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                       <!-- Filters above the table -->
                        <div class="d-flex justify-content-between mb-3">
                            <!-- Left side: From and To Date inputs next to each other -->
                            <div class="d-flex">
                                <div class="me-3">
                                    <label for="fromDate">From: </label>
                                    <input type="date" id="fromDate" class="form-control d-inline-block" style="width: 180px;" />
                                </div>
                                <div>
                                    <label for="toDate">To: </label>
                                    <input type="date" id="toDate" class="form-control d-inline-block" style="width: 180px;" />
                                </div>
                            </div>

                            <!-- Right side: Sort Order dropdown -->
                            <div>
                                <label for="sortOrder">Sort Order: </label>
                                <select id="sortOrder" class="form-select d-inline-block" style="width: 150px;">
                                    <option value="ascending">Old</option>
                                    <option value="descending">Recent</option>
                                </select>
                            </div>
                        </div>
                        <!-- Table below the filters -->
                        <div class="table-responsive">
                            <table class="table table-hover" id="activityLogTable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Date</th>
                                        <th>Time</th>
                                        <th>User</th>
                                        <th>Activity</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Log rows will be populated here by JS -->
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination controls below the table -->
                        <div class="pagination-controls mt-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="entries-per-page">
                                    <label>
                                        Show 
                                        <select id="entriesSelect" class="form-select d-inline-block w-auto mx-2">
                                            <option value="5">5</option>
                                            <option value="10">10</option>
                                            <option value="25">25</option>
                                            <option value="50">50</option>
                                            <option value="100">100</option>
                                        </select>
                                        entries
                                    </label>
                                </div>
                                <div class="pagination-numbers">
                                    <nav aria-label="Table navigation">
                                        <ul id="pagination" class="pagination mb-0">
                                            <!-- Pagination buttons will be populated here by JS -->
                                        </ul>
                                    </nav>
                                </div>
                                <div class="page-info">
                                    Showing <span class="showing-start">0</span> to <span class="showing-end">0</span> of <span class="total-entries">0</span> entries
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

    <style>
        .table td {
        vertical-align: middle;
    }

    .badge {
        font-size: 0.875em;
        padding: 0.5em 0.75em;
    }

    .activity-column {
        width: 900px;
        word-wrap: break-word;
    }

    /* New pagination styles */
    .pagination {
        display: flex;
        flex-wrap: wrap;
        gap: 5px;
        justify-content: center;
    }

    .pagination .page-item {
        margin: 2px;
    }

    .pagination .page-link {
        padding: 6px 12px;
        min-width: 38px;
        text-align: center;
    }

    .pagination-container {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .pagination-info {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
    }

    @media (max-width: 768px) {
        .pagination-info {
            flex-direction: column;
            align-items: center;
        }
    }
    </style>

    @endsection

    <script>
document.addEventListener('DOMContentLoaded', function () {
    const logs = <?php echo json_encode($logs)?>; // Logs passed from the backend
    console.log(logs); // Debugging line to check if logs are correctly passed
    
    const itemsPerPageSelect = document.querySelector('#entriesSelect');
    const paginationList = document.querySelector('#pagination');
    const tbody = document.querySelector('#activityLogTable tbody');
    const fromDateInput = document.querySelector('#fromDate');
    const toDateInput = document.querySelector('#toDate');
    const sortOrderSelect = document.querySelector('#sortOrder');
    let currentPage = 1;

    // Set the default sort order to descending
    sortOrderSelect.value = 'descending';

    // Function to update the table and pagination
    const updateTable = () => {
        const itemsPerPage = parseInt(itemsPerPageSelect.value);
        const fromDate = fromDateInput.value ? new Date(fromDateInput.value) : null;
        const toDate = toDateInput.value ? new Date(toDateInput.value) : null;
        const sortOrder = sortOrderSelect.value;
        const start = (currentPage - 1) * itemsPerPage;
        const end = start + itemsPerPage;

        // Filter logs by date range
        const filteredLogs = logs.filter(log => {
            const logDate = new Date(log.datetime);
            return (!fromDate || logDate >= fromDate) && (!toDate || logDate <= toDate);
        });

        console.log(filteredLogs); // Check filtered logs
        
        if (filteredLogs.length === 0) {
            tbody.innerHTML = '<tr><td colspan="5" class="text-center">No logs available for this filter</td></tr>';
            return;
        }

        // Sort logs based on the sort order
        filteredLogs.sort((a, b) => {
            const dateA = new Date(a.datetime);
            const dateB = new Date(b.datetime);
            return sortOrder === 'ascending' ? dateA - dateB : dateB - dateA;
        });

        // Paginate the filtered and sorted logs
        const currentLogs = filteredLogs.slice(start, end);

        // Clear the table body
        tbody.innerHTML = '';

        // Populate the table with rows
        currentLogs.forEach((log, index) => {
            // The row number should be based on the overall index of the logs in descending order
            const rowNumber = sortOrder === 'descending'
                ? (logs.length - filteredLogs.indexOf(log)) // Calculate row number based on overall index
                : (start + index + 1);

            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${rowNumber}</td> <!-- Corrected index for row numbering -->
                <td>${new Date(log.datetime).toLocaleDateString()}</td>
                <td>${new Date(log.datetime).toLocaleTimeString()}</td>
                <td>${log.user || 'N/A'}</td>
                <td class="activity-column">${log.message}</td>
            `;
            tbody.appendChild(row);
        });

        updatePagination(filteredLogs);
    };

    // Function to update the pagination controls
    const updatePagination = (filteredLogs) => {
        const itemsPerPage = parseInt(itemsPerPageSelect.value);
        const totalPages = Math.ceil(filteredLogs.length / itemsPerPage);
        
        // Clear existing page numbers
        paginationList.innerHTML = '';

        // Previous Button
        paginationList.innerHTML += `
            <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
                <button class="page-link prev-page" aria-label="Previous">&laquo;</button>
            </li>`;

        // Calculate visible page range
        let startPage = Math.max(1, currentPage - 2);
        let endPage = Math.min(totalPages, startPage + 4);
        
        // Adjust start if we're near the end
        if (endPage - startPage < 4) {
            startPage = Math.max(1, endPage - 4);
        }

        // First page and ellipsis
        if (startPage > 1) {
            paginationList.innerHTML += `
                <li class="page-item">
                    <button class="page-link page-number" data-page="1">1</button>
                </li>`;
            if (startPage > 2) {
                paginationList.innerHTML += `
                    <li class="page-item disabled">
                        <span class="page-link">...</span>
                    </li>`;
            }
        }

        // Page Numbers
        for (let i = startPage; i <= endPage; i++) {
            paginationList.innerHTML += `
                <li class="page-item ${currentPage === i ? 'active' : ''}">
                    <button class="page-link page-number" data-page="${i}">${i}</button>
                </li>`;
        }

        // Last page and ellipsis
        if (endPage < totalPages) {
            if (endPage < totalPages - 1) {
                paginationList.innerHTML += `
                    <li class="page-item disabled">
                        <span class="page-link">...</span>
                    </li>`;
            }
            paginationList.innerHTML += `
                <li class="page-item">
                    <button class="page-link page-number" data-page="${totalPages}">${totalPages}</button>
                </li>`;
        }

        // Next Button
        paginationList.innerHTML += `
            <li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
                <button class="page-link next-page" aria-label="Next">&raquo;</button>
            </li>`;

        // Update showing entries text
        const start = filteredLogs.length > 0 ? (currentPage - 1) * itemsPerPage + 1 : 0;
        const end = Math.min(currentPage * itemsPerPage, filteredLogs.length);
        document.querySelector('.showing-start').textContent = start;
        document.querySelector('.showing-end').textContent = end;
        document.querySelector('.total-entries').textContent = filteredLogs.length;
    };

    // Event listener for pagination controls
    paginationList.addEventListener('click', function (e) {
        if (e.target.classList.contains('page-number')) {
            currentPage = parseInt(e.target.dataset.page);
            updateTable();
        } else if (e.target.classList.contains('prev-page') && currentPage > 1) {
            currentPage--;
            updateTable();
        } else if (e.target.classList.contains('next-page')) {
            const totalPages = Math.ceil(logs.length / parseInt(itemsPerPageSelect.value));
            if (currentPage < totalPages) {
                currentPage++;
                updateTable();
            }
        }
    });

    // Event listener for entries per page
    itemsPerPageSelect.addEventListener('change', updateTable);

    // Event listeners for date range filter
    fromDateInput.addEventListener('change', updateTable);
    toDateInput.addEventListener('change', updateTable);

    // Event listener for sort order change
    sortOrderSelect.addEventListener('change', updateTable);

    // Initial load
    updateTable();
});

</script>
