@extends('layouts.guestlayout')

@section('content')
    <div class="container mt-2">
        <h2 class="text-center">Packages in San Mateo</h2>
        <div class="row">
            @foreach($packages as $id => $package)
                @if(isset($package['is_displayed']) && $package['is_displayed'] === true)
                    <div class="col-md-4 mb-4">
                        <!-- Make the entire card clickable by wrapping it in a <a> tag -->
                        <a href="{{ route('package.show', ['id' => $id]) }}" class="card-link">
                            <div class="card text-center h-100">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $package['package_name'] }}</h5>
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
</style>
