<!DOCTYPE html>
<html>
<head>
    <title>Quotation Request Confirmation</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <h2>Quotation Request Confirmation</h2>
        
        <p>Dear {{ $quotationData['firstname'] }} {{ $quotationData['lastname'] }},</p>
        
        <p>Thank you for submitting your quotation request. Here are the details of your request:</p>
        
        <div style="background-color: #f9f9f9; padding: 15px; margin: 20px 0;">
            <p><strong>Reference Number:</strong> {{ $quotationData['reference_number'] }}</p>
            <p><strong>Status:</strong> {{ ucfirst($quotationData['status']) }}</p>
            
            <p><strong>Event Details:</strong></p>
            <ul style="list-style: none; padding-left: 20px;">
                <li>Event Title: {{ $quotationData['event_title'] }}</li>
                <li>Date: {{ date('F j, Y', strtotime($quotationData['event_date'])) }}</li>
                <li>Time: {{ $quotationData['event_time'] }}</li>
                <li>Venue: {{ $quotationData['venue'] }}</li>
                <li>Theme: {{ $quotationData['theme'] }}</li>
                <li>Number of Guests: {{ $quotationData['guest_count'] }}</li>
            </ul>

            <p><strong>Package Details:</strong></p>
            <ul style="list-style: none; padding-left: 20px;">
                <li>Package Name: {{ $quotationData['package'] }}</li>
                <li>Menu Name: {{ $quotationData['menu_name'] }}</li>
            </ul>
            
            <p><strong>Contact Information:</strong></p>
            <ul style="list-style: none; padding-left: 20px;">
                <li>Phone: {{ $quotationData['phone'] }}</li>
                <li>Email: {{ $quotationData['email'] }}</li>
            </ul>
            
            <p><strong>Complete Address:</strong></p>
            <ul style="list-style: none; padding-left: 20px;">
                <li>{{ $quotationData['street_houseno'] }}</li>
                <li>{{ $quotationData['barangay'] }}, {{ $quotationData['city'] }}</li>
                <li>{{ $quotationData['province'] }}, {{ $quotationData['region'] }}</li>
            </ul>
        </div>

        <p>We will review your request and prepare a detailed quotation based on your requirements.</p>
        
        <p>Please keep your reference number handy for future communications.</p>
        
        <p>Best regards,<br>Your Catering Team</p>
    </div>
</body>
</html>