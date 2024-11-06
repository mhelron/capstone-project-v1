@extends('layouts.guestlayout')

@section('content')
<div class="container mt-2">
    <h2 class="text-left pb-2">Packages in Marikina</h2>

    <div class="row">
        @foreach($packages as $id => $package)
            @if(isset($package['is_displayed']) && $package['is_displayed'] === true)
                <div class="col-md-4 mb-4">

                    <!-- Make the entire card clickable by wrapping it in a <a> tag -->
                    <a href="{{ route('package.show', ['id' => $id]) }}" class="card-link">
                        <div class="card text-center h-100 card-border" style="border: 3px solid rgba(255, 87, 34, 0.7);">
                            <div class="card-body">
                                
                                
                                
                                <!-- Package Title -->
                                <h5 class="card-title">{{ $package['package_name'] }}</h5>

                                <!-- Add the image dynamically from the admin input -->
                                @if(isset($package['image_url']) && !empty($package['image_url']))
                                    <img src="{{ asset($package['image_url']) }}" class="card-img-top" alt="{{ $package['package_name'] }} Image">
                                @else
                                    <img src="https://via.placeholder.com/300x200.png?text=No+Image" class="card-img-top" alt="Sample Image">
                                @endif

                                
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
        }

        /* Optional: Change border color on hover */
        .card-border:hover {
            border-color: #FF3D00; /* Darker orange shade on hover */
        }
    </style>
