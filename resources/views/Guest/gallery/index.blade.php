@extends('layouts.guestlayout')

@section('content')

@vite('resources/css/gallery.css')

<div class="scroll-nav">
    <ul class="nav-links">
        <li><a href="#weddingGallery" class="nav-link">Wedding Gallery</a></li>
        <li><a href="#debutGallery" class="nav-link">Debut Gallery</a></li>
        <li><a href="#kiddieGallery" class="nav-link">Kiddie Gallery</a></li>
        <li><a href="#adultGallery" class="nav-link">Adult Gallery</a></li>
        <li><a href="#corporateGallery" class="nav-link">Corporate Gallery</a></li>
    </ul>
</div>

<div class="container" style="padding-top: 50px;">
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
                    <!-- Close button inside the modal -->
                    <button type="button" id="closeModalButton" class="btn-close position-absolute top-0 end-0 m-3" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0 text-center">
                    <!-- Large Image Display -->
                    <img src="" id="modalImage" class="modal-image" alt="Modal Image">
                </div>
            </div>
        </div>
    </div>

@vite('resources/js/gallery.js')

@endsection