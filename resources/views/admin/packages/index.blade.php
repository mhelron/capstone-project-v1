@extends('layouts.adminlayout')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0" style="padding-top: 35px;">Packages</h1>
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
                    <a href="{{ route('admin.packages.add') }}" class="btn btn-primary mb-2">Add Packages</a>
                </div>

                <!-- Package Table -->
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">

                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Package Name</th>
                                        <th scope="col">Persons</th>
                                        <th scope="col">Price</th>
                                        <th scope="col">Food</th>
                                        <th scope="col">Services</th>
                                        <th scope="col">Options</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @php $i = 1; @endphp
                                    @forelse ($packages as $key => $item)
                                    <tr>
                                        <td>{{ $i++ }}</td>
                                        <td>{{ $item['package_name'] }}</td>
                                        <td>{{ $item['persons'] }}</td>
                                        <td>₱{{ number_format($item['price'], 2) }}</td>
                                        <td>
                                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#menuModal{{ $key }}">View Menu</button>

                                            <!-- Menu Modal -->
                                            <div class="modal fade" id="menuModal{{ $key }}" tabindex="-1" aria-labelledby="menuModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="menuModalLabel">Menus for {{ $item['package_name'] }}</h5>
                                                        </div>
                                                        <div class="modal-body">
                                                            @if (isset($item['menus']) && count($item['menus']) > 0)
                                                                <table class="table table-bordered">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Menu Name</th>
                                                                            <th>Food Items</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @foreach ($item['menus'] as $menu)
                                                                        <tr>
                                                                            <td>{{ $menu['menu_name'] }}</td>
                                                                            <td>
                                                                                @if (isset($menu) && isset($menu['foods']) && is_array($menu['foods']) && count($menu['foods']) > 0)
                                                                                    <ul class="list-unstyled">
                                                                                        @foreach ($menu['foods'] as $food)
                                                                                            <li>{{ $food['food'] ?? 'Unknown Food' }}</li>
                                                                                        @endforeach
                                                                                    </ul>
                                                                                @else
                                                                                    No Food Items Available
                                                                                @endif
                                                                            </td>
                                                                        </tr>
                                                                        @endforeach
                                                                    </tbody>
                                                                </table>
                                                            @else
                                                                <p>No Menus Available</p>
                                                            @endif
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#serviceModal{{ $key }}">View Services</button>

                                            <!-- Services Modal -->
                                            <div class="modal fade" id="serviceModal{{ $key }}" tabindex="-1" aria-labelledby="serviceModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="serviceModalLabel">Services for {{ $item['package_name'] }}</h5>
                                                        </div>
                                                        <div class="modal-body">
                                                            @if (isset($item['services']) && count($item['services']) > 0)
                                                                <ul class="list-group">
                                                                    @foreach ($item['services'] as $service)
                                                                        <li class="list-group-item">{{ $service['service'] }}</li>
                                                                    @endforeach
                                                                </ul>
                                                            @else
                                                                <p>No Services Available</p>
                                                            @endif
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex">
                                                <a href="{{ url('admin/packages/edit-package/' . $key) }}" class="btn btn-sm btn-success me-2">Edit</a>
                                                <button type="button" class="btn btn-sm btn-danger me-2" data-bs-toggle="modal" data-bs-target="#archiveModal" data-id="{{ $key }}" data-name="{{ $item['package_name'] }}">Archive</button>
                                                @if (isset($item['is_displayed']) && $item['is_displayed'])
                                                    <form action="{{ route('admin.packages.toggleDisplay', ['packageId' => $key]) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="btn btn-secondary btn-sm">Hide</button>
                                                    </form>
                                                @else
                                                    <form action="{{ route('admin.packages.toggleDisplay', ['packageId' => $key]) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="btn btn-primary btn-sm">Show</button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7">No Packages Found</td>
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

<!-- Archive Confirmation Modal -->
<div class="modal fade" id="archiveModal" tabindex="-1" aria-labelledby="archiveModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="archiveModalLabel">Confirm Archive</h5>
            </div>
            <div class="modal-body">
                Are you sure you want to archive <strong id="packageName"></strong>?
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
    const packageNameField = document.getElementById('packageName');
    const confirmArchiveButton = document.getElementById('confirm-archive-btn');

    archiveButtons.forEach(button => {
        button.addEventListener('click', function() {
            const packageName = this.getAttribute('data-name');
            const packageId = this.getAttribute('data-id');
            packageNameField.textContent = packageName;

            // Set up the confirm button with the package ID
            confirmArchiveButton.onclick = function() {
                window.location.href = '{{ url("admin/packages/archive-package/") }}/' + packageId;
            };
        });
    });
</script>

@endsection
