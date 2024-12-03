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
                    <a href="{{ route('admin.logs.download') }}" class="btn btn-success me-2">
                        <i class="fas fa-download"></i> Export
                    </a>
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#removeAllModal">
                        <i class="fas fa-trash"></i> Remove All
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

<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
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
                                    @forelse ($logs as $index => $log)
                                        <tr>
                                            <td>{{ count($logs) - $index }}</td>
                                            <td>{{ Carbon\Carbon::parse($log['datetime'])->format('M d, Y') }}</td>
                                            <td>{{ Carbon\Carbon::parse($log['datetime'])->format('h:i:s A') }}</td>
                                            <td>{{ $log['user'] ?? 'N/A' }}</td>
                                            <td>{{ $log['message'] }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center">No activity logs found</td>
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

<style>
    .table td {
        vertical-align: middle;
    }
    
    .badge {
        font-size: 0.875em;
        padding: 0.5em 0.75em;
    }
</style>

@endsection