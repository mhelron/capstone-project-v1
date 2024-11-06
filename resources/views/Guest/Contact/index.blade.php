@extends('layouts.guestLayout')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>Contact Us</h1>
            <p>If you have any questions, comments, or would like to request a quote, please don't hesitate to reach out to us. We look forward to hearing from you!</p>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-6">
            <h2>Contact Form</h2>
            <form action="#" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Enter your full name">
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email">
                </div>
                <div class="mb-3">
                    <label for="message" class="form-label">Message</label>
                    <textarea class="form-control" id="message" name="message" rows="4" placeholder="Enter your message"></textarea>
                </div>
                <button type="submit" class="btn btn-darkorange">Send Message</button>
            </form>
        </div>
        
        <div class="col-md-6">
            <h2>Find Us Here</h2>
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3858.6241387904693!2d121.1428500759106!3d14.733829685768413!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397bb3faab936f5%3A0x42fb7eff3ec2cca3!2sKyla%20%26%20Kyle%20Catering%20Services!5e0!3m2!1sen!2sph!4v1730787129879!5m2!1sen!2sph" width="100%" height="300" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
    </div>
</div>

@endsection
