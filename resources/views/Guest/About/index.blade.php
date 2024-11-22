@extends('layouts.guestlayout')

@section('content')

<meta name="robots" content="noindex, nofollow">


<!-- Carousel Section -->
<div class="container" style="padding-top: 125px;">
    <div id="backgroundContainer">
        <div id="textOnlyCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">
            
            <!-- Carousel Indicators -->
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#textOnlyCarousel" data-bs-slide-to="0" class="active custom-indicator" aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#textOnlyCarousel" data-bs-slide-to="1" class="custom-indicator" aria-label="Slide 2"></button>
            </div>

            <div class="carousel-inner">
                <!-- First Item -->
                <div class="carousel-item active">
                    <div class="carousel-content">
                    <h1>OUR MISSION</h1>  
                    <div class="header-line"></div> <!-- Line below the title -->
                    <p><strong>To make every event unforgettable. At Kyla and Kyle Catering Services, we are dedicated to bringing your vision to life. We handle every detail, from designing and setting up the venue to catering and serving our carefully crafted menu selected by you. Our goal is to create a seamless, memorable experience perfectly tailored to your needs.</strong></p>
                    <p><br></p>
                </div>
                </div>
                <!-- Second Item -->
                <div class="carousel-item">
                    <div class="carousel-content">
                    <h1>OUR VISION</h1>
                    <div class="header-line"></div> <!-- Line below the title -->
                    <p><strong>We aspire to be the caterer that people trust for all their special occasions in the future. Our mission is to unite and delight our guests through exceptional service, a memorable atmosphere, and delicious meals. Through our unwavering commitment to creating extraordinary events, we want to form meaningful connections and provide individuals we assist with experiences they will never forget.</strong></p>
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
</div>

<!-- Container for the Two-Column Layout -->
<div class="container" style="padding-top: 50px;"> <!-- Light yellow background for the container -->
    <div class="row">
        <!-- Left Column (Mission Section) -->
        <div class="col-sm-12 col-md-6 d-flex align-items-center justify-content-center" style="padding: 20px;"> <!-- Flexbox for centering -->
            <!-- Mission Section -->
            <div style="box-shadow: 0 0 25px rgba(255, 69, 0, 0.8); background-color: #FFE0B2; border-radius: 8px; border: 2px solid #FF7F00; width: 100%; margin: 0 auto;"> <!-- Full width on small screens -->
                <div class="row mt-3 text-center justify-content-center">
                    <div class="col-md-8">
                        <h1>ABOUT US</h1>  
                        <div class="header-line"></div> <!-- Line below the title -->
                        <p style="padding-bottom: 55px;"><strong>At Kyla and Kyle Catering Services, we’re here to make your event truly special. From weddings and corporate gatherings to family celebrations, our goal is to handle every detail so you can enjoy the day. Known for our friendly service and high-quality food, we’re dedicated to bringing your vision to life and creating a memorable experience for you and your guests. Let us take care of everything so you can focus on making memories.</strong></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column (History Section) -->
        <div class="col-sm-12 col-md-6 d-flex align-items-center justify-content-center" style="padding: 20px;"> <!-- Flexbox for centering -->
            <!-- History Section -->
            <div style="box-shadow: 0 0 25px rgba(255, 69, 0, 0.8); background-color: #FFE0B2; border-radius: 8px; border: 2px solid #FF7F00; width: 100%; margin: 0 auto;"> <!-- Full width on small screens -->
                <div class="row mt-3 text-center justify-content-center">
                    <div class="col-md-8">
                        <h1>OUR HISTORY</h1>  
                        <div class="header-line"></div> <!-- Line below the title -->
                        <p style="padding-bottom: 0px"><strong>Kyla and Kyle Catering Services began over a decade ago as a small family eatery in the heart of Montalban, Rizal. Founded on the values of quality, humility, and a deep connection to the community, we’ve grown into one of Rodriguez’s leading catering providers, known for bringing each client’s vision to life with exceptional service and a personal touch. What started as a small team has now blossomed into a full-service catering company committed to making every celebration memorable, beautiful, and worry-free.</strong></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Our Values Section -->
<div class="row mt-3 text-center">
    <h1 style="padding-top: 25px;">OUR VALUES</h1>
    <div class="header-line"></div> <!-- Line below the title -->
</div>

<!-- Service Cards Section -->
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

<!-- Meet the Team Section -->
<div class="row mt-4">
    <div class="col-md-12">
        <h1 class="text-center" style="padding-top: 20px;">MEET THE TEAM</h1>
        <div class="header-line"></div> <!-- Line below the title -->
    </div>

    <div class="row text-center" style="padding-top:20px;">
        <!-- First Image -->
        <div class="col-md-4 mb-4">
            <a href="#" class="d-block" data-bs-toggle="modal" data-bs-target="#imageModal" 
               data-bs-image="{{ asset('images/MyTeam/Team1.png') }}">
                <img src="{{ asset('images/MyTeam/team1.png') }}" class="gallery-image" alt="Team Member 1">
            </a>
        </div>

        <!-- Second Image -->
        <div class="col-md-4 mb-4">
            <a href="#" class="d-block" data-bs-toggle="modal" data-bs-target="#imageModal" 
               data-bs-image="{{ asset('images/MyTeam/Team2.png') }}">
                <img src="{{ asset('images/MyTeam/team2.png') }}" class="gallery-image" alt="Team Member 2">
            </a>
        </div>

        <!-- Third Image -->
        <div class="col-md-4 mb-4">
            <a href="#" class="d-block" data-bs-toggle="modal" data-bs-target="#imageModal" 
               data-bs-image="{{ asset('images/MyTeam/Team3.png') }}">
                <img src="{{ asset('images/MyTeam/team3.png') }}" class="gallery-image" alt="Team Member 3">
            </a>
        </div>
    </div>
</div>

<!-- Modal for displaying larger image -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalLabel">Team Members</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <!-- Image will be injected here dynamically -->
                <img id="modalImage" src="" alt="Larger view" class="img-fluid">
            </div>
        </div>
    </div>
</div>

<!-- CSS 1st part Text Carousel-->
<style>
/* Main Headings (Mission & Vision Titles) */
h1 {
    font-family: 'Playfair Display', serif; /* Elegant font for headings */
    font-weight: 700; /* Bold font style */
    font-size: 2rem; /* Adjust the size as needed */
    color: #000; 
    text-transform: uppercase; /* All caps for emphasis */
    letter-spacing: 2px;
    margin-bottom: 15px;
    text-align: center; /* Center header text on all screen sizes */
}

/* Paragraph Text (for Mission and Vision) */
p {
    font-family: 'Roboto', sans-serif;
    font-weight: 400; /* Normal weight for the text */
    font-size: 1.1rem;
    color: #333; /* Lighter gray for the text */
    margin-bottom: 20px;
    text-align: center; /* Center align paragraph text */
}

/* Custom Header Line Style */
.header-line {
    width: 50%; /* Adjust width */
    height: 5px; /* Line thickness */
    background-color: #FF7F00; /* Dark orange color */
    margin: 20px auto; /* Centered line with spacing */
    border-radius: 25px; /* Rounded edges */
}

/* Carousel Text Styling */
.carousel-content {
    text-align: center;
    padding: 20px;
}

/* Customize Carousel Control Icons */
.carousel-control-prev-icon, .carousel-control-next-icon {
    background-color: #FF7F00; /* Control icon color */
}

/* Ensure the background takes full width and a smaller height */
#backgroundContainer {
    position: relative;
    width: 100%; /* Full width */
    height: 400px; /* Reduced height for the background container */
    background-color: #FFE0B2; /* Light orange background */
    padding-top: 50px;
    background-image: url('{{ asset('images/About/AboutUs.png') }}'); /* Path to your background image */
    background-size: cover; /* Ensure the background image covers the entire container */
    background-position: center center; /* Center the background image */
    background-attachment: fixed; /* Make the background image fixed */
    border: 2px solid #FF7F00; /* Orange border color */
    border-radius: 15px; /* Rounded corners */
    box-shadow: 0 0 25px rgba(255, 69, 0, 0.8); /* Orange shadow around the border */
    display: flex;
    justify-content: center; /* Horizontally center content */
    align-items: center; /* Vertically center content */
    padding-bottom: 50px; /* Extra space at the bottom for indicators */
}

/* Ensure content is responsive and full width */
#textOnlyCarousel {
    position: relative;
    width: 100%; /* Content takes full width of the parent container */
    padding: 0 20px; /* Add some padding for better readability on smaller screens */
    box-sizing: border-box;
}

/* Carousel content styling with light orange background (no transparency) */
.carousel-content {
    padding: 40px;
    color: black;
    text-align: center;
    background-color: #FFE0B2; /* Solid light orange background */
    width: 100%; /* Content takes the full width */
    max-width: 1200px; /* Max width for the content */
    margin: 0 auto; /* Center the content */
    box-sizing: border-box;
    border: 2px solid #FF7F00; /* Orange border color */
    border-radius: 10px; /* Rounded corners for content */
    z-index: 1; /* Ensure the content appears above the background */
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center; /* Center content vertically and horizontally */
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

/* Default style for the custom indicator */
.carousel-indicators {
    position: absolute;
    bottom: -25px; /* Position the indicators outside the background */
    left: 50%;
    transform: translateX(-50%); /* Center the indicators horizontally */
    display: flex;
    justify-content: center;
    padding: 0;
    margin: 0;
}

.carousel-indicators button.custom-indicator {
    width: 12px; /* Indicator size */
    height: 12px; /* Indicator size */
    background-color: darkorange; /* Dark orange color */
    border-radius: 50%; /* Make it circular */
    opacity: 0.6; /* Slightly transparent by default */
    cursor: pointer; /* Show cursor on hover */
    transition: opacity 0.3s, transform 0.3s; /* Smooth transition for opacity and scaling */
}

/* Style for the active indicator */
.carousel-indicators button.custom-indicator.active {
    opacity: 1; /* Full opacity for active indicator */
    transform: scale(1.3); /* Slightly enlarge the active indicator */
}

/* Style for the indicator on hover */
.carousel-indicators button.custom-indicator:hover {
    opacity: 1; /* Full opacity on hover */
    background-color: #FF7F00; /* Slightly brighter dark orange color on hover */
}

/* Add styles to make carousel control buttons stand out */
.carousel-control-prev-icon, .carousel-control-next-icon {
    background-color: darkorange; /* Set icon color to dark orange */
    border-radius: 50%;
    opacity: 0.6; /* Set the default opacity */
    transition: opacity 0.3s ease; /* Smooth transition for opacity */
}

/* Make the icons brighter on hover */
.carousel-control-prev-icon:hover, .carousel-control-next-icon:hover {
    background-color: #FF7F00; /* Brighter orange on hover */
    opacity: 1; /* Full opacity when hovered */
}

/* Further styles for carousel control buttons for better visibility */
.carousel-control-prev-icon, .carousel-control-next-icon {
    width: 30px; /* Increase size */
    height: 30px;
    border-radius: 50%;
}

/* Further adjustments for very small screens */
@media (max-width: 768px) {
    /* Adjust the header for smaller screens */
    h1 {
        font-size: 1.5rem; /* Smaller font size for mobile */
    }

    /* Adjust padding and font size for carousel content */
    .carousel-content {
        padding: 20px;
    }

    .carousel-content h5 {
        font-size: 24px; /* Smaller font size for titles */
    }

    .carousel-content p {
        font-size: 14px; /* Smaller font size for paragraphs */
    }

    #textOnlyCarousel {
        padding: 0 10px; /* Slightly reduce padding on smaller screens */
    }

    /* Adjust the position of the indicators for smaller screens */
    .carousel-indicators {
        bottom: -15px; /* Further adjust bottom space */
    }

    /* Responsive adjustments for carousel controls */
    .carousel-control-prev-icon, .carousel-control-next-icon {
        width: 25px; /* Reduce icon size */
        height: 25px;
    }
}

/* Adjustments for very small screens (mobile) */
@media (max-width: 480px) {
    #backgroundContainer {
        height: 350px; /* Further reduce height on very small screens */
    }

    .carousel-content {
        padding: 10px; /* Even less padding on small screens */
        align-items: center; /* Ensure content stays centered on very small screens */
    }

    .carousel-content h5 {
        font-size: 18px; /* Smaller font size for titles */
    }

    .carousel-content p {
        font-size: 12px; /* Smaller font size for paragraphs */
    }

    #textOnlyCarousel {
        width: 100%; /* Ensure it uses full width on mobile */
        padding: 0 5px; /* Minimal padding for small screens */
    }

    /* Adjust the position of the indicators for very small screens */
    .carousel-indicators {
        bottom: -15px; /* Further adjust bottom space */
    }
}
    


    </style>

<!-- CSS 2nd part Our history-->
    <style>
          /* General styling */
.mission-section, .history-section {
    box-shadow: 0 0 25px rgba(255, 69, 0, 0.8);
    background-color: #FFE0B2;
    border-radius: 8px;
    border: 2px solid #FF7F00;
    width: 100%;
    margin: 0 auto;
    padding: 20px;
}

.header-line {
    width: 50%; /* Adjust this value to make the line wider or narrower */
    height: 5px; /* Adjust thickness of the line */
    background-color: #FF7F00; /* Dark orange color */
    margin: 20px auto; /* Center the line and add margin for spacing */
    border-radius: 25px; /* Rounded edges */
}

/* Responsive styling */
@media (max-width: 767px) {

    .col-12 {
        padding-left: 15px;
        padding-right: 15px;
        margin-bottom: 20px; /* Adding margin at the bottom for spacing */
    }

    h1 {
        font-size: 1.5rem; /* Adjust the font size for mobile */
        margin-bottom: 15px; /* Adding margin at the bottom of h1 */
    }

    .header-line {
        width: 60%; /* Make the header line smaller for mobile */
        margin: 15px auto; /* Reduce the margin for mobile */
    }

    p {
        font-size: 0.9rem; /* Reduce the font size for smaller screens */
        line-height: 1.5; /* Improve readability */
        margin-bottom: 15px; /* Adding margin at the bottom of the paragraph */
    }
}

@media (max-width: 480px) {
    h1 {
        font-size: 2.0rem; /* Smaller font size for very small screens */
        margin-bottom: 10px; /* Smaller margin for small screens */
    }

    .header-line {
        width: 50%; /* Even smaller header line for very small screens */
        margin: 10px auto; /* Even smaller margin for very small screens */
    }

    p {
    font-size: 0.8rem; /* Further reduce font size for very small screens */
    margin-top: 15px;  /* Add margin at the top of the paragraph */
    margin-bottom: 15px; /* Adding margin at the bottom of the paragraph */
    margin-left: 10px;   /* Optional: Add margin to the left */
    margin-right: 10px;  /* Optional: Add margin to the right */
}

}
   
    </style>

<!-- CSS third part Image Values-->
    <style>
            .header-line {
                width: 50%; /* Adjust this value to make the line wider or narrower */
                height: 5px; /* Adjust thickness of the line */
                background-color: #FF7F00; /* Dark orange color */
                margin: 20px auto; /* Center the line and add margin for spacing */
                border-radius: 25px; /* Rounded edges */
            }
            @media (max-width: 767px) {
            .service-card {
                height: 200px; /* Adjust height for mobile */
            }

            .service-card h2 {
                font-size: 20px; /* Adjust font size on smaller screens */
            }
        }


        .service-card {
    position: relative;
    height: 250px; /* Fixed height for the card */
    width: 100%; /* Full width of the column */
    max-width: 400px; /* Optional: Set a max-width for each card */
    background-size: cover; /* Ensures the image covers the full card area */
    background-position: center center; /* Keeps the image centered */
    background-repeat: no-repeat;
    border-radius: 10px;
    border: 2px solid #FF7F00; /* Orange border color */
    overflow: hidden;
    /* Add shadow around the border */
    box-shadow: 0 0 10px rgba(255, 69, 0, 0.8); /* Orange shadow around the border */
}

/* Overlay styling */
.service-card .overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    display: flex;
    align-items: center; /* Vertically center the text */
    justify-content: center; /* Horizontally center the text */
    background-color: rgba(0, 0, 0, 0.4); /* Semi-transparent black background */
    color: white;
    font-size: 24px;
    font-weight: bold;
    text-transform: uppercase;
    opacity: 1; /* Content visible by default */
    transition: opacity 0.3s ease-in-out, background-color 0.3s ease-in-out; /* Smooth transition for both content and background */
}

/* On hover, hide content and show lighter white background */
.service-card:hover .overlay {
    opacity: 0; /* Hide the content */
    background-color: rgba(255, 255, 255, 0.5); /* Lighter white overlay with 50% opacity */
}

/* Header text in the overlay */
.service-card h2 {
    margin: 0; /* Remove default margin */
    text-align: center; /* Ensure text is centered */
    font-size: 24px; /* Adjust font size */
    color: white; /* Ensure text color is white */
}

/* Mobile responsiveness */
@media (max-width: 767px) {
    .service-card {
        height: 200px; /* Adjust height for mobile */
    }

    .service-card h2 {
        font-size: 20px; /* Adjust font size on smaller screens */
    }
}
     </style>

<!-- CSS 4th Team Gallery-->
<style>
            .header-line {
    width: 50%; /* Adjust this value to make the line wider or narrower */
    height: 5px; /* Adjust thickness of the line */
    background-color: #FF7F00; /* Dark orange color */
    margin: 20px auto; /* Center the line and add margin for spacing */
    border-radius: 25px; /* Rounded edges */
}

/* Ensure the gallery images are smaller, responsive, and centered */
.gallery-image {
    width: 100%; /* Make images take up 100% of their container's width */
    max-width: 350px; /* Limit max-width of images */
    height: auto; /* Allow the height to scale proportionally */
    object-fit: cover; /* Make sure the image is cropped correctly without distorting */
    border-radius: 10px; /* Optional: rounded corners for the images */
    border: 2px solid #FF7F00; /* Orange border color */
    /* Add shadow around the border */
    box-shadow: 0 0 20px rgba(255, 69, 0, 0.8); /* Orange shadow around the border */
}

/* Flexbox adjustments to center images inside columns */
.row {
    display: flex;
    justify-content: center;
    align-items: center;
    flex-wrap: wrap; /* Allow for wrapping on smaller screens */
}


/* Make modal image responsive */
.modal-body img {
    width: 100%; /* Ensure image takes up full width inside modal */
    max-width: 800px; /* Maximum width to avoid oversized images */
    height: auto; /* Maintain the aspect ratio of the image */
    border-radius: 10px; /* Rounded corners for the image */
    object-fit: contain; /* Ensure image doesn't overflow */
}

/* Optional: For responsiveness, you can adjust the size for smaller screens */
@media (max-width: 768px) {
    .modal-content {
        width: 95%; /* Modal should take 95% of the width on smaller screens */
        margin-top: 10px; /* Add some margin for top space */
        margin-bottom: 10px; /* Margin for bottom space */
    }

    .modal-body {
        padding: 10px; /* Reduce padding on mobile */
    }

    .modal-header, .modal-footer {
        padding: 10px; /* Reduce padding for header and footer */
    }

    /* Optional: Reduce size of close button on mobile */
    .btn-close {
        font-size: 1.2rem; /* Make close button smaller */
    }
}

/* Additional Styles for Mobile */
@media (max-width: 576px) {
    .modal-content {
        width: 100%; /* Full width on extremely small screens */
        margin-top: 20px; /* Adjust margin */
    }

    /* Adjust image size in smaller modals */
    .modal-body img {
        width: 100%;
        height: auto;
    }
}
            </style>

<!-- JavaScript for Carousel Indicator Activation -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
    var imageModal = document.getElementById('imageModal');
    
    // Disable page scrolling when modal is shown
    imageModal.addEventListener('show.bs.modal', function () {
        document.body.style.overflow = 'hidden'; // Disable scrolling
    });
    
    // Enable page scrolling when modal is hidden
    imageModal.addEventListener('hidden.bs.modal', function () {
        document.body.style.overflow = ''; // Enable scrolling again
    });

    // Image modal setup (same as before)
    imageModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        var imageSrc = button.getAttribute('data-bs-image'); // Get the image source
        var modalImage = document.getElementById('modalImage');
        modalImage.src = imageSrc; // Set the image source in the modal
    });
});


</script>



@endsection
