<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #333333;
            margin-top: 0;
        }

        p {
            color: #555555;
            margin-bottom: 16px;
        }

        .verification-code {
            background-color: #f2f2f2;
            padding: 10px;
            border-radius: 5px;
            font-size: 18px;
            font-weight: bold;
            color: #333333;
        }

        .instructions {
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Dear User</h1>
        <p>We're excited to have you join our community. To complete your registration, please use the verification code below:</p>
        <div class="verification-code">
            {{ $data['verification_code'] }}
        </div>
        <p class="instructions">Enter this code in the provided field to verify your account.</p>
    </div>
</body>
</html>
