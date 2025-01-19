@extends('layouts.adminlayout')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0" style="padding-top: 35px;">Quotation Requests</h1>
            </div>
        </div>
    </div>
</div>

<!-- Main content -->
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <!-- Bootstrap Toast -->
                @if (session('status'))
                    <div class="toast-container position-fixed top-0 end-0 p-3">
                        <div class="toast text-bg-light border border-dark custom-toast-size" role="alert" aria-live="assertive" aria-atomic="true">
                            <div class="d-flex">
                                <div class="toast-body">
                                    {{ session('status') }}
                                </div>
                                <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Quotations Table -->
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Reference #</th>
                                        <th scope="col">Full Name</th>
                                        <th scope="col">Event</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Total Price</th>
                                        <th scope="col">Payment Status</th>
                                        <th scope="col">Details</th>
                                        <th scope="col">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $i = 1; @endphp
                                    @forelse ($quotations as $key => $item)
                                    <tr>
                                        <td>{{ $i++ }}</td>
                                        <td>{{ $item['reference_number'] }}</td>
                                        <td>{{ $item['lastname'] }}, {{ $item['firstname'] }}</td>
                                        <td>{{ $item['event'] }}</td>
                                        <td>
                                            <span class="badge bg-{{ $item['status'] === 'pending' ? 'warning' : ($item['status'] === 'approved' ? 'success' : 'danger') }}">
                                                {{ ucfirst($item['status']) }}
                                            </span>
                                        </td>
                                        <td>₱{{ number_format($item['total_price'], 2) }}</td>
                                        <td>{{ $item['payment_status'] }}</td>
                                        <td>
                                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#detailsModal{{ $key }}">
                                                Details
                                            </button>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-1">
                                                <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#setPricingModal{{ $key }}">
                                                    Set Price
                                                </button>
                                                <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#paymentStatusModal{{ $key }}">
                                                    Payment
                                                </button>
                                                @if($item['status'] === 'pending')
                                                    <button class="btn btn-success btn-sm" onclick="updateStatus('{{ $key }}', 'approved')">
                                                        Approve
                                                    </button>
                                                    <button class="btn btn-danger btn-sm" onclick="updateStatus('{{ $key }}', 'rejected')">
                                                        Reject
                                                    </button>
                                                @endif
                                            </div>

                                            <!-- Set Pricing Modal -->
                                            <div class="modal fade" id="setPricingModal{{ $key }}" tabindex="-1">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Set Total Price</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <form action="{{ route('admin.quotation.setPricing', ['id' => $key]) }}" method="POST">
                                                            @csrf
                                                            <div class="modal-body">
                                                                <div class="mb-3">
                                                                    <label for="total_price" class="form-label">Total Price (₱)</label>
                                                                    <input type="number" step="0.01" class="form-control" id="total_price" name="total_price" 
                                                                        value="{{ $item['total_price'] }}" required>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                                <button type="submit" class="btn btn-primary">Save Price</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Payment Status Modal -->
                                            <div class="modal fade" id="paymentStatusModal{{ $key }}" tabindex="-1">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Update Payment Status</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <form action="{{ route('admin.quotation.updatePaymentStatus', ['id' => $key]) }}" method="POST">
                                                            @csrf
                                                            <div class="modal-body">
                                                                <div class="mb-3">
                                                                    <label for="payment_status" class="form-label">Payment Status</label>
                                                                    <select class="form-control" id="payment_status" name="payment_status" required>
                                                                        <option value="Not Paid" {{ $item['payment_status'] === 'Not Paid' ? 'selected' : '' }}>Not Paid</option>
                                                                        <option value="Partially Paid" {{ $item['payment_status'] === 'Partially Paid' ? 'selected' : '' }}>Partially Paid</option>
                                                                        <option value="Paid" {{ $item['payment_status'] === 'Paid' ? 'selected' : '' }}>Paid</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                                <button type="submit" class="btn btn-primary">Update Status</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Details Modal -->
                                            <div class="modal fade" id="detailsModal{{ $key }}" tabindex="-1">
                                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Quotation Details</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <table class="table table-bordered">
                                                                <thead class="table-light">
                                                                    <tr>
                                                                        <th colspan="2" class="bg-light">Reference Information</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr>
                                                                        <td width="30%"><strong>Reference Number</strong></td>
                                                                        <td>{{ $item['reference_number'] }}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><strong>Status</strong></td>
                                                                        <td>{{ ucfirst($item['status']) }}</td>
                                                                    </tr>
                                                                </tbody>

                                                                <thead class="table-light">
                                                                    <tr>
                                                                        <th colspan="2" class="bg-light">Personal Information</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr>
                                                                        <td><strong>Full Name</strong></td>
                                                                        <td>{{ $item['firstname'] }} {{ $item['lastname'] }}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><strong>Email</strong></td>
                                                                        <td>{{ $item['email'] }}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><strong>Phone</strong></td>
                                                                        <td>{{ $item['phone'] }}</td>
                                                                    </tr>
                                                                </tbody>

                                                                <thead class="table-light">
                                                                    <tr>
                                                                        <th colspan="2" class="bg-light">Event Details</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr>
                                                                        <td><strong>Event Type</strong></td>
                                                                        <td>{{ $item['is_wedding'] ? 'Wedding' : 'Regular Event' }}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><strong>Event</strong></td>
                                                                        <td>{{ $item['event'] }}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><strong>Date & Time</strong></td>
                                                                        <td>{{ \Carbon\Carbon::parse($item['event_date'])->format('M d, Y') }} at {{ $item['event_time'] }}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><strong>Venue</strong></td>
                                                                        <td>{{ $item['venue'] }}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><strong>Theme</strong></td>
                                                                        <td>{{ $item['theme'] }}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><strong>Guest Count</strong></td>
                                                                        <td>{{ $item['guest_count'] }}</td>
                                                                    </tr>
                                                                </tbody>

                                                                <thead class="table-light">
                                                                    <tr>
                                                                        <th colspan="2" class="bg-light">Menu Details</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach($item['menu_content'] as $menu)
                                                                    <tr>
                                                                        <td><strong>{{ $menu['category'] }}</strong></td>
                                                                        <td>{{ $menu['food'] }}</td>
                                                                    </tr>
                                                                    @endforeach
                                                                </tbody>

                                                                <thead class="table-light">
                                                                    <tr>
                                                                        <th colspan="2" class="bg-light">Address Information</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr>
                                                                        <td><strong>Complete Address</strong></td>
                                                                        <td>
                                                                            {{ $item['street_houseno'] }}, 
                                                                            {{ $item['barangay'] }}, 
                                                                            {{ $item['city'] }}, 
                                                                            {{ $item['province'] }}, 
                                                                            {{ $item['region'] }}
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="9" class="text-center">No Quotation Requests Found</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function updateStatus(inquiryId, status) {
    if (confirm(`Are you sure you want to ${status} this quotation?`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `{{ url('admin/quotation/update-status') }}/${inquiryId}`;
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);
        
        const statusInput = document.createElement('input');
        statusInput.type = 'hidden';
        statusInput.name = 'status';
        statusInput.value = status;
        form.appendChild(statusInput);
        
        document.body.appendChild(form);
        form.submit();
    }
}

// Initialize Bootstrap toasts
document.addEventListener('DOMContentLoaded', function() {
    var toastElList = [].slice.call(document.querySelectorAll('.toast'));
    var toastList = toastElList.map(function(toastEl) {
        return new bootstrap.Toast(toastEl, {
            autohide: true,
            delay: 3000
        });
    });
    toastList.forEach(toast => toast.show());
});

// Format currency inputs
document.querySelectorAll('input[name="total_price"]').forEach(function(input) {
    input.addEventListener('blur', function(e) {
        const value = parseFloat(e.target.value);
        if (!isNaN(value)) {
            e.target.value = value.toFixed(2);
        }
    });
});
</script>

<style>
.custom-toast-size {
    min-width: 300px;
}

.table th {
    background-color: #f8f9fa;
    vertical-align: middle;
}

.table-light {
    background-color: #e9ecef !important;
    font-weight: bold;
}

.badge {
    font-size: 0.875rem;
    padding: 0.5em 0.75em;
}

.bg-warning {
    color: #000;
}

.modal-lg {
    max-width: 800px;
}

.btn-group-sm > .btn, .btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
    border-radius: 0.2rem;
}

.gap-1 {
    gap: 0.25rem !important;
}

.badge.bg-warning {
    background-color: #ffc107 !important;
}

.badge.bg-success {
    background-color: #198754 !important;
}

.badge.bg-danger {
    background-color: #dc3545 !important;
}
</style>
@endsection