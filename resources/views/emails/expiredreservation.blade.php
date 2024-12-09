<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservation Cancelled</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            line-height: 1.6;
        }
        .container {
            max-width: 600px;
            margin: 30px auto;
            background: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            font-size: 24px;
            color: #ff6f00;
            text-align: center;
            margin-bottom: 20px;
        }
        p {
            color: #333;
            margin: 10px 0;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 14px;
            color: #777;
        }
        .button {
            display: block;
            text-align: center;
            background-color: #ff6f00;
            color: #ffffff;
            padding: 12px;
            margin: 20px auto;
            text-decoration: none;
            width: fit-content;
            border-radius: 5px;
            font-weight: bold;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }
        .button:hover {
            background-color: #e65c00;
        }
        @media (max-width: 600px) {
            .container {
                padding: 15px;
            }
            h1 {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Pencil Reservation Expired</h1>

        <p>Dear {{ $first_name }} {{ $last_name }},</p>

        <p>We regret to inform you that your pencil reservation for Kyla and Kyle Catering Services has expired. Unfortunately, we were unable to confirm the reservation within the allotted time, and the date has been made available to other clients.</p>

        <p>If you still wish to book our services, we encourage you to check the availability of your preferred date and make a new reservation. Please don't hesitate to reach out to us if you need any assistance or have any questions.</p>

        <p>We sincerely apologize for any inconvenience caused and appreciate your understanding.</p>

        <p>Thank you for considering us, and we hope to have the opportunity to serve you in the future.</p>

        <p>Best regards,<br>
        The Kyla and Kyle Catering Services Team</p>

        <a href="mailto:kylaandkylecs@gmail.com" class="button">Contact Us</a>

        <div class="footer">
            &copy; 2024 Kyla and Kyle Catering Services. All rights reserved.
        </div>
    </div>
</body>
</html>
