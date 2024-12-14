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
                        <p>The ideal catering package for events with a minimum of <strong>{{ $package['persons'] }}</strong> guests.</p>
                        <p>The packages starts at <strong>{{ number_format($package['price']) }}</strong></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-lg-12 mb-4">
                <h3 class="text-left mb-4">Menus</h3>
                <p style="font-style: italic; color: red;">Please select a menu to proceed in the reservation form *</p>
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-2 g-4">
                    @foreach($package['menus'] as $menu)
                        <div class="col d-flex">
                            <div class="card shadow-sm flex-fill" style="border: 2px solid darkorange;">
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title text-center text-darkorange">{{ $menu['menu_name'] }}</h5>
                                    <ul class="list-unstyled flex-grow-1">
                                        @if(isset($menu['foods']) && is_array($menu['foods']))
                                            @foreach($menu['foods'] as $food)
                                                <li>
                                                    <span style="font-weight: bold;">{{ $food['category'] ?? 'No Category' }}</span> : 
                                                    {{ $food['food'] ?? 'No Food' }}
                                                </li>
                                            @endforeach
                                        @else
                                            <li>No Foods Available</li>
                                        @endif
                                    </ul>
                                    <div class="d-flex gap-2 mt-3 justify-content-center w-100">
                                        <a href="{{ route('guest.reserve', [
                                            'package' => $package['package_name'], 
                                            'menu' => $menu['menu_name'],
                                            'price' => $package['price']
                                        ]) }}" 
                                        class="btn btn-darkorange flex-grow-1 menu-btn"
                                        data-href="{{ route('guest.reserve', [
                                            'package' => $package['package_name'], 
                                            'menu' => $menu['menu_name'],
                                            'price' => $package['price']
                                        ]) }}">
                                            <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                            <span class="btn-text">Select Menu</span>
                                        </a>
                                        <a href="{{ route('menu.customize', [
                                            'packageId' => $package['id'] ?? array_key_first($packages),
                                            'menuName' => $menu['menu_name']
                                        ]) }}" 
                                        class="btn btn-secondary flex-grow-1 menu-btn"
                                        data-href="{{ route('menu.customize', [
                                            'packageId' => $package['id'] ?? array_key_first($packages),
                                            'menuName' => $menu['menu_name']
                                        ]) }}">
                                            <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                            <span class="btn-text">Customize Menu</span>
                                        </a>
                                    </div>
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

    .card {
        border-radius: 10px;
        font-size: 16px;
        transition: transform 0.3s ease, box-shadow 0.3s ease, border-color 0.3s ease;
        display: flex;
        flex-direction: column;
    }

    /* Rest of your existing card styles... */

    /* Add these new button styles */
    .btn {
        padding: 8px 16px;
        transition: all 0.3s ease;
        border-radius: 5px;
        font-weight: bold;
        text-align: center;
        white-space: nowrap;
        min-width: 120px; /* Ensure minimum width for buttons */
    }

    .btn-darkorange:hover {
        background-color: #FF8C00;
        transform: translateY(-2px);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .btn-secondary {
        background-color: #6c757d;
        color: white;
        border: none;
    }

    .btn-secondary:hover {
        background-color: #5a6268;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .btn {
            padding: 8px 12px;
            font-size: 0.9rem;
            min-width: auto; /* Remove minimum width on mobile */
        }

        .d-flex.gap-2 {
            flex-direction: column; /* Stack buttons on mobile */
            gap: 0.5rem !important;
        }

        .btn {
            width: 100%; /* Full width buttons on mobile */
        }
    }

    .btn.loading {
        position: relative;
        cursor: not-allowed;
        opacity: 0.8;
    }

    .spinner-border-sm {
        width: 1rem;
        height: 1rem;
        margin-right: 0.5rem;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    .spinner-border {
        animation: spin 0.8s linear infinite;
    }

    /* Add hover effects */
    .btn-darkorange:not(.loading):hover,
    .btn-secondary:not(.loading):hover {
        transform: translateY(-2px);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    /* Transition for hover effects */
    .btn {
        transition: all 0.3s ease;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle all menu buttons
    document.querySelectorAll('.menu-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault(); // Prevent immediate navigation
            
            const button = this;
            const loadingText = button.classList.contains('btn-darkorange') ? 'Selecting...' : 'Customizing...';
            const href = button.getAttribute('data-href');
            
            // Prevent multiple clicks
            if (button.classList.contains('loading')) {
                return;
            }

            // Add loading state
            button.classList.add('loading');
            button.querySelector('.spinner-border').classList.remove('d-none');
            button.querySelector('.btn-text').textContent = loadingText;

            // Disable all menu buttons in the same container
            const buttonContainer = button.closest('.d-flex');
            buttonContainer.querySelectorAll('.menu-btn').forEach(btn => {
                btn.style.pointerEvents = 'none';
                if (btn !== button) {
                    btn.style.opacity = '0.5';
                }
            });

            // Navigate after a short delay to show loading state
            setTimeout(() => {
                window.location.href = href;
            }, 500); // 500ms delay to show loading state
        });
    });
});

// Reset buttons if user navigates back
window.addEventListener('pageshow', function(event) {
    if (event.persisted) {
        document.querySelectorAll('.menu-btn').forEach(button => {
            button.classList.remove('loading');
            button.querySelector('.spinner-border').classList.add('d-none');
            button.querySelector('.btn-text').textContent = 
                button.classList.contains('btn-darkorange') ? 'Select Menu' : 'Customize Menu';
            button.style.pointerEvents = 'auto';
            button.style.opacity = '1';
        });
    }
});
</script>
