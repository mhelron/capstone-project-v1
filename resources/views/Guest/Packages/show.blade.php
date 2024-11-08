@extends('layouts.guestlayout')

@section('content')
    <div class="container mt-5">
        <h2 class="text-center text-dark mb-5">{{ $package['package_name'] }}</h2>

        <div class="row">
            <!-- Column 1: Package Details and Services -->
            <div class="col-lg-4">
                <!-- Package Details (Row 1) -->
                <div class="row mb-4">
                    <div class="col-lg-12">
                        <div class="card shadow-sm border-0 hover-zoom">
                            <div class="card-body">
                                <p><strong>Price:</strong> <span class="text-darkorange">{{ $package['price'] }}</span></p>
                                <p><strong>Number of Persons:</strong> {{ $package['persons'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Services (Row 2) -->
                <div class="row mb-4">
                    <div class="col-lg-12">
                        <div class="card shadow-sm border-0 hover-zoom">
                            <div class="card-body">
                                <h5 class="card-title text-darkorange">Services</h5>
                                <ul class="list-unstyled">
                                    @foreach($package['services'] as $service)
                                        <li class="text-muted">{{ $service['service'] }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Column 2: Menus -->
            <div class="col-lg-8">
                <h3 class="text-center text-dark mb-4">Menus</h3>
                <div class="row">
                    @foreach($package['menus'] as $menu)
                        <div class="col-lg-4 mb-4">
                            <div class="card shadow-sm border-0 hover-zoom">
                                <div class="card-body">
                                    <h5 class="card-title text-center text-darkorange">{{ $menu['menu_name'] }}</h5>
                                    <ul class="list-unstyled">
                                        @foreach($menu['foods'] as $food)
                                            <li class="text-muted">{{ $food['food'] }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection

<style>
    .card-title {
        color: darkorange;
        font-weight: bold;
        text-transform: uppercase;
    }
    .card-body ul {
        padding-left: 0;
    }
    .card-body ul li {
        font-size: 0.9rem;
    }
    .card h5.card-title {
        font-size: 1.25rem;
    }
    .card {
        border-radius: 10px;
        overflow: hidden;
        transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
    }
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }
    .card-body {
        padding: 2rem;
    }
    .container {
        max-width: 1200px;
    }
    .text-darkorange {
        color: darkorange;
    }
    .hover-zoom:hover {
        transform: scale(1.05);
    }
</style>
