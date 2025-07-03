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
                        {{-- Mengubah judul menjadi ucapan selamat datang --}}
                        <h3 class="header-title">Selamat Datang, {{ Auth::user()->name }}!</h3>
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

                        <div class="row mb-4">
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

                        <div class="sub-card">
                            <div class="sub-card-header">
                                <i class="bx bx-list-ul"></i>
                                <h5 class="sub-card-title">Ikhtisar Cepat Status Ruangan</h5>
                            </div>
                            <div class="sub-card-body">
                                @if(empty($rooms))
                                    <div class="alert alert-info text-center">
                                        Tidak ada fasilitas yang tersedia untuk ditinjau.
                                    </div>
                                @else
                                    <div class="room-status-grid">
                                        @foreach($rooms as $room)
                                            <div class="room-status-item">
                                                <div class="item-header">
                                                    <div class="item-icon"><i class="bx bx-door-open"></i></div>
                                                    <h6 class="item-title">{{ $room->name }}</h6>
                                                </div>
                                                <div class="item-body">
                                                    <div class="item-detail">
                                                        <span class="detail-label"><i class="bx bx-check-circle"></i> Status:</span>
                                                        <span class="detail-value">
                                                            @php
                                                                $statusData = app(\App\Http\Controllers\StatusController::class)->getRoomStatus($room->id);
                                                                $displayStatus = 'N/A';
                                                                if (isset($statusData['ongoingBooking'])) {
                                                                    $displayStatus = 'Sedang Digunakan';
                                                                } elseif (isset($statusData['upcomingBookings']) && $statusData['upcomingBookings']->isNotEmpty()) {
                                                                    $displayStatus = 'Tersedia (Next: ' . \Carbon\Carbon::parse($statusData['upcomingBookings']->first()->booking_time)->format('H:i') . ')';
                                                                } else {
                                                                    $displayStatus = 'Tersedia';
                                                                }
                                                            @endphp
                                                            @if($displayStatus == 'Tersedia')
                                                                <span class="badge badge-success-custom">{{ $displayStatus }}</span>
                                                            @elseif($displayStatus == 'Sedang Digunakan')
                                                                <span class="badge badge-danger-custom">{{ $displayStatus }}</span>
                                                            @else
                                                                <span class="badge badge-secondary-custom">{{ $displayStatus }}</span>
                                                            @endif
                                                        </span>
                                                    </div>
                                                    <div class="item-detail">
                                                        <span class="detail-label"><i class="bx bx-calendar"></i> Pemesanan Berikutnya:</span>
                                                        <span class="detail-value">
                                                            @if(isset($statusData['upcomingBookings']) && $statusData['upcomingBookings']->isNotEmpty())
                                                                {{ \Carbon\Carbon::parse($statusData['upcomingBookings']->first()->booking_time)->format('d F Y, H:i') }}
                                                            @else
                                                                Tidak ada
                                                            @endif
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
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
        height: 2px;
        background: linear-gradient(90deg, #667eea, #764ba2, #f093fb, #f5576c);
        background-size: 400% 400%;
        animation: gradient-flow 3s ease infinite;
    }

    @keyframes gradient-flow {
        0%, 100% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
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

    /* Summary Cards (as clickable shortcuts) */
    .summary-card-link {
        text-decoration: none; /* Remove underline from links */
        display: block; /* Make the whole column clickable */
        color: inherit; /* Inherit text color */
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
    }

    .summary-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
        filter: brightness(1.05); /* Slightly brighten on hover */
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
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }

    .summary-card .card-details {
        flex-grow: 1;
    }

    .summary-card .card-title {
        font-size: 1rem;
        color: #6b7280;
        margin-bottom: 5px;
        font-weight: 500;
    }

    .summary-card .card-value {
        font-size: 2.2rem;
        font-weight: 700;
        color: #1e3c72;
    }

    /* Specific Summary Card Colors */
    .primary-summary { border-color: #667eea; }
    .primary-summary .card-icon { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }

    .success-summary { border-color: #38ef7d; }
    .success-summary .card-icon { background: linear-gradient(135deg, #38ef7d 0%, #11998e 100%); }

    .info-summary { border-color: #4facfe; }
    .info-summary .card-icon { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }

    .warning-summary { border-color: #fbc02d; }
    .warning-summary .card-icon { background: linear-gradient(135deg, #fbc02d 0%, #f57f17 100%); }

    /* Sub-Card for Quick Overview (Room Status) */
    .sub-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        margin-top: 30px;
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

    /* Room Status Grid (Card-per-row styling) */
    .room-status-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); /* Responsive grid columns */
        gap: 20px;
    }

    .room-status-item {
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        padding: 20px;
        transition: all 0.2s ease;
        border: 1px solid #e2e8f0;
    }

    .room-status-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.15);
    }

    .item-header {
        display: flex;
        align-items: center;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 1px solid #e2e8f0;
    }

    .item-icon {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        width: 40px;
        height: 40px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
        color: white;
        margin-right: 15px;
        box-shadow: 0 4px 10px rgba(102, 126, 234, 0.3);
    }

    .item-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #2d3748;
        margin: 0;
    }

    .item-body .item-detail {
        display: flex;
        align-items: center;
        margin-bottom: 10px;
    }

    .item-body .item-detail:last-child {
        margin-bottom: 0;
    }

    .item-body .detail-label {
        font-weight: 500;
        color: #4a5568;
        display: flex;
        align-items: center;
        flex-shrink: 0;
        margin-right: 10px;
    }

    .item-body .detail-label i {
        font-size: 1rem;
        margin-right: 5px;
        color: #4299e1; /* A light blue for icons in details */
    }

    .item-body .detail-value {
        color: #2d3748;
        flex-grow: 1;
        text-align: right; /* Align value to the right */
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

    /* Responsive Design */
    @media (max-width: 992px) {
        .summary-card {
            flex-direction: column;
            text-align: center;
            padding: 20px;
        }
        .summary-card .card-icon { margin-bottom: 15px; }
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

        .room-status-grid {
            grid-template-columns: 1fr; /* Single column on small screens */
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
<script>
    // Alert close functionality
    document.querySelectorAll('.alert-close').forEach(button => {
        button.addEventListener('click', function() {
            this.closest('.custom-alert').style.display = 'none';
        });
    });

    // You might still have Chart.js and FullCalendar scripts from your previous dashboard
    // If you need them for other elements, ensure they are included and initialized properly.
    // The provided content for this iteration doesn't directly use them in the HTML,
    // but the original dashboard did. If they were meant to be in a hidden part or
    // you want to re-add them, ensure their initialization is adapted.

    // Example Chart.js (if applicable, would need a canvas element in HTML)
    // document.addEventListener('DOMContentLoaded', function () {
    //     var ctx = document.getElementById('userChart'); // Make sure this canvas ID exists
    //     if (ctx) {
    //         var userCounts = {!! json_encode($userCounts) !!}; // Ensure $userCounts is passed from controller
    //         var userTypes = Object.keys(userCounts);
    //         new Chart(ctx.getContext('2d'), {
    //             type: 'bar',
    //             data: { /* ... chart data ... */ },
    //             options: { /* ... chart options ... */ }
    //         });
    //     }
    // });

    // Example FullCalendar (if applicable, would need a div with id 'calendar' in HTML)
    // document.addEventListener('DOMContentLoaded', function () {
    //     var calendarEl = document.getElementById('calendar'); // Make sure this div ID exists
    //     if (calendarEl) {
    //         var calendar = new FullCalendar.Calendar(calendarEl, {
    //             // ... calendar options and events ($bookedDates needs to be passed) ...
    //         });
    //         calendar.render();
    //     }
    // });
</script>
@endsection