@extends('layouts.adminlayout')

@php
use Illuminate\Support\Facades\Storage;
@endphp

@section('content')

<style>
/* Add some spacing and limit button width */
button.btn-success {
    margin: 10px 0;
    width: auto; /* Adjust to fit content */
}

.preview-wrapper {
    margin-top: 10px;
}

.team-image-container img {
    display: block;
    max-height: 100px;
    margin: 10px 0;
}
</style>


<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0" style="padding-top: 30px;">About Content</h1>
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
                        <form action="{{ route('admin.about.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <!-- Header Text -->
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="mission">Our Mission</label>
                                        <textarea name="mission" 
                                                id="mission-editor" 
                                                class="form-control" 
                                                rows="3">{!! $content['mission'] ?? '' !!}</textarea>
                                    </div>
                                </div>
                                <!-- Header Text -->
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="vision">Our Vision</label>
                                        <textarea name="vision" 
                                                id="vision-editor" 
                                                class="form-control" 
                                                rows="3">{!! $content['vision'] ?? '' !!}</textarea>
                                    </div>
                                </div>
                            </div>             
                            <div class="row">
                                <!-- Header Text -->
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="about">About Us</label>
                                        <textarea name="about" 
                                                id="about-editor" 
                                                class="form-control" 
                                                rows="3">{!! $content['about'] ?? '' !!}</textarea>
                                    </div>
                                </div>
                                <!-- Header Text -->
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="history">Our History</label>
                                        <textarea name="history" 
                                                id="history-editor" 
                                                class="form-control" 
                                                rows="3">{!! $content['history'] ?? '' !!}</textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Our Values Section -->
                            <h3>Our Values</h3>
                            <div class="row">
                                @php
                                    $defaultTitles = [
                                        'Quality First',
                                        'Customer Care',
                                        'Honesty & Openness',
                                        'Community Connection',
                                        'Visible Progress'
                                    ];
                                @endphp
                                
                                @for($i = 0; $i < 5; $i++)
                                <div class="col-md-4 mb-4">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="form-group">
                                                <label>Value Title {{ $i + 1 }}</label>
                                                <input type="text" 
                                                    name="values_titles[]" 
                                                    class="form-control mb-3"
                                                    value="{{ $content['values'][$i]['title'] ?? $defaultTitles[$i] }}" 
                                                    required>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label>Value Image {{ $i + 1 }}</label>
                                                <div class="preview-wrapper">
                                                    <input type="file" 
                                                        name="values_images[]" 
                                                        class="form-control"
                                                        accept="image/*"
                                                        onchange="previewImage(this)">
                                                    <div class="preview-container">
                                                        @if(isset($content['values'][$i]['image_path']))
                                                            <img src="{{ asset('storage/' . $content['values'][$i]['image_path']) }}" 
                                                                alt="Current Value Image" 
                                                                style="max-height: 100px; margin-top: 10px;">
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endfor
                            </div>

                            <!-- Meet Our Team Section -->
                            <h3 class="mt-4">Meet Our Team</h3>
                            <div class="row mb-3">
                                <!-- Add Team Member Button -->
                                <div class="col-12 text-end">
                                    <button type="button" class="btn btn-success btn-sm" onclick="addTeamImage()">
                                        Add Team Member Image
                                    </button>
                                </div>
                            </div>

                            <div class="row" id="team-container">
                                <!-- Static Input Placeholder -->
                                <div class="col-md-4 mb-4 team-image-container">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="form-group">
                                                <label>Team Member Image 1</label>
                                                <div class="preview-wrapper">
                                                    <input type="file" 
                                                        name="team_images[]" 
                                                        class="form-control"
                                                        accept="image/*"
                                                        onchange="previewImage(this)">
                                                    <div class="preview-container"></div>
                                                </div>
                                            </div>
                                            <button type="button" class="btn btn-danger mt-2" onclick="removeTeamImage(this)">Remove</button>
                                        </div>
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

<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.0/tinymce.min.js" referrerpolicy="origin"></script>

<script>
function addTeamImage() {
    const container = document.getElementById('team-container');
    const imageCount = container.children.length;
    
    const newImageHtml = `
        <div class="col-md-4 mb-4 team-image-container">
            <div class="card">
                <div class="card-body">
                    <div class="form-group">
                        <label>Team Member Image ${imageCount + 1}</label>
                        <div class="preview-wrapper">
                            <input type="file" 
                                   name="team_images[]" 
                                   class="form-control"
                                   accept="image/*"
                                   onchange="previewImage(this)">
                            <div class="preview-container"></div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-danger mt-2" onclick="removeTeamImage(this)">Remove</button>
                </div>
            </div>
        </div>
    `;
    
    container.insertAdjacentHTML('beforeend', newImageHtml);
}

function removeTeamImage(button) {
    button.closest('.team-image-container').remove();
}
</script>

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
    selector: '#mission-editor',
    height: 300,
});

tinymce.init({
    ...editorConfig,
    selector: '#vision-editor',
    height: 300,
});

tinymce.init({
    ...editorConfig,
    selector: '#about-editor',
    height: 300,
});

tinymce.init({
    ...editorConfig,
    selector: '#history-editor',
    height: 300,
});


document.querySelector('form').addEventListener('submit', function(e) {
    const missionContent = tinymce.get('mission-editor').getContent();
    const visionContent = tinymce.get('vision-editor').getContent();
    const aboutContent = tinymce.get('about-editor').getContent();
    const historyContent = tinymce.get('history-editor').getContent();

    document.querySelector('#mission-editor').value = missionContent;
    document.querySelector('#vision-editor').value = visionContent;
    document.querySelector('#about-editor').value = aboutContent;
    document.querySelector('#history-editor').value = historyContent;
});
</script>

@endsection
