<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard')</title>
    <link rel="icon" href="{{ asset('bookmeet.png') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('css/admin_dashboard.css') }}" />
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/css/bootstrap.min.css" rel="stylesheet"> 
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/main.min.css">
    <link rel="stylesheet" href="{{ asset('css/calendar_custom.css') }}">
    @yield('styles')
    <style>
        .logout {
            transition: font-weight 0.3s ease;
        }
        .logout:hover{
            font-weight: bold;
        }
        /* Tambahan CSS responsif untuk layout dasar */
        #sidebar {
            width: 280px;
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%); /* Warna senada */
            z-index: 2000;
            transition: all .3s ease;
            overflow-x: hidden;
            overflow-y: auto; /* Untuk scroll sidebar jika konten banyak */
        }
        #sidebar.hide {
            width: 60px; /* Lebar saat disembunyikan */
        }
        #content {
            position: relative;
            width: calc(100% - 280px);
            left: 280px;
            transition: all .3s ease;
        }
        #content.shrink {
            width: calc(100% - 60px);
            left: 60px;
        }

        /* Navigasi atas */
        nav {
            height: 60px;
            background: #fff;
            padding: 0 20px;
            display: flex;
            align-items: center;
            grid-gap: 20px;
            position: sticky;
            top: 0;
            left: 0;
            z-index: 1000;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        nav .bx.bx-menu {
            cursor: pointer;
            font-size: 24px;
            color: #333;
        }
        nav .profile {
            display: flex;
            align-items: center;
            cursor: pointer;
            position: relative;
            margin-left: auto; /* Untuk menempatkan profil di kanan */
        }
        nav .profile img {
            width: 36px;
            height: 36px;
            object-fit: cover;
            border-radius: 50%;
            margin-left: 10px;
        }
        nav .dropdown-content {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
            right: 0;
            top: 100%; /* Agar dropdown muncul di bawah gambar profil */
            border-radius: 8px;
            overflow: hidden;
        }
        nav .dropdown-content a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            transition: background-color 0.2s ease;
        }
        nav .dropdown-content a:hover {
            background-color: #ddd;
        }
        nav .profile:hover .dropdown-content {
            display: block;
        }

        /* Responsive */
        @media (max-width: 768px) {
            #sidebar {
                width: 0; /* Sembunyikan sidebar di mobile secara default */
                left: -280px;
            }
            #sidebar.show {
                width: 280px; /* Tampilkan saat toggle */
                left: 0;
            }
            #content {
                width: 100%; /* Konten ambil lebar penuh */
                left: 0;
            }
            #content.shrink {
                width: 100%;
                left: 0;
            }
            nav .bx.bx-menu {
                display: block; /* Pastikan tombol menu terlihat */
            }
            nav .profile {
                margin-left: auto; /* Tetap di kanan */
            }
        }
        @media (max-width: 576px) {
            nav {
                grid-gap: 10px;
                padding: 0 10px;
            }
        }
    </style>
</head>
<body>

<section id="sidebar">
    <a href="{{route('admin.dashboard')}}" class="brand" style="text-decoration: none;">
        <div>
            <img src="{{ asset('img/msa.png') }}" alt="logo" style="width: 65px;" />
        </div>
        <span class="text">Booking Meeting Room</span>
    </a>
    <ul class="side-menu top">
        {{-- Dashboard --}}
        <li class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <a style="text-decoration: none;" href="{{ route('admin.dashboard') }}">
                <i class='bx bxs-dashboard' ></i>
                <span class="text">Dashboard</span>
            </a>
        </li>
        {{-- User Management --}}
        @if(auth()->check() && auth()->user()->user_type === 'admin')
            <li class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <a style="text-decoration: none;" href="{{ route('admin.users.index') }}">
                    <i class='bx bxs-user'></i>
                    <span class="text">User Management</span>
                </a>
            </li>
        @endif
        {{-- Bookings --}}
        <li class="{{ request()->routeIs('admin.bookings.*') ? 'active' : '' }}">
            <a style="text-decoration: none;" href="{{ route('admin.bookings.index') }}">
                <i class='bx bxs-calendar'></i>
                <span class="text">Bookings</span>
            </a>
        </li>
        {{-- Facilities --}}
        <li class="{{ request()->routeIs('admin.facilities.*') ? 'active' : '' }}">
            <a style="text-decoration: none;" href="{{ route('admin.facilities.index') }}">
                <i class='bx bxs-building'></i>
                <span class="text">Facilities</span>
            </a>
        </li>
        {{-- Calendar --}}
        @if(auth()->check() && auth()->user()->user_type === 'admin')
            <li class="{{ request()->routeIs('admin.calendar') ? 'active' : '' }}">
                <a style="text-decoration: none;" href="{{ route('admin.calendar') }}">
                    <i class='bx bxs-calendar'></i>
                    <span class="text">Calendar</span>
                </a>
            </li>
        @endif
        {{-- Notification --}}
        @if(auth()->check() && auth()->user()->user_type === 'admin')
            <li class="{{ request()->routeIs('admin.notifications.*') ? 'active' : '' }}">
                <a style="text-decoration: none;" href="{{ route('admin.notifications.index') }}">
                    <i class='bx bxs-bell'></i>
                    <span class="text">Notification</span>
                </a>
            </li>
        @endif
        {{-- Contact Us --}}
        @if(auth()->check() && auth()->user()->user_type === 'admin')
            <li class="{{ request()->routeIs('admin.contact.*') ? 'active' : '' }}">
                <a style="text-decoration: none;" href="{{ route('admin.contact.index') }}">
                    <i class='bx bx-envelope'></i>
                    <span class="text">Contact Us</span>
                </a>
            </li>
        @endif
    </ul>
    <ul class="side-menu">
        {{-- Logout --}}
        <li class="{{ request()->routeIs('logout') ? 'active' : '' }}">
            <a style="text-decoration: none;" href="{{ route('logout') }}" class="logout">
                <i class='bx bxs-log-out-circle' ></i>
                <span class="text">Logout</span>
            </a>
        </li>
    </ul>
</section>

<section id="content">
    <nav>
        <i class='bx bx-menu' ></i>
        <div class="dropdown">
            </div>
        <div class="dropdown">
            </div>
        <div class="dropdown">
            <a href="{{ route('admin.profile') }}" class="profile" id="profileDropdown" style="text-decoration: none;">
                @if (auth()->check() && $user = auth()->user())
                    @if ($user->profile_picture && Storage::exists('public/profile_pictures/' . $user->profile_picture))
                        <img src="{{ asset('storage/profile_pictures/' . $user->profile_picture) }}" alt style="width: 30px;">
                    @else
                        <img src="https://bootdey.com/img/Content/avatar/avatar1.png" alt="Default Profile Picture" style="width: 30px;">
                    @endif
                @endif
            </a>
            <div class="dropdown-content" id="profileDropdownContent">
                <a href="{{ route('admin.profile') }}" style="text-decoration: none;">Profile</a>
                <a href="{{ route('logout') }}" style="text-decoration: none;">Logout</a>
            </div>
        </div>
    </nav>

    {{-- KONTEN UTAMA HALAMAN --}}
    <main class="py-4">
        @yield('content')
    </main>
</section>

{{-- Mengganti admin_dashboard.js dengan script inline untuk toggle sidebar --}}
<script>
    const sidebar = document.getElementById('sidebar');
    const content = document.getElementById('content');
    const menuIcon = document.querySelector('nav .bx.bx-menu');

    menuIcon.addEventListener('click', () => {
        sidebar.classList.toggle('hide');
        content.classList.toggle('shrink');
    });

    // Menangani dropdown profil
    const profileDropdown = document.getElementById('profileDropdown');
    const profileDropdownContent = document.getElementById('profileDropdownContent');

    profileDropdown.addEventListener('click', (event) => {
        event.preventDefault(); // Mencegah navigasi langsung
        profileDropdownContent.style.display = profileDropdownContent.style.display === 'block' ? 'none' : 'block';
    });

    // Sembunyikan dropdown jika klik di luar
    document.addEventListener('click', (event) => {
        if (!profileDropdown.contains(event.target) && !profileDropdownContent.contains(event.target)) {
            profileDropdownContent.style.display = 'none';
        }
    });

    // Responsifitas saat resize atau load
    function handleResize() {
        if (window.innerWidth < 768) {
            sidebar.classList.add('hide');
            content.classList.add('shrink');
        } else {
            sidebar.classList.remove('hide');
            content.classList.remove('shrink');
        }
    }

    window.addEventListener('resize', handleResize);
    handleResize(); // Panggil saat memuat halaman
</script>
{{-- Area untuk memuat script --}}
@yield('scripts')
</body>
</html>