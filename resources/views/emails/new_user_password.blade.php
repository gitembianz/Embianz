<!DOCTYPE html>
<html>
<head>
    <title>Welcome to Our Platform</title>
</head>
<body>
    <h2>Hello {{ $name }},</h2>

    <p>Your account has been created. Here are your login details:</p>

    <p><strong>Email:</strong> {{ $email }}</p>
    <p><strong>Password:</strong> {{ $password }}</p>

    <p>You can log in using the button below:</p>
    <a href="{{ url('/login') }}">Login Here</a>

    <p>Please change your password after logging in.</p>

    <p>Regards,<br>Team</p>
</body>
</html>
