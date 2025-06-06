@extends('layouts.guestlayout')

@section('content')

<div class="container">
<div class="row mt-4">
    <!-- Left Column: Contact Us title and description -->
    <div class="col-md-6">
    <div style="display: flex; flex-direction: column; text-align: left; padding-top: 100px;">
        <h1>Contact Us</h1>
        <p style="font-size: 25px; line-height: 1.6;">Have questions or need a quote? We’re here to assist you! Reach out to us, and our team will guide you through everything, from menu selection to event planning.</p>
    </div>
</div>

    <!-- Right Column: Contact Methods List -->
    <div class="col-md-6">
        <h3 style="padding-top: 100px;">You can contact us through the following methods:</h3>
        <ul class="list-unstyled" style="padding-top: 25px;">
            <li><strong>Phone:</strong> Call us to start our conversation.</li>
            <li><strong>Email:</strong> For inquiries, kindly direct to our Email Account.</li>
            <li><strong>Form:</strong> Fill out the form below, and we’ll get back to you ASAP.</li>
            <li><strong>Visit Us:</strong> Stop by our location.</li>
        </ul>
    </div>
</div>


    <div class="row mt-4">
        <div class="col-md-6">
            <h2>Contact Form</h2>
            <form action="{{ route('contactus.send') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="name" style="color: solid black; font-family: 'Arial'; font-weight: bolder;" class="form-label">Name</label>  
                    <input type="text" class="form-control" style="border: 3px solid darkorange;" id="name" name="name" placeholder="Enter your Full Name">
                    @if ($errors->has('name'))
                        <small class="text-danger">{{ $errors->first('name') }}</small>
                    @endif
                </div>
                <div class="mb-3">
                    <label for="phone" style="color: solid black; font-family: 'Arial'; font-weight: bolder;" class="form-label">Phone Number</label>
                    <input type="phone" class="form-control"  style="border: 3px solid darkorange;" id="phone" name="phone" placeholder="Enter your Contact Number">
                    @if ($errors->has('phone'))
                        <small class="text-danger">{{ $errors->first('phone') }}</small>
                    @endif
                </div>
                <div class="mb-3">
                    <label for="email" style="color: solid black; font-family: 'Arial'; font-weight: bolder;" class="form-label">Email</label>
                    <input type="email" class="form-control"  style="border: 3px solid darkorange;" id="email" name="email" placeholder="Enter your Email">
                    @if ($errors->has('email'))
                        <small class="text-danger">{{ $errors->first('email') }}</small>
                    @endif
                </div>
                <div class="mb-3">
                    <label for="message" style="color: solid black; font-family: 'Arial'; font-weight: bolder;" class="form-label" >Message</label>
                    <textarea class="form-control"  style="border: 3px solid darkorange;" id="message" name="message" rows="4" placeholder="Enter your Message"></textarea>
                    @if ($errors->has('message'))
                        <small class="text-danger">{{ $errors->first('message') }}</small>
                    @endif
                </div>
                <button type="submit" class="btn btn-darkorange">Send Message</button>
            </form>
        </div>
        
        <div class="col-md-6" style="padding-top: 5px;">
            <h2>Find Us Here</h2>
            <div class="map-container">
                <iframe 
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3858.6241387904693!2d121.1428500759106!3d14.733829685768413!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397bb3faab936f5%3A0x42fb7eff3ec2cca3!2sKyla%20%26%20Kyle%20Catering%20Services!5e0!3m2!1sen!2sph!4v1730787129879!5m2!1sen!2sph" 
                    width="100%" 
                    height="405" 
                    allowfullscreen="" 
                    loading="lazy" 
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
        </div>
    </div>


                    <style>
                    /* Map container style with dark orange border */
                    .map-container {
                        border: 3px solid darkorange; /* Dark orange border */
                        border-radius: 5px; /* Optional: rounded corners */
                        overflow: hidden; /* Clips any overflow from the iframe */
                    }
                    </style>


                        <style>
                          
                            /* Responsive styling for mobile */
                            @media (max-width: 768px) {
                                div[style*="display: flex; justify-content: center; gap: 50px;"] {
                                    flex-direction: column;
                                    align-items: center;
                                    gap: 0; /* Removes the gap between items */
                                }

                                /* Adjust gap and alignment for mobile view */
                                div[style*="display: flex; justify-content: center; gap: 50px;"] > div {
                                    text-align: center;
                                    min-width: 100%;
                                    margin-bottom: 0; /* Removes bottom margin to avoid extra space */
                                }
                            }

                            /* Ensure consistent font style for all items */
                            ul li {
                                font-family: inherit; /* Use the same inherited font for consistency */
                                font-size: inherit;   /* Inherit font size to keep it consistent */
                                font-weight: normal;  /* Remove any bold styling unless specified in <strong> */
                            }
                        </style>

                    <p style="text-align: center; font-weight: bold; font-size: 20px; padding-top: 15px;">We look forward to hearing from you and making your next event unforgettable!</p>

                </div>

            </div>
        </div>
    </div>
</div>

@endsection
