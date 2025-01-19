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
                <li>Event Type: {{ $quotationData['is_wedding'] == '1' ? 'Wedding' : 'Regular Event' }}</li>
                <li>Event: {{ $quotationData['event'] }}</li>
                <li>Date: {{ date('F j, Y', strtotime($quotationData['event_date'])) }}</li>
                <li>Time: {{ $quotationData['event_time'] }}</li>
                <li>Venue: {{ $quotationData['venue'] }}</li>
                <li>Theme: {{ $quotationData['theme'] }}</li>
                <li>Number of Guests: {{ $quotationData['guest_count'] }}</li>
            </ul>

            <p><strong>Menu Details:</strong></p>
            <ul style="list-style: none; padding-left: 20px;">
                @foreach($quotationData['menu_content'] as $menu)
                    <li><strong>{{ $menu['category'] }}:</strong> {{ $menu['food'] }}</li>
                @endforeach
            </ul>
            
            <p><strong>Contact Information:</strong></p>
            <ul style="list-style: none; padding-left: 20px;">
                <li>Phone: {{ $quotationData['phone'] }}</li>
                <li>Email: {{ $quotationData['email'] }}</li>
            </ul>
            
            <p><strong>Complete Address:</strong></p>
            <p style="margin-left: 20px;">
                {{ $quotationData['street_houseno'] }}, 
                {{ $quotationData['barangay'] }}, 
                {{ $quotationData['city'] }}, 
                {{ $quotationData['province'] }}, 
                {{ $quotationData['region'] }}
            </p>
        </div>

        <p>We will review your request and prepare a detailed quotation based on your requirements.</p>
        
        <p>Please keep your reference number handy for future communications.</p>
        
        <p>Best regards,<br>Your Catering Team</p>
    </div>
</body>
</html>