<!DOCTYPE html>
<html>
<head>
    <title>Contact Us</title>
</head>
<body>
<h1>Verification Message</h1>
<p>Hello {{ $user->name }}:</p>
<p>this is a verification code for your account: <span style="font-weight: bold">{{ $user->verification_code }}</span></p>
<p>Best regards,</p>
<p>Your Application</p>
</body>
</html>