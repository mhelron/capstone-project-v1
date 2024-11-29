<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pending Reservation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 20px;
        }
        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 20px;
            font-size: 28px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #ffffff;
            border: 1px solid #ddd;
        }
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: grey; /* Light orange */
            font-weight: bold;
            color: #fff;
        }
        td {
            color: #555;
        }
        ul {
            margin: 0;
            padding: 0;
        }
        li {
            list-style-type: none;
            padding: 5px 0;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 14px;
            color: #888;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            margin-top: 20px;
            background-color: #ff6f00;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
        }
        .button:hover {
            background-color: #e65c00;
        }
        @media (max-width: 600px) {
            table {
                width: 100%;
                font-size: 14px;
            }
            th, td {
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    <h1>Pending Reservation</h1>

    <p>Dear {{ $first_name }} {{ $last_name }},</p>
    <p>We have received your payment for your reservation. Our team will verify your payment shortly.</p>
    <p>Thank you for reserving with Kyla and Kyle Catering Services! Here are your reservation details:</p>

    <table>
        <tr>
            <th>Full Name:</th>
            <td>{{ $first_name }} {{ $last_name }}</td>
        </tr>
        <tr>
            <th>Phone:</th>
            <td>{{ $phone }}</td>
        </tr>
        <tr>
            <th>Email:</th>
            <td>{{ $email }}</td>
        </tr>
        <tr>
            <th>Address:</th>
            <td>{{ $street_houseno }}, {{ $barangay }}, {{ $city }}, {{ $province }}, {{ $region }}
        </tr>
        <tr>
            <th>Package Name:</th>
            <td>{{ $package_name }}</td>
        </tr>
        <tr>
            <th>Event Title:</th>
            <td>{{ $event_title }}</td>
        </tr>
        <tr>
            <th>Menu Name:</th>
            <td>{{ $menu_name }}</td>
        </tr>
        <tr>
            <th>Menu Content:</th>
            <td>
                @if($menu_content && count($menu_content) > 0)
                    <table>
                        @foreach ($menu_content as $item)
                            <tr>
                                <td><strong>{{ $item['category'] }}:</strong></td>
                                <td>{{ $item['food'] }}</td>
                            </tr>
                        @endforeach
                    </table>
                @else
                    <p>No menu content available.</p>
                @endif
            </td>
        </tr>
        <tr>
            <th>Event Date:</th>
            <td>{{ \Carbon\Carbon::parse($event_date)->format('F j, Y') }}</td>
        </tr>
        <tr>
            <th>Event Time:</th>
            <td>{{ $event_time }}</td>
        </tr>
        <tr>
            <th>Guests:</th>
            <td>{{ $guests_number }}</td>
        </tr>
        <tr>
            <th>Sponsors:</th>
            <td>{{ $sponsors }}</td>
        </tr>
        <tr>
            <th>Venue:</th>
            <td>{{ $venue }}</td>
        </tr>
        <tr>
            <th>Theme:</th>
            <td>{{ $theme }}</td>
        </tr>
        <tr>
            <th>Other Requests:</th>
            <td>{{ $other_requests }}</td>
        </tr>
        <tr>
            <th>Status:</th>
            <td>{{ $status }}</td>
        </tr>
        <tr>
            <th>Total Price:</th>
            <td>{{ $total_price }}</td>
        </tr>
        <tr>
            <th>Payment Status:</th>
            <td>{{ $payment_status }}</td>
        </tr>
        <tr>
            <th>Reservation Created At:</th>
            <td>{{ \Carbon\Carbon::parse($created_at)->format('F j, Y g:i A') }}</td>
        </tr>
    </table>

    <p>We look forward to making your event memorable. Thank you for choosing Kyla and Kyle Catering Services!</p>

    <div class="footer">
        <p>&copy; 2024 Kyla and Kyle Catering Services</p>
    </div>
</body>
</html>
