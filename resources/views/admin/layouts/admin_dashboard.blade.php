<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard')</title>
    <link rel="icon" href="{{ asset('bookmeet.png') }}" type="image/x-icon">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/admin_dashboard.css') }}">
    <link href="https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/main.min.css">
    <link rel="stylesheet" href="{{ asset('css/calendar_custom.css') }}">
    @yield('styles')
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        .sidebar {
            width: 250px;
            position: fixed;
            height: 100vh;
            background: linear-gradient(135deg, #1e3c72, #2a5298);
            color: #fff;
            padding-top: 20px;
            z-index: 1000;
        }
        .sidebar a {
            color: white;
            text-decoration: none;
            display: block;
            padding: 15px 25px;
        }
        .sidebar a:hover,
        .sidebar .active > a {
            background-color: rgba(255, 255, 255, 0.1);
            font-weight: bold;
        }
        .sidebar i {
            margin-right: 10px;
        }
        .main-content {
            margin-left: 250px;
            padding: 80px 20px 20px 20px;
        }
        nav.navbar {
            position: fixed;
            left: 250px;
            right: 0;
            top: 0;
            height: 60px;
            background: #fff;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            justify-content: flex-end;
            padding: 0 20px;
            z-index: 999;
        }
        .profile-dropdown {
            position: relative;
        }
        .profile-dropdown img {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            object-fit: cover;
            cursor: pointer;
        }
        .profile-menu {
            display: none;
            position: absolute;
            top: 45px;
            right: 0;
            background-color: #f9f9f9;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 8px 16px rgba(0,0,0,0.15);
        }
        .profile-menu a {
            display: block;
            padding: 10px 15px;
            color: #333;
            text-decoration: none;
        }
        .profile-menu a:hover {
            background-color: #eee;
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <div class="text-center mb-4">
        <img src="{{ asset('img/logo3.png') }}" alt="logo" style="width: 80px;">
        <h5 class="mt-2">Booking Meeting Room</h5>
    </div>
    <ul class="list-unstyled">
        <li class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <a href="{{ route('admin.dashboard') }}"><i class='bx bxs-dashboard'></i> Dashboard</a>
        </li>
        @if(auth()->user()->user_type === 'admin')
            <li class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <a href="{{ route('admin.users.index') }}"><i class='bx bxs-user'></i> User Management</a>
            </li>
        @endif
        <li class="{{ request()->routeIs('admin.bookings.*') ? 'active' : '' }}">
            <a href="{{ route('admin.bookings.index') }}"><i class='bx bxs-calendar'></i> Bookings</a>
        </li>
        <li class="{{ request()->routeIs('admin.facilities.*') ? 'active' : '' }}">
            <a href="{{ route('admin.facilities.index') }}"><i class='bx bxs-building'></i> Facilities</a>
        </li>
        <li class="{{ request()->routeIs('admin.calendar') ? 'active' : '' }}">
            <a href="{{ route('admin.calendar') }}"><i class='bx bxs-calendar'></i> Calendar</a>
        </li>
        <li class="{{ request()->routeIs('admin.notifications.*') ? 'active' : '' }}">
            <a href="{{ route('admin.notifications.index') }}"><i class='bx bxs-bell'></i> Notification</a>
        </li>
        <li class="{{ request()->routeIs('admin.contact.*') ? 'active' : '' }}">
            <a href="{{ route('admin.contact.index') }}"><i class='bx bx-envelope'></i> Contact Us</a>
        </li>
        <li>
            <a href="{{ route('logout') }}"><i class='bx bxs-log-out-circle'></i> Logout</a>
        </li>
    </ul>
</div>

<!-- Navbar -->
<nav class="navbar">
    <div class="profile-dropdown">
        @php $user = auth()->user(); @endphp
        <img src="{{ $user->profile_picture && Storage::exists('public/profile_pictures/' . $user->profile_picture)
            ? asset('storage/profile_pictures/' . $user->profile_picture)
            : 'https://bootdey.com/img/Content/avatar/avatar1.png' }}" alt="Profile" id="profileAvatar">
        <div class="profile-menu" id="profileMenu">
            <a href="{{ route('admin.profile') }}"><i class="bx bxs-user"></i> Profile</a>
            <a href="{{ route('logout') }}"><i class="bx bxs-door-open"></i> Logout</a>
        </div>
    </div>
</nav>

<!-- Main Content -->
<div class="main-content">
    @yield('content')
</div>

<!-- Scripts -->
<script>
    const avatar = document.getElementById('profileAvatar');
    const menu = document.getElementById('profileMenu');

    avatar.addEventListener('click', () => {
        menu.style.display = (menu.style.display === 'block') ? 'none' : 'block';
    });

    window.addEventListener('click', (e) => {
        if (!avatar.contains(e.target) && !menu.contains(e.target)) {
            menu.style.display = 'none';
        }
    });
</script>
@yield('scripts')
</body>
</html>