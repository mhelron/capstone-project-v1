@extends('layouts.guestlayout')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>About Us</h1>
            <p>At Kyla and Kyle Catering Services, we’re here to make your event truly special. From weddings and corporate gatherings to family celebrations, our goal is to handle every detail so you can enjoy the day. Known for our friendly service and high-quality food, we’re dedicated to bringing your vision to life and creating a memorable experience for you and your guests. Let us take care of everything so you can focus on making memories.</p>
        </div>
    </div>
    
    <div class="row mt-4">
        <div class="col-md-6">
            <h2>Our History</h2>
            <p> Kyla and Kyle Catering Services began over a decade ago as a small family eatery in the heart of Montalban, Rizal. Founded on the values of quality humility, and a deep connection to the community, we’ve grown into one of Rodriguez’s leading catering providers, known for bringing each client’s vision to life with exceptional service and a personal touch. What started as a small team has now blossomed into a full-service catering company committed to making every celebration memorable, beautiful, and worry-free</p>
        </div>
        <div class="col-md-6">
            <h2>Our Mission</h2>
            <p> To make every event unforgettable. At Kyla and Kyle Catering Services, we are dedicated to bringing your vision to life. We handle every detail, from designing and setting up the venue to catering and serving our carefully crafted menu selected by you. Our goal is to create a seamless, memorable experience perfectly tailored to your needs</p>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-6">
            <h2>Meet the Team</h2>
            <p>The people behind your celebrations. Our team of skilled chefs, friendly waiters, dedicated support staff, and reliable drivers work together to provide professional, attentive service at every event. We’re proud to be recognized for our warm and dependable approach, making your occasion truly unforgettable</p>
        </div>

        <div class="col-md-6">

        <div id="imageCarousel" class="carousel slide carousel-fade overflow-hidden" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img src="{{ asset('images/MyTeam/team1.png') }}" class="d-block w-100 carousel-image" alt="My Team Image">
                    </div>
                    <div class="carousel-item">
                        <img src="{{ asset('images/MyTeam/team2.png') }}" class="d-block w-100 carousel-image" alt="My Team Image">
                    </div>
                    <div class="carousel-item">
                    <img src="{{ asset('images/MyTeam/team3.png') }}" class="d-block w-100 carousel-image" alt="My Team Image">
                    </div>
                    <div class="carousel-item">
                        <img src="{{ asset('images/MyTeam/team4.png') }}" class="d-block w-100 carousel-image" `alt="My Team Image">
                    </div>
                    <div class="carousel-item">
                        <img src="{{ asset('images/MyTeam/team5.png') }}" class="d-block w-100 carousel-image" alt="My Team Image">
                    </div>
                </div>

                <!-- Carousel Arrows -->
        <button class="carousel-control-prev" type="button" data-bs-target="#imageCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#imageCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>

                <!-- Circle Index Indicators -->
                <div class="carousel-indicators-container">
                    <div class="carousel-indicator" data-bs-target="#imageCarousel" data-bs-slide-to="0"></div>
                    <div class="carousel-indicator" data-bs-target="#imageCarousel" data-bs-slide-to="1"></div>
                    <div class="carousel-indicator" data-bs-target="#imageCarousel" data-bs-slide-to="2"></div>
                    <div class="carousel-indicator" data-bs-target="#imageCarousel" data-bs-slide-to="3"></div>
                    <div class="carousel-indicator" data-bs-target="#imageCarousel" data-bs-slide-to="4"></div>
                </div>
        
        </div>

        
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-12">
            <h2>Our Values</h2>
            <ul>
                <li> <strong>Quality First:</strong> We’re all about great food and reliable service, no matter the event.</li>
                <li> <strong>Customer Care:</strong> Our clients are like family. We’re here to make your vision come to life.</li>
                <li> <strong>Honesty & Openness:</strong> We believe in clear communication and keeping things transparent from start to finish.</li>
                <li> <strong>Community Connection:</strong> We’re proud to support our local community and give back whenever we can..</li>
                <li> <strong>Visible Progress:</strong> We keep learning and growing to make each event better than the last.</li>
            </ul>
        </div>
    </div>
</div>

                    <style>
                    /* The carousel container */
                    #imageCarousel {
                        position: relative;
                        box-shadow: 0 0 30px rgba(0, 0, 0, 0.5); /* Dark shadow for outer box */
                        border-bottom-left-radius: 100px; /* Rounded bottom-left corner */  
                        border-top-left-radius: 0; /* Normal top-left corner */
                        border-bottom-right-radius: 100px; /* Normal bottom-right corner */
                        border-top-right-radius: 100px;
                        border-top-left-radius: 100px;
                        border: 1px solid darkorange; /* Thick orange border */
                    }

                    /* Create a wrapper for the image that clips the shadow around the rounded corners */
                    .carousel-image-wrapper {
                        overflow: hidden; /* Ensures that the shadow is clipped around the image's rounded corners */
                        border-top-right-radius: 100px; /* Rounded top-right corner */
                        border-bottom-left-radius: 100px; /* Rounded bottom-left corner */
                    }

                    /* The carousel image itself */
                    .carousel-image {
                        height: 500px; /* Fixed height */
                        width: 300%; /* Responsive width */
                        object-fit: cover; /* Ensures the image covers the area without stretching */
                        border-top-left-radius: 0; /* No rounding on the top-left corner */
                        border-bottom-right-radius: 100px; /* No rounding on the bottom-right corner */
                        border: 3px solid darkorange; /* Thin black border around the image */
                    }

                    /* Apply the orange shadow to the outer shadow of the carousel container */
                    #imageCarousel {
                        box-shadow: 0px 0px 20px 10px rgba(255, 87, 34, 0.7); /* Darker orange shadow */

                    }

                    /* Custom Circle Indicators */
                    .carousel-indicators-container {
                        position: absolute;
                        bottom: 20px;
                        left: 50%;
                        transform: translateX(-50%);
                        display: flex;
                        gap: 10px;
                        z-index: 100;
                    }

                    .carousel-indicator {
                        width: 12px;
                        height: 12px;
                        background-color: darkorange;
                        border-radius: 50%;
                        opacity: 0.6;
                        cursor: pointer;
                        transition: opacity 0.3s, transform 0.3s;
                    }

                    .carousel-indicator.active {
                        opacity: 1;
                        transform: scale(1.2); /* Slightly enlarge the active indicator */
                    }

                    .carousel-indicator:hover {
                        opacity: 1;
                        background-color: #ff7f00; /* Change color on hover */
                    }

                    </style>

                    <script>
                    // JavaScript to update active class on carousel item change
                    const carousel = document.querySelector('#imageCarousel');
                    const indicators = document.querySelectorAll('.carousel-indicator');

                    carousel.addEventListener('slide.bs.carousel', (event) => {
                        const activeIndex = event.to;
                        indicators.forEach((indicator, index) => {
                            if (index === activeIndex) {
                                indicator.classList.add('active');
                            } else {
                                indicator.classList.remove('active');
                            }
                        });
                    });

                    // Optionally, handle clicking the circle to change the slide
                    indicators.forEach((indicator) => {
                        indicator.addEventListener('click', (e) => {
                            const slideToIndex = e.target.getAttribute('data-bs-slide-to');
                            const carouselInstance = new bootstrap.Carousel(carousel);
                            carouselInstance.to(parseInt(slideToIndex));
                        });
                    });
                    </script>

@endsection
