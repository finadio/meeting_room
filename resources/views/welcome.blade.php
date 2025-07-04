<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Meeting Room</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/user_dashboard.css') }}">
    <style>
        body {
            font-family: 'Poppins', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: var(--background-light);
            color: var(--text-primary);
            margin: 0;
            padding: 0;
        }
        .hero-landing {
            background: linear-gradient(120deg, var(--primary-color) 60%, var(--primary-dark) 100%);
            color: white;
            padding: 3.5rem 0 2.5rem 0;
            text-align: center;
            position: relative;
        }
        .hero-landing .hero-logo {
            width: 90px;
            margin-bottom: 1.2rem;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.07);
            background: white;
            padding: 8px;
        }
        .hero-landing .hero-title {
            font-size: 2.2rem;
            font-weight: 800;
            margin-bottom: 0.7rem;
            letter-spacing: -1px;
        }
        .hero-landing .hero-desc {
            font-size: 1.05rem;
            color: #e0e7ef;
            margin-bottom: 2.2rem;
            max-width: 420px;
            margin-left: auto;
            margin-right: auto;
        }
        .hero-cta-group, .login-btn-group {
            display: flex;
            justify-content: center;
            gap: 0.8rem;
            flex-wrap: wrap;
            margin-bottom: 0.5rem;
        }
        .hero-cta-group .btn, .login-btn-group .btn {
            background: var(--primary-color);
            color: #fff;
            font-weight: 700;
            border-radius: 12px;
            padding: 0.85rem 2.1rem;
            font-size: 1.08rem;
            border: none;
            transition: all 0.18s;
            box-shadow: 0 2px 8px rgba(30,64,175,0.08);
            outline: none;
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 140px;
            text-decoration: none;
        }
        .hero-cta-group .btn:hover, .login-btn-group .btn:hover {
            background: var(--primary-dark);
            color: #fff;
            transform: translateY(-2px) scale(1.04);
            box-shadow: 0 6px 24px rgba(30,64,175,0.13);
        }
        .login-btn-group .btn:last-child {
            background: var(--primary-dark);
        }
        .features-landing {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 2.2rem;
            margin: 0 auto 2.2rem auto;
            max-width: 1000px;
        }
        .feature-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 2px 12px rgba(30,64,175,0.07);
            padding: 1.7rem 1.2rem 1.2rem 1.2rem;
            max-width: 300px;
            min-width: 220px;
            flex: 1 1 220px;
            text-align: center;
            transition: box-shadow 0.18s, transform 0.18s;
            border: 1px solid var(--border-color);
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .feature-card:hover {
            box-shadow: 0 8px 32px rgba(30,64,175,0.13);
            transform: translateY(-4px) scale(1.03);
        }
        .feature-card i {
            font-size: 2.3rem;
            color: var(--primary-dark);
            margin-bottom: 0.7rem;
        }
        .feature-card h3 {
            color: var(--primary-dark);
            font-size: 1.08rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        .feature-card p {
            color: var(--text-secondary);
            font-size: 0.97rem;
            line-height: 1.5;
            margin-bottom: 0;
        }
        @media (max-width: 900px) {
            .features-landing {
                flex-direction: column;
                align-items: center;
            }
        }
        @media (max-width: 600px) {
            .hero-landing .hero-title {
                font-size: 1.3rem;
            }
            .features-landing {
                gap: 1.1rem;
            }
            .feature-card {
                padding: 1.1rem 0.7rem;
            }
            .hero-cta-group, .login-btn-group { flex-direction: column; gap: 0.5rem; }
            .hero-cta-group .btn, .login-btn-group .btn { width: 100%; min-width: 0; }
        }
        footer {
            background: var(--primary-dark);
            color: white;
            text-align: center;
            padding: 1em 0;
            font-size: 0.97rem;
            margin-top: 1.5rem;
        }
    </style>
</head>

<body style="font-family: 'Poppins', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: var(--background-light); color: var(--text-primary); margin:0; padding:0;">

<section class="hero-landing">
    <img src="{{ asset('img/shaka_utama.png') }}" alt="logo" class="hero-logo">
    <div class="hero-title">Booking Meeting Room System</div>
    <div class="hero-bprmsa-info" style="font-size: 1.01rem; color: #e0e7ef; margin-bottom: 0.5rem; font-weight: 500; letter-spacing: 0.1px;">
        <i class="fas fa-building" style="margin-right: 5px;"></i>Powered by <b>BPR MSA</b>
    </div>
    <div class="hero-desc">Pesan ruang rapat favorit Anda dengan mudah, cepat, dan nyaman. Reservasi meeting room profesional untuk kebutuhan bisnis, komunitas, maupun acara penting Anda.</div>
    <div class="hero-cta-group">
        <a href="{{ route('register') }}" class="btn">Sign Up Now</a>
        <a href="{{ route('login') }}" class="btn">Login</a>
    </div>
    @if(auth()->check())
        <div class="info-alert" style="max-width: 420px; margin: 1.5rem auto 0 auto; background: #f3f6fd; color: var(--primary-dark); border-radius: 12px; border: 1.5px solid var(--primary-color); box-shadow: 0 4px 18px rgba(30,64,175,0.10); padding: 1.1rem 1.2rem; text-align: center;">
            <div style="font-size: 1.01rem; font-weight: 500; margin-bottom: 0.7rem; display: flex; align-items: center; justify-content: center; gap: 0.5rem;">
                <i class="fas fa-info-circle" style="font-size: 1.2rem; color: var(--primary-dark);"></i>
                <span>Anda sudah login.</span>
            </div>
            <div class="login-btn-group">
                @if(auth()->user()->user_type === 'admin')
                    <a href="{{ route('admin.dashboard') }}" class="btn" style="background: var(--primary-color); color: white; font-size: 0.97rem; padding: 0.5rem 1.2rem; border-radius: 8px; font-weight: 600; box-shadow: 0 2px 8px rgba(30,64,175,0.08); display: flex; align-items: center; gap: 6px;">
                        <i class="fas fa-home"></i> Dashboard
                    </a>
                @else
                    <a href="{{ route('user.dashboard') }}" class="btn" style="background: var(--primary-color); color: white; font-size: 0.97rem; padding: 0.5rem 1.2rem; border-radius: 8px; font-weight: 600; box-shadow: 0 2px 8px rgba(30,64,175,0.08); display: flex; align-items: center; gap: 6px;">
                        <i class="fas fa-home"></i> Dashboard
                    </a>
                @endif
                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="btn" style="background: var(--primary-dark); color: white; font-size: 0.97rem; padding: 0.5rem 1.2rem; border-radius: 8px; font-weight: 600; display: flex; align-items: center; gap: 6px;"><i class="fas fa-sign-out-alt"></i> Logout</a>
                <form id="logout-form" action="{{ route('logout') }}" method="GET" style="display: none;">@csrf</form>
            </div>
        </div>
    @endif
</section>

<section style="padding: 1.5rem 0 0.5rem 0; background: var(--background-light);">
    <h2 class="section-title" style="color: var(--primary-dark); font-size: 1.3rem; font-weight: 700; text-align: center; margin-bottom: 1.7rem; letter-spacing: -0.5px;">Fitur Unggulan</h2>
    <div class="features-landing">
        <div class="feature-card">
            <i class="fas fa-calendar-check"></i>
            <h3>Booking Online Mudah</h3>
            <p>Proses pemesanan ruang rapat yang cepat, praktis, dan bisa diakses kapan saja secara online.</p>
        </div>
        <div class="feature-card">
            <i class="fas fa-users"></i>
            <h3>Fleksibel & Kolaboratif</h3>
            <p>Berbagai pilihan ruang meeting untuk kebutuhan tim, komunitas, atau acara bisnis Anda.</p>
        </div>
        <div class="feature-card">
            <i class="fas fa-shield-alt"></i>
            <h3>Keamanan & Kenyamanan</h3>
            <p>Fasilitas terjaga, sistem terintegrasi, dan dukungan customer service yang responsif.</p>
        </div>
    </div>
</section>

<footer>
    &copy; 2024 Booking Meeting Room System. All rights reserved.
</footer>

</body>

</html>
