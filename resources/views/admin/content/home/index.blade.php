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
                <h1 class="m-0" style="padding-top: 30px;">Home Content</h1>
            </div>
        </div>
    </div>
</div>
<!-- /.content-header -->

<!-- Main content -->
<div class="content">
    <div class="container-fluid">
        <div class="row d-flex justify-content-center">
            <div class="col-lg-12">
                <!-- Edit Content Form -->
                <div class="card">
                    <div class="card-body form-container">
                        <form action="{{ route('admin.home.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            
                            <!-- Start of row -->
                            <div class="row">
                                <h1>Header</h1>
                                <div class="form-group mb-3">
                                    <label for="logo">Logo</label>
                                    <div class="preview-wrapper">
                                        <div class="preview-container mt-2 mb-2">
                                            @if (!empty($content['logo_path']))
                                                <img src="{{ Storage::url($content['logo_path']) }}" alt="Logo" style="max-height: 100px;">
                                            @endif
                                        </div>
                                        <div class="d-flex">
                                            <div class="input-group flex-grow-1">
                                                <input type="file" name="logo" class="form-control" onchange="previewImage(this)">
                                                <div class="form-control" style="background-color: #e9ecef;">
                                                    @if (!empty($content['logo_path']))
                                                        Current: {{ basename($content['logo_path']) }}
                                                    @else
                                                        No file chosen
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Header Text -->
                                <div class="col-md-6">
                                    <div class="form-group mb-6">
                                        <label for="title_nav">Title</label>
                                        <textarea name="title_nav" 
                                                id="title-nav-editor" 
                                                class="form-control" 
                                                rows="3">{!! $content['title_nav'] ?? '' !!}</textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                            <h1 class="mt-3">Home</h1>
                                <!-- Home Title -->
                                <div class="col-md-6 mt-3">
                                    <div class="form-group mb-3">
                                        <label for="title_home">Title</label>
                                        <input type="text" name="title_home" id="title-header-editor"  class="form-control" value="{{ $content['title_home'] ?? '' }}" placeholder="Enter title">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <!-- Home Header -->
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="headline">Headline</label>
                                        <textarea name="headline" 
                                                id="headline-editor" 
                                                class="form-control" 
                                                rows="3">{!! $content['headline'] ?? '' !!}</textarea>
                                    </div>
                                </div>
                                <!-- Home Subheader -->
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="subtext">Subtext</label>
                                        <textarea name="subtext" 
                                                id="subtext-editor" 
                                                class="form-control" 
                                                rows="3">{!! $content['subtext'] ?? '' !!}</textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <h1>Footer</h1>
                                <div class="row">
                                    <!-- Footer Logo -->
                                        <div class="col-md-12">
                                            <div class="form-group mb-3">
                                                <label for="logo_footer">Footer Logo</label>
                                                <div class="preview-wrapper">
                                                    <div class="preview-container mt-2 mb-2">
                                                        @if (!empty($content['logo_footer_path']))
                                                            <img src="{{ Storage::url($content['logo_footer_path']) }}" alt="Footer Logo" style="max-height: 100px;">
                                                        @endif
                                                    </div>
                                                    <div class="d-flex">
                                                        <div class="input-group flex-grow-1">
                                                            <input type="file" name="logo_footer" class="form-control" onchange="previewImage(this)">
                                                            <div class="form-control" style="background-color: #e9ecef;">
                                                                @if (!empty($content['logo_footer_path']))
                                                                    Current: {{ basename($content['logo_footer_path']) }}
                                                                @else
                                                                    No file chosen
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                </div>
                                <div class="row mb-3">
                                <!-- Address -->
                                    <div class="col-md-6">
                                        <div class="form-group mb-6">
                                            <label for="title_footer">Title</label>
                                            <textarea name="title_footer" 
                                                    id="title-footer-editor" 
                                                    class="form-control" 
                                                    rows="3">{!! $content['title_footer'] ?? '' !!}</textarea>
                                        </div>
                                    </div>
                                    <div class="row mt-4">
                                    <!-- Address -->
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="address">Address</label>
                                            <input type="text" name="address" class="form-control" value="{{ $content['address'] ?? '' }}" placeholder="Enter address">
                                        </div>
                                    </div>
                                    <!-- Number 1 -->
                                    <div class="col-md-3">
                                        <div class="form-group mb-3">
                                            <label for="number1">Number 1</label>
                                            <input type="text" name="number1" class="form-control" value="{{ $content['number1'] ?? '' }}" placeholder="Enter number">
                                        </div>
                                    </div>
                                    <!-- Number 2-->
                                    <div class="col-md-3">
                                        <div class="form-group mb-3">
                                            <label for="number2">Number 2</label>
                                            <input type="text" name="number2" class="form-control" value="{{ $content['number2'] ?? '' }}" placeholder="Enter number">
                                        </div>
                                    </div>
                                </div>
                                </div>


                            </div>
                            <!-- End of row -->

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

<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.0/tinymce.min.js" referrerpolicy="origin"></script>

<script>
function previewImage(input) {
    const file = input.files[0];
    if (file) {
        const reader = new FileReader();
        const previewContainer = input.closest('.preview-wrapper').querySelector('.preview-container');
        const filenameDiv = input.nextElementSibling;
        
        // Update filename display
        if (filenameDiv) {
            filenameDiv.textContent = 'Selected: ' + file.name;
        }
        
        // Create and display image preview
        reader.onload = function(e) {
            previewContainer.innerHTML = `
                <img src="${e.target.result}" alt="Preview" style="max-height: 100px; margin-top: 10px;">
            `;
        }
        
        reader.readAsDataURL(file);
    }
}

// Function to initialize preview for existing images
function initializeExistingPreviews() {
    const fileInputs = document.querySelectorAll('input[type="file"]');
    fileInputs.forEach(input => {
        const previewContainer = input.closest('.preview-wrapper')?.querySelector('.preview-container');
        const currentImage = previewContainer?.querySelector('img');
        
        if (currentImage) {
            const filename = currentImage.src.split('/').pop();
            const filenameDiv = input.nextElementSibling;
            if (filenameDiv) {
                filenameDiv.textContent = 'Current: ' + decodeURIComponent(filename);
            }
        }
    });
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    initializeExistingPreviews();
});
</script>

<script>
// Configuration for both editors
const editorConfig = {
    menubar: false,
    plugins: [
        'advlist', 'autolink', 'lists', 'link', 'charmap', 'preview',
        'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
        'insertdatetime', 'table', 'code', 'help', 'wordcount'
    ],
    toolbar: 'undo redo | formatselect | ' +
        'bold italic | forecolor backcolor | alignleft aligncenter ' +
        'alignright alignjustify | bullist numlist | ' +
        'removeformat',
    content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; font-size: 14px; }',
    color_map: [
        '000000', 'Black',
        'FFFFFF', 'White',
        'A020F0', 'Purple',
        '00FF00', 'Green',
        '0000FF', 'Blue',
        'FFFF00', 'Yellow',
        'FF0000', 'Red',
        'FF8000', 'Orange'
    ]
};

// Initialize both editors
tinymce.init({
    ...editorConfig,
    selector: '#headline-editor',
    height: 300,
});

tinymce.init({
    ...editorConfig,
    selector: '#subtext-editor',
    height: 300,
});

tinymce.init({
    ...editorConfig,
    selector: '#title-nav-editor',
    height: 120,
});

tinymce.init({
    ...editorConfig,
    selector: '#title-header-editor',
    height: 120,
});

tinymce.init({
    ...editorConfig,
    selector: '#title-footer-editor',
    height: 120,
});


// Update form submission to handle both editors
document.querySelector('form').addEventListener('submit', function(e) {
    const subtextContent = tinymce.get('subtext-editor').getContent();
    const headlineContent = tinymce.get('headline-editor').getContent();
    const titleNavContent = tinymce.get('title-nav-editor').getContent();
    const titleHeaderContent = tinymce.get('title-header-editor').getContent();
    const titleFooterContent = tinymce.get('title-footer-editor').getContent();
    const footerContent = tinymce.get('footer-content-editor').getContent();
    
    document.querySelector('#subtext-editor').value = subtextContent;
    document.querySelector('#headline-editor').value = headlineContent;
    document.querySelector('#title-nav-editor').value = titleNavContent;
    document.querySelector('#title-header-editor').value = titleHeaderContent;
    document.querySelector('#title-footer-editor').value = titleFooterContent;
    document.querySelector('#footer-content-editor').value = footerContent;
});
</script>

@endsection
