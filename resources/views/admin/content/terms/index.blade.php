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
                <h1 class="m-0" style="padding-top: 30px;">Terms and Conditions</h1>
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
                        <form action="{{ route('admin.terms.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf         
                            <!-- Start of row -->
                            <div class="row mb-3">
                                <!-- Header Text -->
                                <div class="col-md-12">
                                    <div class="form-group mb-6">
                                        <label for="terms">Add/Edit</label>
                                        <textarea name="terms" 
                                                id="terms-editor" 
                                                class="form-control" 
                                                rows="3">{!! $content['terms'] ?? '' !!}</textarea>
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
const editorConfig = {
    menubar: false,
    plugins: [
        'advlist', 'autolink', 'lists', 'link', 'charmap', 'preview',
        'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
        'insertdatetime', 'table', 'indent', 'outdent', 'code', 'help', 'wordcount'
    ],
    toolbar: 'undo redo | formatselect | fontsize | indent outdent | ' +
        'bold italic | forecolor backcolor | alignleft aligncenter ' +
        'alignright alignjustify | bullist numlist | ' +
        'removeformat',
    fontsize_formats: '8pt 10pt 12pt 14pt 18pt 24pt 36pt', // Font sizes you want to appear in the dropdown
    content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif; font-size: 14px; }',
    color_map: [
        '000000', 'Black',
        'FFFFFF', 'White',
        'A020F0', 'Purple',
        '00FF00', 'Green',
        '0000FF', 'Blue',
        'FFFF00', 'Yellow',
        'FF0000', 'Red',
        'FF8000', 'Orange'
    ],
};

// Initialize the editor
tinymce.init({
    ...editorConfig,
    selector: '#terms-editor',
    height: 550,
});

// Ensure the form handles editor content
document.querySelector('form').addEventListener('submit', function(e) {
    const subtextContent = tinymce.get('terms-editor').getContent();
    document.querySelector('#terms-editor').value = subtextContent;
});

</script>

@endsection
