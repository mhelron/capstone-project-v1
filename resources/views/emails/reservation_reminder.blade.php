<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservation Reminder</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #f9f9f9; }
        .container { width: 100%; max-width: 600px; margin: 0 auto; background-color: white; padding: 20px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); }
        h1 { color: #333; }
        p { color: #666; line-height: 1.6; }
        .button { background-color: #FF5722; color: white; padding: 10px 20px; text-align: center; border-radius: 4px; text-decoration: none; }
        .footer { font-size: 12px; text-align: center; color: #999; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Reminder: Your Reservation is Coming Up!</h1>
        <p>Dear {{ $first_name }} {{ $last_name }},</p>
        <p>This is a reminder that your reservation for <strong>{{ $event_title }}</strong> is scheduled for <strong>{{ $event_date }}</strong> at <strong>{{ $event_time }}</strong>.</p>
        <p>We look forward to serving you!</p>
        <p>Thank you for choosing our services. If you need any assistance, feel free to contact us.</p>
        
        <p class="footer">Thank you for using our services!<br> Kyla and Kyle Catering Services</p>
    </div>
</body>
</html>
