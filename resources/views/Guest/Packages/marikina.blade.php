@extends('layouts.guestlayout')

@section('content')
<div class="container mt-2">
    <h2 class="text-left pb-2">Packages in Marikina</h2>

    <div class="row">
        @foreach($packages as $id => $package)
            @if(isset($package['is_displayed']) && $package['is_displayed'] === true)
                <div class="col-md-4 mb-4"> <!-- Each card takes 4 out of 12 columns (3 per row) -->
                    <!-- Make the entire card clickable by wrapping it in a <a> tag -->
                    <a href="{{ route('package.show', ['id' => $id]) }}" class="card-link">
                        <div class="card text-center h-100 card-border" style="border: 3px solid rgba(255, 87, 34, 0.7);">
                            <div class="card-body" style="background-color: #FFF9C4;">

                                <!-- Add the image dynamically from the admin input -->
                                @if(isset($package['image_url']) && !empty($package['image_url']))
                                    <img src="{{ asset('storage/' . $package['image_url']) }}" class="card-img-top" alt="{{ $package['package_name'] }} Image">
                                @else
                                    <img src="https://via.placeholder.com/300x200.png?text=No+Image" class="card-img-top" alt="Sample Image">
                                @endif

                                <!-- Package Title -->
                                <h5 class="card-title mt-3">{{ $package['package_name'] }}</h5>
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
            .card-link {
                text-decoration: none; /* Removes underline */
                color: inherit; /* Inherit the text color from the card */
            }

            .card-link:hover {
                text-decoration: none; /* Ensures no underline on hover */
            }

            /* Add a dark orange border to the card */
            .card-border {
                border: 2px solid #FF5722; /* Dark orange color */
                border-radius: 8px; /* Optional: Add rounded corners */
                transition: all 0.1s ease; /* Smooth transition for border and box-shadow */
            }

            /* Hover effect: Add inset shadow and change border color */
            .card-border:hover {
                box-shadow: inset 0 0 10px rgba(255, 87, 34, 0.7); /* Dark orange inset shadow */
                border-color: #FF3D00; /* Darker orange color on hover */
            }

            /* Style the image inside the card */
            .card-img-top {
                width: 100%; /* Ensure the image takes up the full width of the card */
                height: 100; /* Set a fixed height for the image */
                object-fit: cover; /* Make sure the image covers the area of the card without stretching */
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.7); /* Black outer shadow effect */
                border-top-left-radius: 8px; /* Add rounded top-left corner */
                border-top-right-radius: 8px; /* Add rounded top-right corner */
            }

            /* Ensure that the cards in the row are evenly spaced */
            .row {
                display: flex;
                flex-wrap: wrap; /* Ensure cards wrap when necessary */
            }
        </style>

