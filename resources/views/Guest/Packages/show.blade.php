@extends('layouts.guestlayout')

@section('content')
    <div class="container mt-5">
        <h2 class="text-center text-dark mb-2">{{ $package['package_name'] }}</h2>

        <div class="row mb-4">
            <!-- Back Button (Floating Left) -->
            <div class="col">
                <a href="{{ route('guest.packages.marikina')}}" class="btn btn-darkorange float-start">Back</a>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-lg-12">
            <h3 class="text-left mb-4">Package Details</h3>
                <div class="card shadow-sm" style="border: 2px solid darkorange;">
                    <div class="card-body">
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Labore id, fugit laudantium rerum praesentium officiis impedit odit ex maiores minima, dolores deserunt ad sint, excepturi nemo ducimus! Sed, similique illo!</p>
                        <p>The ideal catering package for events with a minimum of <strong>{{ $package['persons'] }}</strong> guests.</p>
                        <p>The packages starts at <strong>{{ number_format($package['price']) }}</strong></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-lg-12 mb-4">
                <h3 class="text-left mb-4">Menus</h3><p style="font-style: italic; color: red;">Please select a menu to proceed in the reservation form *</p>
                    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-2 g-4">
                        @foreach($package['menus'] as $menu)
                            <div class="col d-flex">
                                <div class="card shadow-sm flex-fill" style="border: 2px solid darkorange;">
                                    <div class="card-body d-flex flex-column">
                                        <h5 class="card-title text-center text-darkorange">{{ $menu['menu_name'] }}</h5>
                                        <ul class="list-unstyled flex-grow-1">
                                            @foreach($menu['foods'] as $food)
                                                <li>
                                                    <span style="font-weight: bold;">{{ $food['category'] }}</span> : 
                                                    {{ $food['food'] }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            <!-- Column 2: Package Details and Services (Right Side) -->
            <div class="col-lg-12 mx-auto mb-4"> <!-- Add mx-auto to center the column -->
                <h3 class="text-left mb-4">Services</h3>
                <div class="card shadow-sm" style="border: 2px solid #FF5D29;">
                    <div class="card-body">
                        <div class="row">
                            @php
                                $services = collect($package['services']);
                                $half = ceil($services->count() / 2);
                            @endphp
                            <div class="col-12 col-md-6"> <!-- Use col-12 for mobile and col-md-6 for larger screens -->
                                <ul class="list-unstyled services-list">
                                    @foreach($services->take($half) as $service)
                                        <li class="astig">{{ $service['service'] }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            <div class="col-12 col-md-6"> <!-- Same for the second column -->
                                <ul class="list-unstyled services-list">
                                    @foreach($services->skip($half) as $service)
                                        <li class="astig">{{ $service['service'] }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <!-- Column 1: Menus (Left Side) -->
        </div>
    </div>
@endsection

<style>
    .card {
        border-radius: 10px;
        font-size: 16px;
        transition: transform 0.3s ease, box-shadow 0.3s ease, border-color 0.3s ease;
        display: flex;
        flex-direction: column;
    }

    .card-body {
        padding: 1.5rem;
        display: flex;
        background-color: #f5f5dc; /* Light beige */
        flex-direction: column;
    }

    .card-title {
        color: darkorange;
        font-weight: bold;
        text-transform: uppercase;
        font-size: 1.5rem; /* Slightly larger font for titles */
    }

    .card-body ul {
        padding-left: 0;
    }

    .card-body ul li {
        font-size: 16px;
    }

    .card:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); /* Adds subtle shadow */
        transform: scale(1.05); /* Slight zoom effect */
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .container {
        max-width: 1200px;
    }

    .text-darkorange {
        color: darkorange;
        transition: color 0.3s ease-in-out;
    }

    .btn-darkorange {
        background-color: darkorange;
        color: white;
        border: none;
        padding: 0.5rem 1rem;
        font-weight: bold;
        border-radius: 5px;
        text-align: center;
        text-decoration: none;
        transition: background-color 0.3s ease;
    }

    @media (max-width: 768px) {
        .card-body p {
            font-size: 1rem;
        }

        .btn-darkorange {
            width: 100%; /* Full-width button on mobile */
        }

        .card-title {
            font-size: 1.2rem; /* Adjust title font size */
        }
    }

    h3 {
        color: darkorange;
        font-weight: bold;
        font-size: 1.5rem;
        text-align: left;
        margin-bottom: 20px;
    }
    p{
        font-size: 16px;
    }
</style>
