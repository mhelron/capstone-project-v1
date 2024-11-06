@extends('layouts.guestlayout')

@section('content')
    <div class="container mt-4">
        <h2 class="text-center">{{ $package['package_name'] }}</h2>

        <!-- Menus Section -->
        <h3 class="text-center mt-4">Menus</h3>
        <div class="row md-4">
            @foreach($package['menus'] as $menu)
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">{{ $menu['menu_name'] }}</h5>
                            <ul class="list-unstyled">
                                @foreach($menu['foods'] as $food)
                                    <li>{{ $food['food'] }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="row">
            <!-- Services Card -->
            <div class="col-md-8 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">Services</h5>
                        <ul class="list-unstyled">
                            @foreach($package['services'] as $service)
                                <li>{{ $service['service'] }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Package Details Card -->
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <p><strong>Price:</strong> {{ $package['price'] }}</p>
                        <p><strong>Number of Persons:</strong> {{ $package['persons'] }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

<style>
    .card-title {
        color: darkorange; /* Optional: main color for consistency */
        font-weight: bold;
    }
    .card-body ul {
        padding-left: 0;
    }
    .card h5.card-title {
        text-align: center; /* Center-align the title */
    }
</style>
