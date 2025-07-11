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
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%) !important;
            color: #fff;
            padding-top: 20px;
            z-index: 1000;
            transition: all 0.3s ease;
            overflow-x: hidden;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
            border-right: 1px solid rgba(255,255,255,0.1);
        }
        
        .sidebar.hide {
            width: 70px !important;
            min-width: 70px !important;
            max-width: 70px !important;
            transition: width .3s ease;
        }
        
        .sidebar.hide .text-center {
            display: none;
        }
        
        .sidebar.hide ul li a {
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 70px;
            padding: 15px 0;
            width: 100%;
            position: relative;
        }
        
        .sidebar.hide ul li a i {
            margin-right: 0;
            font-size: 1.5rem;
            transition: color 0.2s, transform 0.2s;
        }
        
        .sidebar.hide ul li a:hover i {
            color: #4A6DFB;
            transform: scale(1.15);
        }
        
        /* Tooltip for collapsed sidebar */
        .sidebar.hide ul li a:hover::after {
            content: attr(data-title);
            position: absolute;
            left: 100%;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(0,0,0,0.8);
            color: white;
            padding: 8px 12px;
            border-radius: 4px;
            font-size: 0.8rem;
            white-space: nowrap;
            z-index: 1002;
            margin-left: 10px;
            opacity: 0;
            animation: fadeIn 0.3s ease forwards;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-50%) translateX(-10px); }
            to { opacity: 1; transform: translateY(-50%) translateX(0); }
        }
        
        .sidebar.hide ul li a span {
            display: none;
        }
        
        .sidebar a {
            color: white;
            text-decoration: none;
            display: block;
            padding: 15px 25px;
            transition: all 0.3s ease;
            position: relative;
        }
        
        .sidebar ul {
            max-height: calc(100vh - 120px);
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: rgba(255,255,255,0.3) transparent;
        }
        
        .sidebar ul::-webkit-scrollbar {
            width: 4px;
        }
        
        .sidebar ul::-webkit-scrollbar-track {
            background: transparent;
        }
        
        .sidebar ul::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,0.3);
            border-radius: 2px;
        }
        
        .sidebar a:hover,
        .sidebar .active > a {
            background-color: rgba(255, 255, 255, 0.1);
            font-weight: bold;
            transform: translateX(5px);
        }
        
        .sidebar.hide a:hover,
        .sidebar.hide .active > a {
            transform: none;
        }
        
        .sidebar i {
            margin-right: 10px;
            font-size: 1.2rem;
            transition: all 0.3s ease;
        }
        
        .sidebar a:hover i {
            transform: scale(1.1);
        }
        
        .sidebar .text-center {
            padding: 20px 15px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            margin-bottom: 20px;
        }
        
        .sidebar .text-center h5 {
            font-size: 0.9rem;
            font-weight: 600;
            margin-top: 10px;
            opacity: 0.9;
        }
        
        .sidebar .text-center img {
            transition: all 0.3s ease;
        }
        
        .sidebar:hover .text-center img {
            transform: scale(1.05);
        }
        
        .main-content {
            margin-left: 250px;
            padding: 80px 20px 20px 20px;
            transition: margin-left 0.3s ease;
        }
        
        .sidebar.hide ~ .main-content {
            margin-left: 70px;
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
            justify-content: space-between;
            padding: 0 20px;
            z-index: 999;
            transition: left 0.3s ease;
        }
        
        .sidebar.hide ~ nav.navbar {
            left: 70px;
        }
        
        .sidebar-toggle {
            cursor: pointer;
            font-size: 1.8rem;
            color: #333;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #f8f9fa;
            border: 1px solid #e9ecef;
        }
        
        .sidebar-toggle:hover {
            color: #4A6DFB;
            background: #e9ecef;
            transform: scale(1.05);
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
            transition: all 0.3s ease;
        }
        
        .profile-dropdown img:hover {
            transform: scale(1.1);
        }
        
        .profile-menu {
            display: none;
            position: absolute;
            top: 45px;
            right: 0;
            background-color: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 8px 16px rgba(0,0,0,0.15);
            min-width: 150px;
            z-index: 1002;
        }
        
        .profile-menu a {
            display: flex;
            align-items: center;
            padding: 12px 16px;
            color: #333;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }
        
        .profile-menu a:hover {
            background-color: #f8f9fa;
        }
        
        .profile-menu a i {
            margin-right: 8px;
            font-size: 1rem;
        }
        
        .sidebar .sidebar-brand {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 20px 0 10px 0;
            transition: all 0.3s;
            min-height: 90px;
        }
        
        .sidebar .sidebar-logo {
            width: 80px;
            height: 80px;
            object-fit: contain;
            margin-bottom: 8px;
            transition: all 0.3s;
            background: none !important;
            border-radius: 0 !important;
            box-shadow: none !important;
        }
        
        .sidebar .sidebar-title {
            font-size: 1.1rem;
            font-weight: 700;
            letter-spacing: 0.5px;
            color: #fff;
            margin: 0;
            transition: all 0.3s;
            text-align: center;
        }
        
        .sidebar.hide .sidebar-brand {
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 12px 0 8px 0;
            min-height: 60px;
        }
        
        .sidebar.hide .sidebar-logo {
            width: 44px;
            height: 44px;
            margin-bottom: 2px;
        }
        
        .sidebar.hide .sidebar-title {
            font-size: 0.85rem;
            margin: 0;
            letter-spacing: 0.5px;
            white-space: nowrap;
            text-align: center;
            font-weight: 700;
            display: none;
        }
        
        .sidebar.hide .sidebar-title::after {
            content: 'MSA';
            display: block;
            visibility: visible;
            font-size: 0.75rem;
            color: #fff;
            margin-top: 2px;
        }
        
        .sidebar-overlay {
            display: none;
            position: fixed;
            z-index: 999;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0,0,0,0.5);
            transition: opacity 0.3s;
        }
        
        /* Responsive design for mobile */
        @media (max-width: 768px) {
            .sidebar {
                width: 0;
                min-width: 0;
                max-width: 0;
                overflow: hidden;
                z-index: 1000;
            }
            
            .sidebar.show {
                width: 250px;
                min-width: 250px;
                max-width: 80vw;
                box-shadow: 2px 0 10px rgba(0,0,0,0.2);
            }
            
            .main-content {
                margin-left: 0;
                padding: 70px 15px 15px 15px;
            }
            
            nav.navbar {
                left: 0;
                padding: 0 15px;
            }
            
            .profile-dropdown img {
                width: 30px;
                height: 30px;
            }
            
            .sidebar-toggle {
                display: flex;
                width: 35px;
                height: 35px;
                font-size: 1.5rem;
            }
            
            .sidebar-overlay.active {
                display: block;
                opacity: 1;
            }
            
            body.sidebar-open {
                overflow: hidden;
            }
            
            .sidebar .sidebar-brand {
                padding: 12px 0 8px 0;
                min-height: 60px;
            }
            
            .sidebar .sidebar-logo {
                width: 32px;
                height: 32px;
            }
            
            .sidebar .sidebar-title {
                font-size: 0.95rem;
            }
        }
        
        /* Tablet responsive design */
        @media (min-width: 769px) and (max-width: 1024px) {
            .sidebar {
                width: 200px;
            }
            
            .sidebar.hide {
                width: 60px;
            }
            
            .main-content {
                margin-left: 200px;
            }
            
            .sidebar.hide ~ .main-content {
                margin-left: 60px;
            }
            
            nav.navbar {
                left: 200px;
            }
            
            .sidebar.hide ~ nav.navbar {
                left: 60px;
            }
        }
        
        /* Large desktop responsive design */
        @media (min-width: 1025px) {
            .sidebar {
                width: 280px;
            }
            
            .sidebar.hide {
                width: 80px;
            }
            
            .main-content {
                margin-left: 280px;
            }
            
            .sidebar.hide ~ .main-content {
                margin-left: 80px;
            }
            
            nav.navbar {
                left: 280px;
            }
            
            .sidebar.hide ~ nav.navbar {
                left: 80px;
            }
        }
        
        /* Default state: sidebar is collapsed */
        .sidebar {
            width: 70px !important;
            min-width: 70px !important;
            max-width: 70px !important;
        }
        
        .sidebar.expanded {
            width: 250px !important;
            min-width: 250px !important;
            max-width: 250px !important;
        }
        
        .sidebar .sidebar-brand {
            padding: 12px 0 8px 0;
            min-height: 60px;
        }
        
        .sidebar .sidebar-logo {
            width: 44px;
            height: 44px;
            margin-bottom: 2px;
        }
        
        .sidebar .sidebar-title {
            display: none;
        }
        
        .sidebar .sidebar-title::after {
            content: 'MSA';
            display: block;
            visibility: visible;
            font-size: 0.75rem;
            color: #fff;
            margin-top: 2px;
        }
        
        .sidebar ul li a {
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 70px;
            padding: 15px 0;
            width: 100%;
            position: relative;
        }
        
        .sidebar ul li a i {
            margin-right: 0;
            font-size: 1.5rem;
        }
        
        .sidebar ul li a span {
            display: none;
        }
        
        .sidebar.expanded .sidebar-brand {
            padding: 20px 0 10px 0;
            min-height: 90px;
        }
        
        .sidebar.expanded .sidebar-logo {
            width: 80px;
            height: 80px;
            margin-bottom: 8px;
        }
        
        .sidebar.expanded .sidebar-title {
            display: block;
            font-size: 1.1rem;
        }
        
        .sidebar.expanded .sidebar-title::after {
            display: none;
        }
        
        .sidebar.expanded ul li a {
            padding: 15px 25px;
            justify-content: flex-start;
        }
        
        .sidebar.expanded ul li a i {
            margin-right: 10px;
            font-size: 1.2rem;
        }
        
        .sidebar.expanded ul li a span {
            display: inline;
        }
        
        .main-content {
            margin-left: 70px;
        }
        
        .sidebar.expanded ~ .main-content {
            margin-left: 250px;
        }
        
        nav.navbar {
            left: 70px;
        }
        
        .sidebar.expanded ~ nav.navbar {
            left: 250px;
        }
        
        /* Mobile adjustments */
        @media (max-width: 768px) {
            .sidebar {
                width: 0 !important;
                min-width: 0 !important;
                max-width: 0 !important;
                overflow: hidden;
            }
            
            .sidebar.expanded {
                width: 250px !important;
                min-width: 250px !important;
                max-width: 80vw !important;
                box-shadow: 2px 0 10px rgba(0,0,0,0.2);
            }
            
            .main-content {
                margin-left: 0;
            }
            
            nav.navbar {
                left: 0;
            }
        }
        
        /* Large desktop adjustments */
        @media (min-width: 1025px) {
            .sidebar {
                width: 80px !important;
                min-width: 80px !important;
                max-width: 80px !important;
            }
            
            .sidebar.expanded {
                width: 280px !important;
                min-width: 280px !important;
                max-width: 280px !important;
            }
            
            .main-content {
                margin-left: 80px;
            }
            
            .sidebar.expanded ~ .main-content {
                margin-left: 280px;
            }
            
            nav.navbar {
                left: 80px;
            }
            
            .sidebar.expanded ~ nav.navbar {
                left: 280px;
            }
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <img src="{{ asset('img/logo3.png') }}" alt="logo" class="sidebar-logo">
        <span class="sidebar-title">MSA Meeting Room</span>
    </div>
    <ul class="list-unstyled">
        <li class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <a href="{{ route('admin.dashboard') }}" data-title="Dashboard"><i class='bx bxs-dashboard'></i> <span>Dashboard</span></a>
        </li>
        @if(auth()->user()->user_type === 'admin')
            <li class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <a href="{{ route('admin.users.index') }}" data-title="User Management"><i class='bx bxs-user'></i> <span>User Management</span></a>
            </li>
        @endif
        <li class="{{ request()->routeIs('admin.bookings.*') ? 'active' : '' }}">
            <a href="{{ route('admin.bookings.index') }}" data-title="Bookings"><i class='bx bx-calendar-check'></i> <span>Bookings</span></a>
        </li>
        <li class="{{ request()->routeIs('admin.facilities.*') ? 'active' : '' }}">
            <a href="{{ route('admin.facilities.index') }}" data-title="Facilities"><i class='bx bxs-building'></i> <span>Facilities</span></a>
        </li>
        <li class="{{ request()->routeIs('admin.calendar') ? 'active' : '' }}">
            <a href="{{ route('admin.calendar') }}" data-title="Calendar"><i class='bx bxs-calendar'></i> <span>Calendar</span></a>
        </li>
        <li class="{{ request()->routeIs('admin.notifications.*') ? 'active' : '' }}">
            <a href="{{ route('admin.notifications.index') }}" data-title="Notification"><i class='bx bxs-bell'></i> <span>Notification</span></a>
        </li>
        <li class="{{ request()->routeIs('admin.contact.*') ? 'active' : '' }}">
            <a href="{{ route('admin.contact.index') }}" data-title="Contact Us"><i class='bx bx-envelope'></i> <span>Contact Us</span></a>
        </li>
        <li class="{{ request()->routeIs('admin.send_message.*') ? 'active' : '' }}">
            <a href="{{ route('admin.send_message.index') }}" data-title="Kirim Agenda Harian"><i class='bx bxs-message-dots'></i> <span>Kirim Agenda Harian</span></a>
        </li>
        <li>
            <a href="{{ route('logout') }}" data-title="Logout"><i class='bx bxs-log-out-circle'></i> <span>Logout</span></a>
        </li>
    </ul>
</div>
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<!-- Navbar -->
<nav class="navbar">
    <i class="bx bx-menu sidebar-toggle" id="sidebarToggle"></i>
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

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>

<!-- Scripts -->
<script>
    const avatar = document.getElementById('profileAvatar');
    const menu = document.getElementById('profileMenu');
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('sidebar');
    const sidebarOverlay = document.getElementById('sidebarOverlay');
    
    // Variables to track sidebar state
    let sidebarExpanded = false;
    let expandTimeout;

    // Profile dropdown functionality
    if (avatar && menu) {
        avatar.addEventListener('click', (e) => {
            e.stopPropagation();
            menu.style.display = (menu.style.display === 'block') ? 'none' : 'block';
        });

        window.addEventListener('click', (e) => {
            if (!avatar.contains(e.target) && !menu.contains(e.target)) {
                menu.style.display = 'none';
            }
        });
    }

    // Sidebar toggle functionality
    if (sidebarToggle && sidebar && sidebarOverlay) {
        sidebarToggle.addEventListener('click', (e) => {
            e.stopPropagation();
            
            if (window.innerWidth <= 768) {
                // Mobile behavior
                sidebar.classList.toggle('expanded');
                sidebarOverlay.classList.toggle('active');
                document.body.classList.toggle('sidebar-open', sidebar.classList.contains('expanded'));
            } else {
                // Desktop behavior
                sidebarExpanded = !sidebarExpanded;
                if (sidebarExpanded) {
                    sidebar.classList.add('expanded');
                } else {
                    sidebar.classList.remove('expanded');
                }
            }
        });

        // Close sidebar when clicking overlay (mobile)
        sidebarOverlay.addEventListener('click', () => {
            sidebar.classList.remove('expanded');
            sidebarOverlay.classList.remove('active');
            document.body.classList.remove('sidebar-open');
            sidebarExpanded = false;
        });
        
        // Handle window resize
        window.addEventListener('resize', () => {
            if (window.innerWidth <= 768) {
                // Mobile: close sidebar and remove expanded class
                sidebar.classList.remove('expanded');
                sidebarOverlay.classList.remove('active');
                document.body.classList.remove('sidebar-open');
                sidebarExpanded = false;
            } else {
                // Desktop: remove mobile classes
                sidebarOverlay.classList.remove('active');
                document.body.classList.remove('sidebar-open');
            }
        });
    }

    // Prevent sidebar from expanding when clicking menu items
    const menuLinks = document.querySelectorAll('.sidebar ul li a');
    menuLinks.forEach(link => {
        link.addEventListener('click', (e) => {
            // Don't prevent default navigation, just ensure sidebar doesn't expand
            if (window.innerWidth > 768 && !sidebarExpanded) {
                // On desktop, if sidebar is collapsed, don't expand it
                e.stopPropagation();
            } else if (window.innerWidth <= 768) {
                // On mobile, close sidebar after clicking menu item
                setTimeout(() => {
                    sidebar.classList.remove('expanded');
                    sidebarOverlay.classList.remove('active');
                    document.body.classList.remove('sidebar-open');
                    sidebarExpanded = false;
                }, 100);
            }
        });
    });

    // Close profile menu when clicking outside
    document.addEventListener('click', (e) => {
        if (menu && !avatar.contains(e.target) && !menu.contains(e.target)) {
            menu.style.display = 'none';
        }
    });
</script>
@yield('scripts')
</body>
</html>