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
    <!-- Add a row to contain the columns -->
    <div class="row">
        <!-- Column 1 -->
        <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-secondary">
                <div class="inner">
                    <h3>150</h3>
                    <p>Pencil</p>
                </div>
                <div class="icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <a href="{{ route('admin.reservation', ['tab' => 'penbook']) }}" class="small-box-footer">
                    More info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <!-- Column 2 -->
        <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>150</h3>
                    <p>Pending</p>
                </div>
                <div class="icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <a href="{{ route('admin.reservation', ['tab' => 'pending']) }}" class="small-box-footer">
                    More info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <!-- Column 3 -->
        <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>150</h3>
                    <p>Confirmed</p>
                </div>
                <div class="icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <a href="{{ route('admin.reservation', ['tab' => 'confirmed']) }}" class="small-box-footer">
                    More info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <!-- Column 4 -->
        <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>150</h3>
                    <p>Cancelled</p>
                </div>
                <div class="icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <a href="{{ route('admin.reservation', ['tab' => 'cancelled']) }}" class="small-box-footer">
                    More info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>
    <div class="row">
        <!-- Column 1 -->
        <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-primary">
                <div class="inner">
                    <h3>150</h3>
                    <p>Finished</p>
                </div>
                <div class="icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <a href="{{ route('admin.reservation', parameters: ['tab' => 'finished']) }}" class="small-box-footer">
                    More info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>

</div>

@endsection
