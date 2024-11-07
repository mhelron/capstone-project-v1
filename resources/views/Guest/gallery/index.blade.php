@extends('layouts.guestlayout')

@section('content')

<div class="scroll-nav">
    <ul class="nav-links">
        <li><a href="#weddingGallery" class="nav-link">Wedding Gallery</a></li>
        <li><a href="#debutGallery" class="nav-link">Debut Gallery</a></li>
        <li><a href="#kiddieGallery" class="nav-link">Kiddie Gallery</a></li>
        <li><a href="#adultGallery" class="nav-link">Adult Gallery</a></li>
        <li><a href="#corporateGallery" class="nav-link">Corporate Gallery</a></li>
    </ul>
</div>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="container mt-4 mb-5">
        
 <!-- WEDDING GALLERY -->
                <!-- Wedding Title -->
                <h1 id="weddingGallery" class="locations-header">
                    <span class="border-line left-border"></span>
                    Wedding Gallery
                    <span class="border-line right-border"></span>
                </h1>
                <!-- First Row -->
                <div class="row text-center" style="padding-top:20px;">
                    <!-- First Image -->
                    <div class="col-md-4 mb-4">
                        <a href="#" class="d-block" data-bs-toggle="modal" data-bs-target="#imageModal" 
                           data-bs-image="{{ asset('images/Gallery/wedding1.png') }}">
                            <img src="{{ asset('images/Gallery/wedding1.png') }}" class="gallery-image" alt="Wedding Image">
                        </a>
                    </div>

                    <!-- Second Image -->
                    <div class="col-md-4 mb-4">
                        <a href="#" class="d-block" data-bs-toggle="modal" data-bs-target="#imageModal" 
                           data-bs-image="{{ asset('images/Gallery/wedding2.png') }}">
                            <img src="{{ asset('images/Gallery/wedding2.png') }}" class="gallery-image" alt="Wedding Image">
                        </a>
                    </div>

                    <!-- Third Image -->
                    <div class="col-md-4 mb-4">
                        <a href="#" class="d-block" data-bs-toggle="modal" data-bs-target="#imageModal" 
                           data-bs-image="{{ asset('images/Gallery/wedding3.png') }}">
                            <img src="{{ asset('images/Gallery/wedding3.png') }}" class="gallery-image" alt="Wedding Image">
                        </a>
                    </div>
                </div>

               

                <!-- Second Row -->
                <div class="row text-center" style="padding-top:20px;">
                    <!-- First Image -->
                    <div class="col-md-4 mb-4">
                        <a href="#" class="d-block" data-bs-toggle="modal" data-bs-target="#imageModal" 
                           data-bs-image="{{ asset('images/Gallery/wedding4.png') }}">
                            <img src="{{ asset('images/Gallery/wedding4.png') }}" class="gallery-image" alt="Wedding Image">
                        </a>
                    </div>

                    <!-- Second Image -->
                    <div class="col-md-4 mb-4">
                        <a href="#" class="d-block" data-bs-toggle="modal" data-bs-target="#imageModal" 
                           data-bs-image="{{ asset('images/Gallery/wedding5.png') }}">
                            <img src="{{ asset('images/Gallery/wedding5.png') }}" class="gallery-image" alt="Wedding Image">
                        </a>
                    </div>

                    <!-- Third Image -->
                    <div class="col-md-4 mb-4">
                        <a href="#" class="d-block" data-bs-toggle="modal" data-bs-target="#imageModal" 
                           data-bs-image="{{ asset('images/Gallery/wedding6.png') }}">
                            <img src="{{ asset('images/Gallery/wedding6.png') }}" class="gallery-image" alt="Wedding Image">
                        </a>
                    </div>
                </div>

                

<!-- DEBUT GALLERY -->

             <!-- Debut Title -->
             <h1 id="debutGallery" class="locations-header">
                    <span class="border-line left-border"></span>
                    Debut Gallery
                    <span class="border-line right-border"></span>
                </h1>
                <!-- First Row -->
                <div class="row text-center" style="padding-top:20px;">
                    <!-- First Image -->
                    <div class="col-md-4 mb-4">
                        <a href="#" class="d-block" data-bs-toggle="modal" data-bs-target="#imageModal" 
                           data-bs-image="{{ asset('images/Gallery/debut1.png') }}">
                            <img src="{{ asset('images/Gallery/debut1.png') }}" class="gallery-image" alt="Debut Image">
                        </a>
                    </div>

                    <!-- Second Image -->
                    <div class="col-md-4 mb-4">
                        <a href="#" class="d-block" data-bs-toggle="modal" data-bs-target="#imageModal" 
                           data-bs-image="{{ asset('images/Gallery/debut2.png') }}">
                            <img src="{{ asset('images/Gallery/debut2.png') }}" class="gallery-image" alt="Debut Image">
                        </a>
                    </div>

                    <!-- Third Image -->
                    <div class="col-md-4 mb-4">
                        <a href="#" class="d-block" data-bs-toggle="modal" data-bs-target="#imageModal" 
                           data-bs-image="{{ asset('images/Gallery/debut3.png') }}">
                            <img src="{{ asset('images/Gallery/debut3.png') }}" class="gallery-image" alt="Debut Image">
                        </a>
                    </div>
                </div>

                <!-- Second Row -->
                <div class="row text-center" style="padding-top:20px;">
                    <!-- First Image -->
                    <div class="col-md-4 mb-4">
                        <a href="#" class="d-block" data-bs-toggle="modal" data-bs-target="#imageModal" 
                           data-bs-image="{{ asset('images/Gallery/debut4.png') }}">
                            <img src="{{ asset('images/Gallery/debut4.png') }}" class="gallery-image" alt="Debut Image">
                        </a>
                    </div>

                    <!-- Second Image -->
                    <div class="col-md-4 mb-4">
                        <a href="#" class="d-block" data-bs-toggle="modal" data-bs-target="#imageModal" 
                           data-bs-image="{{ asset('images/Gallery/debut5.png') }}">
                            <img src="{{ asset('images/Gallery/debut5.png') }}" class="gallery-image" alt="Debut Image">
                        </a>
                    </div>

                    <!-- Third Image -->
                    <div class="col-md-4 mb-4">
                        <a href="#" class="d-block" data-bs-toggle="modal" data-bs-target="#imageModal" 
                           data-bs-image="{{ asset('images/Gallery/debut6.png') }}">
                            <img src="{{ asset('images/Gallery/debut6.png') }}" class="gallery-image" alt="Debut Image">
                        </a>
                    </div>
                </div>
                
                

<!-- KIDDIE GALLERY -->

             <!-- Kiddie Title -->
             <h1 id="kiddieGallery" class="locations-header">
                    <span class="border-line left-border"></span>
                    Kiddie Gallery
                    <span class="border-line right-border"></span>
                </h1>
                <!-- First Row -->
                <div class="row text-center" style="padding-top:20px;">
                    <!-- First Image -->
                    <div class="col-md-4 mb-4">
                        <a href="#" class="d-block" data-bs-toggle="modal" data-bs-target="#imageModal" 
                           data-bs-image="{{ asset('images/Gallery/kiddie1.png') }}">
                            <img src="{{ asset('images/Gallery/kiddie1.png') }}" class="gallery-image" alt="Kiddie Image">
                        </a>
                    </div>

                    <!-- Second Image -->
                    <div class="col-md-4 mb-4">
                        <a href="#" class="d-block" data-bs-toggle="modal" data-bs-target="#imageModal" 
                           data-bs-image="{{ asset('images/Gallery/kiddie2.png') }}">
                            <img src="{{ asset('images/Gallery/kiddie2.png') }}" class="gallery-image" alt="Kiddie Image">
                        </a>
                    </div>

                    <!-- Third Image -->
                    <div class="col-md-4 mb-4">
                        <a href="#" class="d-block" data-bs-toggle="modal" data-bs-target="#imageModal" 
                           data-bs-image="{{ asset('images/Gallery/kiddie3.png') }}">
                            <img src="{{ asset('images/Gallery/kiddie3.png') }}" class="gallery-image" alt="Kiddie Image">
                        </a>
                    </div>
                </div>

                <!-- Second Row -->
                <div class="row text-center" style="padding-top:20px;">
                    <!-- First Image -->
                    <div class="col-md-4 mb-4">
                        <a href="#" class="d-block" data-bs-toggle="modal" data-bs-target="#imageModal" 
                           data-bs-image="{{ asset('images/Gallery/kiddie4.png') }}">
                            <img src="{{ asset('images/Gallery/kiddie4.png') }}" class="gallery-image" alt="Kiddie Image">
                        </a>
                    </div>

                    <!-- Second Image -->
                    <div class="col-md-4 mb-4">
                        <a href="#" class="d-block" data-bs-toggle="modal" data-bs-target="#imageModal" 
                           data-bs-image="{{ asset('images/Gallery/kiddie5.png') }}">
                            <img src="{{ asset('images/Gallery/kiddie5.png') }}" class="gallery-image" alt="Kiddie Image">
                        </a>
                    </div>

                    <!-- Third Image -->
                    <div class="col-md-4 mb-4">
                        <a href="#" class="d-block" data-bs-toggle="modal" data-bs-target="#imageModal" 
                           data-bs-image="{{ asset('images/Gallery/kiddie6.png') }}">
                            <img src="{{ asset('images/Gallery/kiddie6.png') }}" class="gallery-image" alt="Kiddie Image">
                        </a>
                    </div>
                </div>


<!-- ADULT GALLERY -->

             <!-- Adult Title -->
             <h1 id="adultGallery" class="locations-header">
                    <span class="border-line left-border"></span>
                    Adult Gallery
                    <span class="border-line right-border"></span>
                </h1>
                <!-- First Row -->
                <div class="row text-center" style="padding-top:20px;">
                    <!-- First Image -->
                    <div class="col-md-4 mb-4">
                        <a href="#" class="d-block" data-bs-toggle="modal" data-bs-target="#imageModal" 
                           data-bs-image="{{ asset('images/Gallery/adult1.png') }}">
                            <img src="{{ asset('images/Gallery/adult1.png') }}" class="gallery-image" alt="Adult Image">
                        </a>
                    </div>

                    <!-- Second Image -->
                    <div class="col-md-4 mb-4">
                        <a href="#" class="d-block" data-bs-toggle="modal" data-bs-target="#imageModal" 
                           data-bs-image="{{ asset('images/Gallery/adult2.png') }}">
                            <img src="{{ asset('images/Gallery/adult2.png') }}" class="gallery-image" alt="Adult Image">
                        </a>
                    </div>

                    <!-- Third Image -->
                    <div class="col-md-4 mb-4">
                        <a href="#" class="d-block" data-bs-toggle="modal" data-bs-target="#imageModal" 
                           data-bs-image="{{ asset('images/Gallery/adult3.png') }}">
                            <img src="{{ asset('images/Gallery/adult3.png') }}" class="gallery-image" alt="Adult Image">
                        </a>
                    </div>
                </div>

                <!-- Second Row -->
                <div class="row text-center" style="padding-top:20px;">
                    <!-- First Image -->
                    <div class="col-md-4 mb-4">
                        <a href="#" class="d-block" data-bs-toggle="modal" data-bs-target="#imageModal" 
                           data-bs-image="{{ asset('images/Gallery/adult4.png') }}">
                            <img src="{{ asset('images/Gallery/adult4.png') }}" class="gallery-image" alt="Adult Image">
                        </a>
                    </div>

                    <!-- Second Image -->
                    <div class="col-md-4 mb-4">
                        <a href="#" class="d-block" data-bs-toggle="modal" data-bs-target="#imageModal" 
                           data-bs-image="{{ asset('images/Gallery/adult5.png') }}">
                            <img src="{{ asset('images/Gallery/adult5.png') }}" class="gallery-image" alt="Adult Image">
                        </a>
                    </div>

                    <!-- Third Image -->
                    <div class="col-md-4 mb-4">
                        <a href="#" class="d-block" data-bs-toggle="modal" data-bs-target="#imageModal" 
                           data-bs-image="{{ asset('images/Gallery/adult6.png') }}">
                            <img src="{{ asset('images/Gallery/adult6.png') }}" class="gallery-image" alt="Adult Image">
                        </a>
                    </div>
                </div>

                

<!-- CORPORATE GALLERY -->

             <!-- Corporate Title -->
             <h1 id="corporateGallery" class="locations-header">
                    <span class="border-line left-border"></span>
                    Corporate Gallery
                    <span class="border-line right-border"></span>
                </h1>
                <!-- First Row -->
                <div class="row text-center" style="padding-top:20px;">
                    <!-- First Image -->
                    <div class="col-md-4 mb-4">
                        <a href="#" class="d-block" data-bs-toggle="modal" data-bs-target="#imageModal" 
                           data-bs-image="{{ asset('images/Gallery/corporate1.png') }}">
                            <img src="{{ asset('images/Gallery/corporate1.png') }}" class="gallery-image" alt="Corporate Image">
                        </a>
                    </div>

                    <!-- Second Image -->
                    <div class="col-md-4 mb-4">
                        <a href="#" class="d-block" data-bs-toggle="modal" data-bs-target="#imageModal" 
                           data-bs-image="{{ asset('images/Gallery/corporate2.png') }}">
                            <img src="{{ asset('images/Gallery/corporate2.png') }}" class="gallery-image" alt="Corporate Image">
                        </a>
                    </div>

                    <!-- Third Image -->
                    <div class="col-md-4 mb-4">
                        <a href="#" class="d-block" data-bs-toggle="modal" data-bs-target="#imageModal" 
                           data-bs-image="{{ asset('images/Gallery/corporate3.png') }}">
                            <img src="{{ asset('images/Gallery/corporate3.png') }}" class="gallery-image" alt="Corporate Image">
                        </a>
                    </div>
                </div>

                <!-- Second Row -->
                <div class="row text-center" style="padding-top:20px;">
                    <!-- First Image -->
                    <div class="col-md-4 mb-4">
                        <a href="#" class="d-block" data-bs-toggle="modal" data-bs-target="#imageModal" 
                           data-bs-image="{{ asset('images/Gallery/corporate4.png') }}">
                            <img src="{{ asset('images/Gallery/corporate4.png') }}" class="gallery-image" alt="Corporate Image">
                        </a>
                    </div>

                    <!-- Second Image -->
                    <div class="col-md-4 mb-4">
                        <a href="#" class="d-block" data-bs-toggle="modal" data-bs-target="#imageModal" 
                           data-bs-image="{{ asset('images/Gallery/corporate5.png') }}">
                            <img src="{{ asset('images/Gallery/corporate5.png') }}" class="gallery-image" alt="Corporate Image">
                        </a>
                    </div>

                    <!-- Third Image -->
                    <div class="col-md-4 mb-4">
                        <a href="#" class="d-block" data-bs-toggle="modal" data-bs-target="#imageModal" 
                           data-bs-image="{{ asset('images/Gallery/corporate6.png') }}">
                            <img src="{{ asset('images/Gallery/corporate6.png') }}" class="gallery-image" alt="Corporate Image">
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Modal to show image in large size -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close position-absolute top-0 end-0 m-3" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0 text-center">
                <!-- Large Image Display -->
                <img src="" id="modalImage" class="modal-image" alt="Modal Image">
            </div>
        </div>
    </div>
</div>

<!-- Include Bootstrap JS and Popper if not already included -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.min.js"></script>

<script>
    // Get the modal and nav elements
    var imageModal = document.getElementById('imageModal');
    var navBar = document.querySelector('.scroll-nav');  // The navigation bar

    // When the modal is about to be shown
    imageModal.addEventListener('show.bs.modal', function (event) {
        // Hide the navbar when the modal is opened
        navBar.style.display = 'none';  // Or add a class to hide it (e.g., navBar.classList.add('hidden');)
    });

    // When the modal is hidden
    imageModal.addEventListener('hidden.bs.modal', function (event) {
        // Show the navbar when the modal is closed
        navBar.style.display = 'flex';  // Or add a class to show it (e.g., navBar.classList.remove('hidden');)
    });

    // Set the image source in the modal based on the clicked image's data attribute
    imageModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget; // Button that triggered the modal
        var imageSrc = button.getAttribute('data-bs-image'); // Get the data-bs-image attribute value
        var modalImage = imageModal.querySelector('#modalImage');
        modalImage.src = imageSrc; // Set the src of the modal image
    });
</script>


<style>
      
    .gallery-image {
        width: 100%; /* Makes the image responsive within the column */
        aspect-ratio: 1 / 1; /* Ensures images are square */
        object-fit: cover; /* Maintains aspect ratio within the square */
        border: 2px solid orange; /* Border around the image */
        border-radius: 8px; /* Optional: Rounded corners */
        box-shadow: 0 4px 8px rgba(0,0,0,0.2); /* Optional: Adds shadow */
    }

    /* Modal Fullscreen Image */
    .modal-image {
        width: 100%;
        height: 100%;
        object-fit: contain; /* Make the image scale while maintaining aspect ratio */
    }

    /* Remove padding/margins from the modal to maximize image size */
    .modal-body {
        padding: 0;
    }

    /* Ensure the modal fills the screen */
    .modal-dialog.modal-fullscreen {
        max-width: 100%;
        width: 100%;
        height: 100%;
        margin: 0;
    }

    .modal-content {
        height: 100%;
        background-color: transparent; /* Set the modal background to transparent */
        border: none; /* Remove any borders */
    }

    .modal-header {
        position: relative;
        padding: 0;
    }

    .btn-close.position-absolute {
        position: absolute;
        top: 10px;  /* Adjust the position from the top */
        right: 10px; /* Adjust the position from the right */
        z-index: 1050; /* Ensure it overlaps the image */
        background-color: transparent; /* Make the background transparent */
        font-size: 2rem; /* Increase the size of the close button */
    }
    /* Border Design */
        .locations-header {
            text-align: center;
            font-weight: bolder;
            font-family: 'Times New Roman';
            font-size: 40px;
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


        /* Gallery Button Style */
        .scroll-nav {
            position: fixed;
            bottom: 0px;
            left: 50%;
            transform: translateX(-50%);
            background-color: rgba(255, 255, 255, 0.8);
            padding: 10px 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            z-index: 9999;
            border-radius: 5px;
        }

        .scroll-nav ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            justify-content: space-around;
        }

        .scroll-nav .nav-link {
            text-decoration: none;
            color: black;
            font-size: 15px;
            font-weight: bold; /* Makes the text bold */
            padding: 8px 15px;
            transition: background-color 0.3s, color 0.3s; /* Added transition for color change */
        }

        /* Hover effect for changing the color to orange */
        .scroll-nav .nav-link:hover {
            background-color: orange;
            color: white; /* Change text color to white when hovered */
            border-radius: 3px;
        }

        /* Active link style */
        .scroll-nav .nav-link.active {
            background-color: orange;
            color: orange;
            border-radius: 3px;
        }
</style>

@endsection