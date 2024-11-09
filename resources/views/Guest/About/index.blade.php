@extends('layouts.guestlayout')

@section('content')

<!-- Carousel Section -->
<div class="container">
    <div id="backgroundContainer">
        <div id="textOnlyCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <!-- First Item -->
                <div class="carousel-item active">
                    <div class="carousel-content">
                        <h5>About Us</h5>
                        <p>At Kyla and Kyle Catering Services, we’re here to make your event truly special. From weddings and corporate gatherings to family celebrations, our goal is to handle every detail so you can enjoy the day. Known for our friendly service and high-quality food, we’re dedicated to bringing your vision to life and creating a memorable experience for you and your guests. Let us take care of everything so you can focus on making memories.</p>
                    </div>
                </div>
                <!-- Second Item -->
                <div class="carousel-item">
                    <div class="carousel-content">
                        <h5>Our History</h5>
                        <p>Kyla and Kyle Catering Services began over a decade ago as a small family eatery in the heart of Montalban, Rizal. Founded on the values of quality, humility, and a deep connection to the community, we’ve grown into one of Rodriguez’s leading catering providers, known for bringing each client’s vision to life with exceptional service and a personal touch. What started as a small team has now blossomed into a full-service catering company committed to making every celebration memorable, beautiful, and worry-free.</p>
                    </div>
                </div>
                <!-- Third Item -->
            <div class="carousel-item">
                <div class="carousel-content">
                    <h5>Our Mission</h5>
                    <p>To make every event unforgettable. At Kyla and Kyle Catering Services, we are dedicated to bringing your vision to life. We handle every detail, from designing and setting up the venue to catering and serving our carefully crafted menu selected by you. Our goal is to create a seamless, memorable experience perfectly tailored to your needs.</p>
                </div>
            </div>
        </div>

        <!-- Carousel Controls -->
        <button class="carousel-control-prev" type="button" data-bs-target="#textOnlyCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#textOnlyCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
</div>

<!-- _________________________________________________________________________________________________________- -->

<!-- _________________________________________________________________________________________________________- -->


    <!-- Meet the Team Section -->
    <div class="row mt-4">
        <div class="col-md-12">
            <h2 class="text-center">Meet the Team</h2>
        </div>

   <!-- Carousel -->
<div class="col-md-12">
    <div id="imageCarousel" class="carousel slide carousel-fade overflow-hidden" data-bs-ride="carousel">
        <div class="carousel-inner">
            @foreach(['team1.png', 'team2.png', 'team3.png', 'team4.png', 'team5.png'] as $index => $image)
                <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                    <div class="carousel-image-container">
                        <img src="{{ asset('images/MyTeam/' . $image) }}" class="d-block w-100 carousel-image" alt="Team Image">
                        <!-- Dark overlay background for text readability -->
                        <div class="carousel-overlay"></div>
                    </div>
                </div>
            @endforeach
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

        <!-- Circle Indicators -->
        <div class="carousel-indicators-container">
            @foreach(['0', '1', '2', '3', '4'] as $index)
                <div class="carousel-indicator" data-bs-target="#imageCarousel" data-bs-slide-to="{{ $index }}"></div>
            @endforeach
        </div>
    </div>
</div>

    </div>
</div>


<div class="row mt-3 text-center">
        <h2>Our Values</h2>
    </div>
    
 <!--Service Cards Section .-->
<div class="row mt-3 justify-content-center">
    <div class="col-md-2 mb-3">
        <div class="service-card" style="background-image: url('{{ asset('images/About/OurValues/qualityfirst.png') }}');">
            <div class="overlay">
                <h2>Quality First</h2>
            </div>
        </div>
    </div>
    <div class="col-md-2 mb-3">
        <div class="service-card" style="background-image: url('{{ asset('images/About/OurValues/customercare.png') }}');">
            <div class="overlay">
                <h2>Customer Care</h2>
            </div>
        </div>
    </div>
    <div class="col-md-2 mb-3">
        <div class="service-card" style="background-image: url('{{ asset('images/About/OurValues/honestyopeness.png') }}');">
            <div class="overlay">
                <h2>Honesty & Openness</h2>
            </div>
        </div>
    </div>
    <div class="col-md-2 mb-3">
        <div class="service-card" style="background-image: url('{{ asset('images/About/OurValues/communityconnection.png') }}');">
            <div class="overlay">
                <h2>Community Connection</h2>
            </div>
        </div>
    </div>
    <div class="col-md-2 mb-3">
        <div class="service-card" style="background-image: url('{{ asset('images/About/OurValues/visibleprogress.png') }}');">
            <div class="overlay">
                <h2>Visible Progress</h2>
            </div>
        </div>
    </div>
</div>
</div>

<!-- CSS first part-->
<style>
/* Ensure the background takes full width and height */
#backgroundContainer {
    position: relative;
    width: 100%; /* Full width */
    height: 600px; /* Fixed height for the background container */
    background-color: #FFE0B2; /* Light orange background */
    background-image: url('{{ asset('images/About/AboutUs.png') }}'); /* Path to your background image */
    background-size: cover; /* Ensure the background image covers the entire container */
    background-position: center center; /* Center the background image */
    background-attachment: fixed; /* Make the background image fixed */
}

/* Ensure content is responsive and full width */
#textOnlyCarousel {
    position: absolute;
    top: 50%; /* Position carousel vertically in the center */
    left: 50%;
    transform: translate(-50%, -50%); /* Center the carousel content horizontally and vertically */
    width: 100%; /* Content takes full width of the parent container */
    padding: 0 20px; /* Add some padding for better readability on smaller screens */
    box-sizing: border-box;
}

/* Carousel content styling with light orange background and transparency */
.carousel-content {
    padding: 40px;
    color: black;
    text-align: center;
    background-color: rgba(255, 224, 178, 0.8); /* Light orange background with transparency */
    width: 100%; /* Content takes the full width */
    max-width: 1200px; /* Max width for the content */
    margin: 0 auto; /* Center the content */
    box-sizing: border-box;
    border-radius: 10px; /* Rounded corners for content */
    z-index: 1; /* Ensure the content appears above the background */
}

/* Title styling */
.carousel-content h5 {
    font-size: 32px;
    font-weight: bold;
    text-transform: uppercase;
    margin-bottom: 15px;
}

/* Paragraph styling */
.carousel-content p {
    font-size: 18px;
    line-height: 1.6; /* Improve line spacing */
    margin: 0 auto;
}

/* Center carousel controls vertically */
.carousel-control-prev, .carousel-control-next {
    align-items: center;
}
</style>

<!-- CSS for Layout and Effects 2nd part-->
<style>
    @media (max-width: 767px) {
    .service-card {
        height: 200px; /* Adjust height for mobile */
    }

    .service-card h2 {
        font-size: 20px; /* Adjust font size on smaller screens */
    }
}


    /* Our Values Design*/
   .service-card {
    position: relative;
    height: 250px; /* Fixed height for the card */
    width: 100%; /* Full width of the column */
    max-width: 400px; /* Optional: Set a max-width for each card */
    background-size: cover; /* Ensures the image covers the full card area */
    background-position: center center; /* Keeps the image centered */
    background-repeat: no-repeat;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    overflow: hidden;
}

.service-card .overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    display: flex;
    align-items: center; /* Vertically center the text */
    justify-content: center; /* Horizontally center the text */
    background-color: rgba(0, 0, 0, 0.5); /* Dark background for text readability */
    color: white;
    font-size: 24px;
    font-weight: bold;
    text-transform: uppercase;
    opacity: 0; /* Hidden by default */
    transition: opacity 0.3s ease-in-out; /* Smooth fade-in effect */
}

.service-card:hover .overlay {
    opacity: 1; /* Show the overlay on hover */
}

.service-card h2 {
    margin: 0; /* Remove default margin */
    text-align: center; /* Ensure text is centered */
    font-size: 24px; /* Make font size responsive */
}

</style>

<style>   

/* Style for the images inside the carousel 3rd part */
.carousel-image {
    width: 100%;        /* Ensure image takes up the full width of the carousel container */
    height: 100%;       /* Ensure image takes up the full height of the carousel container */
    object-fit: fill;   /* Stretch or squeeze the image to fit the container */
    border-radius: 20px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    margin: auto;       /* Center the image inside the carousel item */
}

/* Carousel container fixed size with reduced width */
#imageCarousel {
    width: 60%;           /* Reduced width to 60% of the parent container */
    height: 400px;        /* Fixed height of the carousel */
    margin: auto;         /* Center the carousel horizontally */
    overflow: hidden;     /* Hide any overflow content if the images exceed the container size */
    position: relative;   /* Positioning for control buttons */
    display: flex;        /* Use flex to center the images */
    justify-content: center;
    align-items: center;
    background-color: #fff; /* Optional background color */
    border-radius: 15px;   /* Optional rounded corners for carousel */
}

/* Carousel indicators (circles) styling */
.carousel-indicators-container {
    position: absolute;
    bottom: 10px;
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

.carousel-indicator.active, .carousel-indicator:hover {
    opacity: 1;
    transform: scale(1.2);
    background-color: #ff7f00;
}

/* Carousel arrows styling */
.carousel-control-prev, .carousel-control-next {
    align-items: center; /* Vertically center the controls */
    z-index: 10;         /* Ensure the arrows appear above other content */
}

</style>


<!-- JavaScript for Carousel Indicator Activation -->
<script>
    document.querySelector('#imageCarousel').addEventListener('slide.bs.carousel', function(event) {
        document.querySelectorAll('.carousel-indicator').forEach((indicator, index) => {
            indicator.classList.toggle('active', index === event.to);
        });
    });

    document.querySelectorAll('.carousel-indicator').forEach((indicator, index) => {
        indicator.addEventListener('click', function() {
            document.querySelector('#imageCarousel').carousel(index);
        });
    });
    
    
</script>



@endsection
