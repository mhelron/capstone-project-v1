@extends('layouts.adminlayout')

@php
use Illuminate\Support\Facades\Storage;
@endphp

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0" style="padding-top: 30px;">Carousel Image Home</h1>
            </div>
        </div>
    </div>
</div>

<!-- Main content -->
<div class="content">
    <div class="container-fluid">
        <div class="row d-flex justify-content-center">
            <div class="col-lg-12">
                <!-- Edit Content Form -->
                <div class="card">
                    <div class="card-body form-container">
                        <form action="{{ route('admin.carousel.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div>
                                <!-- Carousel Images -->
                                <div class="col-md-12">
                                    <div class="form-group mb-3">
                                        <h1><label for="carousel_images">Add/Edit</label></h1>
                                        <div id="carousel-images-list">
                                            @if (old('carousel_images'))
                                                @foreach (old('carousel_images') as $index => $image)
                                                    <div class="row mb-2">
                                                        <div class="col-md-12">
                                                            <div class="preview-container mt-2 mb-2">
                                                                <p>No preview available</p>
                                                            </div>
                                                            <div class="d-flex">
                                                                <div class="input-group flex-grow-1">
                                                                    <input type="file" name="carousel_images[{{$index}}]" class="form-control mb-2" onchange="previewImage(this)">
                                                                    <div class="form-control mb-2" style="background-color: #e9ecef;">
                                                                        No file chosen
                                                                    </div>
                                                                </div>
                                                                <button class="btn btn-danger remove-image ms-2 mb-2" type="button" onclick="removeImage(this)">Remove</button>
                                                            </div>
                                                            @error("carousel_images.$index")
                                                                <small class="text-danger" style="margin-top: 2px; margin-bottom: 1px; display: block;">{{ $message }}</small>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @elseif (!empty($content['carousel_images']))
                                                @foreach ($content['carousel_images'] as $index => $image)
                                                    <div class="row mb-2">
                                                        <div class="col-md-12">
                                                            <div class="preview-container mt-2 mb-2">
                                                                <img src="{{ Storage::url($image) }}" alt="Carousel Image" style="max-height: 200px; max-width: 100%;">
                                                            </div>
                                                            <div class="d-flex">
                                                                <div class="input-group flex-grow-1">
                                                                    <input type="file" name="carousel_images[{{$index}}]" class="form-control mb-2" onchange="previewImage(this)">
                                                                    <div class="form-control mb-2" style="background-color: #e9ecef;">
                                                                        Current: {{ basename($image) }}
                                                                    </div>
                                                                </div>
                                                                @if ($index > 0)
                                                                    <button class="btn btn-danger remove-image ms-2 mb-2" type="button" onclick="removeImage(this)">Remove</button>
                                                                @endif
                                                            </div>
                                                            @error("carousel_images.$index")
                                                                <small class="text-danger" style="margin-top: 2px; margin-bottom: 1px; display: block;">{{ $message }}</small>
                                                            @enderror
                                                            <input type="hidden" name="existing_images[{{$index}}]" value="{{ $image }}">
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @else
                                                <div class="row mb-2">
                                                    <div class="col-md-12">
                                                        <div class="preview-container mt-2 mb-2">
                                                            <!-- Preview will be inserted here -->
                                                        </div>
                                                        <div class="d-flex">
                                                            <div class="input-group flex-grow-1">
                                                                <input type="file" name="carousel_images[0]" class="form-control mb-2" onchange="previewImage(this)">
                                                                <div class="form-control mb-2" style="background-color: #e9ecef;">
                                                                    No file chosen
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @error('carousel_images.0')
                                                            <small class="text-danger" style="margin-top: 2px; margin-bottom: 1px; display: block;">{{ $message }}</small>
                                                        @enderror
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                        <button id="add-image" class="btn btn-sm btn-success mt-2" type="button">Add More Images</button>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit button -->
                            <div class="form-group mb-3">
                                <button type="submit" class="btn btn-primary float-end">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function previewImage(input) {
    const file = input.files[0];
    if (file) {
        const reader = new FileReader();
        const previewDiv = input.closest('.col-md-12').querySelector('.preview-container');
        const filenameDiv = input.nextElementSibling;
        
        if (filenameDiv) {
            filenameDiv.textContent = 'Selected: ' + file.name;
        }
        
        reader.onload = function(e) {
            previewDiv.innerHTML = `<img src="${e.target.result}" alt="Preview" style="max-height: 200px; max-width: 100%; margin-top: 10px;">`;
        }
        
        reader.readAsDataURL(file);
    }
}

// Add more images button handler
document.getElementById('add-image').addEventListener('click', function() {
    const imagesList = document.getElementById('carousel-images-list');
    const newIndex = imagesList.querySelectorAll('.row').length;
    
    const imageGroup = `
        <div class="row mb-2">
            <div class="col-md-12">
                <div class="preview-container mt-2 mb-2">
                    <!-- Preview will be inserted here -->
                </div>
                <div class="d-flex">
                    <div class="input-group flex-grow-1">
                        <input type="file" name="carousel_images[${newIndex}]" class="form-control mb-2" onchange="previewImage(this)">
                        <div class="form-control mb-2" style="background-color: #e9ecef;">
                            No file chosen
                        </div>
                    </div>
                    <button class="btn btn-danger remove-image ms-2 mb-2" type="button" onclick="removeImage(this)">Remove</button>
                </div>
                <small class="text-danger" style="margin-top: 2px; margin-bottom: 1px; display: block;"></small>
            </div>
        </div>
    `;

    imagesList.insertAdjacentHTML('beforeend', imageGroup);
});

// Remove image handler
function removeImage(button) {
    const row = button.closest('.row');
    const hiddenInput = row.querySelector('input[name^="existing_images"]');
    
    // If there's a hidden input with an existing image path, remove it
    if (hiddenInput) {
        hiddenInput.remove();
    }
    
    row.remove();
}

document.addEventListener('DOMContentLoaded', function() {
    const fileInputs = document.querySelectorAll('input[type="file"]');
    fileInputs.forEach(input => {
        input.addEventListener('change', function() {
            previewImage(this);
        });
    });
});
</script>
@endsection