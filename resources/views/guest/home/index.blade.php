@extends('layouts.guestlayout')

@section('content')

@php
use Illuminate\Support\Facades\Storage;
@endphp

<div class="container">

<div class="container" style="padding-top: 50px;">

    <!-- Bootstrap Toast -->
    @if (session('status'))
        <div class="toast-container position-fixed top-0 end-0 p-3">
            <div class="toast text-bg-light border border-dark custom-toast-size" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        {{ session('status') }}
                    </div>
                    <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        </div>
    @endif

    <div class="row align-items-center pt-4">
        <div class="col-md-6">
       
        <h3 style="color: solid black; font-family: 'Monaco'; border-bottom: 4px solid darkorange; padding-bottom:
        3px;">{!! $content['title_home'] !!}</h3>

            <h1 style="font-weight: 900; font-family: 'Georgia', sans-serif; font-size: 48px; padding-top: 5px;">{!! $content['headline'] !!}</h1>
            <p>{!! $content['subtext'] !!}</p>
            
            <div class="d-flex mt-4 mb-4">
                <a href="{{route('guest.reserve')}}" class="btn btn-darkorange me-2">Reserve</a>
                <a href="{{route('guest.check')}}" class="btn btn-darkorange me-2">View Status</a>
                <a href="{{route('guest.quote')}}" class="btn btn-darkorange me-2">Quote</a>
                <a href="{{route('guest.foodtaste.index')}}" class="btn btn-darkorange">Food Taste</a>
            </div>
        </div>

        <!-- Image Carousel with Fade Effect -->
        <div class="col-md-6 mt-5 mb-4">
            <div id="imageCarousel" class="carousel slide carousel-fade overflow-hidden" data-bs-ride="carousel">
                @if(isset($content['carousel_images']) && count($content['carousel_images']) > 0)
                    <div class="carousel-inner">
                        @foreach($content['carousel_images'] as $index => $image)
                            <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                <img src="{{ Storage::url($image) }}" class="d-block w-100 carousel-image" alt="Carousel Image {{ $index + 1 }}">
                            </div>
                        @endforeach
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
                        @foreach($content['carousel_images'] as $index => $image)
                            <div class="carousel-indicator {{ $index === 0 ? 'active' : '' }}" 
                                 data-bs-target="#imageCarousel" 
                                 data-bs-slide-to="{{ $index }}">
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-carousel">
                        <div class="empty-message">
                            <i class="bi bi-image"></i>
                            <p>No images to display</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Add Bootstrap Icons for the chatbot bubble -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

</div>

<style>
  @media (max-width: 768px) {
        .responsive-heading {
            padding-top: 1000px;
        }
    }
  
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

        .empty-carousel {
    height: 600px;
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #f8f9fa;
    border: 2px solid darkorange;
    border-top-right-radius: 100px;
    border-bottom-left-radius: 100px;
    box-shadow: 0px 0px 10px 5px rgba(255, 87, 34, 0.7);
    }

    .empty-message {
        text-align: center;
        color: #6c757d;
    }

    .empty-message i {
        font-size: 48px;
        margin-bottom: 10px;
    }

    .empty-message p {
        font-size: 18px;
        margin: 0;
    }
</style>

@if(isset($content['carousel_images']) && count($content['carousel_images']) > 0)
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const carousel = document.querySelector('#imageCarousel');
            const indicators = document.querySelectorAll('.carousel-indicator');

            if (carousel && indicators.length > 0) {
                // Update active class on carousel item change
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

                // Handle clicking the indicator to change the slide
                indicators.forEach((indicator) => {
                    indicator.addEventListener('click', (e) => {
                        const slideToIndex = e.target.getAttribute('data-bs-slide-to');
                        const carouselInstance = bootstrap.Carousel.getOrCreateInstance(carousel);
                        carouselInstance.to(parseInt(slideToIndex));
                    });
                });
            }
        });
    </script>
@endif

@endsection
