@extends('layouts.adminlayout')

@php
use Illuminate\Support\Facades\Storage;
@endphp

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0" style="padding-top: 30px;">Gallery Management</h1>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">
        <div class="row d-flex justify-content-center">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.gallery.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            
                            @php
                                $categories = [
                                    'wedding' => 'Wedding Gallery',
                                    'debut' => 'Debut Gallery',
                                    'kiddie' => 'Kiddie Gallery',
                                    'adult' => 'Adult Gallery',
                                    'corporate' => 'Corporate Gallery'
                                ];
                            @endphp

                            @foreach ($categories as $category => $title)
                                <div class="col-md-12 mb-4">
                                    <div class="form-group">
                                        <h2>{{ $title }}</h2>
                                        <div id="{{ $category }}-images-list">
                                            @if (!empty($content[$category . '_gallery']))
                                                @foreach ($content[$category . '_gallery'] as $index => $image)
                                                    <div class="row mb-3">
                                                        <div class="col-md-12">
                                                            <div class="preview-container mt-2 mb-2">
                                                                <img src="{{ Storage::url($image) }}" alt="{{ $title }} Image" style="max-height: 200px; max-width: 100%;">
                                                            </div>
                                                            <div class="d-flex">
                                                                <div class="input-group flex-grow-1">
                                                                    <input type="file" name="{{ $category }}_images[{{$index}}]" class="form-control" onchange="previewImage(this)">
                                                                    <div class="form-control" style="background-color: #e9ecef;">
                                                                        Current: {{ basename($image) }}
                                                                    </div>
                                                                </div>
                                                                <button class="btn btn-danger remove-image ms-2" type="button" onclick="removeImage(this)">Remove</button>
                                                            </div>
                                                            <input type="hidden" name="existing_{{ $category }}_images[{{$index}}]" value="{{ $image }}">
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                        <button type="button" class="btn btn-success btn-sm mt-2" onclick="addImage('{{ $category }}')">
                                            Add More {{ $title }} Images
                                        </button>
                                    </div>
                                </div>
                            @endforeach

                            <div class="form-group">
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

function addImage(category) {
    const imagesList = document.getElementById(`${category}-images-list`);
    const newIndex = imagesList.querySelectorAll('.row').length;
    
    const imageGroup = `
        <div class="row mb-3">
            <div class="col-md-12">
                <div class="preview-container mt-2 mb-2">
                    <!-- Preview will be inserted here -->
                </div>
                <div class="d-flex">
                    <div class="input-group flex-grow-1">
                        <input type="file" name="${category}_images[${newIndex}]" class="form-control" onchange="previewImage(this)">
                        <div class="form-control" style="background-color: #e9ecef;">
                            No file chosen
                        </div>
                    </div>
                    <button class="btn btn-danger remove-image ms-2" type="button" onclick="removeImage(this)">Remove</button>
                </div>
            </div>
        </div>
    `;

    imagesList.insertAdjacentHTML('beforeend', imageGroup);
}

function removeImage(button) {
    const row = button.closest('.row');
    const hiddenInput = row.querySelector('input[type="hidden"]');
    
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