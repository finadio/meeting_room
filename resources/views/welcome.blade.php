<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Meeting Room</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        body {
            font-family: 'Karla', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #D5EAF2;
            color: #121257;
        }

        header {
            background-color: #121257;
            color: white;
            text-align: center;
            padding: 4em 0;
        }

        header img {
            width: 280px;
        }

        section {
            text-align: center;
            padding: 4em 0;
        }

        h1 {
            color: #121257;
        }

        p {
            color: #6c757d;
            font-size: 1.2em;
            max-width: 800px;
            margin: 0 auto;
            line-height: 1.6;
        }

        .btn {
            display: inline-block;
            background-color: #121257;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            font-size: 1.2em;
            border-radius: 5px;
            margin-top: 1em;
            transition: background-color 0.3s, color 0.3s;
        }

        .btn:hover {
            background-color: black;
            color: white;
        }


        .features {
            display: flex;
            justify-content: space-around;
            margin-top: 4em;
        }

        .feature {
            text-align: left;
            max-width: 300px;
            padding: 20px;
            border-radius: 8px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
        }

        .feature i {
            font-size: 2em;
            color: #121257;
        }

        .feature h3 {
            color: #121257;
            margin-top: 1em;
        }

        .feature p {
            color: #6c757d;
            font-size: 1em;
            line-height: 1.4;
        }

        .feature:hover {
            transform: translateY(-10px);
        }

        footer {
            background-color: #121257;
            color: white;
            text-align: center;
            padding: 1em 0;
        }
    </style>
</head>

<body>

<header>
    <img src="{{ asset('img/shaka_utama.png') }}" alt="logo" />
    <h1>Meeting Room Booking System</h1>
    <p>Pesan Ruang Rapat favorit Anda dengan mudah dan nikmati kenyamanannya bersama teman dan kolega Anda!</p>
    <br>
    @if(auth()->check())
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <strong style="color: white">Hey there!</strong> <p style="color: red">You are already logged in. If you wish to log in with a different account, please log out first your previous account.</p>
            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="btn" style="color: red; font-weight: bold; text-decoration: none;">Log out</a>
            <form id="logout-form" action="{{ route('logout') }}" method="GET" style="display: none;">
                @csrf
            </form>
        </div>
    @endif

    <a href="{{ route('register') }}" class="btn">Sign Up Now</a>
    <a href="{{ route('login') }}" class="btn">Login</a>
</header>

<section id="features">
    <h2>Fitur Utama</h2>
    <section class="features">
        <div class="feature">
            <i class="fas fa-calendar-alt"></i>
            <h3>Easy Booking</h3>
            <p>Jadwalkan Meeting Anda secara online dengan mudah dengan sistem booking kami yang ramah pengguna.</p>
        </div>

        <div class="feature">
        <!-- <img src="{{ asset('img/logo3.png') }}" alt="logo" /> -->
            <!-- <i class="fas fa-trophy"></i>
            <h3>Tournaments</h3>
            <p>Play in tournaments, earn certificates, and engage in exciting matches to showcase your Meeting Room.</p> -->
        </div>
        <div class="feature">
            <i class="fas fa-star"></i>
            <h3>Events</h3>
            <p>Jelajahi tantangan Meeting mingguan Anda dengan fasilitas terbaik untuk meningkatkan pengalaman meeting Anda.</p>
        </div>
    </section>

</section>

<footer>
    &copy; 2024 Booking Meeting Room System. All rights reserved.
</footer>

</body>

</html>
