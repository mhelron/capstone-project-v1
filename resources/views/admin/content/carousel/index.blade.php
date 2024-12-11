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
                $categoriesChunked = array_chunk($categories, 2, true);
            @endphp

            @foreach ($categoriesChunked as $categoryRow)
                <div class="row">
                    @foreach ($categoryRow as $category => $title)
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h2 class="card-title mb-0">{{ $title }}</h2>
                                </div>
                                <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr class="bg-light">
                                                <th style="width: 60px;">#</th>
                                                <th>Image</th>
                                            </tr>
                                        </thead>
                                        <tbody id="{{ $category }}-images-list">
                                            @if (!empty($content[$category . '_gallery']))
                                                @foreach ($content[$category . '_gallery'] as $index => $image)
                                                    <tr class="image-row">
                                                        <td class="text-center align-middle number-cell">
                                                            {{$index + 1}}
                                                        </td>
                                                        <td>
                                                            <div class="preview-container mt-2 mb-2 text-center">
                                                                <img src="{{ Storage::url($image) }}" alt="{{ $title }} Image" style="max-height: 200px; max-width: 100%;">
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
                                                            @error("${category}_images.$index")
                                                                <small class="text-danger">{{ $message }}</small>
                                                            @enderror
                                                            <input type="hidden" name="existing_{{ $category }}_images[{{$index}}]" value="{{ $image }}">
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <!-- Add initial empty row when no images exist -->
                                                <tr class="image-row">
                                                    <td class="text-center align-middle number-cell">1</td>
                                                    <td>
                                                        <div class="preview-container mt-2 mb-2 text-center">
                                                            <!-- Preview will be inserted here -->
                                                        </div>
                                                        <div class="d-flex mt-2">
                                                            <div class="input-group flex-grow-1">
                                                                <input type="file" name="{{ $category }}_images[0]" class="form-control" onchange="previewImage(this)">
                                                                <div class="form-control bg-light">
                                                                    No file chosen
                                                                </div>
                                                            </div>
                                                            <button class="btn btn-danger remove-image ms-2" type="button" onclick="removeImage(this)">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                                    <div class="text-center mt-3">
                                        <button type="button" class="btn btn-success" onclick="addImage('{{ $category }}')">
                                            <i class="fas fa-plus"></i> Add New Image
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endforeach

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body text-end">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save"></i> Save All Changes
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function previewImage(input) {
    const file = input.files[0];
    if (file) {
        const reader = new FileReader();
        const previewDiv = input.closest('td').querySelector('.preview-container');
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

function updateRowNumbers(category) {
    const tbody = document.getElementById(`${category}-images-list`);
    const rows = tbody.querySelectorAll('tr');
    rows.forEach((row, index) => {
        const numberCell = row.querySelector('.number-cell');
        if (numberCell) {
            numberCell.textContent = index + 1;
        }
    });
}

function addImage(category) {
    const tbody = document.getElementById(`${category}-images-list`);
    const newIndex = tbody.querySelectorAll('tr').length;
    
    const newRow = `
        <tr class="image-row">
            <td class="text-center align-middle number-cell">
                ${newIndex + 1}
            </td>
            <td>
                <div class="preview-container mt-2 mb-2 text-center">
                    <!-- Preview will be inserted here -->
                </div>
                <div class="d-flex mt-2">
                    <div class="input-group flex-grow-1">
                        <input type="file" name="${category}_images[${newIndex}]" class="form-control" onchange="previewImage(this)">
                        <div class="form-control bg-light">
                            No file chosen
                        </div>
                    </div>
                    <button class="btn btn-danger remove-image ms-2" type="button" onclick="removeImage(this)">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </td>
        </tr>
    `;

    tbody.insertAdjacentHTML('beforeend', newRow);
}

function removeImage(button) {
    const row = button.closest('tr');
    const tbody = row.parentElement;
    const category = tbody.id.replace('-images-list', '');
    const hiddenInput = row.querySelector('input[type="hidden"]');
    
    if (hiddenInput) {
        hiddenInput.remove();
    }
    
    row.remove();
    updateRowNumbers(category);
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