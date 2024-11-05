@extends('layouts.guestLayout')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-12">
        <h3 style="text-align: center; font-weight: bolder; font-family: ''; padding-top: 10px; border-bottom: 4px solid darkorange;">LOCATIONS WE SERVE</h3>


        </div>
    </div>

    <div class="row mt-4">
        <!-- Marikina Card -->
    
        <div class="col-md-4">
            <div class="card" style="box-shadow: 0px 0px 20px 5px rgba(255, 127, 0, 0.7); border: 1px solid rgba(255, 127, 0, 0.7);">
                <img src="https://via.placeholder.com/300x200.png?text=Marikina+Package" class="card-img-top" alt="Marikina Package Image">
                <div class="card-body text-center">
                    <h5 class="card-title">Marikina</h5>
                    <p class="card-text">Details about the Marikina package go here.</p>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nesciunt eaque facilis aperiam, necessitatibus molestias mollitia dolorem temporibus assumenda, optio reprehenderit iste soluta modi. Ipsum esse sint cumque provident vel enim.</p>
                    <a href="#" class="btn btn-darkorange">View Package</a>
                </div>
            </div>
        </div>

        <!-- San Mateo Card -->
        <div class="col-md-4">
            <div class="card" style="box-shadow: 0px 0px 20px 5px rgba(255, 127, 0, 0.7); border: 1px solid rgba(255, 127, 0, 0.7);">
                <img src="https://via.placeholder.com/300x200.png?text=San+Mateo+Package" class="card-img-top" alt="San Mateo Package Image">
                <div class="card-body text-center">
                    <h5 class="card-title">San Mateo</h5>
                    <p class="card-text">Details about the San Mateo package go here.</p>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Perferendis ipsa laudantium voluptatum, facere repudiandae ipsam ducimus animi eum nesciunt incidunt labore explicabo voluptates dolore dolorem veniam facilis, eos repellat nisi?</p>
                    <a href="#" class="btn btn-darkorange">View Package</a>
                </div>
            </div>
        </div>

        <!-- Montalban Card -->
        <div class="col-md-4">
            <div class="card" style="box-shadow: 0px 0px 20px 5px rgba(255, 127, 0, 0.7); border: 1px solid rgba(255, 127, 0, 0.7);">
                <img src="https://via.placeholder.com/300x200.png?text=Montalban+Package" class="card-img-top" alt="Montalban Package Image">
                <div class="card-body text-center">
                    <h5 class="card-title">Montalban</h5>
                    <p class="card-text">Details about the Montalban package go here.</p>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Sint enim temporibus asperiores ullam nemo amet possimus soluta, maxime nulla odio id corrupti et dolorem similique beatae! Veritatis earum aperiam accusamus!</p>
                    <a href="#" class="btn btn-darkorange">View Package</a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .card-spacing {
        margin-left: 20px; /* Adjust the margin value as needed */
    }
</style>

@endsection
