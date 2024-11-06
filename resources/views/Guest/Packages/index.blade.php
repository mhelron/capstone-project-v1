@extends('layouts.guestLayout')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <!-- LOCATIONS WE SERVE header with orange lines on both sides -->
            <p style="text-align: center; font-weight: bolder; font-family: 'Times New Roman'; font-size: 25px; 
                      padding-top: 20px; position: relative; color: black;">
                <span style="border-top: 4px solid darkorange; width: 475px; position: absolute; left: 0; top: 50%;"></span>
                LOCATIONS WE SERVE
                <span style="border-top: 4px solid darkorange; width: 475px; position: absolute; right: 0; top: 50%;"></span>
            </p>
        </div>
    </div>

    <div class="row mt-4">
        <!-- Montalban Card -->
        <div class="col-md-4">
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
<div class="col-md-4">
    <div class="card" style="border: 3px solid rgba(255, 87, 34, 0.7); box-shadow: inset 0 0 10px rgba(255, 87, 34, 0.7);">
        <img src="https://via.placeholder.com/300x200.png?text=Marikina+Package" class="card-img-top" alt="Marikina Package Image" >
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

@endsection
