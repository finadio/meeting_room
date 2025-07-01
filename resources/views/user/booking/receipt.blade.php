<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Receipt</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f8f9fa;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            margin-top: 0;
            color: #fd7e14;
        }
        h3 {
            margin-top: 20px;
            color: #fd7e14;
        }
        p {
            margin: 10px 0;
        }
        strong {
            font-weight: bold;
        }
        .logo {
            text-align: center;
            margin-bottom: 20px;
        }
        .logo h2 {
            font-family: 'Arial Black', sans-serif;
            font-size: 36px;
            color: #fd7e14;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin: 0;
        }
        .footer {
            text-align: right;
            margin-top: 20px;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="logo">
        <h2>Booking Meeting Room</h2>
    </div>
    <h2>Booking Receipt</h2>
    <h3>User Details:</h3>
    <p><strong>Name:</strong> {{ $user->name }}</p>
    <p><strong>Email:</strong> {{ $user->email }}</p>
    @if($user->contact_number)
        <p><strong>Contact Number:</strong> {{ $user->contact_number }}</p>
    @endif
    <h3>Booking Details:</h3>
    <p><strong>Nama Ruang Meeting:</strong> {{ $facility->name }}</p>
    <p><strong>Lokasi:</strong> {{ $facility->location }}</p>
    <p><strong>Tanggal Booking:</strong> {{ \Carbon\Carbon::parse($bookingDate)->format('F j, Y') }}</p>
    <p><strong>Jam Booking:</strong> {{ \Carbon\Carbon::parse($bookingTime)->format('h:i A') }}</p>
    <p><strong>lama Booking:</strong> {{ $bookingHour }} hour</p>
    <!-- <p><strong>Price:</strong> Rs. {{ $bookingAmount }}</p> -->
    <br>
    <div class="footer-bottom text-md-right text-sm-center">
        <p>&copy; {{ date('Y') }} Booking Meeting Room. All rights reserved.</p>
    </div>
</div>
</body>
</html>
