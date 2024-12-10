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
                <h5>PLEASE READ THE FOLLOWING:</h5>
                <h6><strong>1. Services Provided:</strong></h6>
                <p>The caterer agrees to provide catering services for the event specified in the contract. Services include menu planning, food preparation, setup, service during the event, and cleanup.</p>
                
                <h6><strong>2. Duration of Services:</strong></h6>
                <p>The standard duration of catering services provided by the Caterer is up to 5 hours from the scheduled start time of the event. If the Client wishes to extend the duration, an additional service charge will apply.</p>
                
                <h6><strong>3. Payment and Cancellation:</strong></h6>
                <p>3.1 The Client agrees to pay the agreed-upon total amount as outlined in the catering contract. This amount includes the base catering package and any additional services requested by the Client.</p>
                <p>3.2 A non-refundable deposit of ₱5,000.00 of the total contract amount is due upon confirming this agreement, to be paid within 3 days before the event. The remaining balance must be paid according to the following schedule:</p>
                <ul>
                    <li>₱5,000.00 Reservation</li>
                    <li>50% Down Payment (1 week prior to the event)</li>
                    <li>Remaining balance (The day of the event)</li>
                </ul>

                <h6><strong>4. Pencil Booking Policy:</strong></h6>
                <p>Pencil bookings are held for a maximum duration of 1 week. Clients who provide proof of payment will be prioritized over pencil bookings. If another client secures the date with payment, the pencil booking may be released.</p>
                
                <h6><strong>5. Rescheduling and Cancellation:</strong></h6>
                <p>5.1 Rescheduling: If the event is rescheduled 3 days or fewer prior to the event date, the reservation fee of ₱5,000.00 will be non-refundable for events with fewer than 500 attendees. For events with 500 or more attendees, 5% of the total package amount will be deducted from the deposit as a rescheduling fee.</p>
                <p>5.2 Cancellation: If cancellation occurs 3 days or fewer before the event, the Client agrees to pay 10% of the total contract amount to cover preparations made by the Caterer.</p>
                <p>5.3 Exemptions: The above rescheduling and cancellation fees will be waived in cases of severe weather conditions (e.g., typhoon), death, or other extraordinary circumstances.</p>
                
                <h6><strong>6. Liability:</strong></h6>
                <p>The Caterer shall not be held liable for any damages or injuries to persons or property that may occur during the event, except in cases of gross negligence or intentional misconduct.</p>
                
                <h6><strong>7. Privacy Policy:</strong></h6>
                <p>Kyla and Kyle Catering Services value your privacy. All personal information collected is used solely for business purposes, including event planning, communication, and service improvements. We ensure that your information is kept confidential and secure, and it will not be shared with third parties except as required by law or with your permission. By providing your information, you consent to its use for these purposes. If you have any questions or wish to update or remove your information, please contact us directly.</p>
                
                <h6><strong>8. Governing Law:</strong></h6>
                <p>This Agreement shall be governed by and construed in accordance with the laws of the Republic of the Philippines.</p>

                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="modalDoneBtn" disabled>Done</button>
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
