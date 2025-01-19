<!DOCTYPE html>
<html>
<head>
    <title>Quotation Status Update</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <h2>Quotation Request Update</h2>
        
        <p>Dear {{ $quotationData['firstname'] }} {{ $quotationData['lastname'] }},</p>
        
        <p>Your quotation request (Reference Number: {{ $quotationData['reference_number'] }}) has been <strong>{{ strtoupper($quotationData['status']) }}</strong>.</p>
        
        @if($quotationData['status'] === 'approved')
            <div style="background-color: #f9f9f9; padding: 15px; margin: 20px 0; border-left: 4px solid #28a745;">
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
                    <li>Total Price: ₱{{ number_format($quotationData['total_price'], 2) }}</li>
                    <li>Payment Status: {{ $quotationData['payment_status'] }}</li>
                </ul>

                <p><strong>Next Steps:</strong></p>
                <ol style="padding-left: 20px;">
                    <li>Please review the quotation details carefully</li>
                    <li>Contact us to discuss payment arrangements</li>
                    <li>Initial payment is required to secure your booking</li>
                    <li>Once payment is received, your event will be confirmed in our calendar</li>
                </ol>
            </div>
        @elseif($quotationData['status'] === 'rejected')
            <div style="background-color: #f9f9f9; padding: 15px; margin: 20px 0; border-left: 4px solid #dc3545;">
                <p>We regret to inform you that we cannot accommodate your quotation request at this time.</p>
                <p>If you would like to submit a new request with modifications or have any questions about why your request was rejected, please don't hesitate to contact us.</p>
            </div>
        @endif

        <p>If you have any questions or need to make changes, please contact us immediately.</p>
        
        <p>Best regards,<br>Your Catering Team</p>
    </div>
</body>
</html>