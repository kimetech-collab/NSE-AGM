<!doctype html>
<html>
<body>
    <p>Hello {{ $registration->name }},</p>
    <p>Your registration OTP is: <strong>{{ $otp }}</strong></p>
    <p>This code expires in 10 minutes. If you did not attempt to register, ignore this message.</p>
    <p>Thanks,<br/>NSE Portal Team</p>
</body>
</html>
