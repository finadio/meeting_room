@extends('admin.layouts.admin_dashboard')
@section('title', 'Admin Dashboard')

@section('content')
<div class="booking-page-wrapper">
    <div class="container-fluid px-4">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="booking-card">
                    <div class="booking-header">
                        <div class="header-icon">
                            <i class="bx bx-home-alt"></i>
                        </div>
                        <h3 class="header-title">Selamat Datang, {{ $user->name }}!</h3> 
                        <div class="header-decoration"></div>
                    </div>
                    
                    <div class="booking-content">
                        @if(session('success'))
                            <div class="custom-alert success-alert">
                                <div class="alert-icon">
                                    <i class="bx bx-check-circle"></i>
                                </div>
                                <div class="alert-content">
                                    <strong>Berhasil!</strong>
                                    <span>{{ session('success') }}</span>
                                </div>
                                <button type="button" class="alert-close" data-bs-dismiss="alert">
                                    <i class="bx bx-x"></i>
                                </button>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="custom-alert danger-alert">
                                <div class="alert-icon">
                                    <i class="bx bx-error-circle"></i>
                                </div>
                                <div class="alert-content">
                                    <strong>Error!</strong>
                                    <span>{{ session('error') }}</span>
                                </div>
                                <button type="button" class="alert-close" data-bs-dismiss="alert">
                                    <i class="bx bx-x"></i>
                                </button>
                            </div>
                        @endif

                        <div class="row mb-5">
                            <div class="col-xl-3 col-md-6 mb-4">
                                <a href="{{ route('admin.users.index') }}" class="summary-card-link">
                                    <div class="summary-card primary-summary">
                                        <div class="card-icon">
                                            <i class="bx bx-user-check"></i>
                                        </div>
                                        <div class="card-details">
                                            <div class="card-title">Total Pengguna</div>
                                            <div class="card-value">{{ $userCount }}</div>
                                        </div>
                                        <div class="card-arrow"><i class='bx bx-right-arrow-alt'></i></div>
                                    </div>
                                </a>
                            </div>

                            <div class="col-xl-3 col-md-6 mb-4">
                                <a href="{{ route('admin.bookings.index') }}" class="summary-card-link">
                                    <div class="summary-card success-summary">
                                        <div class="card-icon">
                                            <i class="bx bx-book-bookmark"></i>
                                        </div>
                                        <div class="card-details">
                                            <div class="card-title">Total Pemesanan</div>
                                            <div class="card-value">{{ $bookingCount }}</div>
                                        </div>
                                        <div class="card-arrow"><i class='bx bx-right-arrow-alt'></i></div>
                                    </div>
                                </a>
                            </div>

                            <div class="col-xl-3 col-md-6 mb-4">
                                <a href="{{ route('admin.facilities.index') }}" class="summary-card-link">
                                    <div class="summary-card info-summary">
                                        <div class="card-icon">
                                            <i class="bx bx-building"></i>
                                        </div>
                                        <div class="card-details">
                                            <div class="card-title">Total Fasilitas</div>
                                            <div class="card-value">{{ $facilityCount }}</div>
                                        </div>
                                        <div class="card-arrow"><i class='bx bx-right-arrow-alt'></i></div>
                                    </div>
                                </a>
                            </div>

                            <div class="col-xl-3 col-md-6 mb-4">
                                <a href="{{ route('admin.bookings.index', ['status' => 'Menunggu Konfirmasi']) }}" class="summary-card-link">
                                    <div class="summary-card warning-summary">
                                        <div class="card-icon">
                                            <i class="bx bx-time-five"></i>
                                        </div>
                                        <div class="card-details">
                                            <div class="card-title">Pemesanan Menunggu</div>
                                            <div class="card-value">{{ $totalPendingBookings }}</div>
                                        </div>
                                        <div class="card-arrow"><i class='bx bx-right-arrow-alt'></i></div>
                                    </div>
                                </a>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6 col-md-12 mb-4"> {{-- Pastikan col-md-12 untuk mobile --}}
                                <div class="sub-card">
                                    <div class="sub-card-header">
                                        <div class="header-icon">
                                            <i class="bx bx-pie-chart-alt-2"></i>
                                        </div>
                                        <h5 class="sub-card-title">Distribusi Pengguna Berdasarkan Tipe</h5>
                                    </div>
                                    <div class="sub-card-body">
                                        <div class="chart-container" style="position: relative; height:350px; width:100%">
                                            <canvas id="userTypeChart"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-12 mb-4"> {{-- Pastikan col-md-12 untuk mobile --}}
                                <div class="sub-card">
                                    <div class="sub-card-header">
                                        <div class="header-icon">
                                            <i class="bx bx-notepad"></i>
                                        </div>
                                        <h5 class="sub-card-title">Catatan Pemesanan Terkini (1 Bulan)</h5>
                                    </div>
                                    <div class="sub-card-body">
                                        @php
                                            $recentBookings = collect($bookedDates)->filter(function($booking) {
                                                return isset($booking['bookingDate']) && \Carbon\Carbon::parse($booking['bookingDate'])->greaterThanOrEqualTo(\Carbon\Carbon::now()->subMonth());
                                            })->sortByDesc(function($booking) {
                                                return ($booking['bookingDate'] ?? '') . ' ' . ($booking['bookingTime'] ?? '');
                                            })->take(5);
                                        @endphp

                                        @if($recentBookings->isEmpty())
                                            <div class="alert alert-info text-center m-2 p-2 rounded-lg shadow-sm">
                                                Tidak ada pemesanan terkini dalam 1 bulan terakhir.
                                            </div>
                                        @else
                                            <div class="recent-bookings-list">
                                                @foreach($recentBookings as $booking)
                                                    <div class="booking-note-item">
                                                        <div class="note-icon">
                                                            @if(($booking['bookingStatus'] ?? '') == 'Disetujui')
                                                                <i class='bx bx-check-circle text-success'></i>
                                                            @elseif(($booking['bookingStatus'] ?? '') == 'Menunggu Konfirmasi')
                                                                <i class='bx bx-time text-warning'></i>
                                                            @else
                                                                <i='bx bx-x-circle text-danger'></i>
                                                            @endif
                                                        </div>
                                                        <div class="note-content">
                                                            <p class="note-title">{{ $booking['title'] ?? 'N/A' }}</p>
                                                            <p class="note-meta">
                                                                <i class='bx bx-calendar'></i> {{ isset($booking['bookingDate']) ? \Carbon\Carbon::parse($booking['bookingDate'])->format('d M Y') : 'N/A' }}
                                                                <i class='bx bx-time'></i> {{ isset($booking['bookingTime']) ? \Carbon\Carbon::parse($booking['bookingTime'])->format('H:i') : 'N/A' }}
                                                                <span class="badge {{ ($booking['bookingStatus'] ?? '') == 'Disetujui' ? 'badge-success-custom' : (($booking['bookingStatus'] ?? '') == 'Menunggu Konfirmasi' ? 'badge-info-custom' : 'badge-danger-custom') }}">{{ $booking['bookingStatus'] ?? 'N/A' }}</span>
                                                            </p>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                            <div class="text-center mt-3">
                                                <a href="{{ route('admin.bookings.index') }}" class="view-all-notes-link">
                                                    Lihat Semua Pemesanan <i class='bx bx-right-arrow-alt'></i>
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
{{-- HAPUS LINK INI - BOOTSTRAP SUDAH DIMUAT DI ADMIN_DASHBOARD.BLADE.PHP --}}
{{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/css/bootstrap.min.css" rel="stylesheet"> --}}
<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
<style>
    /* Page Wrapper */
    .booking-page-wrapper {
        min-height: 100vh;
        background: rgba(255, 255, 255, 0.95);
        padding: 40px 0;
        position: relative;
    }

    .booking-page-wrapper::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="%23ffffff" opacity="0.05"/><circle cx="75" cy="75" r="1" fill="%23ffffff" opacity="0.05"/><circle cx="50" cy="10" r="1" fill="%23ffffff" opacity="0.03"/><circle cx="10" cy="50" r="1" fill="%23ffffff" opacity="0.03"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
        pointer-events: none;
    }

    /* Main Card */
    .booking-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border-radius: 24px;
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
        overflow: hidden;
        position: relative;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .booking-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0; /* Changed height to bottom: 0 to cover full card */
        background: radial-gradient(circle at 100% 0%, rgba(102, 126, 234, 0.05) 0%, transparent 50%),
                    radial-gradient(circle at 0% 100%, rgba(102, 126, 234, 0.05) 0%, transparent 50%); /* Subtle radial gradients */
        pointer-events: none;
        z-index: 0;
    }


    /* Header */
    .booking-header {
        background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        padding: 30px;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .booking-header::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
        animation: rotate 8s linear infinite;
    }

    @keyframes rotate {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }

    .header-icon {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        width: 70px;
        height: 70px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        position: relative;
        z-index: 2;
        box-shadow: 0 10px 25px rgba(79, 172, 254, 0.3);
    }

    .header-icon i {
        font-size: 2rem;
        color: white;
    }

    .header-title {
        color: white;
        font-size: 1.8rem;
        font-weight: 700;
        margin: 0;
        position: relative;
        z-index: 2;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
    }

    .header-decoration {
        width: 60px;
        height: 4px;
        background: linear-gradient(90deg, #4facfe, #00f2fe);
        margin: 15px auto 0;
        border-radius: 2px;
        position: relative;
        z-index: 2;
    }

    /* Content */
    .booking-content {
        padding: 40px;
        position: relative; /* Needed for z-index of content */
        z-index: 1;
    }

    /* Alerts */
    .custom-alert {
        display: flex;
        align-items: center;
        padding: 20px;
        border-radius: 16px;
        margin-bottom: 30px;
        position: relative;
        overflow: hidden;
        border: none;
        animation: slideInDown 0.5s ease-out;
    }

    @keyframes slideInDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .success-alert {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        color: white;
    }

    .danger-alert {
        background: linear-gradient(135deg, #ff416c 0%, #ff4b2b 100%);
        color: white;
    }

    .alert-icon {
        font-size: 1.5rem;
        margin-right: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 50%;
    }

    .alert-content {
        flex: 1;
    }

    .alert-content strong {
        display: block;
        font-size: 1rem;
        margin-bottom: 2px;
    }

    .alert-close {
        background: none;
        border: none;
        color: white;
        font-size: 1.2rem;
        cursor: pointer;
        padding: 5px;
        border-radius: 50%;
        transition: background-color 0.3s ease;
    }

    .alert-close:hover {
        background: rgba(255, 255, 255, 0.2);
    }

    /* Summary Cards */
    .summary-card-link {
        text-decoration: none; /* Remove underline from links */
        display: block; /* Make the whole column clickable */
        color: inherit; /* Inherit text color */
        position: relative; /* For ripple effect, if applied globally */
    }

    .summary-card {
        background: white;
        border-radius: 16px;
        padding: 25px;
        display: flex;
        align-items: center;
        gap: 20px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        border-left: 6px solid;
        position: relative; /* Untuk card-arrow */
        overflow: hidden; 
        z-index: 1; /* Agar card-arrow tidak menimpa text */
    }

    .summary-card::before { /* Untuk efek overlay/pattern di dalam kartu */
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain-card" width="10" height="10" patternUnits="userSpaceOnUse"><circle cx="1" cy="1" r="0.5" fill="%23000000" opacity="0.03"/><circle cx="6" cy="6" r="0.5" fill="%23000000" opacity="0.03"/></pattern></defs><rect width="100" height="100" fill="url(%23grain-card)"/></svg>');
        opacity: 0.2;
        pointer-events: none;
        z-index: 0;
    }

    .summary-card:hover {
        transform: translateY(-8px); /* Lebih dinamis */
        box-shadow: 0 20px 45px rgba(0, 0, 0, 0.25); /* Bayangan lebih dalam */
        filter: brightness(1.08); /* Lebih cerah saat hover */
    }
    
    .summary-card .card-arrow {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%) translateX(20px); /* Mulai di luar sedikit */
        font-size: 2rem; /* Ukuran lebih besar */
        color: rgba(0,0,0,0.15); /* Warna samar */
        transition: transform 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94), color 0.3s ease; /* Transisi lebih halus */
        z-index: 1;
    }

    .summary-card:hover .card-arrow {
        transform: translateY(-50%) translateX(0); /* Masuk ke dalam saat hover */
        color: rgba(0,0,0,0.4); /* Lebih jelas saat hover */
    }

    .summary-card .card-icon {
        width: 70px; /* Lebih besar */
        height: 70px; /* Lebih besar */
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem; /* Ikon lebih besar */
        color: white;
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2); /* Bayangan lebih jelas */
        transition: all 0.3s ease;
    }

    .summary-card:hover .card-icon {
        transform: scale(1.05); /* Membesar sedikit saat hover */
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
    }

    .summary-card .card-details {
        flex-grow: 1;
        z-index: 2; /* Pastikan detail di atas arrow dan overlay */
    }

    .summary-card .card-title {
        font-size: 1.1rem; /* Sedikit lebih besar */
        color: #4a5568; /* Warna teks lebih gelap */
        margin-bottom: 8px; /* Spasi lebih banyak */
        font-weight: 600;
        letter-spacing: 0.5px; /* Sedikit spasi antar huruf */
    }

    .summary-card .card-value {
        font-size: 2.8rem; /* Ukuran angka paling besar */
        font-weight: 800; /* Sangat tebal */
        color: #1e3c72; /* Warna angka lebih gelap */
        margin-top: 0; 
        margin-bottom: 0; 
        line-height: 1.1; /* Jarak baris lebih rapat */
        text-shadow: 1px 1px 2px rgba(0,0,0,0.05); /* Bayangan teks samar */
    }

    /* Specific Summary Card Colors */
    .primary-summary { border-color: #667eea; }
    .primary-summary .card-icon { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
    .primary-summary .card-value { color: #667eea; } /* Sesuaikan warna angka dengan tema */

    .success-summary { border-color: #38ef7d; }
    .success-summary .card-icon { background: linear-gradient(135deg, #38ef7d 0%, #11998e 100%); }
    .success-summary .card-value { color: #11998e; }

    .info-summary { border-color: #4facfe; }
    .info-summary .card-icon { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }
    .info-summary .card-value { color: #00f2fe; }

    .warning-summary { border-color: #fbc02d; }
    .warning-summary .card-icon { background: linear-gradient(135deg, #fbc02d 0%, #f57f17 100%); }
    .warning-summary .card-value { color: #f57f17; }


    /* Sub-Card for Charts and Notes */
    .sub-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        margin-top: 30px;
        position: relative;
        z-index: 1; /* Pastikan sub-card di atas overlay booking-card */
    }

    .sub-card-header {
        background: linear-gradient(135deg, #e0e7ff 0%, #c3dafe 100%);
        padding: 20px 30px;
        display: flex;
        align-items: center;
        gap: 15px;
        border-bottom: 2px solid #a7b7ff;
    }

    .sub-card-header i {
        font-size: 1.5rem;
        color: #1e3c72;
        background: rgba(30, 60, 114, 0.1);
        padding: 8px;
        border-radius: 8px;
    }

    .sub-card-title {
        font-size: 1.3rem;
        font-weight: 700;
        color: #1e3c72;
        margin: 0;
    }

    .sub-card-body {
        padding: 30px;
    }

    /* Chart Container (for user distribution) */
    .chart-container {
        padding: 20px;
        background: #fdfefe;
        border-radius: 12px;
        box-shadow: inset 0 0 5px rgba(0,0,0,0.05); /* Inner shadow for depth */
        position: relative;
        width: 100%;
        min-height: 300px;
        max-height: 400px;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    /* Chart responsive improvements */
    .chart-container canvas {
        max-width: 100% !important;
        height: auto !important;
        display: block;
    }
    
    /* Sub-card containing chart */
    .sub-card {
        height: 100%;
        display: flex;
        flex-direction: column;
        background: white;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(30,64,175,0.07);
        border: 1px solid var(--border-color);
        overflow: hidden;
    }
    
    .sub-card-header {
        padding: 20px 25px 15px 25px;
        border-bottom: 1px solid #e2e8f0;
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    }
    
    .sub-card-body {
        flex: 1;
        display: flex;
        flex-direction: column;
        padding: 20px 25px 25px 25px;
    }
    
    .sub-card-body .chart-container {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 300px;
    }
    
    /* Ensure chart maintains aspect ratio */
    .chart-container canvas {
        aspect-ratio: 1;
        max-width: 100%;
        max-height: 100%;
    }
    
    /* Desktop optimizations */
    @media (min-width: 1200px) {
        .chart-container {
            min-height: 350px;
            max-height: 450px;
        }
        
        .chart-container canvas {
            max-width: 85% !important;
            max-height: 85% !important;
        }
    }
    
    /* Medium desktop landscape */
    @media (min-width: 1200px) and (max-width: 1400px) and (orientation: landscape) {
        .chart-container {
            min-height: 380px;
            max-height: 480px;
        }
        
        .chart-container canvas {
            max-width: 82% !important;
            max-height: 82% !important;
        }
    }
    
    /* Medium desktop portrait */
    @media (min-width: 1200px) and (max-width: 1400px) and (orientation: portrait) {
        .chart-container {
            min-height: 420px;
            max-height: 520px;
        }
        
        .chart-container canvas {
            max-width: 88% !important;
            max-height: 88% !important;
        }
    }
    
    /* Large desktop optimizations */
    @media (min-width: 1400px) {
        .chart-container {
            min-height: 400px;
            max-height: 500px;
        }
        
        .chart-container canvas {
            max-width: 80% !important;
            max-height: 80% !important;
        }
    }
    
    /* Large desktop landscape */
    @media (min-width: 1400px) and (max-width: 1920px) and (orientation: landscape) {
        .chart-container {
            min-height: 430px;
            max-height: 530px;
        }
        
        .chart-container canvas {
            max-width: 78% !important;
            max-height: 78% !important;
        }
    }
    
    /* Large desktop portrait */
    @media (min-width: 1400px) and (max-width: 1920px) and (orientation: portrait) {
        .chart-container {
            min-height: 480px;
            max-height: 580px;
        }
        
        .chart-container canvas {
            max-width: 85% !important;
            max-height: 85% !important;
        }
    }
    
    /* 4K and ultra-wide screens */
    @media (min-width: 1920px) {
        .chart-container {
            min-height: 450px;
            max-height: 550px;
        }
        
        .chart-container canvas {
            max-width: 75% !important;
            max-height: 75% !important;
        }
    }
    
    /* 4K landscape mode */
    @media (min-width: 1920px) and (orientation: landscape) {
        .chart-container {
            min-height: 500px;
            max-height: 600px;
        }
        
        .chart-container canvas {
            max-width: 72% !important;
            max-height: 72% !important;
        }
    }
    
    /* 4K portrait mode */
    @media (min-width: 1920px) and (orientation: portrait) {
        .chart-container {
            min-height: 550px;
            max-height: 650px;
        }
        
        .chart-container canvas {
            max-width: 78% !important;
            max-height: 78% !important;
        }
    }
    
    /* Ultra-wide screens */
    @media (min-width: 2560px) {
        .chart-container {
            min-height: 600px;
            max-height: 700px;
        }
        
        .chart-container canvas {
            max-width: 70% !important;
            max-height: 70% !important;
        }
    }
    
    /* Ultra-wide landscape mode */
    @media (min-width: 2560px) and (orientation: landscape) {
        .chart-container {
            min-height: 650px;
            max-height: 750px;
        }
        
        .chart-container canvas {
            max-width: 68% !important;
            max-height: 68% !important;
        }
    }
    
    /* Ultra-wide portrait mode */
    @media (min-width: 2560px) and (orientation: portrait) {
        .chart-container {
            min-height: 700px;
            max-height: 800px;
        }
        
        .chart-container canvas {
            max-width: 75% !important;
            max-height: 75% !important;
        }
    }
    
    /* 5K screens */
    @media (min-width: 5120px) {
        .chart-container {
            min-height: 800px;
            max-height: 900px;
        }
        
        .chart-container canvas {
            max-width: 65% !important;
            max-height: 65% !important;
        }
    }
    
    /* 5K landscape mode */
    @media (min-width: 5120px) and (orientation: landscape) {
        .chart-container {
            min-height: 850px;
            max-height: 950px;
        }
        
        .chart-container canvas {
            max-width: 62% !important;
            max-height: 62% !important;
        }
    }
    
    /* 5K portrait mode */
    @media (min-width: 5120px) and (orientation: portrait) {
        .chart-container {
            min-height: 900px;
            max-height: 1000px;
        }
        
        .chart-container canvas {
            max-width: 70% !important;
            max-height: 70% !important;
        }
    }
    
    /* 8K screens */
    @media (min-width: 7680px) {
        .chart-container {
            min-height: 1000px;
            max-height: 1100px;
        }
        
        .chart-container canvas {
            max-width: 60% !important;
            max-height: 60% !important;
        }
    }
    
    /* 8K landscape mode */
    @media (min-width: 7680px) and (orientation: landscape) {
        .chart-container {
            min-height: 1100px;
            max-height: 1200px;
        }
        
        .chart-container canvas {
            max-width: 58% !important;
            max-height: 58% !important;
        }
    }
    
    /* 8K portrait mode */
    @media (min-width: 7680px) and (orientation: portrait) {
        .chart-container {
            min-height: 1200px;
            max-height: 1300px;
        }
        
        .chart-container canvas {
            max-width: 65% !important;
            max-height: 65% !important;
        }
    }
    
    /* 10K screens */
    @media (min-width: 10240px) {
        .chart-container {
            min-height: 1300px;
            max-height: 1400px;
        }
        
        .chart-container canvas {
            max-width: 55% !important;
            max-height: 55% !important;
        }
    }
    
    /* 10K landscape mode */
    @media (min-width: 10240px) and (orientation: landscape) {
        .chart-container {
            min-height: 1400px;
            max-height: 1500px;
        }
        
        .chart-container canvas {
            max-width: 52% !important;
            max-height: 52% !important;
        }
    }
    
    /* 10K portrait mode */
    @media (min-width: 10240px) and (orientation: portrait) {
        .chart-container {
            min-height: 1500px;
            max-height: 1600px;
        }
        
        .chart-container canvas {
            max-width: 60% !important;
            max-height: 60% !important;
        }
    }
    
    /* 12K screens */
    @media (min-width: 12288px) {
        .chart-container {
            min-height: 1600px;
            max-height: 1700px;
        }
        
        .chart-container canvas {
            max-width: 50% !important;
            max-height: 50% !important;
        }
    }
    
    /* 12K landscape mode */
    @media (min-width: 12288px) and (orientation: landscape) {
        .chart-container {
            min-height: 1700px;
            max-height: 1800px;
        }
        
        .chart-container canvas {
            max-width: 48% !important;
            max-height: 48% !important;
        }
    }
    
    /* Badges (re-used from other files) */
    .badge-success-custom {
        background-color: #38ef7d;
        color: white;
        padding: 5px 9px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.75em;
    }
    .badge-danger-custom {
        background-color: #ff416c;
        color: white;
        padding: 5px 9px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.75em;
    }
    .badge-secondary-custom {
        background-color: #6c757d;
        color: white;
        padding: 5px 9px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.75em;
    }

    /* Recent Bookings Notes Section */
    .recent-bookings-list {
        max-height: 400px; /* Tinggi maksimal untuk scroll */
        overflow-y: auto; /* Aktifkan scroll jika melebihi tinggi maksimal */
        padding-right: 10px; /* Untuk estetika scrollbar */
    }

    .booking-note-item {
        display: flex;
        align-items: center;
        background: #fcfdfe;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        margin-bottom: 15px;
        padding: 15px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.03);
        transition: all 0.3s ease;
    }

    .booking-note-item:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    .note-icon {
        flex-shrink: 0;
        font-size: 1.8rem;
        margin-right: 15px;
        color: #667eea; /* Warna default icon notes */
    }

    .note-icon .bx-check-circle { color: #38ef7d; }
    .note-icon .bx-time { color: #f57f17; }
    .note-icon .bx-x-circle { color: #ff416c; }

    .note-content {
        flex-grow: 1;
    }

    .note-title {
        font-weight: 600;
        color: #1e3c72;
        margin-bottom: 5px;
        font-size: 1rem;
        line-height: 1.4;
    }

    .note-meta {
        font-size: 0.85rem;
        color: #6c757d;
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .note-meta i {
        font-size: 1rem;
        color: #4facfe;
    }

    .view-all-notes-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: #667eea;
        text-decoration: none;
        font-weight: 600;
        font-size: 0.95rem;
        transition: color 0.3s ease;
    }

    .view-all-notes-link:hover {
        color: #4facfe;
        text-decoration: underline;
    }

    /* Responsive Design (for dashboard specific components) */
    @media (max-width: 1199.98px) { /* xl breakpoint in Bootstrap 5, for larger laptops/desktops */
        .dashboard-stats-row .col-xl-3 {
            flex: 0 0 50%; /* 2 kartu per baris */
            max-width: 50%;
        }
    }

    @media (max-width: 991.98px) { /* lg breakpoint in Bootstrap 5, for tablets and smaller laptops */
        .dashboard-stats-row .col-lg-3, /* This rule is often used by Bootstrap 4 cols, so it's a good target */
        .dashboard-stats-row .col-md-6, /* Also target md cols */
        .dashboard-stats-row .col-xl-3 { /* Target xl cols as well for stacking */
            flex: 0 0 100%; /* 1 kartu per baris */
            max-width: 100%;
        }
        .summary-card {
            flex-direction: column; /* Stack icon and details vertically */
            text-align: center;
            align-items: center; /* Center content when stacked */
            padding: 20px 15px; /* Adjust padding for mobile */
        }
        .summary-card .card-icon {
            margin-bottom: 10px; /* Space between icon and text when stacked */
        }
        .summary-card .card-arrow {
            position: relative; /* Allow it to flow naturally */
            transform: none; /* Reset transform */
            margin-top: 10px;
            color: rgba(0,0,0,0.2); /* Make it subtle */
        }
        .dashboard-welcome-title {
            font-size: 2rem;
        }
        .dashboard-content {
            padding: 20px; /* Kurangi padding utama */
        }
        /* Chart and recent bookings section */
        .col-lg-6 { /* Pastikan kolom ini menumpuk di bawah 991px */
            flex: 0 0 100%;
            max-width: 100%;
        }
        
        /* Ensure chart cards stack properly on tablets */
        .row > .col-lg-6 {
            margin-bottom: 20px;
        }
        
        .sub-card {
            height: auto;
            min-height: 400px;
        }
        
        /* Improve chart container on tablets */
        .chart-container {
            min-height: 280px;
            max-height: 350px;
        }
        
        .chart-container canvas {
            max-width: 90% !important;
            max-height: 90% !important;
        }
        
        /* Tablet landscape mode */
        @media (min-width: 768px) and (max-width: 1024px) and (orientation: landscape) {
            .chart-container {
                height: 320px !important;
                min-height: 300px;
            }
            
            .chart-container canvas {
                max-height: 300px !important;
            }
        }
        
        /* Tablet portrait mode */
        @media (min-width: 768px) and (max-width: 1024px) and (orientation: portrait) {
            .chart-container {
                height: 380px !important;
                min-height: 350px;
            }
            
            .chart-container canvas {
                max-height: 360px !important;
            }
        }
        
        /* Large tablet landscape mode */
        @media (min-width: 1024px) and (max-width: 1366px) and (orientation: landscape) {
            .chart-container {
                height: 400px !important;
                min-height: 380px;
            }
            
            .chart-container canvas {
                max-height: 380px !important;
            }
        }
        
        /* Large tablet portrait mode */
        @media (min-width: 1024px) and (max-width: 1366px) and (orientation: portrait) {
            .chart-container {
                height: 450px !important;
                min-height: 420px;
            }
            
            .chart-container canvas {
                max-height: 430px !important;
            }
        }
        
        /* Small tablet landscape mode */
        @media (min-width: 600px) and (max-width: 767px) and (orientation: landscape) {
            .chart-container {
                height: 300px !important;
                min-height: 280px;
            }
            
            .chart-container canvas {
                max-height: 280px !important;
            }
        }
        
        /* Small tablet portrait mode */
        @media (min-width: 600px) and (max-width: 767px) and (orientation: portrait) {
            .chart-container {
                height: 350px !important;
                min-height: 320px;
            }
            
            .chart-container canvas {
                max-height: 330px !important;
            }
        }
    }

    @media (max-width: 767.98px) { /* md breakpoint in Bootstrap 5, for phones and small tablets */
        .booking-page-wrapper {
            padding: 15px 0;
        }
        .booking-content {
            padding: 25px 15px;
        }
        .booking-header {
            padding: 20px 15px;
        }
        .header-title {
            font-size: 1.4rem;
        }
        .header-icon {
            width: 50px;
            height: 50px;
            font-size: 1.5rem;
        }
        /* Sub-cards and content inside them */
        .sub-card-header {
            flex-direction: column; /* Tumpuk header sub-card */
            text-align: center;
        }
        .sub-card-header .header-icon {
            margin-bottom: 10px;
        }
        .sub-card-title {
            font-size: 1.1rem;
        }
        .chart-container {
            height: 300px !important;
        }
        .recent-bookings-list {
            max-height: 250px; /* Sesuaikan tinggi daftar recent bookings */
        }
        .booking-note-item {
            flex-direction: column; /* Tumpuk item note jika terlalu kecil */
            text-align: center;
        }
        .note-icon {
            margin-right: 0;
            margin-bottom: 10px;
        }
        .note-meta {
            justify-content: center; /* Pusatkan meta info */
        }
    }

    @media (max-width: 575.98px) { /* sm breakpoint in Bootstrap 5, for very small phones */
        .container-fluid {
            padding-left: 10px;
            padding-right: 10px;
        }
        .booking-content {
            padding: 20px 10px;
        }
        .header-title {
            font-size: 1.2rem;
        }
        
        .chart-container {
            height: 250px !important;
            min-height: 200px;
            padding: 10px;
        }
        
        .chart-container canvas {
            max-height: 230px !important;
        }
        
        /* Make chart legend smaller on mobile */
        .chart-container .chartjs-legend {
            font-size: 10px !important;
        }
        
        /* Ensure sub-cards stack properly on mobile */
        .sub-card {
            margin-bottom: 20px;
        }
        
        /* Improve chart responsiveness on very small screens */
        .chart-container {
            aspect-ratio: auto;
        }
        
        .chart-container canvas {
            aspect-ratio: auto;
            width: 100% !important;
            height: auto !important;
        }
        
        /* Landscape mode on mobile */
        @media (max-width: 576px) and (orientation: landscape) {
            .chart-container {
                height: 200px !important;
                min-height: 180px;
            }
            
            .chart-container canvas {
                max-height: 180px !important;
            }
        }
        
        /* Landscape mode on small mobile */
        @media (max-width: 480px) and (orientation: landscape) {
            .chart-container {
                height: 160px !important;
                min-height: 140px;
            }
            
            .chart-container canvas {
                max-height: 140px !important;
            }
        }
        
        /* Adjust sub-card padding on mobile */
        .sub-card-header {
            padding: 15px 20px 10px 20px;
        }
        
        .sub-card-body {
            padding: 15px 20px 20px 20px;
        }
        
        /* Portrait mode on mobile */
        @media (max-width: 576px) and (orientation: portrait) {
            .chart-container {
                height: 280px !important;
                min-height: 250px;
            }
            
            .chart-container canvas {
                max-height: 260px !important;
            }
        }
        
        /* Portrait mode on small mobile */
        @media (max-width: 480px) and (orientation: portrait) {
            .chart-container {
                height: 240px !important;
                min-height: 220px;
            }
            
            .chart-container canvas {
                max-height: 220px !important;
            }
        }
        
        /* Landscape mode on medium mobile */
        @media (min-width: 481px) and (max-width: 576px) and (orientation: landscape) {
            .chart-container {
                height: 220px !important;
                min-height: 200px;
            }
            
            .chart-container canvas {
                max-height: 200px !important;
            }
        }
        
        /* Portrait mode on medium mobile */
        @media (min-width: 481px) and (max-width: 576px) and (orientation: portrait) {
            .chart-container {
                height: 300px !important;
                min-height: 280px;
            }
            
            .chart-container canvas {
                max-height: 280px !important;
            }
        }
        
        /* Very small screens */
        @media (max-width: 375px) {
            .chart-container {
                height: 220px !important;
                min-height: 200px;
                padding: 10px;
            }
            
            .chart-container canvas {
                max-height: 200px !important;
            }
            
            .sub-card-header {
                padding: 10px 15px 8px 15px;
            }
            
            .sub-card-body {
                padding: 10px 15px 15px 15px;
            }
        }
        
        /* Extra small screens */
        @media (max-width: 320px) {
            .chart-container {
                height: 180px !important;
                min-height: 160px;
                padding: 8px;
            }
            
            .chart-container canvas {
                max-height: 160px !important;
            }
            
            .sub-card-header {
                padding: 8px 12px 6px 12px;
            }
            
            .sub-card-body {
                padding: 8px 12px 12px 12px;
            }
        }
        
        /* Extra small screens landscape */
        @media (max-width: 320px) and (orientation: landscape) {
            .chart-container {
                height: 120px !important;
                min-height: 100px;
                padding: 6px;
            }
            
            .chart-container canvas {
                max-height: 100px !important;
            }
        }
        
        /* Extra small screens portrait */
        @media (max-width: 320px) and (orientation: portrait) {
            .chart-container {
                height: 160px !important;
                min-height: 140px;
                padding: 8px;
            }
            
            .chart-container canvas {
                max-height: 140px !important;
            }
        }
    }

    .dashboard-stats-wrapper {
        width: 100%;
        display: flex;
        justify-content: center;
    }
    .dashboard-stats-cards {
        display: flex;
        flex-wrap: wrap;
        gap: 24px;
        width: 100%;
        justify-content: center;
    }
    .dashboard-stats-cards .summary-card-link {
        flex: 1 1 220px;
        max-width: 320px;
        min-width: 220px;
        margin: 0;
    }
    @media (max-width: 992px) {
        .dashboard-stats-cards {
            flex-direction: column;
            gap: 18px;
            align-items: stretch;
        }
        .dashboard-stats-cards .summary-card-link {
            max-width: 100%;
            min-width: 0;
        }
    }
</style>
@endsection

@section('scripts')
{{-- Memuat Chart.js dari CDN --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Alert close functionality
    document.querySelectorAll('.alert-close').forEach(button => {
        button.addEventListener('click', function() {
            this.closest('.custom-alert').style.display = 'none';
        });
    });

    // Inisialisasi Chart.js untuk Distribusi Pengguna
    document.addEventListener('DOMContentLoaded', function () {
        var ctx = document.getElementById('userTypeChart');
        if (ctx) {
            // Pastikan variabel userCounts diteruskan dari controller
            var userCounts = @json($userCounts);

            var userChart = new Chart(ctx, {
                type: 'doughnut', // Atau 'pie' atau 'bar'
                data: {
                    labels: ['Admin', 'Pengguna'], // Sesuaikan label sesuai user_type Anda
                    datasets: [{
                        label: 'Jumlah Pengguna',
                        data: [userCounts.Admin, userCounts.User], // Menggunakan .Admin dan .User sesuai struktur di controller
                        backgroundColor: [
                            'rgba(102, 126, 234, 0.8)', // Primary blue
                            'rgba(56, 239, 125, 0.8)'  // Success green
                        ],
                        hoverOffset: 10, // Efek hover saat mouse di atas segmen
                        borderColor: [
                            '#fff',
                            '#fff'
                        ],
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                color: '#4a5568',
                                font: {
                                    size: 14
                                },
                                padding: 15,
                                usePointStyle: true,
                                pointStyle: 'circle'
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    if (context.parsed !== null) {
                                        label += context.parsed + ' pengguna'; // Tambah teks 'pengguna'
                                    }
                                    return label;
                                }
                            }
                        },
                        title: {
                            display: true,
                            text: 'Distribusi Pengguna Berdasarkan Tipe',
                            color: '#1e3c72',
                            font: {
                                size: 18,
                                weight: 'bold'
                            },
                            padding: {
                                top: 10,
                                bottom: 20
                            }
                        }
                    }
                }
            });
            
            // Handle window resize for better responsiveness
            window.addEventListener('resize', function() {
                if (userChart) {
                    userChart.resize();
                }
            });
            
            // Handle orientation change on mobile
            window.addEventListener('orientationchange', function() {
                setTimeout(function() {
                    if (userChart) {
                        userChart.resize();
                    }
                }, 100);
            });
        }
    });
</script>
@endsection