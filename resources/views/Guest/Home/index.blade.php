@extends('layouts.guestlayout')

@section('content')

<div class="container">
    <div class="row align-items-center pt-4">
        <div class="col-md-6">
       
        <h3 style="color: solid black; font-family: 'Monaco'; border-bottom: 4px solid darkorange; padding-bottom:
        3px;">Kyla and Kyle Catering Services</>

            <h1 style="font-weight: 900; font-family: 'Georgia', sans-serif; font-size: 48px; padding-top: 5px;">One of 
            the most known Catering Services in Montalban</h1>
            <p>At Kyla and Kyle Catering Services, we bring people together through great food and exceptional service. Based in Rodriguez, Rizal, we specialize in creating unforgettable experiences for every occasion, from weddings and corporate events to family celebrations. </p>

            <p>With our skilled team and attention to detail, we handle everything from venue setup to delicious, carefully prepared meals. Let us bring your vision to life and make your next event truly special</p>
            
            <div class="d-flex mt-4 mb-4">
                <a href="#" class="btn btn-darkorange me-3">Reserve</a>
                <a href="#" class="btn btn-darkorange">View Status</a>
            </div>
        </div>

        <!-- Image Carousel with Fade Effect -->
        <div class="col-md-6 mt-4 mb-5">
            <div id="imageCarousel" class="carousel slide carousel-fade overflow-hidden" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img src="{{ asset('images/Home/Wedding.png') }}" class="d-block w-100 carousel-image" alt="Wedding Image">
                    </div>
                    <div class="carousel-item">
                        <img src="{{ asset('images/Home/Style.png') }}" class="d-block w-100 carousel-image" alt="Venue Design Image">
                    </div>
                    <div class="carousel-item">
                    <img src="{{ asset('images/Home/Debut.png') }}" class="d-block w-100 carousel-image" alt="Debut Image">
                    </div>
                    <div class="carousel-item">
                        <img src="{{ asset('images/Home/Catering.png') }}" class="d-block w-100 carousel-image" `alt="Food Catering Image">
                    </div>
                    <div class="carousel-item">
                        <img src="{{ asset('images/Home/Birthday.png') }}" class="d-block w-100 carousel-image" alt="Regular Birthday Image">
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
    border: px solid darkorange; /* Thick orange border */
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
