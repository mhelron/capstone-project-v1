document.addEventListener('DOMContentLoaded', function () {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });

    // Store original rows for each table
    const tableOriginalRows = {};
    const itemsPerPageOptions = [5, 10, 25, 50, 100];
    const defaultItemsPerPage = 5;
    let currentPage = 1;

    // Add pagination controls to each tab
    function generatePageNumbers(currentPage, totalPages, maxVisiblePages = 5) {
        const pages = [];
        let startPage = Math.max(1, currentPage - Math.floor(maxVisiblePages / 2));
        let endPage = Math.min(totalPages, startPage + maxVisiblePages - 1);

        // Adjust start page if we're near the end
        if (endPage - startPage + 1 < maxVisiblePages) {
            startPage = Math.max(1, endPage - maxVisiblePages + 1);
        }

        // Add page numbers
        for (let i = startPage; i <= endPage; i++) {
            pages.push(`
                <li class="page-item ${i === currentPage ? 'active' : ''}">
                    <button class="page-link page-number" data-page="${i}">${i}</button>
                </li>
            `);
        }

        return pages.join('');
    }

    // Modified addPaginationControls function
    function addPaginationControls(tableId) {
        const table = document.querySelector(`#${tableId} table`);
        if (!table) return;

        const paginationContainer = document.createElement('div');
        paginationContainer.className = 'pagination-controls mt-3';
        paginationContainer.innerHTML = `
            <div class="d-flex justify-content-between align-items-center">
                <div class="entries-per-page">
                    <label>
                        Show 
                        <select class="form-select d-inline-block w-auto mx-2 entries-select">
                            ${itemsPerPageOptions.map(num => 
                                `<option value="${num}">${num}</option>`
                            ).join('')}
                        </select>
                        entries
                    </label>
                </div>
                <div class="pagination-numbers">
                    <nav aria-label="Table navigation">
                        <ul class="pagination mb-0">
                            <li class="page-item">
                                <button class="page-link prev-page" aria-label="Previous">Previous</button>
                            </li>
                            <!-- Page numbers will be inserted here -->
                            <li class="page-item">
                                <button class="page-link next-page" aria-label="Next">Next</button>
                            </li>
                        </ul>
                    </nav>
                </div>
                <div class="page-info">
                    Showing <span class="showing-start">0</span> to <span class="showing-end">0</span> of <span class="total-entries">0</span> entries
                </div>
            </div>
        `;

        table.parentNode.insertBefore(paginationContainer, table.nextSibling);

        // Add event listeners for pagination controls
        const entriesSelect = paginationContainer.querySelector('.entries-select');
        const pagination = paginationContainer.querySelector('.pagination');

        entriesSelect.addEventListener('change', () => {
            currentPage = 1;
            filterTable(tableId);
        });

        pagination.addEventListener('click', (e) => {
            const pageButton = e.target.closest('.page-number');
            if (pageButton) {
                currentPage = parseInt(pageButton.dataset.page);
                filterTable(tableId);
            }
        });

        paginationContainer.querySelector('.prev-page').addEventListener('click', () => {
            if (currentPage > 1) {
                currentPage--;
                filterTable(tableId);
            }
        });

        paginationContainer.querySelector('.next-page').addEventListener('click', () => {
            const table = document.querySelector(`#${tableId} table`);
            const entriesSelect = document.querySelector(`#${tableId} .entries-select`);
            const totalPages = Math.ceil(tableOriginalRows[tableId].length / parseInt(entriesSelect.value));
            
            if (currentPage < totalPages) {
                currentPage++;
                filterTable(tableId);
            }
        });
    }

    // Function to update pagination numbers    
     function updatePaginationInfo(tableId, visibleRows, itemsPerPage, currentPage) {
        const container = document.querySelector(`#${tableId} .pagination-controls`);
        if (!container) return;

        const totalEntries = visibleRows.length;
        const start = (currentPage - 1) * itemsPerPage + 1;
        const end = Math.min(start + itemsPerPage - 1, totalEntries);
        const totalPages = Math.ceil(totalEntries / itemsPerPage);

        // Update page numbers
        const pagination = container.querySelector('.pagination');
        const nextPageButton = container.querySelector('.next-page').parentElement;
        const pageNumbers = generatePageNumbers(currentPage, totalPages);
        
        // Remove existing page numbers
        Array.from(pagination.children).forEach(child => {
            if (!child.querySelector('.prev-page') && !child.querySelector('.next-page')) {
                child.remove();
            }
        });

        // Insert new page numbers before the next button
        nextPageButton.insertAdjacentHTML('beforebegin', pageNumbers);

        container.querySelector('.showing-start').textContent = totalEntries === 0 ? 0 : start;
        container.querySelector('.showing-end').textContent = end;
        container.querySelector('.total-entries').textContent = totalEntries;

        // Update pagination buttons state
        const prevButton = container.querySelector('.prev-page');
        const nextButton = container.querySelector('.next-page');
        
        prevButton.disabled = currentPage === 1;
        nextButton.disabled = end >= totalEntries;
    }

    // Modified filterTable function to include pagination
    function filterTable(tableId) {
        console.log('Filtering table:', tableId);

        const table = document.querySelector(`#${tableId} table`);
        if (!table) {
            console.error('Table not found:', tableId);
            return;
        }

        const tbody = table.querySelector('tbody');
        if (!tbody) {
            console.error('Tbody not found:', tableId);
            return;
        }

        const dateFilter = document.querySelector(`#${tableId} .date-filter`);
        const venueFilter = document.querySelector(`#${tableId} .venue-filter`);
        const entriesSelect = document.querySelector(`#${tableId} .entries-select`);
        const itemsPerPage = parseInt(entriesSelect?.value || defaultItemsPerPage);

        if (!dateFilter || !venueFilter) {
            console.error('Filters not found for table:', tableId);
            return;
        }

        // Initialize original rows if not already stored
        if (!tableOriginalRows[tableId]) {
            tableOriginalRows[tableId] = Array.from(tbody.querySelectorAll('tr'));
        }

        const dateValue = dateFilter.value;
        const locationValue = venueFilter.value;
        const today = new Date();
        today.setHours(0, 0, 0, 0);

        // Filter rows
        const visibleRows = tableOriginalRows[tableId].filter(row => {
            if (row.querySelector('td[colspan]')) return false;

            const location = row.getAttribute('data-location');
            const eventDate = row.getAttribute('data-event-date');
            const createdDate = row.getAttribute('data-created');

            if (locationValue !== 'all' && (!location || location.toLowerCase() !== locationValue.toLowerCase())) {
                return false;
            }

            if (dateValue === 'upcoming') {
                if (!eventDate) return false;
                const eventDateTime = new Date(eventDate);
                eventDateTime.setHours(0, 0, 0, 0);
                return eventDateTime >= today;
            }

            return true;
        });

        // Sort visible rows
        if (dateValue !== 'all') {
            visibleRows.sort((a, b) => {
                switch (dateValue) {
                    case 'newest':
                        return new Date(b.getAttribute('data-created') || '') - new Date(a.getAttribute('data-created') || '');
                    case 'upcoming':
                    case 'ascending':
                        return new Date(a.getAttribute('data-event-date')) - new Date(b.getAttribute('data-event-date'));
                    case 'descending':
                        return new Date(b.getAttribute('data-event-date')) - new Date(a.getAttribute('data-event-date'));
                    default:
                        return 0;
                }
            });
        }

        // Calculate pagination
        const startIndex = (currentPage - 1) * itemsPerPage;
        const endIndex = startIndex + itemsPerPage;
        const paginatedRows = visibleRows.slice(startIndex, endIndex);

        // Clear tbody
        tbody.innerHTML = '';

        // Handle no results or repopulate tbody
        if (visibleRows.length === 0) {
            const colspan = table.querySelector('thead tr').children.length;
            tbody.innerHTML = `<tr><td colspan="${colspan}" class="text-center">No reservations found matching the selected filters</td></tr>`;
        } else {
            // Clone and reappend rows in sorted order
            paginatedRows.forEach((row, index) => {
                const clonedRow = row.cloneNode(true);
                const firstCell = clonedRow.querySelector('td:first-child');
                if (firstCell) {
                    firstCell.textContent = (startIndex + index + 1).toString();
                }
                tbody.appendChild(clonedRow);
            });
        }

        // Update pagination information
        updatePaginationInfo(tableId, visibleRows, itemsPerPage, currentPage);
    }

    // Initialize pagination for each tab
    const tabPanes = ['penbook', 'pending', 'confirmed', 'cancelled', 'finished'];
    
    tabPanes.forEach(paneId => {
        // Add pagination controls
        addPaginationControls(paneId);

        // Add event listeners to filter controls
        const dateFilter = document.querySelector(`#${paneId} .date-filter`);
        const venueFilter = document.querySelector(`#${paneId} .venue-filter`);

        if (dateFilter) {
            dateFilter.addEventListener('change', () => {
                currentPage = 1;
                filterTable(paneId);
            });
        }

        if (venueFilter) {
            venueFilter.addEventListener('change', () => {
                currentPage = 1;
                filterTable(paneId);
            });
        }
    });

    // Handle tab changes
    const tabLinks = document.querySelectorAll('[data-bs-toggle="tab"]');
    tabLinks.forEach(tab => {
        tab.addEventListener('shown.bs.tab', function (e) {
            const targetId = e.target.getAttribute('data-bs-target').replace('#', '');
            currentPage = 1;
            filterTable(targetId);
        });
    });

    // Initial filter on page load
    tabPanes.forEach(paneId => {
        filterTable(paneId);
    });
});