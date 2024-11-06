@extends('layouts.guestLayout')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-12">

            <!-- LOCATIONS WE SERVE header with orange lines on both sides -->
            <p class="locations-header">
                <span class="border-line left-border"></span>
                LOCATIONS WE SERVE
                <span class="border-line right-border"></span>
            </p>

        </div>
    </div>

    <div class="row mt-4">
        <!-- Montalban Card -->
        <div class="col-md-4 mb-4">
            <div class="card" style="border: 3px solid rgba(255, 87, 34, 0.7);">
                <img src="https://via.placeholder.com/300x200.png?text=Montalban+Package" class="card-img-top" alt="Montalban Package Image">
                <div class="card-body text-center">
                    <h5 class="card-title" style="font-family: 'Arial'; font-weight: bolder">MONTALBAN</h5>
                    <p class="card-text">Details about the Montalban package go here.</p>
                    <a href="#" class="btn btn-darkorange">View Package</a>
                </div>
            </div>
        </div>

        <!-- Marikina Card -->
        <div class="col-md-4 mb-4">
            <div class="card" style="border: 3px solid rgba(255, 87, 34, 0.7); box-shadow: inset 0 0 10px rgba(255, 87, 34, 0.7);">
                <img src="https://via.placeholder.com/300x200.png?text=Marikina+Package" class="card-img-top" alt="Marikina Package Image">
                <div class="card-body text-center">
                    <h5 class="card-title" style="font-family: 'Arial'; font-weight: bolder">MARIKINA</h5>
                    <p class="card-text">Details about the Marikina package go here.</p>
                    <a href="#" class="btn btn-darkorange">View Package</a>
                </div>
            </div>
        </div>

        <!-- San Mateo Card -->
        <div class="col-md-4">
            <div class="card" style="border: 3px solid rgba(255, 87, 34, 0.7);">
                <img src="https://via.placeholder.com/300x200.png?text=San+Mateo+Package" class="card-img-top" alt="San Mateo Package Image">
                <div class="card-body text-center">
                    <h5 class="card-title" style="font-family: 'Arial'; font-weight: bolder">SAN MATEO</h5>
                    <p class="card-text">Details about the San Mateo package go here.</p>
                    <a href="#" class="btn btn-darkorange">View Package</a>
                </div>
            </div>
        </div>

        <!-- Footer Text -->
        <h5 style="text-align: center; font-family: 'Georgia'; padding-top: 50px; padding-bottom: 50px;">
            Delicious food has the power to transform any event into something truly memorable, and at Kyla and Kyle Catering, 
            we’re passionate about making your celebration unforgettable. Serving select areas in Montalban and surrounding locations, 
            we offer personalized menus crafted to suit your unique preferences. Whether you're planning a cozy family event or an 
            elegant affair, our team is dedicated to delivering top-notch service and mouthwatering meals that will leave your 
            guests talking long after the last bite. Let us bring the flavors of your dreams to life right on the specified areas.
        </h5>
    </div>
</div>

<style>
    /* Default styling for desktop view */
    .locations-header {
        text-align: center;
        font-weight: bolder;
        font-family: 'Times New Roman';
        font-size: 25px;
        padding-top: 20px;
        position: relative;
        color: black;
    }

    .border-line {
        border-top: 4px solid darkorange;
        position: absolute;
        top: 65%;
    }

    .left-border {
        width: 475px;
        left: 0;
    }

    .right-border {
        width: 475px;
        right: 0;
    }

    /* Styling for mobile view */
    @media (max-width: 767px) {
        .locations-header {
            font-size: 20px; /* Adjust font size for mobile */
            padding-top: 10px;
        }

        .border-line {
            width: 100px; /* Reduce border width for mobile */
        }

        .left-border {
            left: 10%;
        }

        .right-border {
            right: 10%;
        }
    }

    /* Styling for very small screens */
    @media (max-width: 480px) {
        .locations-header {
            font-size: 18px; /* Further reduce font size */
        }

        .border-line {
            display: none; /* Hide borders on very small screens */
        }
    }
</style>

@endsection
