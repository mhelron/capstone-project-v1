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
                            <div class="form-container" style="max-height: 70vh; overflow-y: auto;">
                                @php
                                    $categoryRows = [
                                        ['wedding' => 'Wedding Gallery', 'kiddie' => 'Kiddie Gallery'],
                                        ['adult' => 'Adult Gallery', 'debut' => 'Debut Gallery'],
                                        ['corporate' => 'Corporate Gallery']
                                    ];
                                @endphp

                                @foreach ($categoryRows as $row)
                                    <div class="row mb-4">
                                        @foreach ($row as $category => $title)
                                            <div class="col-md-{{ count($row) == 1 ? '12' : '6' }}">
                                                <div class="card">
                                                    <div class="card-header bg-light">
                                                        <h2 class="card-title mb-0">{{ $title }}</h2>
                                                    </div>
                                                    <div class="card-body">
                                                        <div id="{{ $category }}-images-list">
                                                            @if (!empty($content[$category . '_gallery']))
                                                                @foreach ($content[$category . '_gallery'] as $index => $image)
                                                                    <div class="row mb-3">
                                                                        <div class="col-12">
                                                                            <div class="preview-container mt-2 mb-2">
                                                                                <img src="{{ Storage::url($image) }}" alt="{{ $title }} Image" style="max-height: 200px; max-width: 100%; object-fit: cover;">
                                                                            </div>
                                                                            <div class="d-flex mt-2">
                                                                                <div class="input-group flex-grow-1">
                                                                                    <input type="file" name="{{ $category }}_images[{{$index}}]" class="form-control" onchange="previewImage(this)">
                                                                                    <div class="form-control bg-light">
                                                                                        Current: {{ basename($image) }}
                                                                                    </div>
                                                                                </div>
                                                                                <button class="btn btn-danger remove-image ms-2" type="button" onclick="removeImage(this)">
                                                                                    <i class="fas fa-trash"></i>
                                                                                </button>
                                                                            </div>
                                                                            <input type="hidden" name="existing_{{ $category }}_images[{{$index}}]" value="{{ $image }}">
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            @endif
                                                        </div>
                                                        <button type="button" class="btn btn-success btn-sm mt-2" onclick="addImage('{{ $category }}')">
                                                            <i class="fas fa-plus"></i> Add {{ $title }} Image
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endforeach
                            </div>

                            <div class="form-group mt-3 border-top pt-3">
                                <button type="submit" class="btn btn-primary float-end">
                                    <i class="fas fa-save"></i> Save All Changes
                                </button>
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
        const row = input.closest('.row');
        const previewDiv = row.querySelector('.preview-container');
        const filenameDiv = input.nextElementSibling;
        
        if (filenameDiv) {
            filenameDiv.textContent = 'Selected: ' + file.name;
        }
        
        reader.onload = function(e) {
            previewDiv.innerHTML = `<img src="${e.target.result}" alt="Preview" style="max-height: 200px; max-width: 100%; object-fit: cover;">`;
        }
        
        reader.readAsDataURL(file);
    }
}

function addImage(category) {
    const imagesList = document.getElementById(`${category}-images-list`);
    const newIndex = imagesList.querySelectorAll('.row').length;
    
    const imageGroup = `
        <div class="row mb-3">
            <div class="col-md-6">
                <div class="preview-container mt-2 mb-2">
                    <!-- Preview will be inserted here -->
                </div>
            </div>
            <div class="col-md-6">
                <div class="d-flex flex-column">
                    <div class="input-group mb-2">
                        <input type="file" name="${category}_images[${newIndex}]" class="form-control" onchange="previewImage(this)">
                        <div class="input-group-text bg-light">
                            No file chosen
                        </div>
                    </div>
                    <button class="btn btn-danger remove-image" type="button" onclick="removeImage(this)">
                        <i class="fas fa-trash"></i> Remove
                    </button>
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