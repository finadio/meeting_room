@extends('admin.layouts.admin_dashboard')
@section('title', 'Booking Details')

@section('content')
<div class="booking-page-wrapper">
    <div class="container-fluid px-4">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10">
                <div class="booking-card">
                    <div class="booking-header">
                        <div class="header-icon">
                            <i class="bx bx-detail"></i>
                        </div>
                        <h3 class="header-title">Detail Pemesanan</h3>
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

                        <div class="detail-grid">
                            <div class="display-group-enhanced">
                                <label class="form-label-enhanced">
                                    <div class="label-icon">
                                        <i class="bx bx-hash"></i>
                                    </div>
                                    <span>ID Pemesanan</span>
                                </label>
                                <p class="display-value">{{ $booking->id }}</p>
                            </div>

                            <div class="display-group-enhanced">
                                <label class="form-label-enhanced">
                                    <div class="label-icon">
                                        <i class="bx bx-user"></i>
                                    </div>
                                    <span>Pengguna Pemesan</span>
                                </label>
                                <p class="display-value">{{ $booking->user->name ?? 'N/A' }}</p>
                            </div>

                            <div class="display-group-enhanced">
                                <label class="form-label-enhanced">
                                    <div class="label-icon">
                                        <i class="bx bx-building"></i>
                                    </div>
                                    <span>Nama Fasilitas</span>
                                </label>
                                <p class="display-value">{{ $booking->facility->name ?? 'N/A' }}</p>
                            </div>

                            @if($booking->meeting_title)
                            <div class="display-group-enhanced">
                                <label class="form-label-enhanced">
                                    <div class="label-icon">
                                        <i class="bx bx-message-square-detail"></i>
                                    </div>
                                    <span>Judul Rapat/Event</span>
                                </label>
                                <p class="display-value">{{ $booking->meeting_title }}</p>
                            </div>
                            @endif

                            @if($booking->group_name)
                            <div class="display-group-enhanced">
                                <label class="form-label-enhanced">
                                    <div class="label-icon">
                                        <i class="bx bx-group"></i>
                                    </div>
                                    <span>Nama Grup</span>
                                </label>
                                <p class="display-value">{{ $booking->group_name }}</p>
                            </div>
                            @endif

                            <div class="display-group-enhanced">
                                <label class="form-label-enhanced">
                                    <div class="label-icon">
                                        <i class="bx bx-calendar"></i>
                                    </div>
                                    <span>Tanggal Pemesanan</span>
                                </label>
                                <p class="display-value">{{ \Carbon\Carbon::parse($booking->booking_date)->format('d F Y') }}</p>
                            </div>

                            <div class="display-group-enhanced">
                                <label class="form-label-enhanced">
                                    <div class="label-icon">
                                        <i class="bx bx-time"></i>
                                    </div>
                                    <span>Waktu Mulai</span>
                                </label>
                                <p class="display-value">{{ \Carbon\Carbon::parse($booking->booking_time)->format('h:i A') }}</p>
                            </div>

                            <div class="display-group-enhanced">
                                <label class="form-label-enhanced">
                                    <div class="label-icon">
                                        <i class="bx bx-alarm"></i>
                                    </div>
                                    <span>Waktu Selesai</span>
                                </label>
                                <p class="display-value">{{ \Carbon\Carbon::parse($booking->booking_end)->format('h:i A') }}</p>
                            </div>
                            
                            <div class="display-group-enhanced">
                                <label class="form-label-enhanced">
                                    <div class="label-icon">
                                        <i class="bx bx-timer"></i>
                                    </div>
                                    <span>Durasi</span>
                                </label>
                                <p class="display-value">{{ $booking->hours }} Jam</p>
                            </div>

                            <div class="display-group-enhanced">
                                <label class="form-label-enhanced">
                                    <div class="label-icon">
                                        <i class="bx bx-money"></i>
                                    </div>
                                    <span>Jumlah Pembayaran</span>
                                </label>
                                <p class="display-value">Rp. {{ number_format($booking->amount, 0, ',', '.') }}</p>
                            </div>

                            <div class="display-group-enhanced">
                                <label class="form-label-enhanced">
                                    <div class="label-icon">
                                        <i class="bx bx-wallet"></i>
                                    </div>
                                    <span>Metode Pembayaran</span>
                                </label>
                                <p class="display-value">{{ $booking->payment_method }}</p>
                            </div>

                            <div class="display-group-enhanced">
                                <label class="form-label-enhanced">
                                    <div class="label-icon">
                                        <i class="bx bx-check-circle"></i>
                                    </div>
                                    <span>Status Pemesanan</span>
                                </label>
                                <p class="display-value">
                                    @php
                                        $statusClass = '';
                                        switch ($booking->status) {
                                            case 'Disetujui': $statusClass = 'badge-success-custom'; break;
                                            case 'Menunggu Konfirmasi': $statusClass = 'badge-info-custom'; break;
                                            case 'Ditolak': $statusClass = 'badge-danger-custom'; break;
                                            case 'Selesai': $statusClass = 'badge-secondary-custom'; break;
                                            default: $statusClass = 'badge-secondary-custom'; break;
                                        }
                                    @endphp
                                    <span class="badge {{ $statusClass }}">{{ $booking->status }}</span>
                                </p>
                            </div>

                            <div class="display-group-enhanced">
                                <label class="form-label-enhanced">
                                    <div class="label-icon">
                                        <i class="bx bx-log-in-circle"></i>
                                    </div>
                                    <span>Check-in</span>
                                </label>
                                <p class="display-value">
                                    @if($booking->is_checked_in)
                                        <i class='bx bx-check-circle text-success'></i> Ya ({{ \Carbon\Carbon::parse($booking->checked_in_at)->format('d M Y, H:i') }})
                                    @else
                                        <i class='bx bx-x-circle text-danger'></i> Belum
                                    @endif
                                </p>
                            </div>

                            <div class="display-group-enhanced">
                                <label class="form-label-enhanced">
                                    <div class="label-icon">
                                        <i class="bx bx-log-out-circle"></i>
                                    </div>
                                    <span>Check-out</span>
                                </label>
                                <p class="display-value">
                                    @if($booking->is_checked_out)
                                        <i class='bx bx-check-circle text-success'></i> Ya ({{ \Carbon\Carbon::parse($booking->checked_out_at)->format('d M Y, H:i') }})
                                    @else
                                        <i class='bx bx-x-circle text-danger'></i> Belum
                                    @endif
                                </p>
                            </div>

                            <div class="display-group-enhanced">
                                <label class="form-label-enhanced">
                                    <div class="label-icon">
                                        <i class="bx bx-calendar-plus"></i>
                                    </div>
                                    <span>Dibuat Pada</span>
                                </label>
                                <p class="display-value">{{ \Carbon\Carbon::parse($booking->created_at)->format('d F Y, H:i A') }}</p>
                            </div>
                        </div>

                        <div class="form-section submit-section mt-5 pt-4 border-top">
                            <div class="button-group">
                                @php
                                    $currentTime = now()->setTimezone('Asia/Jakarta');
                                    $bookingDateOnly = \Carbon\Carbon::parse($booking->booking_date)->format('Y-m-d');
                                    $bookingEndTimeFull = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $bookingDateOnly . ' ' . $booking->booking_end);
                                    $timeLeft = $currentTime->diffInMinutes($bookingEndTimeFull->setTimezone('Asia/Jakarta'), false);
                                @endphp

                                @if($booking->is_checked_in == 0 && $booking->status == 'Disetujui')
                                    <form action="{{ route('admin.checkIn', $booking->id) }}" method="POST" class="d-inline-block">
                                        @csrf
                                        <button type="submit" class="btn-submit checkin" title="Check In">
                                            <div class="btn-content"><i class='bx bx-log-in-circle'></i> Check In</div>
                                            <div class="btn-ripple"></div>
                                        </button>
                                    </form>
                                @endif

                                @if($booking->is_checked_in == 1 && $booking->status != 'Selesai')
                                    <form action="{{ route('admin.checkOut', $booking->id) }}" method="POST" class="d-inline-block">
                                        @csrf
                                        <button type="submit" class="btn-submit checkout" title="Check Out">
                                            <div class="btn-content"><i class='bx bx-log-out-circle'></i> Check Out</div>
                                            <div class="btn-ripple"></div>
                                        </button>
                                    </form>
                                @endif

                                @if($timeLeft <= 10 && $timeLeft >= -60 && $booking->check_in && !$booking->check_out && !$booking->wa_sent)
                                    <form action="{{ route('admin.sendWhatsApp', $booking->id) }}" method="POST" class="d-inline-block">
                                        @csrf
                                        <button type="submit" class="btn-submit whatsapp" title="Kirim WhatsApp">
                                            <div class="btn-content"><i class='bx bxl-whatsapp'></i> Kirim WA</div>
                                            <div class="btn-ripple"></div>
                                        </button>
                                    </form>
                                @endif
                                
                                @if($booking->status === 'Menunggu Konfirmasi')
                                    <form action="{{ route('admin.bookings.approve', $booking->id) }}" method="POST" class="d-inline-block">
                                        @csrf
                                        <button type="submit" class="btn-submit approve" title="Setujui">
                                            <div class="btn-content"><i class='bx bx-check'></i> Setujui</div>
                                            <div class="btn-ripple"></div>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.bookings.reject', $booking->id) }}" method="POST" class="d-inline-block">
                                        @csrf
                                        <button type="submit" class="btn-submit reject" title="Tolak">
                                            <div class="btn-content"><i class='bx bx-x'></i> Tolak</div>
                                            <div class="btn-ripple"></div>
                                        </button>
                                    </form>
                                @endif

                                <button type="button" class="btn-back" onclick="window.history.back()">
                                    <div class="btn-content">
                                        <i class="bx bx-arrow-back"></i>
                                        <span>Kembali</span>
                                    </div>
                                    <div class="btn-ripple"></div>
                                </button>
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

    /* Detail Grid (for booking info) */
    .detail-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 20px;
        margin-top: 30px; /* Space from description */
        padding-top: 20px;
        border-top: 1px solid #e2e8f0;
    }

    .display-group-enhanced {
        position: relative;
        margin-bottom: 0; /* Handled by grid gap */
    }

    .form-label-enhanced {
        display: flex;
        align-items: center;
        margin-bottom: 12px;
        font-weight: 600;
        color: #2d3748;
        font-size: 0.95rem;
    }

    .label-icon {
        width: 24px;
        height: 24px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 10px;
        box-shadow: 0 2px 4px rgba(102, 126, 234, 0.3);
    }

    .label-icon i {
        color: white;
        font-size: 0.9rem;
    }

    .display-value, .display-value-text {
        padding: 16px 20px;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        font-size: 1rem;
        background: #f8fafc;
        color: #4a5568;
        margin-bottom: 0;
        word-wrap: break-word;
    }

    .display-value-text {
        line-height: 1.6;
    }

    .display-link {
        color: #4299e1;
        text-decoration: none;
        transition: color 0.2s ease;
    }
    .display-link:hover {
        color: #2b6cb0;
        text-decoration: underline;
    }

    /* Badges */
    .badge-info-custom {
        background-color: #4facfe;
        color: white;
        padding: 6px 10px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.8em;
    }
    .badge-success-custom {
        background-color: #38ef7d;
        color: white;
        padding: 6px 10px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.8em;
    }
    .badge-secondary-custom {
        background-color: #6c757d;
        color: white;
        padding: 6px 10px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.8em;
    }
    .badge-danger-custom {
        background-color: #ff416c;
        color: white;
        padding: 6px 10px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.8em;
    }

    /* Action Buttons */
    .form-section.submit-section {
        text-align: center;
        margin-top: 40px;
    }

    .button-group {
        display: flex;
        gap: 15px;
        justify-content: center;
        align-items: center;
        flex-wrap: wrap; /* Allow wrapping on small screens */
    }

    .btn-submit {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        border-radius: 16px;
        padding: 18px 40px;
        color: white;
        font-weight: 600;
        font-size: 1.1rem;
        cursor: pointer;
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
        min-width: 180px; /* Adjusted minimum width */
        text-decoration: none; /* For anchor tag */
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .btn-submit:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 35px rgba(102, 126, 234, 0.4);
        color: white; /* Ensure text color remains white on hover */
        text-decoration: none;
    }

    .btn-submit:active {
        transform: translateY(-1px);
    }

    /* btn-action for Checkin/Checkout/Whatsapp/Approve/Reject (if used as full buttons) */
    .btn-action {
        border: none;
        border-radius: 16px;
        padding: 18px 40px;
        color: white;
        font-weight: 600;
        font-size: 1.1rem;
        cursor: pointer;
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
        box-shadow: 0 8px 25px rgba(0,0,0,0.1); /* General shadow for actions */
        min-width: 180px; /* Adjusted minimum width */
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .btn-action:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 35px rgba(0,0,0,0.2);
        color: white;
        text-decoration: none;
    }
    
    .btn-action.checkin { background: linear-gradient(135deg, #28a745 0%, #20c997 100%); box-shadow: 0 8px 25px rgba(40, 167, 69, 0.3); }
    .btn-action.checkout { background: linear-gradient(135deg, #fd7e14 0%, #ffc107 100%); box-shadow: 0 8px 25px rgba(253, 126, 20, 0.3); }
    .btn-action.whatsapp { background: linear-gradient(135deg, #25d366 0%, #128c7e 100%); box-shadow: 0 8px 25px rgba(37, 211, 102, 0.3); }
    .btn-action.approve { background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); box-shadow: 0 8px 25px rgba(17, 153, 142, 0.3); }
    .btn-action.reject { background: linear-gradient(135deg, #ff416c 0%, #ff4b2b 100%); box-shadow: 0 8px 25px rgba(255, 65, 108, 0.3); }

    .btn-back {
        background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%);
        border: none;
        border-radius: 16px;
        padding: 18px 40px;
        color: white;
        font-weight: 600;
        font-size: 1.1rem;
        cursor: pointer;
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
        box-shadow: 0 8px 25px rgba(108, 117, 125, 0.3);
        min-width: 180px; /* Adjusted minimum width */
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .btn-back:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 35px rgba(108, 117, 125, 0.4);
        color: white;
        text-decoration: none;
    }

    .btn-back:active {
        transform: translateY(-1px);
    }

    .btn-content {
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        z-index: 2;
    }

    .btn-content i {
        margin-right: 10px;
        font-size: 1.2rem;
    }

    .btn-ripple {
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        background: rgba(255, 255, 255, 0.3);
        border-radius: 50%;
        transform: translate(-50%, -50%);
        transition: width 0.3s ease, height 0.3s ease;
    }

    .btn-submit:active .btn-ripple,
    .btn-back:active .btn-ripple {
        width: 300px;
        height: 300px;
    }

    /* Responsive Design */
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

        .detail-grid {
            grid-template-columns: 1fr;
        }

        .btn-submit,
        .btn-action,
        .btn-back {
            padding: 16px 30px;
            min-width: 100%;
        }

        .button-group {
            flex-direction: column;
            gap: 10px;
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
{{-- Tidak ada script spesifik untuk halaman ini selain yang umum --}}
@endsection