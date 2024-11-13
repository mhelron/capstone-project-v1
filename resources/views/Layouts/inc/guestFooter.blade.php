<footer style="background-color: #000; padding: 7px;">
    <div class="container">
        <div class="row align-items-center">
            <!-- Logo and Title Section -->
            <div class="col-md-4 text-center text-md-start d-flex flex-column align-items-center">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" style="width: 100px; height: auto; margin-bottom: 10px;">
                <h5 style="margin: 0; font-weight: bold; color: white; text-align: center;">
                    <span style="color: darkorange;">Kyla</span> and <span style="color: red;">Kyle</span>
                </h5>
            </div>

            <!-- Contact Information Section -->
            <div class="col-md-4 text-center mt-3">
                <p style="margin: 0; font-weight: bold; color: white;">
                    <i class="bi bi-geo-alt-fill" style="color: darkorange;"></i> 428 Cacho St. Brgy Balite Rodriguez Rizal
                </p>
                <p style="margin: 5px 0; font-weight: bold; color: white;">
                    <i class="bi bi-telephone-fill" style="color: darkorange;"></i> 0917-82-1971 &nbsp;&nbsp;
                    <i class="bi bi-telephone-fill" style="color: darkorange;"></i> 0945-413-0258
                </p>
                <p style="margin-top: 5px; font-size: 14px; font-weight: bold; color: white;">
                    &copy; {{ date('Y') }} Kyla and Kyle Catering Services. All Rights Reserved.
                </p>
                <p style="margin: 5px 0; font-weight: bold; color: white;">
                    <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal" style="color: white; text-decoration: underline;">Terms & Conditions</a>
                </p>
            </div>

            <!-- Social Media Links Section -->
            <div class="col-md-4 text-center">
                <div class="social-icons d-flex justify-content-center">
                    <a href="https://facebook.com/KylaandKyleCS" style="color: darkorange; margin-right: 10px; text-decoration: none;">
                        <i class="bx bxl-facebook-circle" style="font-size: 50px;"></i>
                    </a>
                    <a href="mailto:mc_aguirre12@yahoo.com.ph" style="color: darkorange; text-decoration: none;">
                        <i class="bx bxl-gmail" style="font-size: 50px;"></i>
                    </a>
                </div>
            </div>
        </div>
    
    <!-- Modal -->
     
            <div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="termsModalLabel">Terms and Conditions Policy</h5>
                </div>
                <div class="modal-body" style="max-height: 400px; overflow-y: auto;">
                    <h4> PLEASE READ THE FOLLOWING: </h4>
                    <h6>1. Services Provided:</h6>
                    <p>The caterer agrees to provide catering services for the event specified in the contract. Services include menu planning, food preparation, setup, service during the event, and cleanup.</p>
                    
                    <h6>2. Duration of Services:</h6>
                    <p>The standard duration of catering services provided by the Caterer is up to 5 hours from the scheduled start time of the event. If the Client wishes to extend the duration, an additional service charge will apply.</p>
                    
                    <h6>3. Payment and Cancellation:</h6>
                    <p>3.1 The Client agrees to pay the agreed-upon total amount as outlined in the catering contract. This amount includes the base catering package and any additional services requested by the Client.</p>
                    <p>3.2 A non-refundable deposit of ₱5,000.00 of the total contract amount is due upon confirming this agreement, to be paid within 3 days before the event. The remaining balance must be paid according to the following schedule:</p>
                    <ul>
                        <li>₱5,000.00 Reservation</li>
                        <li>50% Down Payment (1 week prior to the event)</li>
                        <li>Remaining balance (The day of the event)</li>
                    </ul>

                    <h6>4. Pencil Booking Policy:</h6>
                    <p>Pencil bookings are held for a maximum duration of 1 week. Clients who provide proof of payment will be prioritized over pencil bookings. If another client secures the date with payment, the pencil booking may be released.</p>
                    
                    <h6>5. Rescheduling and Cancellation:</h6>
                    <p>5.1 Rescheduling: If the event is rescheduled 3 days or fewer prior to the event date, the reservation fee of ₱5,000.00 will be non-refundable for events with fewer than 500 attendees. For events with 500 or more attendees, 5% of the total package amount will be deducted from the deposit as a rescheduling fee.</p>
                    <p>5.2 Cancellation: If cancellation occurs 3 days or fewer before the event, the Client agrees to pay 10% of the total contract amount to cover preparations made by the Caterer.</p>
                    <p>5.3 Exemptions: The above rescheduling and cancellation fees will be waived in cases of severe weather conditions (e.g., typhoon), death, or other extraordinary circumstances.</p>
                    
                    <h6>6. Liability:</h6>
                    <p>The Caterer shall not be held liable for any damages or injuries to persons or property that may occur during the event, except in cases of gross negligence or intentional misconduct.</p>
                    
                    <h6>7. Privacy Policy:</h6>
                    <p>Kyla and Kyle Catering Services value your privacy. All personal information collected is used solely for business purposes, including event planning, communication, and service improvements. We ensure that your information is kept confidential and secure, and it will not be shared with third parties except as required by law or with your permission. By providing your information, you consent to its use for these purposes. If you have any questions or wish to update or remove your information, please contact us directly.</p>
                    
                    <h6>8. Governing Law:</h6>
                    <p>This Agreement shall be governed by and construed in accordance with the laws of the Republic of the Philippines.</p>
                    
            <!-- Agreement Checkbox -->
            <div class="mt-3">
                    <input type="checkbox" id="agreeCheckboxModal">
                    <label for="agreeCheckboxModal" class="text-muted"> I have read and agree to the Terms and Conditions.</label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="modalDoneBtn" disabled>Done</button>
            </div>
        </div>
    </div>
</div>

        <!-- JavaScript for enabling Done button when checkbox is checked -->
            <script>
                // Get the checkbox and button elements
                const agreeCheckboxModal = document.getElementById('agreeCheckboxModal');
                const doneButton = document.getElementById('modalDoneBtn');

                // Function to enable/disable Done button based on checkbox state
                agreeCheckboxModal.addEventListener('change', () => {
                    if (agreeCheckboxModal.checked) {
                        // Enable Done button
                        doneButton.disabled = false;
                        doneButton.style.backgroundColor = '#ff5722';  // Dark Orange
                        doneButton.style.color = 'white';
                    } else {
                        // Disable Done button and reset color
                        doneButton.disabled = true;
                        doneButton.style.backgroundColor = '';  // Reset to default color
                        doneButton.style.color = '';
                    }
                });
            </script>
                
                <style>
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
                
                 /* Style for the clickable Terms and Conditions link */
    .modal-body a {
        color: #ff5722;
        text-decoration: none;
    }

    .modal-body a:hover {
        text-decoration: underline;
    }
    
         </style>

     
    
</footer>
