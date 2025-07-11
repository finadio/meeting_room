@extends('admin.layouts.admin_dashboard')
@section('title', 'Admin Dashboard')

@section('content')
<div class="booking-page-wrapper"  style="display: flex; min-height: 100vh;">
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
                                    </div>
                                </a>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6 col-md-12 mb-4">
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

                            <div class="col-lg-6 col-md-12 mb-4">
                                <div class="sub-card">
                                    <div class="sub-card-header">
                                        <div class="header-icon">
                                            <i class="bx bx-notepad"></i>
                                        </div>
                                        <h5 class="sub-card-title">Catatan Pemesanan Terkini (1 Bulan)</h5>
                                    </div>
                                    <div class="sub-card-body">
                                        @php
                                            // MODIFIED: Filter dan sort menggunakan kunci 'start' dan 'status' dari $bookedDates
                                            // Removed ->take(5) to show all recent bookings
                                            $recentBookings = collect($bookedDates)->filter(function($booking) {
                                                // Pastikan 'start' ada sebelum parsing
                                                return isset($booking['start']) && \Carbon\Carbon::parse($booking['start'])->greaterThanOrEqualTo(\Carbon\Carbon::now()->subMonth());
                                            })->sortByDesc(function($booking) {
                                                // Urutkan berdasarkan tanggal dan waktu mulai
                                                return $booking['start'];
                                            });
                                        @endphp

                                        @if($recentBookings->isEmpty())
                                            <div class="alert alert-info text-center m-2 p-2 rounded-lg shadow-sm">
                                                Tidak ada pemesanan terkini dalam 1 bulan terakhir.
                                            </div>
                                        @else
                                            <div class="recent-bookings-list">
                                                @foreach($recentBookings as $booking)
                                                    @php
                                                        // MODIFIED: Gunakan kunci 'status' dan parse 'start' untuk tanggal/waktu
                                                        $status = $booking['status'] ?? 'N/A';
                                                        $bookingDate = \Carbon\Carbon::parse($booking['start']);
                                                        $bookingTime = \Carbon\Carbon::parse($booking['start']);
                                                    @endphp
                                                    <div class="booking-note-item">
                                                        <div class="note-icon">
                                                            @if($status == 'Disetujui')
                                                                <i class='bx bx-check-circle text-success'></i>
                                                            @elseif($status == 'Menunggu Konfirmasi')
                                                                <i class='bx bx-time text-warning'></i>
                                                            @else
                                                                <i class='bx bx-x-circle text-danger'></i>
                                                            @endif
                                                        </div>
                                                        <div class="note-content">
                                                            <p class="note-title">{{ $booking['title'] ?? 'N/A' }}</p>
                                                            <p class="note-meta">
                                                                <i class='bx bx-calendar'></i> {{ $bookingDate->format('d M Y') }}
                                                                <i class='bx bx-time'></i> {{ $bookingTime->format('H:i') }}
                                                                <span class="badge {{ $status == 'Disetujui' ? 'badge-success-custom' : ($status == 'Menunggu Konfirmasi' ? 'badge-info-custom' : 'badge-danger-custom') }}">{{ $status }}</span>
                                                            </p>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                            <div class="text-center mt-3">
                                                <a href="{{ route('admin.bookings.index') }}" class="view-all-notes-link">
                                                    Lihat Semua Pemesanan
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
</div>
@endsection

@section('styles')
<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
<style>
    /* Base styles */
    * {
        box-sizing: border-box;
    }

    .booking-page-wrapper {
        min-height: 100vh;
        background: rgba(255, 255, 255, 0.95);
        padding: 20px 0;
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

    /*Main Card */
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
        height: 2px;
        background: linear-gradient(90deg, #667eea, #764ba2, #f093fb, #f5576c);
        background-size: 400% 400%;
        animation: gradient-flow 3s ease infinite;
    }

    @keyframes gradient-flow {
        0%, 100% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
    }

    /*Haeder */
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
        font-size: clamp(1.2rem, 4vw, 1.8rem);
        font-weight: 700;
        color: #1e3c72;
        margin: 0;
        position: relative;
        z-index: 2;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        line-height: 1.2;
        word-wrap: break-word;
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

    .booking-content {
        padding: 20px;
        position: relative;
        z-index: 1;
    }

    /* Alerts */
    .custom-alert {
        display: flex;
        align-items: center;
        padding: 15px;
        border-radius: 16px;
        margin-bottom: 20px;
        position: relative;
        overflow: hidden;
        border: none;
        animation: slideInDown 0.5s ease-out;
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
        flex-shrink: 0;
    }

    .alert-content {
        flex: 1;
        min-width: 0;
    }

    .alert-content strong {
        display: block;
        font-size: 1rem;
        margin-bottom: 2px;
        word-wrap: break-word;
    }

    .alert-content span {
        word-wrap: break-word;
        display: block;
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
        flex-shrink: 0;
    }

    .alert-close:hover {
        background: rgba(255, 255, 255, 0.2);
    }

    /* Summary Cards */
    .summary-card-link {
        text-decoration: none !important;
        display: block;
        color: inherit;
        position: relative;
    }

    .summary-card-link:hover {
        text-decoration: none !important;
        color: inherit;
    }

    .summary-card {
        background: white;
        border-radius: 16px;
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 15px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        border-left: 6px solid;
        position: relative;
        overflow: hidden;
        z-index: 1;
        min-height: 100px;
    }

    .summary-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
    }

    .summary-card .card-icon {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        color: white;
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
        transition: all 0.3s ease;
        flex-shrink: 0;
    }

    .summary-card:hover .card-icon {
        transform: scale(1.05);
    }

    .summary-card .card-details {
        flex-grow: 1;
        z-index: 2;
        min-width: 0;
    }

    .summary-card .card-title {
        font-size: clamp(0.9rem, 2.5vw, 1.1rem);
        color: #4a5568;
        margin-bottom: 8px;
        font-weight: 600;
        letter-spacing: 0.5px;
        line-height: 1.3;
        word-wrap: break-word;
    }

    .summary-card .card-value {
        font-size: clamp(1.8rem, 5vw, 2.5rem);
        font-weight: 800;
        color: #1e3c72;
        margin: 0;
        line-height: 1.1;
        text-shadow: 1px 1px 2px rgba(0,0,0,0.05);
    }

    /* Card Colors */
    .primary-summary { border-color: #667eea; }
    .primary-summary .card-icon { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
    .primary-summary .card-value { color: #667eea; }

    .success-summary { border-color: #38ef7d; }
    .success-summary .card-icon { background: linear-gradient(135deg, #38ef7d 0%, #11998e 100%); }
    .success-summary .card-value { color: #11998e; }

    .info-summary { border-color: #4facfe; }
    .info-summary .card-icon { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }
    .info-summary .card-value { color: #00f2fe; }

    .warning-summary { border-color: #fbc02d; }
    .warning-summary .card-icon { background: linear-gradient(135deg, #fbc02d 0%, #f57f17 100%); }
    .warning-summary .card-value { color: #f57f17; }

    /* Sub-Card */
    .sub-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        position: relative;
        z-index: 1;
    }

    .sub-card-header {
        background: linear-gradient(135deg, #e0e7ff 0%, #c3dafe 100%);
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 15px;
        border-bottom: 2px solid #a7b7ff;
        flex-wrap: wrap;
    }

    .sub-card-header i {
        font-size: 1.5rem;
        color: #1e3c72;
        background: rgba(30, 60, 114, 0.1);
        padding: 8px;
        border-radius: 8px;
        flex-shrink: 0;
    }

    .sub-card-title {
        font-size: clamp(1rem, 3vw, 1.3rem);
        font-weight: 700;
        color: #1e3c72;
        margin: 0;
        line-height: 1.3;
        word-wrap: break-word;
        flex: 1;
        min-width: 0;
    }

    .sub-card-body {
        padding: 20px;
    }

    /* Chart Container */
    .chart-container {
        padding: 15px;
        background: #fdfefe;
        border-radius: 12px;
        box-shadow: inset 0 0 5px rgba(0,0,0,0.05);
    }

    /* Badges */
    .badge-success-custom, .badge-info-custom, .badge-danger-custom {
        padding: 4px 8px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.7rem;
        color: white;
        white-space: nowrap;
    }

    .badge-success-custom { background-color: #38ef7d; }
    .badge-info-custom { background-color: #4facfe; }
    .badge-danger-custom { background-color: #ff416c; }

    /* Booking Notes */
    .recent-bookings-list {
        max-height: 350px;
        overflow-y: auto;
        padding-right: 5px;
    }

    .booking-note-item {
        display: flex;
        align-items: flex-start;
        background: #fcfdfe;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        margin-bottom: 15px;
        padding: 15px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.03);
        transition: all 0.3s ease;
        gap: 10px;
    }

    .booking-note-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    .note-icon {
        flex-shrink: 0;
        font-size: 1.5rem;
        color: #667eea;
        margin-top: 2px;
    }

    .note-content {
        flex-grow: 1;
        min-width: 0;
    }

    .note-title {
        font-weight: 600;
        color: #1e3c72;
        margin-bottom: 8px;
        font-size: clamp(0.9rem, 2.5vw, 1rem);
        line-height: 1.4;
        word-wrap: break-word;
    }

    .note-meta {
        font-size: clamp(0.75rem, 2vw, 0.85rem);
        color: #6c757d;
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
        line-height: 1.4;
    }

    .note-meta i {
        font-size: 1rem;
        color: #4facfe;
        flex-shrink: 0;
    }

    /* View All Link */
    .view-all-notes-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: #667eea;
        text-decoration: none !important;
        font-weight: 600;
        font-size: clamp(0.85rem, 2.5vw, 0.95rem);
        transition: color 0.3s ease;
    }

    .view-all-notes-link:hover {
        color: #4facfe;
        text-decoration: none !important;
    }

    /* Responsive Design */
    @media (max-width: 1199.98px) {
        .col-xl-3 {
            flex: 0 0 50%;
            max-width: 50%;
        }
    }

    @media (max-width: 991.98px) {
        .col-xl-3, .col-md-6 {
            flex: 0 0 100%;
            max-width: 100%;
        }
        
        .booking-content {
            padding: 15px;
        }
        
        .summary-card {
            padding: 15px;
        }
    }

    @media (max-width: 767.98px) {
        .booking-page-wrapper {
            padding: 10px 0;
        }
        
        .booking-content {
            padding: 15px;
        }
        
        .summary-card {
            flex-direction: column;
            text-align: center;
            padding: 15px;
            gap: 10px;
        }
        
        .summary-card .card-icon {
            width: 50px;
            height: 50px;
            font-size: 1.8rem;
        }
        
        .booking-header {
            padding: 20px;
        }
        
        .header-icon {
            width: 60px;
            height: 60px;
        }
        
        .chart-container {
            height: 250px !important;
            padding: 10px;
        }
        
        .sub-card-header {
            padding: 15px;
            flex-direction: column;
            text-align: center;
            gap: 10px;
        }
        
        .sub-card-body {
            padding: 15px;
        }
        
        .note-meta {
            flex-direction: column;
            align-items: flex-start;
            gap: 5px;
        }
    }

    @media (max-width: 575.98px) {
        .summary-card .card-icon {
            width: 45px;
            height: 45px;
            font-size: 1.5rem;
        }
        
        .booking-note-item {
            padding: 12px;
        }
        
        .alert-content strong {
            font-size: 0.9rem;
        }
    }

    a {
        text-decoration: none !important;
    }

    a:hover {
        text-decoration: none !important;
        color: inherit;
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
</style>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Alert close functionality
    document.querySelectorAll('.alert-close').forEach(button => {
        button.addEventListener('click', function() {
            this.closest('.custom-alert').style.display = 'none';
        });
    });

    // Chart initialization
    document.addEventListener('DOMContentLoaded', function () {
        var ctx = document.getElementById('userTypeChart');
        if (ctx) {
            var userCounts = @json($userCounts);

            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Admin', 'Pengguna'],
                    datasets: [{
                        label: 'Jumlah Pengguna',
                        data: [userCounts.Admin, userCounts.User],
                        backgroundColor: [
                            'rgba(102, 126, 234, 0.8)',
                            'rgba(56, 239, 125, 0.8)'
                        ],
                        hoverOffset: 10,
                        borderColor: ['#fff', '#fff'],
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
                                font: { size: 14 }
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
                                        label += context.parsed + ' pengguna';
                                    }
                                    return label;
                                }
                            }
                        }
                    }
                }
            });
        }
    });
</script>
@endsection