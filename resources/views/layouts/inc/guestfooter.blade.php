@php
    use Illuminate\Support\Facades\Storage;
@endphp

<footer style="background-color: #000; padding: 7px;">
    <div class="container">
        <div class="row align-items-center">
            <!-- Logo and Title Section -->
            <div class="col-md-4 text-center text-md-start d-flex flex-column align-items-center">
                @if (!empty($content['logo_path']))
                    <img src="{{ Storage::url($content['logo_footer_path']) }}" alt="Logo" class="me-2" style="height: 70px; width: auto;">
                @endif
                <h5 style="margin: 0; font-weight: bold; color: white; text-align: center;">
                {!! $content['title_footer'] !!}
                </h5>
            </div>

            <!-- Contact Information Section -->
            <div class="col-md-4 text-center mt-3">
            <p style="margin: 0; font-weight: bold; color: white;">
                    <i class="bi bi-geo-alt-fill" style="color: darkorange;"></i> {{$content['address']}}
                </p>
                <p style="margin: 5px 0; font-weight: bold; color: white;">
                    <i class="bi bi-telephone-fill" style="color: darkorange;"></i> {{$content['number1']}} &nbsp;&nbsp;
                    <i class="bi bi-telephone-fill" style="color: darkorange;"></i> {{$content['number2']}}
                </p>
                <p style="margin: 5px 0; font-weight: bold; color: white;">
                    <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal" style="color: white; text-decoration: underline;">Terms & Conditions</a>
                </p>
                <p style="margin-top: 5px; font-size: 14px; font-weight: bold; color: white;">
                    &copy; {{ date('Y') }} Kyla and Kyle Catering Services. All Rights Reserved.
                </p>
            </div>

            <!-- Social Media Links Section -->
            <div class="col-md-4 text-center">
                <div class="social-icons d-flex justify-content-center">
                    <a href="https://facebook.com/KylaandKyleCS" style="color: darkorange; margin-right: 10px; text-decoration: none;">
                        <i class="bx bxl-facebook-circle" style="font-size: 50px;"></i>
                    </a>
                    <a href="mailto:kylaandkylecs@gmail.com" style="color: darkorange; text-decoration: none;">
                        <i class="bx bxl-gmail" style="font-size: 50px;"></i>
                    </a>
                </div>
            </div>
        </div>
    
<!-- Modal -->
<div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-center w-100">
                <!-- Modal Title centered -->
                <h4 class="modal-title" id="termsModalLabel"><strong>Terms and Conditions Policy</strong></h4>
            </div>
            <div class="modal-body" id="termsModalBody" style="max-height: 750px; overflow-y: auto;">
                
            {!! $content['terms'] !!}
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-darkorange" data-bs-dismiss="modal" id="modalDoneBtn" disabled>Done</button>
            </div>
        </div>
    </div>
</div>



    <!-- JavaScript for enabling Done button when checkbox is checked -->
    <script>
       // JavaScript for enabling Done button when the user scrolls to the bottom of the modal
document.addEventListener('DOMContentLoaded', function () {
    // Select the specific modal's body and Done button
    const modalBody = document.getElementById('termsModalBody');
    const doneButton = document.getElementById('modalDoneBtn');

    // Function to enable the Done button when the user scrolls to the bottom
    modalBody.addEventListener('scroll', () => {
        // Check if the user has scrolled to the bottom of the modal
        const scrollHeight = modalBody.scrollHeight; // Total height of the content
        const scrollTop = modalBody.scrollTop; // How much is scrolled from the top
        const clientHeight = modalBody.clientHeight; // Visible height of the modal

        if (scrollTop + clientHeight >= scrollHeight - 5) { // Allowing a small margin
            // Enable Done button
            doneButton.disabled = false;
            doneButton.style.backgroundColor = '#ff5722';  // Dark Orange
            doneButton.style.color = 'white';
        } else {
            // Disable Done button
            doneButton.disabled = true;
            doneButton.style.backgroundColor = '';  // Reset to default color
            doneButton.style.color = '';
        }
    });
});

    </script>

    <style>
/* Ensure that paragraphs and headers are always aligned to the left */
.modal-body p,
.modal-body h5,
.modal-body h6,
.modal-body h4 {
    text-align: left; /* Align text to the left */
    margin-left: 0; /* Remove any unwanted margin */
    margin-right: 0; /* Remove any unwanted margin */
    padding-left: 0; /* Remove any unwanted padding */
}

/* Ensure the modal content itself does not shift or change positions */
.modal .modal-content {
    background-color: white !important; /* Ensure the modal content has a solid white background */
    opacity: 1 !important; /* Force the opacity to be 1 */
}

/* Set proper text alignment for modal content */
.modal-body {
    text-align: left; /* Align text in the modal body to the left */
    margin: 0; /* Ensure no additional margin is applied */
}

/* Make sure modal headers (h4, h5, h6) are left-aligned */
.modal-body h4,
.modal-body h5,
.modal-body h6 {
    text-align: left; /* Align headers to the left */
    margin-left: 0;
    padding-left: 0;
}

/* Optional: Remove any extra margin or padding from modal paragraphs */
.modal-body p {
    margin-left: 0;
    padding-left: 0;
}

/* Ensure the modal overlay doesn't affect text position */
.modal .modal-content {
    background-color: white !important;
    opacity: 1 !important;
}

/* Optional: Set a more solid background for modal backdrop */
.modal-backdrop {
    background-color: rgba(128, 128, 128, 0.5); /* Darker grey with 50% opacity */
    z-index: 1040 !important; /* Ensure it's behind the modal */
}

/* Style adjustments for larger screens */
@media (min-width: 768px) {
    .col-md-4 {
        display: flex;
        flex-direction: column;
        align-items: center;
    }
}

/* Style adjustments for mobile screens */
@media (max-width: 768px) {
    .social-icons {
        gap: 10px;
    }

    .social-icons a {
        font-size: 50px;
        color: darkorange;
        text-decoration: none;
    }
}

/* Modal styling */
.modal .modal-content {
    background-color: white !important;
    opacity: 1 !important;
}

.modal-backdrop {
    background-color: rgba(128, 128, 128, 0.5); /* Darker grey with 50% opacity */
    z-index: 1040 !important;
}

/* Optional: Set a more solid background for modal backdrop */
.modal-body a {
    color: #ff5722;
    text-decoration: none;
}

.modal-body a:hover {
    text-decoration: underline;
}


    </style>

</footer>
