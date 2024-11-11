@extends('layouts.guestlayout')

@section('content')

<meta name="robots" content="noindex, nofollow">


<!-- Carousel Section -->
<div class="container" style="padding-top: 120px;">
    <div id="backgroundContainer">
        <div id="textOnlyCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <!-- First Item -->
                <div class="carousel-item active">
                    <div class="carousel-content">
                        <h5>About Us</h5>
                        <p><strong>At Kyla and Kyle Catering Services, we’re here to make your event truly special. From weddings and corporate gatherings to family celebrations, our goal is to handle every detail so you can enjoy the day. Known for our friendly service and high-quality food, we’re dedicated to bringing your vision to life and creating a memorable experience for you and your guests. Let us take care of everything so you can focus on making memories.</strong></p>
                    </div>
                </div>
                <!-- Second Item -->
                <div class="carousel-item">
                    <div class="carousel-content">
                        <h5>Our History</h5>
                        <p><strong>Kyla and Kyle Catering Services began over a decade ago as a small family eatery in the heart of Montalban, Rizal. Founded on the values of quality, humility, and a deep connection to the community, we’ve grown into one of Rodriguez’s leading catering providers, known for bringing each client’s vision to life with exceptional service and a personal touch. What started as a small team has now blossomed into a full-service catering company committed to making every celebration memorable, beautiful, and worry-free.</strong></p>
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


   <!-- Meet the Team Section -->
<div class="row mt-4">
    <div class="col-md-12">
        <h2 class="text-center" style="padding-top: 20px;">Meet the Team</h2>
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
                <h5 class="modal-title" id="imageModalLabel">Team Member</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <!-- Image will be injected here dynamically -->
                <img id="modalImage" src="" alt="Larger view" class="img-fluid">
            </div>
        </div>
    </div>
</div>
    

<div class="row mt-3 text-center">
        <h2>Our Values</h2>
        <div class="header-line"></div> <!-- Line below the title -->
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

  <!-- Mission Section -->
  <div class="row mt-3 text-center justify-content-center">
    <div class="col-md-8">
        <h2>Our Mission</h2>
        <div class="header-line"></div> <!-- Line below the title -->
        <p  style="padding-top: 20px;"><strong>To make every event unforgettable. At Kyla and Kyle Catering Services, we are dedicated to bringing your vision to life. We handle every detail, from designing and setting up the venue to catering and serving our carefully crafted menu selected by you. Our goal is to create a seamless, memorable experience perfectly tailored to your needs.</strong></p>
    </div>
</div>


<!-- CSS first part Text Carousel-->
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

            /* Add border and rounded corners */
            border: 2px solid #FF7F00; /* Orange border color */
            border-radius: 15px; /* Rounded corners */

            /* Add orange box-shadow */
            box-shadow: 0 8px 25px rgba(255, 69, 0, 0.8); /* Darker orange with wider spread */
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
            border: 2px solid #FF7F00; /* Orange border color */
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

      

        /* Adjust text size and layout for smaller screens */
@media (max-width: 767px) {
    #backgroundContainer {
        height: 400px; /* Adjust height for smaller screens */
    }

    .carousel-content {
        padding: 20px; /* Reduce padding on smaller screens */
    }

    .carousel-content h5 {
        font-size: 24px; /* Smaller font size for titles on mobile */
    }

    .carousel-content p {
        font-size: 16px; /* Smaller font size for paragraphs on mobile */
    }

    /* Make carousel take full width on mobile */
    #textOnlyCarousel {
        width: 100%; /* Full width for smaller screens */
        padding: 0 10px; /* Less padding */
    }

    .carousel-item {
        text-align: center; /* Ensure text is centered in carousel items */
    }
}
    </style>

<!-- CSS second Team Gallery-->
    <style>
            .header-line {
            width: 50%; /* Adjust this value to make the line wider or narrower */
            height: 5px; /* Adjust thickness of the line */
            background-color: #FF7F00; /* Dark orange color */
            margin: 20px auto; /* Center the line and add margin for spacing */
            border-radius: 25px; /* Rounded edges */
        }

        /* Ensure the gallery images are smaller and fixed in size */
        .gallery-image {
            width: 350px; /* Fixed width for all gallery images */
            height: 350px; /* Fixed height for all gallery images */
            object-fit: fill; /* Stretch the image to fill the container */
            border-radius: 10px; /* Optional: rounded corners for the images */
            margin: 0 auto; /* Center the image within its parent div */
            border: 2px solid #FF7F00; /* Orange border color */
             /* Add orange box-shadow */
             box-shadow: 0 8px 25px rgba(255, 69, 0, 0.8); /* Darker orange with wider spread */
        }


            /* Make modal image responsive */
        /* Modal Image Styling */
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
            border: 2px solid #FF7F00; /* Orange border color */
            overflow: hidden;
             /* Add orange box-shadow */
             box-shadow: 0 8px 25px rgba(255, 69, 0, 0.8); /* Darker orange with wider spread */
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


<!-- CSS fourth part Our Mission-->
    <style>
          .header-line {
            width: 50%; /* Adjust this value to make the line wider or narrower */
            height: 5px; /* Adjust thickness of the line */
            background-color: #FF7F00; /* Dark orange color */
            margin: 20px auto; /* Center the line and add margin for spacing */
            border-radius: 25px; /* Rounded edges */
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
