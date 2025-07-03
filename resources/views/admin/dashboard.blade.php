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
                        {{-- Menggunakan variabel $user yang diteruskan dari controller --}}
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
                            <div class="col-lg-6 mb-4">
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

                            <div class="col-lg-6 mb-4">
                                <div class="sub-card">
                                    <div class="sub-card-header">
                                        <div class="header-icon">
                                            <i class="bx bx-notepad"></i>
                                        </div>
                                        <h5 class="sub-card-title">Catatan Pemesanan Terkini (1 Bulan)</h5>
                                    </div>
                                    <div class="sub-card-body">
                                        @php
                                            // Filter bookings for the last month and sort by date/time
                                            // Pastikan $bookedDates ini sudah berisi semua booking yang dimuat dari controller
                                            $recentBookings = collect($bookedDates)->filter(function($booking) {
                                                // Memastikan kunci 'bookingDate' ada sebelum mencoba mengurai
                                                return isset($booking['bookingDate']) && \Carbon\Carbon::parse($booking['bookingDate'])->greaterThanOrEqualTo(\Carbon\Carbon::now()->subMonth());
                                            })->sortByDesc(function($booking) {
                                                // Memastikan kunci 'bookingDate' dan 'bookingTime' ada untuk pengurutan
                                                return ($booking['bookingDate'] ?? '') . ' ' . ($booking['bookingTime'] ?? '');
                                            })->take(5); // Ambil 5 booking terbaru untuk ditampilkan
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
                                                                <i class='bx bx-x-circle text-danger'></i>
                                                            @endif
                                                        </div>
                                                        <div class="note-content">
                                                            {{-- Menggunakan 'title' yang sudah disiapkan di controller --}}
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
<link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/css/bootstrap.min.css" rel="stylesheet">
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

    /* Responsive Design */
    @media (max-width: 992px) {
        .summary-card {
            flex-direction: column;
            text-align: center;
            padding: 20px;
        }
        .summary-card .card-icon { margin-bottom: 15px; }
        .summary-card .card-arrow {
            position: static; /* Let it flow with content */
            transform: none;
            margin-top: 10px;
            color: rgba(0,0,0,0.3);
        }
    }

    @media (max-width: 768px) {
        .booking-page-wrapper {
            padding: 20px 0;
        }
        .booking-content {
            padding: 30px 20px;
        }
        .booking-header {
            padding: 25px 20px;
        }
        .header-title {
            font-size: 1.5rem;
        }
        .header-icon {
            width: 60px;
            height: 60px;
        }
        .header-icon i {
            font-size: 1.5rem;
        }

        .chart-container {
            height: 300px !important;
        }

        .recent-bookings-list {
            max-height: 300px;
        }

        .note-icon {
            font-size: 1.5rem;
            margin-right: 10px;
        }

        .note-title {
            font-size: 0.9rem;
        }

        .note-meta {
            font-size: 0.8rem;
            gap: 5px;
        }
    }

    @media (max-width: 576px) {
        .container-fluid {
            padding-left: 15px;
            padding-right: 15px;
        }
        .booking-content {
            padding: 25px 15px;
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

            new Chart(ctx, {
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
                                }
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
        }
    });
</script>
@endsection