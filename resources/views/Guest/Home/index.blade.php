@extends('layouts.guestLayout')

@section('content')

<div class="container">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h1 style="color: darkorange;">Kyla and Kyle</h1>
            <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Voluptatem qui accusantium explicabo saepe, dignissimos incidunt ullam harum debitis assumenda eveniet sit doloremque nam quidem ex excepturi hic magnam iste quis.</p>
            <div class="d-flex mt-4">
                <a href="#" class="btn btn-darkorange me-3">Reserve</a>
                <a href="#" class="btn btn-darkorange">View Status</a>
            </div>
        </div>
        <div class="col-md-6">
            <img src="{{ asset('images/your-image.jpg') }}" alt="Catering Image" class="img-fluid" />
        </div>
    </div>
</div>

@endsection
