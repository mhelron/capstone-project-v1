@extends('layouts.guestlayout')

@section('content')


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Add the canonical link -->
    <link rel="canonical" href="{{ url()->current() }}">
    
    <!-- Other meta tags, stylesheets, etc. -->
</head>

<div class="container">

<div class="container" style="padding-top: 50px;">

    <div class="row align-items-center pt-4">
        <div class="col-md-6">
       
        <h3 style="color: solid black; font-family: 'Monaco'; border-bottom: 4px solid darkorange; padding-bottom:
        3px;">Kyla and Kyle Catering Services</h3>

            <h1 style="font-weight: 900; font-family: 'Georgia', sans-serif; font-size: 48px; padding-top: 5px;">One of 
            the most known Catering Services in Montalban</h1>
            <p>At Kyla and Kyle Catering Services, we bring people together through great food and
            exceptional service. Based in Rodriguez, Rizal, we specialize in creating unforgettable
            experiences for every occasion, from weddings and corporate events to family
            celebrations. 
            </p>

            <p>With our skilled team and attention to detail, we handle everything from
            venue setup to delicious, carefully prepared meals. Let us bring your vision to life and
            make your next event truly special.</p>
            
            <div class="d-flex mt-4 mb-4">
                <a href="{{route('guest.reserve')}}" class="btn btn-darkorange me-3">Reserve</a>
                <a href="#" class="btn btn-darkorange">View Status</a>
            </div>
        </div>

        <!-- Image Carousel with Fade Effect -->
        <div class="col-md-6 mt-4 mb-5">
            <div id="imageCarousel" class="carousel slide carousel-fade overflow-hidden" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img src="{{ asset('images/Home/image1.png') }}" class="d-block w-100 carousel-image" alt="Wedding Image">
                    </div>
                    <div class="carousel-item">
                        <img src="{{ asset('images/Home/image2.png') }}" class="d-block w-100 carousel-image" alt="Debut Image">
                    </div>
                    <div class="carousel-item">
                        <img src="{{ asset('images/Home/image3.png') }}" class="d-block w-100 carousel-image" alt="Baptismal Image">
                    </div>
                    <div class="carousel-item">
                        <img src="{{ asset('images/Home/image4.png') }}" class="d-block w-100 carousel-image" alt="Corporate Event Image">
                    </div>
                    <div class="carousel-item">
                        <img src="{{ asset('images/Home/image5.png') }}" class="d-block w-100 carousel-image" alt="Birthday Image">
                    </div>
                </div>

                <!-- Carousel Arrows -->
                <button class="carousel-control-prev" type="button" data-bs-target="#imageCarousel" data-bs-slide="prev" aria-label="Previous Slide">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#imageCarousel" data-bs-slide="next" aria-label="Next Slide">
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
</div>

<!-- Add Bootstrap Icons for the chatbot bubble -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

</div>

<style>

  
/* The carousel container */
#imageCarousel {
    position: relative;
    box-shadow: 0 0 30px rgba(0, 0, 0, 0.5); /* Dark shadow for outer box */
    border-top-right-radius: 100px; /* Rounded top-right corner */
    border-bottom-left-radius: 100px; /* Rounded bottom-left corner */  
    border-top-left-radius: 0; /* Normal top-left corner */
    border-bottom-right-radius: 0; /* Normal bottom-right corner */
}

/* Create a wrapper for the image that clips the shadow around the rounded corners */
.carousel-image-wrapper {
    overflow: hidden; /* Ensures that the shadow is clipped around the image's rounded corners */
    border-top-right-radius: 100px; /* Rounded top-right corner */
    border-bottom-left-radius: 100px; /* Rounded bottom-left corner */
}

/* The carousel image itself */
.carousel-image {
    height: 600px; /* Fixed height */
    width: 100%; /* Responsive width */
    object-fit: cover; /* Ensures the image covers the area without stretching */
    border-top-left-radius: 0; /* No rounding on the top-left corner */
    border-bottom-right-radius: 0; /* No rounding on the bottom-right corner */
    border: 2px solid darkorange; /* Thin black border around the image */
}

/* Apply the orange shadow to the outer shadow of the carousel container */
#imageCarousel {
    box-shadow: 0px 0px 10px 5px rgba(255, 87, 34, 0.7); /* Darker orange shadow */

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
    transform: scale(1.3); /* Slightly enlarge the active indicator */
}

.carousel-indicator:hover {
    opacity: 1;
    background-color: #ff7f00; /* Change color on hover */
}

        /* Mobile Responsiveness */
        @media (max-width: 576px) {
            .chatbot-chatbox {
                width: 250px;
                height: 400px;
            }

            .chatbox-send input {
                font-size: 12px;
            }

            .chatbox-send button {
                font-size: 12px;
            }

            .chatbox-restart-back button {
                font-size: 12px;
            }

            /* Stack footer buttons vertically */
            .chatbox-footer {
                flex-direction: column; /* Stack footer buttons on top of each other */
            }
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
