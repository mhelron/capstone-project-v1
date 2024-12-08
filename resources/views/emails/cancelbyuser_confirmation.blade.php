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
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px 15px;
            text-align: left;
            border: 1px solid #ddd; /* Add border for a clean layout */
        }
        th {
            background-color: grey;
            color: #ffffff;
            font-weight: bold;
        }
        td {
            background-color: #ffffff;
            color: #555;
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
            padding: 12px 20px;
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
            table th, table td {
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Reservation Cancelled</h1>
        <p>Dear {{ $first_name }} {{ $last_name }},</p>

        <p>We have received your request to cancel your reservation with Kyla and Kyle Catering Services. Your reservation has been successfully canceled as per your request.</p>

        <table>
            <tr>
                <th>Reference Number</th>
                <td>{{ $reference_number }}</td>
            </tr>
            <tr>
                <th>Status</th>
                <td>{{ $status }}</td>
            </tr>
            <tr>
                <th>Reason</th>
                <td>{{ $cancellation_reason }}</td>
            </tr>
            <tr>
                <th>Cancelled At</th>
                <td>{{ \Carbon\Carbon::parse($cancelled_at)->format('F j, Y g:i A') }}</td>
            </tr>
        </table>

        <p>If you have any questions or need assistance with a future reservation, please don’t hesitate to reach out to us. We would be happy to assist you.</p>

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
