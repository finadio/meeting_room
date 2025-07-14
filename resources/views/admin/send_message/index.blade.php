@extends('admin.layouts.admin_dashboard')

@section('title', 'Kirim Agenda Harian')

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
{{-- Periksa path ini dengan benar di server Anda --}}
{{-- <link href="{{ asset('css/calendar_custom.css') }}" rel="stylesheet"> --}}
<style>
    .booking-page-wrapper {
        min-height: 100vh;
        background: rgba(255, 255, 255, 0.95);
        padding: 40px 0;
        position: relative;
    }
    .booking-page-wrapper::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="%23ffffff" opacity="0.05"/><circle cx="75" cy="75" r="1" fill="%23ffffff" opacity="0.05"/><circle cx="50" cy="10" r="1" fill="%23ffffff" opacity="0.03"/><circle cx="10" cy="50" r="1" fill="%23ffffff" opacity="0.03"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
        pointer-events: none;
    }
    .booking-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border-radius: 24px;
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
        overflow: hidden;
        position: relative;
        border: 1px solid rgba(255, 255, 255, 0.2);
        margin-bottom: 32px;
    }
    /* .booking-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 2px;
        background: linear-gradient(90deg, #667eea, #764ba2, #f093fb, #f5576c);
        background-size: 400% 400%;
        animation: gradient-flow 3s ease infinite;
    } */
    @keyframes gradient-flow {
        0%, 100% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
    }
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
        top: -50%; left: -50%; width: 200%; height: 200%;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
        animation: rotate 8s linear infinite;
    }
    @keyframes rotate {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    .header-icon {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        width: 70px; height: 70px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto 20px; position: relative; z-index: 2;
        box-shadow: 0 10px 25px rgba(79, 172, 254, 0.3);
    }
    .header-title {
        color: white; font-size: 1.8rem; font-weight: 700; margin: 0; position: relative; z-index: 2; text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
    }
    .header-decoration {
        width: 60px; height: 4px; background: linear-gradient(90deg, #4facfe, #00f2fe); margin: 15px auto 0; border-radius: 2px; position: relative; z-index: 2;
    }
    .booking-content { padding: 40px; }
    .table-container { overflow-x: auto; background: #fff; border-radius: 16px; box-shadow: 0 10px 30px rgba(0,0,0,0.08); padding: 20px; }
    .modern-table { width: 100%; border-collapse: separate; border-spacing: 0; background: #fff; border-radius: 12px; overflow: hidden; }
    .modern-table th, .modern-table td { padding: 14px 12px; text-align: left; }
    .modern-table th { background: #f4f7fd; font-weight: 700; color: #4A6DFB; border-bottom: 1px solid #e3e8ee; }
    .modern-table tbody tr { border-bottom: 1px solid #f0f2f7; }
    .modern-table tbody tr:last-child { border-bottom: none; }
    .modern-table tbody tr:nth-child(even) { background: #f8fafc; }
    .modern-table tbody tr:nth-child(odd) { background: #fff; }
    .form-section { margin-bottom: 24px; }
    .form-group-enhanced { margin-bottom: 0; }
    .form-label-enhanced { font-weight: 600; color: #2d3748; font-size: 0.97rem; margin-bottom: 4px; display: block; }
    .form-control-enhanced { width: 100%; padding: 8px 12px; border: 1.5px solid #e2e8f0; border-radius: 8px; font-size: 1rem; background: #fff; transition: border-color 0.2s; outline: none; box-shadow: none; }
    .form-control-enhanced:focus { border-color: #1e3c72; background: #fff; }
    .btn-submit, .btn-add-manual { background: linear-gradient(90deg,#4facfe,#00f2fe); color: #fff; border: none; border-radius: 12px; font-weight: 600; font-size: 1rem; padding: 10px 28px; box-shadow: 0 2px 8px rgba(79,172,254,0.08); transition: background 0.2s; }
    .btn-submit:hover, .btn-add-manual:hover { background: linear-gradient(90deg,#2a5298,#4facfe); color: #fff; }
    .btn-add-manual { font-size: 0.95rem; padding: 7px 18px; margin-top: 6px; margin-bottom: 0; }
    .btn-remove-mingguan { background: #fff; color: #ff416c; border: 1.5px solid #ff416c; border-radius: 12px; font-weight: 600; font-size: 1rem; padding: 8px 12px; transition: background 0.2s, color 0.2s; }
    .btn-remove-mingguan:hover { background: #ff416c; color: #fff; }
    .badge-status { font-size: 0.95em; padding: 6px 14px; border-radius: 8px; letter-spacing: 0.5px; display: inline-flex; align-items: center; gap: 4px; }
    .btn-trash-custom {
        background: #fff;
        border: 1.5px solid #ff416c;
        color: #ff416c;
        border-radius: 10px;
        padding: 8px 12px;
        font-size: 1.15rem;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background 0.2s, color 0.2s;
        font-weight: 600;
        min-width: 40px;
        min-height: 40px;
    }
    .btn-trash-custom:hover {
        background: #ff416c;
        color: #fff;
    }
    .btn-add-agenda-custom {
        background: #1e3c72;
        color: #fff;
        border: none;
        border-radius: 10px;
        padding: 8px 18px 8px 14px;
        font-size: 1.08rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 7px;
        box-shadow: 0 2px 8px rgba(44,62,80,0.06);
        transition: background 0.2s;
    }
    .btn-add-agenda-custom:hover {
        background: #274690;
        color: #fff;
    }
    .agenda-label {
        font-size: 1.08rem;
        font-weight: 700;
        letter-spacing: 0.2px;
    }
    .btn-outline-danger {
        border-radius: 8px !important;
        padding: 8px 12px !important;
        font-size: 1.15rem !important;
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 40px;
        min-height: 40px;
    }
    .gap-2 { gap: 0.5rem !important; }
    .d-flex { display: flex !important; }
    .align-items-center { align-items: center !important; }
    @media (max-width: 767.98px) {
        .btn-add-agenda-custom .agenda-label { display: none; }
        .btn-add-mingguan .agenda-label { display: none; }
    }
    .btn-add-agenda-custom, .btn-add-mingguan {
        /* dihapus, gunakan btn-primary Bootstrap */
    }
    .agenda-label {
        font-size: 1.08rem;
        font-weight: 700;
        letter-spacing: 0.2px;
    }
    .btn-sm { font-size: 0.97rem; padding: 6px 12px; }
    .gap-2 { gap: 0.5rem !important; }
    .d-flex { display: flex !important; }
    .align-items-center { align-items: center !important; }
    @media (max-width: 767.98px) {
        .agenda-label { display: none; }
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
        min-width: 200px;
        display: inline-block;
    }
    .btn-submit.btn-small {
        padding: 8px 18px;
        font-size: 1rem;
        min-width: 0;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(102, 126, 234, 0.15);
    }
    .btn-submit:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 35px rgba(102, 126, 234, 0.4);
    }
    .btn-submit:active {
        transform: translateY(-1px);
    }
    .btn-content {
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        z-index: 2;
        font-weight: 600;
    }
    .btn-content i {
        margin-right: 8px;
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
    .btn-submit:active .btn-ripple {
        width: 300px;
        height: 300px;
    }
    @media (max-width: 767.98px) {
        .btn-content span { display: none; }
        .btn-submit, .btn-submit.btn-small { min-width: 0; padding: 8px 12px; }
    }
    .btn-submit, .btn-submit.btn-small {
        background: #1e3c72 !important;
        color: #fff !important;
        border: none;
        border-radius: 12px;
        font-weight: 700;
        font-size: 1rem;
        cursor: pointer;
        position: relative;
        overflow: hidden;
        transition: background 0.2s, box-shadow 0.2s;
        box-shadow: 0 2px 8px rgba(44,62,80,0.10);
        min-width: 0;
        padding: 8px 18px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        line-height: 1;
        vertical-align: middle;
        height: 42px;
    }
    .btn-submit.btn-small {
        padding: 8px 18px;
        font-size: 1rem;
        border-radius: 12px;
        min-width: 0;
        box-shadow: 0 2px 8px rgba(44,62,80,0.10);
        height: 42px;
    }
    /* Hapus efek hover warna pada tombol simpan/form section */
    .btn-submit:hover, .btn-submit.btn-small:hover {
        background: #1e3c72 !important;
        color: #fff !important;
    }
    .btn-submit i {
        font-size: 1.2rem;
        margin-right: 6px;
        vertical-align: middle;
        display: inline-block;
        line-height: 1;
        position: relative;
        top: 0.5px;
    }
    .btn-submit span {
        font-weight: 700;
        font-size: 1.08rem;
        letter-spacing: 0.2px;
        vertical-align: middle;
        display: inline-block;
        line-height: 1;
    }
    @media (max-width: 767.98px) {
        .btn-submit span { display: none; }
        .btn-submit, .btn-submit.btn-small { min-width: 0; padding: 8px 12px; height: 38px; }
    }
    .custom-alert {
        display: flex;
        align-items: flex-start;
        gap: 14px;
        background: #fff;
        border: 1.5px solid #b7f5d8;
        border-radius: 14px;
        box-shadow: 0 4px 18px rgba(44,62,80,0.08);
        padding: 18px 24px 18px 18px;
        position: relative;
        min-width: 280px;
        max-width: none;
        margin: 0 0 18px 0;
        font-size: 1.08rem;
        color: #222;
        animation: fadeInDown 0.5s;
    }
    .success-alert {
        border-color: #b7f5d8;
        background: #f6fffa;
    }
    .alert-icon {
        width: 38px; height: 38px;
        border-radius: 50%;
        background: #b7f5d8;
        color: #11998e;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.6rem;
        flex-shrink: 0;
        margin-top: 2px;
    }
    .alert-content {
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: center;
        font-size: 1.08rem;
        color: #222;
    }
    .alert-content strong {
        font-weight: 700;
        color: #11998e;
        margin-right: 4px;
    }
    .alert-close {
        position: absolute;
        top: 10px;
        right: 10px;
        width: 32px;
        height: 32px;
        border: none;
        background: transparent;
        color: #888;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
        cursor: pointer;
        transition: background 0.18s, color 0.18s;
        z-index: 2;
    }
    .alert-close:hover {
        background: #e0e0e0;
        color: #ff416c;
    }
    @keyframes fadeInDown {
        from { opacity: 0; transform: translateY(-20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .btn-submit.btn-sm, .btn-submit.btn-sm:hover, .btn-submit.btn-sm:focus {
        text-decoration: none !important;
    }
    #history-loading-spinner {
        position: absolute;
        top: 12px;
        right: 24px;
        z-index: 10;
        display: none; /* Sembunyikan secara default */
    }
    .history-spinner {
        border: 3px solid #e0e7ef;
        border-top: 3px solid #1e3c72;
        border-radius: 50%;
        width: 22px;
        height: 22px;
        animation: spin 0.7s linear infinite;
    }
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    #history-table-wrapper { position: relative; }
</style>
@endsection

@section('content')
<div class="booking-page-wrapper position-relative">
    <div class="container-fluid px-4">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                {{-- HERO SECTION: Agenda Booking Hari Ini --}}
                <div class="booking-card">
                    <div class="booking-header">
                        <div class="header-icon"><i class="bx bx-calendar-event"></i></div>
                        <h2 class="header-title">Agenda Booking Hari Ini</h2>
                        <div class="header-decoration"></div>
                        <div class="text-light mt-2" style="font-size:1.1rem;opacity:0.9;position:relative;z-index:2;">{{ now()->isoFormat('dddd, D MMMM Y') }}</div>
                    </div>
                    <div class="booking-content">
                        <div class="table-container">
                            <table class="modern-table">
                                <thead>
                                    <tr>
                                        <th><i class="bx bx-time-five mr-1"></i>Jam</th>
                                        <th><i class="bx bx-book-content mr-1"></i>Judul</th>
                                        <th><i class="bx bx-door-open mr-1"></i>Ruangan</th>
                                        <th><i class="bx bx-user mr-1"></i>Pemesan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $todayBookings = $bookings->where('booking_date', now()->toDateString()); @endphp
                                    @forelse($todayBookings as $b)
                                        <tr>
                                            <td>{{ $b->booking_time }} - {{ $b->booking_end }}</td>
                                            <td>{{ $b->meeting_title ?? '-' }}</td>
                                            <td>{{ $b->facility->name ?? '-' }}</td>
                                            <td>{{ $b->user->name ?? '-' }}</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="4" class="text-center text-muted py-4">Tidak ada agenda booking hari ini.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                {{-- FORM Tambah/Edit Agenda Harian --}}
                <div class="booking-card">
                    <div class="booking-content">
                        <h5 class="mb-4" style="font-weight:700;color:#1e3c72;letter-spacing:0.5px;">Tambah / Edit Agenda Harian</h5>
                        <div class="alert alert-info mb-4" style="border-radius:12px;border:none;padding:16px 20px;">
                            <div class="d-flex align-items-center">
                                <i class="bx bx-info-circle me-2" style="font-size:1.2rem;"></i>
                                <div>
                                    <strong>Koneksi Tabel:</strong> Agenda manual terhubung dengan tabel agenda harian dan akan muncul di detail send message. 
                                    Data ini dapat diedit melalui halaman detail agenda harian.
                                </div>
                            </div>
                        </div>
                        @if(session('success'))
                            <div class="custom-alert success-alert mb-4">
                                <div class="alert-icon"><i class="bx bx-check-circle"></i></div>
                                <div class="alert-content"><strong>Berhasil!</strong> <span>{{ session('success') }}</span></div>
                                <button type="button" class="alert-close" data-bs-dismiss="alert"><i class="bx bx-x"></i></button>
                            </div>
                        @endif
                        <form id="agendaForm" method="POST" action="{{ route('admin.send_message.store') }}">
                            @csrf
                            <div class="form-section">
                                <div id="mingguan-list">
                                    {{-- Initial row, index 0 --}}
                                    <div class="row g-2 mb-2 align-items-end" data-index="0">
                                        <div class="col-md-3">
                                            <div class="form-group-enhanced mb-0">
                                                <label class="form-label-enhanced">Tanggal</label>
                                                <input type="date" name="ootd[0][date]" class="form-control-enhanced" required>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group-enhanced mb-0">
                                                <label class="form-label-enhanced">OOTD Cewek</label>
                                                <input type="text" name="ootd[0][cewek]" class="form-control-enhanced" placeholder="Contoh: Kebaya Merah">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group-enhanced mb-0">
                                                <label class="form-label-enhanced">OOTD Cowok</label>
                                                <input type="text" name="ootd[0][cowok]" class="form-control-enhanced" placeholder="Contoh: Surjan/Lurik">
                                            </div>
                                        </div>
                                        <div class="col-md-3 d-flex align-items-center gap-2">
                                            {{-- Display none initially for the first row's remove button --}}
                                            <button type="button" class="btn btn-sm btn-outline-danger btn-remove-mingguan" title="Hapus Hari" style="display: none;"><i class="bx bx-trash"></i></button>
                                        </div>
                                        <div class="col-12 agenda-manual-list mt-2"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex gap-2 mt-3 mb-2">
                                <button type="button" class="btn-submit btn-small" id="btn-add-manual" title="Tambah Agenda Manual">
                                    <i class="bx bx-plus"></i> <span>Agenda</span>
                                </button>
                                <button type="button" class="btn-submit btn-small" id="btn-add-mingguan" title="Tambah Hari">
                                    <i class="bx bx-plus"></i> <span>Hari</span>
                                </button>
                            </div>
                            <div class="d-flex justify-content-end mt-3">
                                <button type="submit" class="btn-submit btn-small"><i class="bx bx-save"></i> <span class="d-none d-md-inline">Simpan</span></button>
                            </div>
                        </form>
                    </div>
                </div>
                {{-- TABEL Agenda Booking Hari Ini --}}
                <div class="booking-card">
                    <div class="booking-content">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="mb-0" style="font-weight:700;color:#1e3c72;letter-spacing:0.5px;">Agenda Booking ({{ now()->format('d-m-Y') }})</h5>
                            <a href="{{ route('admin.bookings.index') }}" class="btn-submit btn-small">
                                <i class="bx bx-calendar-check"></i> Manajemen Booking
                            </a>
                        </div>
                        <div class="alert alert-info mb-4" style="border-radius:12px;border:none;padding:16px 20px;">
                            <div class="d-flex align-items-center">
                                <i class="bx bx-info-circle me-2" style="font-size:1.2rem;"></i>
                                <div>
                                    <strong>Koneksi Tabel:</strong> Data booking diambil dari tabel manajemen booking yang sudah disetujui. 
                                    <a href="{{ route('admin.bookings.index') }}" class="text-primary">Kelola booking</a>
                                </div>
                            </div>
                        </div>
                        <div class="table-container">
                            <table class="modern-table">
                                <thead>
                                    <tr>
                                        <th><i class="bx bx-time-five mr-1"></i>Jam</th>
                                        <th><i class="bx bx-book-content mr-1"></i>Judul</th>
                                        <th><i class="bx bx-door-open mr-1"></i>Ruangan</th>
                                        <th><i class="bx bx-user mr-1"></i>Pemesan</th>
                                        <th><i class="bx bx-check-circle mr-1"></i>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $todayBookings = $bookings->where('booking_date', now()->toDateString())->where('status', 'Disetujui'); @endphp
                                    @forelse($todayBookings as $b)
                                        <tr>
                                            <td>{{ $b->booking_time }} - {{ $b->booking_end }}</td>
                                            <td>{{ $b->meeting_title ?? '-' }}</td>
                                            <td>{{ $b->facility->name ?? '-' }}</td>
                                            <td>{{ $b->user->name ?? '-' }}</td>
                                            <td>
                                                <span class="badge-status" style="background:#e6f9f0;color:#11998e;">
                                                    <i class="bx bx-check-circle"></i> Disetujui
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="5" class="text-center text-muted py-4">Tidak ada agenda booking hari ini.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3">
                            <small class="text-muted">
                                <i class="bx bx-info-circle"></i> 
                                Menampilkan booking yang sudah disetujui untuk hari ini. 
                                <a href="{{ route('admin.bookings.index') }}" class="text-primary">Lihat semua booking</a>
                            </small>
                        </div>
                    </div>
                </div>
                {{-- TABEL HISTORY Agenda Harian --}}
                <div class="booking-card">
                    <div class="booking-content">
                        <h5 class="mb-4" style="font-weight:700;color:#1e3c72;letter-spacing:0.5px;">History Agenda Harian</h5>
                        <form method="GET" class="d-flex gap-2 mb-3 align-items-center" style="max-width:900px;" id="filterForm">
                            <input type="text" name="q" class="form-control-enhanced" style="width:200px;" placeholder="Cari agenda atau OOTD..." value="{{ request('q','') }}" />
                            <label class="me-2 mb-0" style="font-weight:600;">Urutkan:</label>
                            <select name="sort" class="form-control-enhanced" style="width:auto;" id="sortSelect">
                                <option value="desc" {{ request('sort','desc')=='desc'?'selected':'' }}>Terbaru</option>
                                <option value="asc" {{ request('sort')=='asc'?'selected':'' }}>Terlama</option>
                            </select>
                            <label class="me-2 mb-0 ms-3" style="font-weight:600;">Periode:</label>
                            <select name="periode" class="form-control-enhanced" style="width:auto;" id="periodeSelect">
                                <option value="all" {{ request('periode','all')=='all'?'selected':'' }}>Semua</option>
                                <option value="minggu" {{ request('periode')=='minggu'?'selected':'' }}>Minggu ini</option>
                                <option value="bulan" {{ request('periode')=='bulan'?'selected':'' }}>Bulan ini</option>
                            </select>
                        </form>
                        <div id="history-table-wrapper">
                            <div id="history-loading-spinner"><div class="history-spinner"></div></div>
                            <table class="modern-table" id="historyTable">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>OOTD Cewek</th>
                                        <th>OOTD Cowok</th>
                                        <th>Status Kirim</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php \Carbon\Carbon::setLocale('id'); @endphp
                                    @forelse($history as $item)
                                        <tr>
                                            <td>{{ $item->tanggal->locale('id')->translatedFormat('d F Y') }}</td>
                                            <td>{{ $item->ootd_cewek }}</td>
                                            <td>{{ $item->ootd_cowok }}</td>
                                            <td>
                                                <span class="badge-status" style="@if($item->status_kirim=='terkirim')background:#e6f9f0;color:#11998e;@elseif($item->status_kirim=='gagal')background:#ffeaea;color:#ff416c;@else background:#f4f7fd;color:#4A6DFB;@endif">
                                                    @if($item->status_kirim=='terkirim')<i class="bx bx-check-circle"></i>@elseif($item->status_kirim=='gagal')<i class="bx bx-x-circle"></i>@else<i class="bx bx-time"></i>@endif
                                                    {{ ucfirst($item->status_kirim) }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.send_message.show', $item->id) }}" class="btn-submit btn-sm px-3 py-1"><i class="bx bx-search"></i> Lihat Detail</a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="5" class="text-center text-muted py-4">Belum ada data</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                            <div class="mt-3" id="history-pagination-wrapper">
                                {{ $history->links('vendor.pagination.bootstrap-4') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
let mingguanIndex = document.querySelectorAll('#mingguan-list .row').length; // Inisialisasi berdasarkan jumlah row yang ada
let agendaManualCounter = []; // Untuk melacak indeks agenda manual per hari

// Fungsi untuk membuat baris OOTD (Mingguan)
function createMingguanRow(idx) {
    return `
        <div class="row g-2 mb-2 align-items-end" data-index="${idx}">
            <div class="col-md-3">
                <div class="form-group-enhanced mb-0">
                    <label class="form-label-enhanced">Tanggal</label>
                    <input type="date" name="ootd[${idx}][date]" class="form-control-enhanced" required>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group-enhanced mb-0">
                    <label class="form-label-enhanced">OOTD Cewek</label>
                    <input type="text" name="ootd[${idx}][cewek]" class="form-control-enhanced" placeholder="Contoh: Kebaya Merah">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group-enhanced mb-0">
                    <label class="form-label-enhanced">OOTD Cowok</label>
                    <input type="text" name="ootd[${idx}][cowok]" class="form-control-enhanced" placeholder="Contoh: Surjan/Lurik">
                </div>
            </div>
            <div class="col-md-3 d-flex align-items-center gap-2">
                <button type="button" class="btn btn-sm btn-outline-danger btn-remove-mingguan" title="Hapus Hari"><i class="bx bx-trash"></i></button>
            </div>
            <div class="col-12 agenda-manual-list mt-2"></div>
        </div>
    `;
}

// Fungsi untuk membuat input agenda manual
function createAgendaManualField(parentMingguanIndex, agendaIdx, tanggal = null) {
    return `
        <div class="row g-2 mb-2 align-items-end agenda-manual-item">
            <input type="hidden" name="agenda_manual[${parentMingguanIndex}][${agendaIdx}][date]" value="${tanggal ? tanggal : ''}">
            <div class="col-md-2">
                <input type="time" name="agenda_manual[${parentMingguanIndex}][${agendaIdx}][jam]" class="form-control-enhanced" placeholder="Jam Mulai">
            </div>
            <div class="col-md-2">
                <input type="time" name="agenda_manual[${parentMingguanIndex}][${agendaIdx}][jam_selesai]" class="form-control-enhanced" placeholder="Jam Selesai">
            </div>
            <div class="col-md-5">
                <input type="text" name="agenda_manual[${parentMingguanIndex}][${agendaIdx}][judul]" class="form-control-enhanced" placeholder="Judul Agenda">
            </div>
            <div class="col-md-2">
                <input type="text" name="agenda_manual[${parentMingguanIndex}][${agendaIdx}][lokasi]" class="form-control-enhanced" placeholder="Lokasi (opsional)">
            </div>
            <div class="col-md-1 d-flex align-items-center">
                <button type="button" class="btn btn-sm btn-outline-danger btn-remove-agenda-manual"><i class="bx bx-trash"></i></button>
            </div>
        </div>
    `;
}

// Event listener untuk tombol "Tambah Hari"
document.getElementById('btn-add-mingguan').addEventListener('click', function() {
    const mingguanList = document.getElementById('mingguan-list');
    mingguanList.insertAdjacentHTML('beforeend', createMingguanRow(mingguanIndex));
    agendaManualCounter[mingguanIndex] = 0; // Inisialisasi counter agenda manual untuk hari baru
    mingguanIndex++;
    updateRemoveHariButtons(); // Panggil untuk memperbarui visibilitas tombol hapus
});

// Event listener untuk tombol "Tambah Agenda"
document.getElementById('btn-add-manual').addEventListener('click', function() {
    const mingguanList = document.getElementById('mingguan-list');
    const allRows = mingguanList.querySelectorAll('.row[data-index]'); // Ambil semua baris mingguan dengan data-index
    
    if (allRows.length === 0) {
        // Jika tidak ada hari sama sekali, tambahkan hari pertama dulu
        mingguanList.insertAdjacentHTML('beforeend', createMingguanRow(mingguanIndex));
        agendaManualCounter[mingguanIndex] = 0;
        mingguanIndex++;
        updateRemoveHariButtons();
        // Kemudian lanjutkan untuk menambahkan agenda ke hari yang baru saja ditambahkan
        const lastRowAdded = mingguanList.lastElementChild;
        const parentMingguanIndex = lastRowAdded.getAttribute('data-index');
        const agendaList = lastRowAdded.querySelector('.agenda-manual-list');
        // Ambil tanggal dari input OOTD
        const tanggalInput = lastRowAdded.querySelector(`input[name="ootd[${parentMingguanIndex}][date]"]`);
        const tanggal = tanggalInput ? tanggalInput.value : '';
        agendaList.insertAdjacentHTML('beforeend', createAgendaManualField(parentMingguanIndex, agendaManualCounter[parentMingguanIndex], tanggal));
        agendaManualCounter[parentMingguanIndex]++;
    } else {
        // Tambah agenda manual ke hari terakhir yang ada
        const lastRow = allRows[allRows.length - 1];
        const parentMingguanIndex = lastRow.getAttribute('data-index'); // Dapatkan indeks dari atribut data-index
        let agendaList = lastRow.querySelector('.agenda-manual-list');
        if (!agendaList) {
            agendaList = document.createElement('div');
            agendaList.className = 'agenda-manual-list mt-2';
            lastRow.appendChild(agendaList);
        }
        // Ambil tanggal dari input OOTD
        const tanggalInput = lastRow.querySelector(`input[name="ootd[${parentMingguanIndex}][date]"]`);
        const tanggal = tanggalInput ? tanggalInput.value : '';
        // Pastikan agendaManualCounter untuk parentMingguanIndex sudah diinisialisasi
        if (typeof agendaManualCounter[parentMingguanIndex] === 'undefined') {
            agendaManualCounter[parentMingguanIndex] = agendaList.querySelectorAll('.agenda-manual-item').length;
        }
        agendaList.insertAdjacentHTML('beforeend', createAgendaManualField(parentMingguanIndex, agendaManualCounter[parentMingguanIndex], tanggal));
        agendaManualCounter[parentMingguanIndex]++;
    }
});

// Delegasi event untuk tombol hapus (hari dan agenda manual)
document.getElementById('mingguan-list').addEventListener('click', function(e) {
    if (e.target.closest('.btn-remove-mingguan')) {
        const rowToRemove = e.target.closest('.row');
        const indexToRemove = rowToRemove.getAttribute('data-index');
        rowToRemove.remove();
        // Hapus juga counter agenda manual untuk indeks ini
        delete agendaManualCounter[indexToRemove];
        updateRemoveHariButtons();
    }
    if (e.target.closest('.btn-remove-agenda-manual')) {
        e.target.closest('.agenda-manual-item').remove();
    }
});

// Fungsi untuk memperbarui visibilitas tombol hapus hari dan mengurutkan ulang indeks
function updateRemoveHariButtons() {
    const items = document.querySelectorAll('#mingguan-list .row');
    const newAgendaManualCounter = []; // Array sementara untuk agendaManualCounter baru

    items.forEach((item, idx) => {
        const btn = item.querySelector('.btn-remove-mingguan');
        if (btn) {
            btn.style.display = (items.length > 1) ? 'inline-block' : 'none';
        }
        // Perbarui data-index pada setiap row yang tersisa
        item.setAttribute('data-index', idx);
        // Perbarui name attribute untuk input di dalam row ini
        item.querySelectorAll('[name^="ootd["]').forEach(input => {
            input.name = input.name.replace(/ootd\[\d+\]/, `ootd[${idx}]`);
        });
        item.querySelectorAll('[name^="agenda_manual["]').forEach(input => {
            const oldName = input.name;
            // regex untuk mengganti indeks hari saja, bukan indeks agenda manual
            input.name = oldName.replace(/agenda_manual\[\d+\]\[(\d+)\]/, `agenda_manual[${idx}][$1]`);
        });

        // Inisialisasi atau perbarui counter agenda manual untuk indeks ini
        const agendaList = item.querySelector('.agenda-manual-list');
        newAgendaManualCounter[idx] = agendaList ? agendaList.querySelectorAll('.agenda-manual-item').length : 0;
    });

    // Ganti agendaManualCounter lama dengan yang baru setelah semua perbaruan
    agendaManualCounter = newAgendaManualCounter;
}

// Fungsi untuk update semua input hidden date pada agenda manual di hari tertentu
function updateAgendaManualDatesForHari(parentMingguanIndex) {
    const mingguanList = document.getElementById('mingguan-list');
    const hariRow = mingguanList.querySelector(`.row[data-index="${parentMingguanIndex}"]`);
    if (!hariRow) return;
    const tanggalInput = hariRow.querySelector(`input[name="ootd[${parentMingguanIndex}][date]"]`);
    const tanggal = tanggalInput ? tanggalInput.value : '';
    // Update semua input hidden date pada agenda manual di hari ini
    hariRow.querySelectorAll(`input[name^="agenda_manual[${parentMingguanIndex}"]][name$="[date]"]`).forEach(function(input) {
        input.value = tanggal;
    });
}

// Script agar tombol close (X) pada popup alert bisa berfungsi
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.alert-close').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const alert = btn.closest('.custom-alert');
            if(alert) alert.remove();
        });
    });

    // Panggil updateRemoveHariButtons saat DOM selesai dimuat
    // untuk memastikan tombol hapus hari pertama disembunyikan jika hanya ada satu
    updateRemoveHariButtons();

    // Inisialisasi agendaManualCounter untuk baris yang sudah ada di HTML saat load
    document.querySelectorAll('#mingguan-list .row').forEach((row, idx) => {
        const agendaList = row.querySelector('.agenda-manual-list');
        agendaManualCounter[idx] = agendaList ? agendaList.querySelectorAll('.agenda-manual-item').length : 0;
        row.setAttribute('data-index', idx); // Pastikan data-index terpasang pada baris awal
    });

    // Pastikan spinner div sudah ada di dalam historyWrapper saat halaman dimuat
    const historyWrapper = document.getElementById('history-table-wrapper');
    const historySpinner = document.getElementById('history-loading-spinner');
    if (historyWrapper && !historyWrapper.contains(historySpinner)) {
        historyWrapper.prepend(historySpinner);
    }

    // Tambahkan event listener untuk semua input tanggal OOTD
    document.getElementById('mingguan-list').addEventListener('input', function(e) {
        if (e.target.matches('input[type="date"][name^="ootd["]')) {
            // Ambil parentMingguanIndex dari name
            const match = e.target.name.match(/ootd\[(\d+)\]\[date\]/);
            if (match) {
                const parentMingguanIndex = match[1];
                updateAgendaManualDatesForHari(parentMingguanIndex);
            }
        }
    });
});
</script>

<script>
// Pindahkan fungsi-fungsi ini ke lingkup global agar dapat diakses oleh semua bagian skrip
// Fungsi untuk melampirkan event paginasi
function attachPaginationEvents() {
    const paginationLinks = document.querySelectorAll('#history-pagination-wrapper .pagination a');
    paginationLinks.forEach(function(link) {
        link.removeEventListener('click', handlePaginationClick); // Hapus listener sebelumnya jika ada
        link.addEventListener('click', handlePaginationClick);
    });
}

// Handler untuk event klik paginasi
function handlePaginationClick(e) {
    e.preventDefault();
    const link = e.currentTarget;
    if (link.href) {
        fetchHistoryPage(link.href);
    }
}

// Fungsi untuk mengambil halaman paginasi tertentu
function fetchHistoryPage(url) {
    const historyWrapper = document.getElementById('history-table-wrapper');
    const historySpinner = document.getElementById('history-loading-spinner');
    let spinnerTimeout;

    // Tampilkan spinner setelah sedikit penundaan
    spinnerTimeout = setTimeout(() => {
        historySpinner.style.display = 'block';
    }, 200);

    fetch(url, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(res => {
        if (!res.ok) {
            throw new Error('Network response was not ok ' + res.statusText);
        }
        return res.text();
    })
    .then(html => {
        clearTimeout(spinnerTimeout); // Hapus timeout spinner jika request cepat selesai
        historySpinner.style.display = 'none'; // Sembunyikan spinner

        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');

        const newTableWrapper = doc.getElementById('history-table-wrapper');
        if (newTableWrapper) {
            // Ganti seluruh konten historyWrapper dengan konten baru
            historyWrapper.innerHTML = newTableWrapper.innerHTML;

            // Pastikan spinner div tetap ada di dalam historyWrapper setelah update
            historyWrapper.prepend(historySpinner); // Re-append spinner

            // Lampirkan kembali event paginasi ke tautan baru
            attachPaginationEvents();
        } else {
            historyWrapper.innerHTML = '<div class="text-danger text-center py-4">Gagal memuat halaman atau halaman tidak ditemukan.</div>';
        }
    })
    .catch(error => {
        clearTimeout(spinnerTimeout); // Pastikan spinner disembunyikan juga jika ada error
        historySpinner.style.display = 'none';
        console.error('Error fetching history page:', error);
        historyWrapper.innerHTML = '<div class="text-danger text-center py-4">Terjadi kesalahan saat memuat halaman. Silakan coba lagi.</div>';
    });
}


// Script AJAX untuk filter (fetchHistory)
document.addEventListener('DOMContentLoaded', function() {
    const filterForm = document.getElementById('filterForm');
    const historyWrapper = document.getElementById('history-table-wrapper');
    const historySpinner = document.getElementById('history-loading-spinner');

    // Fungsi untuk mengambil data riwayat (digunakan untuk filter)
    function fetchHistory() {
        const params = new URLSearchParams(new FormData(filterForm)).toString();
        let spinnerTimeout;

        // Tampilkan spinner setelah sedikit penundaan
        spinnerTimeout = setTimeout(() => {
            historySpinner.style.display = 'block';
        }, 200); // Tunda 200ms

        fetch(window.location.pathname + '?' + params, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => {
            if (!res.ok) {
                throw new Error('Network response was not ok ' + res.statusText);
            }
            return res.text();
        })
        .then(html => {
            clearTimeout(spinnerTimeout); // Hapus timeout spinner jika request cepat selesai
            historySpinner.style.display = 'none'; // Sembunyikan spinner

            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');

            const newTableWrapper = doc.getElementById('history-table-wrapper');
            if (newTableWrapper) {
                // Ganti seluruh konten historyWrapper dengan konten baru
                historyWrapper.innerHTML = newTableWrapper.innerHTML;

                // Pastikan spinner div tetap ada di dalam historyWrapper setelah update
                historyWrapper.prepend(historySpinner);

                // Lampirkan kembali event paginasi ke tautan baru
                attachPaginationEvents();
            } else {
                historyWrapper.innerHTML = '<div class="text-danger text-center py-4">Gagal memuat data atau data tidak ditemukan.</div>';
            }
        })
        .catch(error => {
            clearTimeout(spinnerTimeout); // Pastikan spinner disembunyikan juga jika ada error
            historySpinner.style.display = 'none';
            console.error('Error fetching history:', error);
            historyWrapper.innerHTML = '<div class="text-danger text-center py-4">Terjadi kesalahan saat memuat data. Silakan coba lagi.</div>';
        });
    }

    // Event listener untuk perubahan pada form filter
    filterForm.querySelectorAll('input, select').forEach(element => {
        element.addEventListener('change', fetchHistory);
        element.addEventListener('input', fetchHistory); // Untuk input text agar langsung filter
    });

    // Panggil attachPaginationEvents() setelah DOM dimuat untuk paginasi awal
    attachPaginationEvents();
});
</script>
@endsection