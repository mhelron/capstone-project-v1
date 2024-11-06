@extends('layouts.adminlayout')

@section('content')

 <!-- Display the status message -->
 @if(session('status'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('status') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div>
    <h1>Dashboard Page</h1>
    <p>This is the dashboard page</p>
</div>

@endsection