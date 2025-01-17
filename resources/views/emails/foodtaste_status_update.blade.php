<!DOCTYPE html>
<html>
<head>
    <title>Food Tasting Status Update</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <h2>Food Tasting Request Update</h2>
        
        <p>Dear {{ $firstname }} {{ $lastname }},</p>
        
        <p>Your food tasting request (Reference Number: {{ $reference_number }}) has been <strong>{{ strtoupper($status) }}</strong>.</p>
        
        @if($status === 'approved')
            <div style="background-color: #f9f9f9; padding: 15px; margin: 20px 0; border-left: 4px solid #28a745;">
                <p><strong>Scheduled Details:</strong></p>
                @if($set_date && $set_time)
                    <p>Date: {{ date('F j, Y', strtotime($set_date)) }}</p>
                    <p>Time: {{ $set_time }}</p>
                @endif
                <p>Option: {{ ucfirst($delivery_option) }}</p>
            </div>

            <p>Please make sure to be available at the scheduled time. If you need to make any changes, please contact us immediately.</p>
        @elseif($status === 'rejected')
            <div style="background-color: #f9f9f9; padding: 15px; margin: 20px 0; border-left: 4px solid #dc3545;">
                <p>We regret to inform you that we cannot accommodate your food tasting request at this time. Please feel free to submit another request for a different date.</p>
            </div>
        @endif

        <p>If you have any questions, please don't hesitate to contact us.</p>
        
        <p>Best regards,<br>Your Catering Team</p>
    </div>
</body>
</html>