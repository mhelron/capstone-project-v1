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
        <h1>Reservation Cancelled</h1>

        <p>Dear {{ $first_name }} {{ $last_name }},</p>

        <p>We regret to inform you that your reservation with Kyla and Kyle Catering Services has been canceled. If this was unintentional or you have any questions, please don't hesitate to contact us.</p>

        <p>We sincerely apologize for any inconvenience caused and hope to assist you with your catering needs in the future.</p>

        <p>Thank you for considering us, and we hope to serve you again in the future.</p>

        <p>Best regards,<br>
        The Kyla and Kyle Catering Services Team</p>

        <a href="mailto:kylaandkylecs@gmail.com" class="button">Contact Us</a>

        <div class="footer">
            &copy; 2024 Kyla and Kyle Catering Services. All rights reserved.
        </div>
    </div>
</body>
</html>
