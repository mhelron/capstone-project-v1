<!-- Blade Template -->
@extends('layouts.guestlayout')

@section('content')
<div class="container mt-2">
    <h2 class="text-center pb-2">Packages in Marikina</h2>

    <div class="row justify-content-center cards-container">
        @foreach($packages as $id => $package)
            @if(isset($package['is_displayed']) && $package['is_displayed'] === true)
                <div class="col-md-4 mb-4 d-flex align-items-stretch">
                    <!-- Make the entire card clickable by wrapping it in a <a> tag -->
                    <a href="{{ route('package.show', ['id' => $id]) }}" class="card-link">
                        <div class="card text-center h-100 card-border position-relative">
                            <!-- Add the image dynamically from the admin input -->
                            @if(isset($package['image_url']) && !empty($package['image_url']))
                                <img src="{{ asset('storage/' . $package['image_url']) }}" class="card-img-top" alt="{{ $package['package_name'] }} Image">
                            @else
                                <img src="https://via.placeholder.com/300x200.png?text=No+Image" class="card-img-top" alt="Sample Image">
                            @endif

                            <!-- Overlay Text -->
                            <div class="card-overlay">
                                <h5 class="overlay-text">{{ $package['package_name'] }}</h5>
                            </div>
                        </div>
                    </a>
                </div>
            @endif
        @endforeach
    </div>
</div>
@endsection

<style>
    /* Card styling */
    .card-border {
        border: 2px solid #FF5722;
        border-radius: 8px;
        overflow: hidden;
        transition: transform 0.3s ease; /* Smooth transition */
    }

    .card-border:hover {
        border-color: darkorange;
        transform: scale(1.05); /* Zoom effect */
    }

    /* Image styling */
    .card-img-top {
        width: 100%;
        height: 200px;
        object-fit: cover;
        border-bottom: 2px solid #FF5722;
    }

    /* Overlay styling */
    .card-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: rgba(0, 0, 0, 0.5);
        color: white;
        text-align: center;
        padding: 0.5rem;
    }

    .overlay-text {
        font-size: 1.2rem;
        font-weight: bold;
    }

    /* Grid layout adjustments */
    .cards-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1rem;
    }

    /* Responsive adjustments */
    @media (max-width: 576px) {
        .card-img-top {
            height: 150px;
        }

        .overlay-text {
            font-size: 1rem;
        }
    }
</style>