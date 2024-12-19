@extends('layouts.adminlayout')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0" style="padding-top: 35px;">Food Taste Inquiries</h1>
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

                <!-- Food Taste Table -->
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Full Name</th>
                                        <th scope="col">Option</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Assigned Time</th>
                                        <th scope="col">Assigned Date</th>
                                        <th scope="col">Details</th>
                                        <th scope="col">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $i = 1; @endphp
                                    @forelse ($foodtaste as $key => $item)
                                    <tr>
                                        <td>{{ $i++ }}</td>
                                        <td>{{ $item['lastname'] }}, {{ $item['firstname'] }}</td>
                                        <td>{{ ucfirst($item['delivery_option']) }}</td>
                                        <td>
                                            <span class="badge bg-{{ $item['status'] === 'pending' ? 'warning' : ($item['status'] === 'approved' ? 'success' : 'danger') }}">
                                                {{ ucfirst($item['status']) }}
                                            </span>
                                        </td>
                                        <td>{{ $item['set_time'] }}</td>
                                        <td>{{ \Carbon\Carbon::parse($item['set_date'])->format('M d, Y') }}</td>
                                        <td>
                                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#detailsModal{{ $key }}">
                                                Details
                                            </button>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-1">
                                                <button class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#setTimeModal{{ $key }}">
                                                    Set Time
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

                                            <!-- Set Time Modal -->
                                            <div class="modal fade" id="setTimeModal{{ $key }}" tabindex="-1">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Set Schedule</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <form action="{{ route('admin.foodtaste.setSchedule', ['id' => $key]) }}" method="POST">
                                                            @csrf
                                                            <div class="modal-body">
                                                                <div class="alert alert-info">
                                                                    <strong>Client's Preferred Schedule:</strong><br>
                                                                    Date: {{ \Carbon\Carbon::parse($item['preferred_date'])->format('M d, Y') }}<br>
                                                                    Time: {{ $item['preferred_time'] }}
                                                                    <div class="mt-2">
                                                                        <button type="button" class="btn btn-sm btn-secondary" onclick="usePreferredSchedule('{{ $item['preferred_date'] }}', '{{ $item['preferred_time'] }}', '{{ $key }}')">
                                                                            Use Preferred Schedule
                                                                        </button>
                                                                    </div>
                                                                </div>

                                                                <div class="mb-3">
                                                                    <label for="set_date{{ $key }}" class="form-label">Date</label>
                                                                    <input type="date" class="form-control" id="set_date{{ $key }}" name="set_date" 
                                                                        value="{{ $item['set_date'] ?? '' }}" required>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="set_time{{ $key }}" class="form-label">Time</label>
                                                                    <input type="time" class="form-control" id="set_time{{ $key }}" name="set_time" 
                                                                        value="{{ $item['set_time'] ?? '' }}" required>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                                <button type="submit" class="btn btn-primary">Save Schedule</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Delete Modal -->
                                    <div class="modal fade" id="deleteModal{{ $key }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Confirm Delete</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    Are you sure you want to delete this food taste inquiry?
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="button" class="btn btn-danger" onclick="deleteInquiry('{{ $key }}')">Delete</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No Food Taste Inquiries Found</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>

                                                                <!-- Details Modal -->
                                                                <div class="modal fade" id="detailsModal{{ $key }}" tabindex="-1">
                                        <div class="modal-dialog modal-lg modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Inquiry Details</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <table class="table table-bordered">
                                                        <thead class="table-light">
                                                            <tr>
                                                                <th colspan="2" class="bg-light">Personal Information</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td width="30%"><strong>Reference Number</strong></td>
                                                                <td>{{ $item['reference_number'] }}</td>
                                                            </tr>
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
                                                            @if($item['delivery_option'] === 'delivery')
                                                                <tr>
                                                                    <td><strong>Complete Address</strong></td>
                                                                    <td>{{ strtoupper($item['street_houseno']) }}, {{ $item['barangay'] }}, {{ $item['city'] }}, {{ $item['province'] }}, {{ $item['region'] }}</td>
                                                                </tr>
                                                             @endif
                                                        </tbody>

                                                        <thead class="table-light">
                                                            <tr>
                                                                <th colspan="2" class="bg-light">Schedule Details</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td><strong>Preferred Date</strong></td>
                                                                <td>{{ \Carbon\Carbon::parse($item['preferred_date'])->format('M d, Y') }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>Preferred Time</strong></td>
                                                                <td>{{ $item['preferred_time'] }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>Option</strong></td>
                                                                <td>{{ ucfirst($item['delivery_option']) }}</td>
                                                            </tr>
                                                            @if(isset($item['set_date']) && isset($item['set_time']))
                                                            <tr>
                                                                <td><strong>Assigned Date</strong></td>
                                                                <td>{{ \Carbon\Carbon::parse($item['set_date'])->format('M d, Y') }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>Assigned Time</strong></td>
                                                                <td>{{ $item['set_time'] }}</td>
                                                            </tr>
                                                            @endif
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function updateStatus(inquiryId, status) {
    if (confirm(`Are you sure you want to ${status} this inquiry?`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `{{ url('admin/foodtaste/update-status') }}/${inquiryId}`;
        
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

function deleteInquiry(inquiryId) {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `{{ url('admin/foodtaste/delete') }}/${inquiryId}`;
    
    const csrfToken = document.createElement('input');
    csrfToken.type = 'hidden';
    csrfToken.name = '_token';
    csrfToken.value = '{{ csrf_token() }}';
    form.appendChild(csrfToken);
    
    const methodInput = document.createElement('input');
    methodInput.type = 'hidden';
    methodInput.name = '_method';
    methodInput.value = 'DELETE';
    form.appendChild(methodInput);
    
    document.body.appendChild(form);
    form.submit();
}

function usePreferredSchedule(preferredDate, preferredTime, key) {
    // Convert preferred date to YYYY-MM-DD format for date input
    const formattedDate = new Date(preferredDate).toISOString().split('T')[0];
    
    // Format the time to HH:mm format
    // First, let's check if the time is in 12-hour format (e.g., "2:30 PM")
    const timeMatch = preferredTime.match(/(\d{1,2}):(\d{2})\s*(AM|PM)?/i);
    
    if (timeMatch) {
        let hours = parseInt(timeMatch[1]);
        const minutes = timeMatch[2];
        const period = timeMatch[3] ? timeMatch[3].toUpperCase() : null;

        // Convert to 24-hour format if period (AM/PM) is present
        if (period) {
            if (period === 'PM' && hours !== 12) {
                hours += 12;
            } else if (period === 'AM' && hours === 12) {
                hours = 0;
            }
        }

        // Format hours and minutes to ensure 2 digits
        const formattedTime = `${hours.toString().padStart(2, '0')}:${minutes}`;
        
        // Set the input values
        document.getElementById(`set_date${key}`).value = formattedDate;
        document.getElementById(`set_time${key}`).value = formattedTime;
    } else {
        // If time is already in HH:mm format, use it directly
        document.getElementById(`set_date${key}`).value = formattedDate;
        document.getElementById(`set_time${key}`).value = preferredTime;
    }
    
    // For debugging
    console.log('Original Time:', preferredTime);
    console.log('Formatted Time:', document.getElementById(`set_time${key}`).value);
}
</script>
@endsection