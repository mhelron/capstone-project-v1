@extends('layouts.adminLayout')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Users</h1>
            </div>
        </div>
    </div>
</div>
<!-- /.content-header -->

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

                <div class="d-flex justify-content-end">
                    <a href="{{ route('admin.users.add') }}" class="btn btn-primary mb-2">Add User</a>
                </div>

                <!-- Users Table -->
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">First name</th>
                                        <th scope="col">Last name</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Role</th>
                                        <th scope="col">Options</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @php $i = 1; @endphp
                                    @forelse ($users as $key => $item)
                                    <tr>
                                        <td>{{ $i++ }}</td>
                                        <td>{{ $item['fname'] }}</td>
                                        <td>{{ $item['lname'] }}</td>
                                        <td>{{ $item['email'] }}</td>
                                        <td>{{ $item['user_role'] }}</td>
                                        <td>
                                            <div class="d-flex">
                                                <a href="{{ url('admin/users/edit-user/' . $key) }}" class="btn btn-sm btn-success me-2">Edit</a>
                                                <button type="button" class="btn btn-sm btn-secondary" data-bs-toggle="modal" data-bs-target="#archiveModal" data-id="{{ $key }}" data-name="{{ $item['fname'] }} {{ $item['lname'] }}">Archive</button>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7">No Records Found</td>
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
<!-- /.Main content -->

<!-- Archive Confirmation Modal -->
<div class="modal fade" id="archiveModal" tabindex="-1" aria-labelledby="archiveModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="archiveModalLabel">Confirm Archive</h5>
            </div>
            <div class="modal-body">
                Are you sure you want to archive <strong id="userName"></strong>?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirm-archive-btn">Archive</button>
            </div>
        </div>
    </div>
</div>

<script>
    // Event listener for the Archive button
    const archiveButtons = document.querySelectorAll('[data-bs-target="#archiveModal"]');
    const userNameField = document.getElementById('userName');
    const confirmArchiveButton = document.getElementById('confirm-archive-btn');

    archiveButtons.forEach(button => {
        button.addEventListener('click', function() {
            const userName = this.getAttribute('data-name');
            const userId = this.getAttribute('data-id');
            userNameField.textContent = userName;

            // Set up the confirm button with the user ID
            confirmArchiveButton.onclick = function() {
                window.location.href = '{{ url("admin/users/delete-user/") }}/' + userId;
            };
        });
    });
</script>

@endsection
