@extends('layouts.adminlayout')

@section('content')

<div class="pt-4">
    @if(session('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <h1>Dashboard Page</h1>
    <p>This is the dashboard page</p>
</div>

@endsection