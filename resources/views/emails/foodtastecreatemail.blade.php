<!DOCTYPE html>
<html>
<head>
    <title>Food Tasting Request Confirmation</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <h2>Food Tasting Request Confirmation</h2>
        
        <p>Dear {{ $firstname }} {{ $lastname }},</p>
        
        <p>Thank you for submitting your food tasting request. Here are the details of your request:</p>
        
        <div style="background-color: #f9f9f9; padding: 15px; margin: 20px 0;">
            <p><strong>Reference Number:</strong> {{ $reference_number }}</p>
            <p><strong>Status:</strong> {{ ucfirst($status) }}</p>
            <p><strong>Preferred Date:</strong> {{ date('F j, Y', strtotime($preferred_date)) }}</p>
            <p><strong>Preferred Time:</strong> {{ $preferred_time }}</p>
            <p><strong>Contact Information:</strong></p>
            <ul style="list-style: none; padding-left: 20px;">
                <li>Phone: {{ $phone }}</li>
                <li>Email: {{ $email }}</li>
            </ul>
            
            @if($delivery_option === 'delivery')
            <p><strong>Delivery Address:</strong></p>
            <ul style="list-style: none; padding-left: 20px;">
                <li>{{ $street_houseno }}</li>
                <li>{{ $barangay }}, {{ $city }}</li>
                <li>{{ $province }}, {{ $region }}</li>
            </ul>
            @else
            <p><strong>Delivery Option:</strong> Pickup</p>
            @endif
        </div>

        <p>We will review your request and contact you shortly to confirm the schedule of your food tasting session.</p>
        
        <p>Please keep your reference number handy for future communications.</p>
        
        <p>Best regards,<br>Your Catering Team</p>
    </div>
</body>
</html>