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
            <div class="col-md-4 text-center">
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
            </style>

        </div>
    </div>
</footer>
