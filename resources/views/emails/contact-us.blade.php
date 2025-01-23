<!DOCTYPE html>
<html>
<head>
    <title>Contact Us</title>
</head>
<body>
<h1>Contact Us Message</h1>
<p>{{ $request->name }} sent you a message:</p>
<p>his email is: {{ $request->email }}</p>
<p>{{ $request->message }}</p>
<p>Best regards,</p>
<p>Your Application</p>
</body>
</html>