@extends('layouts.guestlayout')

@section('content')

@php
use Illuminate\Support\Facades\Storage;
@endphp

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
                @php
                    $galleries = [
                        'wedding' => 'Wedding Gallery',
                        'debut' => 'Debut Gallery',
                        'kiddie' => 'Kiddie Gallery',
                        'adult' => 'Adult Gallery',
                        'corporate' => 'Corporate Gallery'
                    ];
                @endphp

                @foreach($galleries as $key => $title)
                    <h1 id="{{ $key }}Gallery" class="locations-header">
                        <span class="border-line left-border"></span>
                        {{ $title }}
                        <span class="border-line right-border"></span>
                    </h1>

                    @if(!empty($content[$key . '_gallery']))
                        @foreach(array_chunk($content[$key . '_gallery'], 3) as $row)
                            <div class="row text-center" style="padding-top:20px;">
                                @foreach($row as $image)
                                    <div class="col-md-4 mb-4">
                                        <a href="#" class="d-block" data-bs-toggle="modal" data-bs-target="#imageModal" 
                                           data-bs-image="{{ Storage::url($image) }}">
                                            <img src="{{ Storage::url($image) }}" class="gallery-image" alt="{{ $title }} Image">
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    @else
                        <div class="row text-center" style="padding-top:20px;">
                            <div class="col-12">
                                <p>No images available for {{ $title }}</p>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- Modal to show image in large size -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" id="closeModalButton" class="btn-close position-absolute top-0 end-0 m-3" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0 text-center">
                <img src="" id="modalImage" class="modal-image" alt="Modal Image">
            </div>
        </div>
    </div>
</div>

@vite('resources/js/gallery.js')

@endsection