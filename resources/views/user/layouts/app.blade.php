<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'User Dashboard')</title>
    <link rel="icon" href="{{ asset('bookmeet.png') }}" type="image/x-icon">

    <!-- Favicon untuk layar retina (misalnya 32x32, 64x64) -->
    <link rel="icon" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" sizes="64x64" href="{{ asset('favicon-64x64.png') }}">

    <!-- Ikon untuk aplikasi web (Web App) -->
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">
    <!-- Preload & load CSS utama lebih awal untuk mencegah FOUC -->
    <link rel="stylesheet" href="{{ asset('css/user_dashboard.css') }}">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <!-- Google Fonts: Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @yield('styles')
</head>
<style>
    .btn-list-facility {
        background-color: white;
        border-color: black;
        color: black;
    }

    .btn-list-facility:hover {
        background-color: #ff8c00;
        border-color: #ffbb33;
        color: black;
        animation: pulse 0.5s ease-in-out;
    }

    @keyframes pulse {
        0% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.1);
        }
        100% {
            transform: scale(1);
        }
    }
</style>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-light justify-content-between fixed-top">
    <a class="navbar-brand" href="{{ route('user.dashboard') }}">
        <img src="{{ asset('img/msa.png') }}" alt="Logo" class="img-fluid" style="max-width: 100px;">
        <span class="text">Booking Meeting Room</span>
    </a>

    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item {{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('user.dashboard') }}">
                    <i class='bx bx-home'></i> Home
                </a>
            </li>
            <li class="nav-item {{ request()->routeIs('user.booking.index') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('user.booking.index') }}">
                    <i class='bx bxs-calendar'></i> Booking
                </a>
            </li>
            <li class="nav-item {{ request()->routeIs('user.calendar') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('user.calendar') }}">
                    <i class='bx bxs-calendar'></i> Calendar
                </a>
            </li>
            <li class="nav-item {{ request()->routeIs('room.status') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('room.status') }}">
                    <i class='bx bx-door-open'></i> Room Status
                </a>
            </li>
            <li class="nav-item {{ request()->routeIs('about.show') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('about.show') }}">
                    <i class='bx bx-info-circle'></i> About
                </a>
            </li>
            <li class="nav-item {{ request()->routeIs('contact.show') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('contact.show') }}">
                    <i class='bx bx-envelope'></i> Contact Us
                </a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link" href="{{ route('user.profile') }}" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <div class="d-flex align-items-center">
                        @php $user = auth()->user(); @endphp
                        @if ($user && $user->profile_picture && file_exists(public_path('storage/profile_pictures/' . $user->profile_picture)))
                            <img src="{{ asset('storage/profile_pictures/' . $user->profile_picture) }}" alt="Profile Picture" class="rounded-circle" style="width: 32px; height: 32px; object-fit: cover; margin-right: 8px;">
                        @else
                            <img src="https://bootdey.com/img/Content/avatar/avatar1.png" alt="Default Profile Picture" class="rounded-circle" style="width: 32px; height: 32px; object-fit: cover; margin-right: 8px;">
                        @endif
                        <span>{{ $user ? $user->name : 'Guest' }}</span>
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item profile-link" href="{{ route('user.profile') }}">
                        <i class='bx bxs-user'></i>
                        Profile
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item bookmark-link" href="{{ route('user.bookings') }}">
                        <i class='bx bxs-calendar'></i>
                        My Booking
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item bookmark-link" href="{{ route('user.bookmarks') }}">
                        <i class='bx bx-bookmark'></i>
                        My Bookmarks
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="{{ route('logout') }}" class="dropdown-item logout-link">
                        <i class='bx bxs-door-open'></i>
                        Logout
                    </a>
                </div>
            </li>
           
        </ul>
    </div>
</nav>

<main class="py-4">
    @yield('content')
</main>
<x-footer />

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('#users').select2({
            placeholder: 'Choose a User',
            width: '100%'
        });
    });
</script>
<!--Start of Mashaka Chat Script-->
<script>
window.embeddedChatbotConfig = {
chatbotId: "80HCJeg1B6Y8EUgYCchaR",
domain: "www.chatbase.co"
}
</script>
<script
src="https://www.chatbase.co/embed.min.js"
chatbotId="80HCJeg1B6Y8EUgYCchaR"
domain="www.chatbase.co"
defer>
</script>
<!--Start of Tawk.to Script-->
<!-- <script type="text/javascript">
    var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
    (function(){
        var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
        s1.async=true;
        s1.src='https://embed.tawk.to/6495d0a194cf5d49dc5f79cb/1hplk9tsd';
        s1.charset='UTF-8';
        s1.setAttribute('crossorigin','*');
        s0.parentNode.insertBefore(s1,s0);
    })();
</script> -->
<!--End of Tawk.to Script-->
@yield('scripts')
</body>
</html>