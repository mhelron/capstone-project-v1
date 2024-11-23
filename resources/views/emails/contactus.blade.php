<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Contact Form Submission</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .message-container {
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            padding: 15px;
            margin-top: 20px;
            border-radius: 5px;
        }
        .field {
            margin-bottom: 10px;
        }
        .field strong {
            color: #555;
        }
    </style>
</head>
<body>
    <h2>New Contact Form Submission</h2>
    
    <div class="message-container">
        <div class="field">
            <strong>Name:</strong> {{ $name }}
        </div>
        
        <div class="field">
            <strong>Phone:</strong> {{ $phone }}
        </div>
        
        <div class="field">
            <strong>Email:</strong> {{ $email }}
        </div>
        
        <div class="field">
            <strong>Message:</strong><br>
            {{ $messageContent }}  <!-- Changed from $message to $messageContent -->
        </div>
    </div>
</body>
</html>