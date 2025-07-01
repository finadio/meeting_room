<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Booking Meeting Room!</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #007bff;
        }
        p {
            margin-bottom: 15px;
            line-height: 1.6;
        }
        .highlight {
            color: #ff5722;
            font-weight: bold;
        }
        .signature {
            font-style: italic;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Welcome to Booking Meeting Room!</h1>
    <p>Dear <span class="highlight">{{ $user->name }}</span>,</p>
    <p>Terimkasih sudah melakukan registrasi di <span class="highlight">Booking Meeting Room</span>. Kami sangat bangga memiliki Anda sebagai bagian dari keluarga besar Bank MSA.</p>
    <p>Silakan jelajahi semua fitur dan layanan yang kami tawarkan. Jika Anda memiliki pertanyaan atau butuh bantuan, jangan ragu untuk menghubungi kami.</p>
    <p>Salam Hangat,</p>
    <p class="signature">Bank MSA | Booking Meeting Room</p>
</div>
</body>
</html>
