@extends('admin.layouts.admin_dashboard')
@section('title', 'Manajemen Kalender')

@section('content')
<!-- Hero Section Baru (Sesuai Manajemen Fasilitas, dengan efek background animasi) -->
<div class="admin-hero-section mb-4 position-relative overflow-hidden">
    <div class="admin-hero-content text-center position-relative" style="z-index:2;">
        <div class="admin-hero-icon mx-auto mb-3">
            <i class="bx bx-calendar-check"></i>
        </div>
        <h1 class="admin-hero-title">Manajemen Kalender</h1>
        <div class="admin-hero-underline"></div>
    </div>
</div>

<!-- Statistik Section (di bawah hero section) -->
<div class="container mb-4">
    <div class="row justify-content-center g-4">
        <div class="col-12 col-md-4">
            <div class="stat-card-admin">
                <div class="stat-icon-admin"><i class="bx bx-calendar-check"></i></div>
                <div class="stat-info-admin">
                    <div class="stat-number-admin">{{ $todayBookings ?? 0 }}</div>
                    <div class="stat-label-admin">Booking Hari Ini</div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="stat-card-admin">
                <div class="stat-icon-admin"><i class="bx bx-check-circle"></i></div>
                <div class="stat-info-admin">
                    <div class="stat-number-admin">{{ $approvedBookings ?? 0 }}</div>
                    <div class="stat-label-admin">Disetujui</div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="stat-card-admin">
                <div class="stat-icon-admin"><i class="bx bx-time"></i></div>
                <div class="stat-info-admin">
                    <div class="stat-number-admin">{{ $pendingBookings ?? 0 }}</div>
                    <div class="stat-label-admin">Menunggu</div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.admin-hero-section {
    background: linear-gradient(120deg, #234080 0%, #355ca8 100%);
    border-radius: 32px;
    padding: 48px 0 28px 0;
    margin-bottom: 32px;
    position: relative;
    box-shadow: 0 8px 32px rgba(44,62,80,0.08);
    overflow: hidden;
}
.admin-hero-section::before {
    content: '';
    position: absolute;
    left: 50%;
    top: 50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle at 50% 50%, rgba(255,255,255,0.10) 0%, transparent 70%);
    transform: translate(-50%, -50%);
    animation: hero-bg-rotate 8s linear infinite;
    z-index: 1;
}
@keyframes hero-bg-rotate {
    0% { transform: translate(-50%, -50%) rotate(0deg); }
    100% { transform: translate(-50%, -50%) rotate(360deg); }
}
.admin-hero-content {
    max-width: 700px;
    margin: 0 auto;
    position: relative;
    z-index: 2;
}
.admin-hero-icon {
    width: 70px;
    height: 70px;
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 0 0 8px rgba(79,172,254,0.10), 0 4px 16px rgba(44,62,80,0.10);
    margin-bottom: 12px;
    animation: hero-glow 4s linear infinite;
}
.admin-hero-icon i {
    font-size: 1.7rem;
    color: #fff;
}
@keyframes hero-glow {
    0% { box-shadow: 0 0 0 16px rgba(79,172,254,0.10), 0 8px 32px rgba(44,62,80,0.10); }
    50% { box-shadow: 0 0 0 32px rgba(79,172,254,0.18), 0 8px 32px rgba(44,62,80,0.10); }
    100% { box-shadow: 0 0 0 16px rgba(79,172,254,0.10), 0 8px 32px rgba(44,62,80,0.10); }
}
.admin-hero-title {
    color: #fff;
    font-size: 1.6rem;
    font-weight: 700;
    margin-bottom: 0.3rem;
    text-shadow: 1px 1px 4px rgba(44,62,80,0.10);
}
.admin-hero-underline {
    width: 36px;
    height: 3px;
    background: linear-gradient(90deg, #4facfe, #00f2fe);
    border-radius: 2px;
    margin: 12px auto 0 auto;
}

/* Statistik Card Admin */
.stat-card-admin {
    background: #fff;
    border-radius: 18px;
    box-shadow: 0 4px 24px rgba(44,62,80,0.08);
    display: flex;
    align-items: center;
    gap: 18px;
    padding: 28px 24px;
    margin-bottom: 0;
    transition: box-shadow 0.3s;
    position: relative;
    overflow: hidden;
    min-height: 120px;
}
.stat-card-admin:hover {
    box-shadow: 0 8px 32px rgba(44,62,80,0.16);
}
.stat-icon-admin {
    width: 54px;
    height: 54px;
    border-radius: 50%;
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    color: #fff;
    box-shadow: 0 2px 8px rgba(79,172,254,0.10);
}
.stat-info-admin {
    flex: 1;
}
.stat-number-admin {
    font-size: 2.1rem;
    font-weight: 700;
    color: #222;
    margin-bottom: 2px;
}
.stat-label-admin {
    font-size: 1.05rem;
    color: #6b7280;
    font-weight: 500;
    letter-spacing: 0.5px;
}
@media (max-width: 900px) {
    .stat-card-admin { flex-direction: column; align-items: flex-start; text-align: left; }
    .stat-icon-admin { margin-bottom: 10px; }
}
@media (max-width: 600px) {
    .admin-hero-section { padding: 24px 0 12px 0; }
    .admin-hero-title { font-size: 1.1rem; }
    .admin-hero-icon { width: 44px; height: 44px; }
    .admin-hero-underline { height: 2px; width: 20px; margin: 8px auto 0 auto; }
    .stat-card-admin { padding: 18px 12px; }
    .stat-number-admin { font-size: 1.3rem; }
}

/* FullCalendar Button Styling */
.fc .fc-toolbar {
    gap: 1.2rem;
    margin-bottom: 18px;
}
.fc .fc-toolbar-chunk {
    display: flex;
    gap: 0.7rem;
}
.fc .fc-button {
    background: #E3EDFF !important;
    color: #234080 !important;
    border-radius: 12px !important;
    padding: 0.6rem 1.4rem !important;
    font-size: 1rem !important;
    font-weight: 600 !important;
    border: none !important;
    box-shadow: 0 2px 8px rgba(44,62,80,0.06);
    transition: background 0.2s, color 0.2s;
    margin: 0 !important;
}
.fc .fc-button:not(:last-child) {
    margin-right: 0.3rem !important;
}
.fc .fc-button:hover, .fc .fc-button:focus {
    background: #2563eb !important;
    color: #fff !important;
}
.fc .fc-button-primary:not(.fc-button-active) {
    background: #b3c6fa !important;
    color: #234080 !important;
}
.fc .fc-button-active, .fc .fc-button-primary.fc-button-active {
    background: #4F8BFF !important;
    color: #fff !important;
    box-shadow: 0 4px 16px rgba(44,62,80,0.10);
}
.fc .fc-button:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}
.fc .fc-toolbar-title {
    font-size: 1.6rem !important;
    font-weight: 700 !important;
    color: #222 !important;
    letter-spacing: 0.5px;
}
.fc .fc-today-button {
    font-size: 0.85rem !important;
    padding: 0.5rem 0.9rem !important;
    min-width: 70px;
    white-space: nowrap;
}
.fc .fc-button-group .fc-button {
    font-size: 0.85rem !important;
    padding: 0.5rem 0.9rem !important;
    min-width: 70px;
    white-space: nowrap;
}
@media (max-width: 600px) {
    .fc .fc-toolbar-title { font-size: 1.1rem !important; }
    .fc .fc-button { font-size: 0.85rem !important; padding: 0.4rem 0.8rem !important; }
}
</style>

<div class="container mb-5">
    <div class="row g-4">
        <!-- Calendar Section -->
        <div class="col-lg-8" id="calendar-col">
            <div class="card shadow rounded-4 mb-4">
                <div class="card-header bg-white border-0 d-flex align-items-center justify-content-between rounded-top-4">
                    <div class="d-flex align-items-center gap-2">
                        <div class="calendar-header-icon">
                            <i class="bx bx-calendar"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-bold">Kalender Pemesanan</h5>
                            <small class="text-muted">Lihat jadwal pemesanan dalam format kalender</small>
                        </div>
                    </div>
                    <div class="header-actions d-flex gap-2">
                        <button class="btn btn-primary btn-sm" onclick="openFilterExportPrintModal()">
                            <i class="bx bx-download"></i> Export
                        </button>
                        <button class="btn btn-outline-primary btn-sm" onclick="openFilterExportPrintModal()">
                            <i class="bx bx-printer"></i> Print
                        </button>
                    </div>
                </div>
                <div class="card-body p-3">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bx bx-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bx bx-error-circle me-2"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    <div id="calendar"></div>
                </div>
            </div>
        </div>
        
        <!-- Booking List Section -->
        <div class="col-lg-4" id="sidebar-col">
            <div class="card shadow rounded-4 mb-4">
                <div class="card-header bg-white border-0 d-flex align-items-center justify-content-between rounded-top-4">
                    <h6 class="mb-0 fw-bold"><i class="bx bx-list-ul"></i> Daftar Pemesanan</h6>
                    <span class="badge bg-primary bg-opacity-10 text-primary">{{ $allBookings ? count($allBookings) : 0 }} Pemesanan</span>
                </div>
                <div class="card-body pb-0">
                    <div class="mb-3 d-flex gap-2">
                        <input type="text" id="searchInput" class="form-control form-control-sm" placeholder="Cari pemesanan...">
                        <select class="form-select form-select-sm" id="statusFilter" style="max-width:140px;">
                            <option value="">Semua Status</option>
                            <option value="Disetujui">Disetujui</option>
                            <option value="Menunggu Konfirmasi">Menunggu Konfirmasi</option>
                            <option value="Ditolak">Ditolak</option>
                            <option value="Selesai">Selesai</option>
                        </select>
                        <select class="form-select form-select-sm" id="orderFilter" style="max-width:120px;">
                            <option value="desc">Terbaru</option>
                            <option value="asc">Terlama</option>
                        </select>
                        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="clearSearch()" id="clearSearchBtn" style="display:none;" data-bs-toggle="tooltip" data-bs-placement="top" title="Hapus filter pencarian">
                            <i class="bx bx-x"></i>
                        </button>
                    </div>
                    <div class="booking-list-content" id="bookingListContent"></div>
                    <div class="d-flex flex-column align-items-center mb-3">
                        <div id="bookingPaginationLabel"></div>
                        <nav>
                            <ul class="pagination pagination-sm mb-0" id="bookingPagination"></ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Booking Detail Modal -->
<div class="modal fade" id="bookingDetailModal" tabindex="-1" aria-labelledby="bookingDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content rounded-4">
            <div class="modal-header rounded-top-4">
                <h5 class="modal-title" id="bookingDetailModalLabel">
                    <i class="bx bx-calendar-check"></i> Detail Pemesanan
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="bookingDetailContent"></div>
        </div>
    </div>
</div>

<!-- Modal Filter Export/Print -->
<div class="modal fade" id="filterExportPrintModal" tabindex="-1" aria-labelledby="filterExportPrintModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="filterExportPrintModalLabel">Filter Export / Print</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="filterExportPrintForm">
                    <div class="mb-3">
                        <label for="filterStartDate" class="form-label">Tanggal Mulai</label>
                        <input type="date" class="form-control" id="filterStartDate" name="start_date">
                    </div>
                    <div class="mb-3">
                        <label for="filterEndDate" class="form-label">Tanggal Akhir</label>
                        <input type="date" class="form-control" id="filterEndDate" name="end_date">
                    </div>
                    <div class="mb-3">
                        <label for="filterStatus" class="form-label">Status Booking</label>
                        <select class="form-select" id="filterStatus" name="status">
                            <option value="">Semua Status</option>
                            <option value="Disetujui">Disetujui</option>
                            <option value="Menunggu Konfirmasi">Menunggu Konfirmasi</option>
                            <option value="Ditolak">Ditolak</option>
                            <option value="Selesai">Selesai</option>
                            <option value="Dibatalkan">Dibatalkan</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" id="btnExportFiltered">Export</button>
                <button type="button" class="btn btn-outline-primary" id="btnPrintFiltered">Print</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
<style>
    body { background: #f8fafc; }
    
    /* Hero Section */
    .hero-section {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: #fff;
        border-radius: 0 0 2rem 2rem;
        box-shadow: 0 8px 32px rgba(44,62,80,0.08);
        position: relative;
        overflow: hidden;
    }
    .hero-icon {
        width: 70px;
        height: 70px;
        background: rgba(255,255,255,0.15);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        margin-bottom: 0.5rem;
    }
    .hero-title {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }
    .hero-subtitle {
        font-size: 1.1rem;
        opacity: 0.9;
    }
    
    /* Stat Cards */
    .stat-card {
        background: #fff;
        color: #667eea;
        border-radius: 1rem;
        box-shadow: 0 2px 12px rgba(102,126,234,0.08);
        padding: 1.2rem 0.5rem;
        text-align: center;
        font-weight: 600;
        transition: box-shadow 0.2s;
        min-width: 110px;
    }
    .stat-card i {
        font-size: 1.5rem;
        margin-bottom: 0.3rem;
        display: block;
    }
    .stat-number {
        font-size: 1.4rem;
        font-weight: 700;
        color: #333;
    }
    .stat-label {
        font-size: 0.9rem;
        color: #888;
    }
    
    /* Calendar Header */
    .calendar-header-icon {
        width: 38px;
        height: 38px;
        background: #f1f5ff;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
        color: #667eea;
        margin-right: 0.5rem;
    }
    
    /* Cards */
    .card {
        border: none;
    }
    .card-header {
        background: #fff;
        border-bottom: 1px solid #f1f5ff;
    }
    
    /* Booking List */
    .booking-list-content {
        min-height: 200px;
        max-height: 420px;
        overflow-y: auto;
        padding-bottom: 1rem;
    }
    .booking-item {
        background: #fff;
        border-radius: 1rem;
        border: 1px solid #e2e8f0;
        padding: 1.1rem 1.2rem 0.7rem 1.2rem;
        margin-bottom: 1.1rem;
        transition: box-shadow 0.2s;
        position: relative;
        box-shadow: 0 2px 8px rgba(102,126,234,0.04);
    }
    .booking-item:hover {
        box-shadow: 0 4px 16px rgba(102,126,234,0.10);
        border-color: #667eea;
    }
    .booking-item-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.3rem;
    }
    .booking-number {
        font-weight: 600;
        color: #667eea;
        font-size: 0.95rem;
    }
    .status-badge {
        font-size: 0.8rem;
        font-weight: 600;
        border-radius: 12px;
        padding: 0.2rem 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .status-approved { background: #10b981; color: #fff; }
    .status-pending { background: #f59e0b; color: #fff; }
    .status-rejected { background: #ef4444; color: #fff; }
    .status-completed { background: #6b7280; color: #fff; }
    .facility-name {
        margin: 0 0 0.1rem 0;
        font-weight: 600;
        color: #1E293B;
        font-size: 1.05rem;
    }
    .user-name {
        margin: 0 0 0.2rem 0;
        color: #64748B;
        font-size: 0.95rem;
    }
    .booking-details {
        display: flex;
        flex-direction: row;
        gap: 1.2rem;
        font-size: 0.93rem;
        color: #475569;
        margin-bottom: 0.2rem;
    }
    .detail-item { display: flex; align-items: center; gap: 0.4rem; }
    .booking-item-actions { margin-top: 0.2rem; display: flex; justify-content: flex-end; }
    .booking-item-actions .btn { font-size: 0.9rem; padding: 0.2rem 0.7rem; border-radius: 8px; }
    .empty-state { color: #94A3B8; text-align: center; padding: 2.5rem 1rem; }
    .empty-state i { font-size: 2.2rem; margin-bottom: 1rem; }
    
    /* FullCalendar Customization */
    .fc { font-family: 'Poppins', sans-serif; background: #fff; border-radius: 15px; }
    .fc-toolbar { background: #f8fafc; padding: 1rem; border-radius: 10px 10px 0 0; border-bottom: 1px solid #e2e8f0; }
    .fc-toolbar-title { font-weight: 600; color: #1E293B; font-size: 1.25rem; }
    .fc-button { background: #667eea !important; border-color: #667eea !important; font-weight: 500; border-radius: 8px; padding: 0.5rem 1rem; font-size: 0.9rem; color: #fff !important; }
    .fc-button:hover, .fc-button-active { background: #5a67d8 !important; border-color: #5a67d8 !important; }
    .fc-daygrid-day {
        border: 1px solid #e5e7eb !important;
        background: #f9fafb;
    }
    .fc-daygrid-day.fc-day-today {
        background: #e0e7ff !important;
    }
    .fc-daygrid-day-number,
    .fc .fc-daygrid-day-top {
        border: none !important;
        background: none !important;
        box-shadow: none !important;
    }
    .fc-day-today .fc-daygrid-day-number {
        background: #667eea;
        color: white;
        border-radius: 6px;
        box-shadow: none !important;
        border: none !important;
    }
    .fc-event-disetujui { background: #10b981 !important; border: none; }
    .fc-event-menunggu-konfirmasi { background: #f59e0b !important; border: none; color: #fff !important; }
    .fc-event-ditolak { background: #ef4444 !important; border: none; }
    .fc-event-selesai { background: #6b7280 !important; border: none; }
    .fc-event { font-size: 0.92em; border-radius: 8px !important; box-shadow: 0 2px 6px rgba(102,126,234,0.08); }
    .fc-daygrid-day-number,
    .fc .fc-daygrid-day-top {
        border: none !important;
        background: none !important;
        box-shadow: none !important;
    }
    .fc-daygrid-day-frame {
        border: none !important;
        background: none !important;
        box-shadow: none !important;
    }
    .fc-daygrid-day-number {
        text-decoration: none !important;
    }
    .fc-daygrid-day-number:hover,
    .fc-daygrid-day-number:focus,
    .fc .fc-daygrid-day-top a {
        text-decoration: none !important;
        outline: none !important;
        box-shadow: none !important;
    }
    .fc-col-header-cell a {
        text-decoration: none !important;
    }
    .fc-col-header-cell a:hover,
    .fc-col-header-cell a:focus {
        text-decoration: none !important;
        outline: none !important;
        box-shadow: none !important;
    }
    
    /* Event Dot Circle */
    .fc-dot-circle {
        color: #fff !important;
        border-radius: 50% !important;
        width: 24px !important;
        height: 24px !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        font-size: 0.95em !important;
        margin: auto !important;
        font-weight: 600 !important;
        z-index: 10 !important;
        position: relative !important;
        box-shadow: 0 2px 4px rgba(0,0,0,0.2) !important;
        border: 2px solid #fff !important;
    }
    
    /* Pastikan event container tidak mengganggu */
    .fc-event {
        background: transparent !important;
        border: none !important;
        box-shadow: none !important;
        padding: 0 !important;
        margin: 0 !important;
    }
    
    .fc-event-main {
        background: transparent !important;
        border: none !important;
        padding: 0 !important;
        margin: 0 !important;
    }
    
    .fc-event-main-frame {
        background: transparent !important;
        border: none !important;
        padding: 0 !important;
        margin: 0 !important;
    }
    
    /* Pastikan event terlihat */
    .fc-event {
        background: transparent !important;
        border: none !important;
        box-shadow: none !important;
    }
    
    .fc-event-main {
        background: transparent !important;
        border: none !important;
    }
    
    /* Responsive Design */
    @media (max-width: 991px) {
        .hero-title { font-size: 2rem; }
        .stat-card { min-width: 90px; padding: 1rem 0.3rem; }
    }
    @media (max-width: 768px) {
        .hero-title { font-size: 1.5rem; }
        .stat-card { min-width: 80px; padding: 0.8rem 0.2rem; }
        .booking-list-content { max-height: 250px; }
        .fc-toolbar { padding: 0.5rem !important; gap: 0.5rem; }
        .fc-toolbar-title { font-size: 0.9em !important; }
        .fc-button { font-size: 0.8rem !important; padding: 0.3rem 0.8rem !important; }
    }
    @media (max-width: 575px) {
        .fc-daygrid-day-number {
            font-size: 0.55em !important;
            padding: 0px 1px !important;
        }
        .booking-details {
            flex-direction: column;
            gap: 0.5rem;
        }
    }
    
    /* Calendar Responsive */
    #calendar-col {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        box-sizing: border-box;
    }
    .calendar-container {
        overflow: visible;
        width: 100%;
        box-sizing: border-box;
    }
    .fc-daygrid-body table,
    .fc-scrollgrid-sync-table {
        table-layout: fixed;
        min-width: unset;
        width: 100% !important;
    }
    .fc-col-header-cell,
    .fc-daygrid-day {
        width: calc(100% / 7) !important;
        box-sizing: border-box;
    }
    .fc-daygrid-day-number {
        font-size: 0.6em !important;
        padding: 1px 2px !important;
    }
</style>

<style>
/* FullCalendar Button Styling */
.fc .fc-toolbar {
    gap: 1.2rem;
    margin-bottom: 18px;
}
.fc .fc-toolbar-chunk {
    display: flex;
    gap: 0.7rem;
}
.fc .fc-button {
    border-radius: 12px !important;
    padding: 0.6rem 1.4rem !important;
    font-size: 1rem !important;
    font-weight: 600 !important;
    background: #6d83e6 !important;
    color: #fff !important;
    border: none !important;
    box-shadow: 0 2px 8px rgba(44,62,80,0.06);
    transition: background 0.2s, color 0.2s;
    margin: 0 !important;
}
.fc .fc-button:not(:last-child) {
    margin-right: 0.3rem !important;
}
.fc .fc-button:hover, .fc .fc-button:focus {
    background: #4666e4 !important;
    color: #fff !important;
}
.fc .fc-button-primary:not(.fc-button-active) {
    background: #b3c6fa !important;
    color: #234080 !important;
}
.fc .fc-button-active, .fc .fc-button-primary.fc-button-active {
    background: #234080 !important;
    color: #fff !important;
    box-shadow: 0 4px 16px rgba(44,62,80,0.10);
}
.fc .fc-button:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}
.fc .fc-toolbar-title {
    font-size: 1.6rem !important;
    font-weight: 700 !important;
    color: #222 !important;
    letter-spacing: 0.5px;
}
@media (max-width: 600px) {
    .fc .fc-toolbar-title { font-size: 1.1rem !important; }
    .fc .fc-button { font-size: 0.85rem !important; padding: 0.4rem 0.8rem !important; }
}
</style>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales-all.min.js'></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var bookedDates = @json($bookedDates);

        // Debug: log data dari controller
        console.log('Data bookedDates dari controller:', bookedDates);

        // Data dari controller sudah dalam format yang benar, langsung gunakan
        const eventsForCalendar = bookedDates;
        
        // Debug: cek apakah data benar-benar ada
        console.log('Raw bookedDates from controller:', bookedDates);
        console.log('Type of bookedDates:', typeof bookedDates);
        console.log('Is Array:', Array.isArray(bookedDates));
        
        // Debug: log events untuk memastikan data benar
        console.log('Events for calendar:', eventsForCalendar);
        console.log('Events length:', eventsForCalendar.length);
        if (eventsForCalendar.length > 0) {
            console.log('First event:', eventsForCalendar[0]);
            console.log('First event extendedProps:', eventsForCalendar[0].extendedProps);
        }
        
        // Pastikan events memiliki format yang benar
        const validatedEvents = eventsForCalendar.map(event => {
            console.log('Processing event:', event);
            if (event.extendedProps && event.extendedProps.bookings) {
                console.log('Event has bookings:', event.extendedProps.bookings.length);
                return event;
            } else {
                console.log('Event missing extendedProps or bookings');
                return null;
            }
        }).filter(event => event !== null);
        
        console.log('Validated events:', validatedEvents);
        
        // Fallback: jika tidak ada events valid, buat dummy event untuk testing
        if (validatedEvents.length === 0) {
            console.log('No valid events found, creating dummy event for testing');
            const today = new Date().toISOString().split('T')[0];
            validatedEvents.push({
                title: '1 Booking',
                start: today,
                end: today,
                allDay: true,
                extendedProps: {
                    bookings: [{
                        id: 1,
                        facilityName: 'Test Facility',
                        userName: 'Test User',
                        bookingDate: today,
                        bookingTime: '10:00:00',
                        bookingHours: 2,
                        bookingStatus: 'Disetujui',
                        bookingAmount: 100000,
                        bookingPaymentMethod: 'Cash'
                    }]
                }
            });
        }

        console.log('Creating FullCalendar with events:', validatedEvents);
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'id',
            events: validatedEvents,
            slotMinTime: '07:00:00', // hanya tampilkan mulai jam 07:00
            slotMaxTime: '22:00:00', // hanya tampilkan sampai jam 22:00
            height: 600, // tinggi grid mingguan agar proporsional
            eventDidMount: function(info) {
                console.log('Event mounted:', info.event);
                console.log('Event extendedProps:', info.event.extendedProps);
                console.log('Event title:', info.event.title);
                console.log('Event start:', info.event.start);
            },
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,dayGridWeek,dayGridDay' // ganti timeGridWeek jadi dayGridWeek
            },
            height: 'auto',
            contentHeight: 'auto',
            eventDisplay: 'auto',
            eventTextColor: '#fff',
            dayMaxEvents: true,
            views: {
                dayGridMonth: {
                    titleFormat: { year: 'numeric', month: 'long' }
                }
            },
            eventContent: function(arg) {
                var viewType = arg.view.type;
                // Tambahan: view hari (dayGridDay) tampilkan list detail booking
                if (viewType === 'dayGridDay' && arg.event.extendedProps && arg.event.extendedProps.bookings && arg.event.extendedProps.bookings.length > 0) {
                    let html = `<div style='display:flex; flex-direction:column; gap:6px; align-items:flex-start; width:100%;'>`;
                    arg.event.extendedProps.bookings.forEach(function(b) {
                        html += `<div style='background:#f1f5ff; border-radius:8px; padding:6px 10px; margin-bottom:2px; width:100%; color:#234080; font-weight:500;'>
                            <b style='color:#234080; font-size:1.08em;'>${b.facilityName}</b> <br>
                            <span style='font-size:0.95em; color:#234080;'>${b.bookingTime} (${b.bookingHours} Jam) - ${b.userName} <span class='badge ${getStatusClass(b.bookingStatus)}'>${b.bookingStatus}</span></span>
                        </div>`;
                    });
                    html += `</div>`;
                    return { html: html };
                }
                // Month view tetap bulatan dan angka
                if (arg.event.extendedProps && arg.event.extendedProps.bookings && arg.event.extendedProps.bookings.length > 0) {
                    const today = new Date();
                    today.setHours(0, 0, 0, 0); // Set ke awal hari
                    const eventDate = new Date(arg.event.start);
                    eventDate.setHours(0, 0, 0, 0); // Set ke awal hari
                    let bgColor = '#2563eb'; // biru untuk hari ini dan masa depan
                    if (eventDate < today) {
                        bgColor = '#6b7280'; // abu-abu untuk kemarin dan sebelumnya
                    }
                    const dotHtml = `<div class='fc-dot-circle' style='background:${bgColor}'>${arg.event.extendedProps.bookings.length}</div>`;
                    return { html: dotHtml };
                }
                // Fallback: jika tidak ada extendedProps, coba dari title
                else if (arg.event.title && arg.event.title.includes('Booking')) {
                    const bookingCount = parseInt(arg.event.title.split(' ')[0]) || 1;
                    const today = new Date();
                    today.setHours(0, 0, 0, 0);
                    const eventDate = new Date(arg.event.start);
                    eventDate.setHours(0, 0, 0, 0);
                    let bgColor = '#2563eb';
                    if (eventDate < today) {
                        bgColor = '#6b7280';
                    }
                    const dotHtml = `<div class='fc-dot-circle' style='background:${bgColor}'>${bookingCount}</div>`;
                    return { html: dotHtml };
                } else {
                    return false;
                }
            },
            eventClick: function(info) {
                // Show modal with list of bookings for that day
                const bookings = info.event.extendedProps.bookings;
                if (!bookings || bookings.length === 0) return;
                let html = `<h6 class='mb-3'>Daftar Booking (${bookings.length})</h6>`;
                html += '<ul class="list-group">';
                bookings.forEach((b, idx) => {
                    html += `<li class='list-group-item d-flex justify-content-between align-items-center'>
                        <span><b>${b.facilityName}</b> <small class='text-muted'>(${b.bookingTime} - ${b.bookingHours} jam)</small></span>
                        <span class='badge ${getStatusClass(b.bookingStatus)}'>${b.bookingStatus}</span>
                    </li>`;
                });
                html += '</ul>';
                document.getElementById('bookingDetailContent').innerHTML = html;
                const modal = new bootstrap.Modal(document.getElementById('bookingDetailModal'));
                modal.show();
            }
        });

        calendar.render();
        
        // Debug: cek events setelah render
        setTimeout(() => {
            const events = calendar.getEvents();
            console.log('Events after render:', events);
            console.log('Events count after render:', events.length);
            if (events.length > 0) {
                console.log('First event after render:', events[0]);
                console.log('First event extendedProps after render:', events[0].extendedProps);
            }
        }, 1000);
        
        // Debug: cek events setelah render
        setTimeout(() => {
            const events = calendar.getEvents();
            console.log('Events after render:', events);
            console.log('Events count after render:', events.length);
            if (events.length > 0) {
                console.log('First event after render:', events[0]);
                console.log('First event extendedProps after render:', events[0].extendedProps);
            }
        }, 1000);

        // Initialize booking list
        updateBookingList();

        // Status filter functionality
        const statusFilter = document.getElementById('statusFilter');
        statusFilter.addEventListener('change', function() {
            performSearch();
        });

        // Order filter functionality
        const orderFilter = document.getElementById('orderFilter');
        orderFilter.addEventListener('change', function() {
            performSearch();
        });

        // Search functionality
        const searchInput = document.getElementById('searchInput');
        searchInput.addEventListener('input', function() {
            performSearch();
        });
    });

    // Global variables
    let allBookings = @json($allBookings);
    let filteredBookings = [...allBookings];
    let bookingsPerPage = 5;
    let currentBookingPage = 1;

    // Utility functions
    function showBookingDetail(event) {
        const props = event.extendedProps;
        const modal = new bootstrap.Modal(document.getElementById('bookingDetailModal'));
        
        const statusClass = getStatusClass(props.bookingStatus);
        const formattedDate = new Date(props.bookingDate).toLocaleDateString('id-ID', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
        const formattedTime = new Date('2000-01-01T' + props.bookingTime).toLocaleTimeString('id-ID', {
            hour: '2-digit',
            minute: '2-digit'
        });
        
        document.getElementById('bookingDetailContent').innerHTML = `
            <div class="row">
                <div class="col-md-6">
                    <div class="detail-section">
                        <h6 class="detail-title">
                            <i class="bx bx-building"></i> Informasi Fasilitas
                        </h6>
                        <div class="detail-content">
                            <p><strong>Nama Fasilitas:</strong> ${props.facilityName}</p>
                            <p><strong>Status:</strong> <span class="badge ${statusClass}">${props.bookingStatus}</span></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="detail-section">
                        <h6 class="detail-title">
                            <i class="bx bx-user"></i> Informasi Pengguna
                        </h6>
                        <div class="detail-content">
                            <p><strong>Nama Pengguna:</strong> ${props.userName}</p>
                            <p><strong>Metode Pembayaran:</strong> ${props.bookingPaymentMethod}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-6">
                    <div class="detail-section">
                        <h6 class="detail-title">
                            <i class="bx bx-calendar"></i> Jadwal
                        </h6>
                        <div class="detail-content">
                            <p><strong>Tanggal:</strong> ${formattedDate}</p>
                            <p><strong>Waktu:</strong> ${formattedTime} (${props.bookingHours} Jam)</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="detail-section">
                        <h6 class="detail-title">
                            <i class="bx bx-credit-card"></i> Pembayaran
                        </h6>
                        <div class="detail-content">
                            <p><strong>Jumlah:</strong> Rp ${props.bookingAmount?.toLocaleString() || 'N/A'}</p>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        modal.show();
    }

    function getStatusClass(status) {
        switch(status) {
            case 'Disetujui': return 'bg-success';
            case 'Menunggu Konfirmasi': return 'bg-warning';
            case 'Ditolak': return 'bg-danger';
            case 'Selesai': return 'bg-secondary';
            default: return 'bg-primary';
        }
    }

    function exportCalendar() {
        // Export all events in FullCalendar to CSV
        var calendarApi = FullCalendar.getCalendar ? FullCalendar.getCalendar('calendar') : null;
        var events = [];
        if (calendarApi && calendarApi.getEvents) {
            events = calendarApi.getEvents();
        } else if (window.calendar && window.calendar.getEvents) {
            events = window.calendar.getEvents();
        } else if (typeof eventsForCalendar !== 'undefined') {
            events = eventsForCalendar;
        }
        let csv = 'Facility,User,Status,Date,Time,Duration\n';
        (events.length ? events : eventsForCalendar).forEach(ev => {
            let props = ev.extendedProps || ev;
            csv += `"${props.facilityName}","${props.userName}","${props.bookingStatus}","${props.bookingDate}","${props.bookingTime}","${props.bookingHours} jam"\n`;
        });
        var blob = new Blob([csv], { type: 'text/csv' });
        var link = document.createElement('a');
        link.href = window.URL.createObjectURL(blob);
        link.download = 'calendar-bookings.csv';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }

    function printCalendar() {
        // Print only the calendar area
        window.print();
    }

    function performSearch() {
        const searchQuery = document.getElementById('searchInput').value;
        const statusFilter = document.getElementById('statusFilter').value;
        const orderFilter = document.getElementById('orderFilter').value;
        const clearSearchBtn = document.getElementById('clearSearchBtn');
        
        let filtered = [...allBookings];

        // Apply search filter
        if (searchQuery) {
            const lowerCaseSearchQuery = searchQuery.toLowerCase();
            filtered = filtered.filter(booking => {
                // Format tanggal booking ke format lokal Indonesia
                const bookingDateObj = new Date(booking.bookingDate);
                const localDate = bookingDateObj.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' }).toLowerCase();
                const localDateShort = bookingDateObj.toLocaleDateString('id-ID').toLowerCase();
                // Format waktu booking
                const localTime = booking.bookingTime ? booking.bookingTime.substring(0,5) : '';
                return (
                    booking.userName.toLowerCase().includes(lowerCaseSearchQuery) ||
                    booking.facilityName.toLowerCase().includes(lowerCaseSearchQuery) ||
                    booking.bookingStatus.toLowerCase().includes(lowerCaseSearchQuery) ||
                    booking.bookingDate.toLowerCase().includes(lowerCaseSearchQuery) ||
                    localDate.includes(lowerCaseSearchQuery) ||
                    localDateShort.includes(lowerCaseSearchQuery) ||
                    localTime.includes(lowerCaseSearchQuery)
                );
            });
        }

        // Apply status filter
        if (statusFilter) {
            filtered = filtered.filter(booking => booking.bookingStatus === statusFilter);
        }

        // Sort by date and time
        filtered.sort((a, b) => {
            const dateA = new Date(a.bookingDate + 'T' + a.bookingTime);
            const dateB = new Date(b.bookingDate + 'T' + b.bookingTime);
            return orderFilter === 'asc' ? dateA - dateB : dateB - dateA;
        });

        filteredBookings = filtered;
        currentBookingPage = 1;
        updateBookingList();
        
        // Show/hide clear search button
        if (searchQuery || statusFilter) {
            clearSearchBtn.style.display = 'inline-block';
        } else {
            clearSearchBtn.style.display = 'none';
        }
    }

    function clearSearch() {
        document.getElementById('searchInput').value = '';
        document.getElementById('statusFilter').value = '';
        document.getElementById('orderFilter').value = 'desc'; // Reset order filter
        document.getElementById('clearSearchBtn').style.display = 'none';
        
        filteredBookings = [...allBookings];
        currentBookingPage = 1;
        updateBookingList();
    }

    function updateBookingList() {
        const bookingListContent = document.getElementById('bookingListContent');
        const bookingPagination = document.getElementById('bookingPagination');
        if (!bookingListContent) return;

        if (!filteredBookings || filteredBookings.length === 0) {
            bookingListContent.innerHTML = `
                <div class="empty-state">
                    <i class="bx bx-calendar-x"></i>
                    <p>Tidak ada pemesanan yang ditemukan.</p>
                    <small class="text-muted">Coba ubah filter atau kata kunci pencarian Anda.</small>
                </div>
            `;
            if (bookingPagination) bookingPagination.innerHTML = '';
            return;
        }

        const totalPages = Math.ceil(filteredBookings.length / bookingsPerPage);
        if (currentBookingPage > totalPages) currentBookingPage = 1;
        const startIdx = (currentBookingPage - 1) * bookingsPerPage;
        const endIdx = startIdx + bookingsPerPage;
        const pageBookings = filteredBookings.slice(startIdx, endIdx);

        const bookingItemsHTML = pageBookings.map((booking, index) => {
            const statusClass = getStatusClass(booking.bookingStatus);
            const formattedDate = new Date(booking.bookingDate).toLocaleDateString('id-ID', {
                day: '2-digit', month: 'short', year: 'numeric'
            });
            const formattedTime = new Date('2000-01-01T' + booking.bookingTime).toLocaleTimeString('id-ID', {
                hour: '2-digit', minute: '2-digit'
            });
            return `
                <div class="booking-item" data-status="${booking.bookingStatus}" data-index="${startIdx + index}">
                    <div class="booking-item-header">
                        <span class="booking-number">#${startIdx + index + 1}</span>
                        <span class="status-badge ${statusClass}">${booking.bookingStatus}</span>
                    </div>
                    <div class="booking-item-body">
                        <h6 class="facility-name">${booking.facilityName}</h6>
                        <p class="user-name">${booking.userName}</p>
                        <div class="booking-details">
                            <span class="detail-item">
                                <i class="bx bx-calendar"></i>
                                ${formattedDate}
                            </span>
                            <span class="detail-item">
                                <i class="bx bx-time"></i>
                                ${formattedTime} (${booking.bookingHours} Jam)
                            </span>
                        </div>
                    </div>
                    <div class="booking-item-actions">
                        <button class="btn btn-sm" onclick="viewBooking(${startIdx + index})" data-bs-toggle="tooltip" data-bs-placement="top" title="Lihat detail pemesanan">
                            <i class="bx bx-show"></i> Detail
                        </button>
                    </div>
                </div>
            `;
        }).join('');

        bookingListContent.innerHTML = `
            <div class="booking-items">
                ${bookingItemsHTML}
            </div>
        `;

        // Tambahkan label halaman di bawah daftar pesanan, sebelum pagination
        document.getElementById('bookingPaginationLabel').innerHTML =
            totalPages > 1 ? `<div class='text-center mb-2 small text-muted'>Halaman ${currentBookingPage} dari ${totalPages}</div>` : '';

        // Pagination
        if (bookingPagination) {
            if (totalPages <= 1) {
                bookingPagination.innerHTML = '';
            } else {
                let pagHTML = '';
                pagHTML += `<li class="page-item${currentBookingPage === 1 ? ' disabled' : ''}"><button class="page-link" onclick="changeBookingPage(${currentBookingPage - 1})">&laquo;</button></li>`;
                for (let i = 1; i <= totalPages; i++) {
                    pagHTML += `<li class="page-item${i === currentBookingPage ? ' active' : ''}"><button class="page-link" onclick="changeBookingPage(${i})">${i}</button></li>`;
                }
                pagHTML += `<li class="page-item${currentBookingPage === totalPages ? ' disabled' : ''}"><button class="page-link" onclick="changeBookingPage(${currentBookingPage + 1})">&raquo;</button></li>`;
                bookingPagination.innerHTML = pagHTML;
            }
        }
    }

    function changeBookingPage(page) {
        const totalPages = Math.ceil(filteredBookings.length / bookingsPerPage);
        if (page < 1 || page > totalPages) return;
        currentBookingPage = page;
        updateBookingList();
    }

    function viewBooking(index) {
        const booking = filteredBookings[index];
        if (booking) {
            // Create a mock event object for the modal
            const mockEvent = {
                extendedProps: booking
            };
            showBookingDetail(mockEvent);
        }
    }

    // Export and Print Functions
    function openFilterExportPrintModal() {
        const modal = new bootstrap.Modal(document.getElementById('filterExportPrintModal'));
        document.getElementById('filterStartDate').value = '';
        document.getElementById('filterEndDate').value = '';
        document.getElementById('filterStatus').value = '';
        modal.show();
    }

    document.getElementById('btnExportFiltered').addEventListener('click', function() {
        const startDate = document.getElementById('filterStartDate').value;
        const endDate = document.getElementById('filterEndDate').value;
        const status = document.getElementById('filterStatus').value;

        let filtered = [...allBookings];

        if (startDate) {
            filtered = filtered.filter(booking => booking.bookingDate >= startDate);
        }
        if (endDate) {
            filtered = filtered.filter(booking => booking.bookingDate <= endDate);
        }
        if (status) {
            filtered = filtered.filter(booking => booking.bookingStatus === status);
        }

        // Export filtered data
        let csv = 'Tanggal,Waktu,Fasilitas,Pengguna,Status,Durasi (Jam),Jumlah\n';
        filtered.forEach(booking => {
            const formattedDate = new Date(booking.bookingDate).toLocaleDateString('id-ID');
            const formattedTime = new Date('2000-01-01T' + booking.bookingTime).toLocaleTimeString('id-ID', {
                hour: '2-digit', minute: '2-digit'
            });
            csv += `"${formattedDate}","${formattedTime}","${booking.facilityName}","${booking.userName}","${booking.bookingStatus}","${booking.bookingHours}","${booking.bookingAmount || 0}"\n`;
        });

        const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
        const link = document.createElement('a');
        const url = URL.createObjectURL(blob);
        link.setAttribute('href', url);
        link.setAttribute('download', `calendar_export_${new Date().toISOString().split('T')[0]}.csv`);
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);

        const modal = bootstrap.Modal.getInstance(document.getElementById('filterExportPrintModal'));
        modal.hide();
    });

    document.getElementById('btnPrintFiltered').addEventListener('click', function() {
        const startDate = document.getElementById('filterStartDate').value;
        const endDate = document.getElementById('filterEndDate').value;
        const status = document.getElementById('filterStatus').value;

        let filtered = [...allBookings];

        if (startDate) {
            filtered = filtered.filter(booking => booking.bookingDate >= startDate);
        }
        if (endDate) {
            filtered = filtered.filter(booking => booking.bookingDate <= endDate);
        }
        if (status) {
            filtered = filtered.filter(booking => booking.bookingStatus === status);
        }

        // Print filtered data
        let rows = filtered.map((booking, idx) => {
            const formattedDate = new Date(booking.bookingDate).toLocaleDateString('id-ID');
            const formattedTime = new Date('2000-01-01T' + booking.bookingTime).toLocaleTimeString('id-ID', {
                hour: '2-digit', minute: '2-digit'
            });
            return `
                <tr>
                    <td>${idx + 1}</td>
                    <td>${booking.facilityName}</td>
                    <td>${booking.userName}</td>
                    <td>${formattedDate}</td>
                    <td>${formattedTime} (${booking.bookingHours} Jam)</td>
                    <td>${booking.bookingStatus}</td>
                </tr>
            `;
        }).join('');

        const html = `
            <html>
            <head>
                <title>Daftar Peminjam Ruang Meeting</title>
                <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css' rel='stylesheet'>
                <style>
                    body { padding: 24px; }
                    h2 { margin-bottom: 24px; }
                    table { width: 100%; border-collapse: collapse; }
                    th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
                    th { background: #f8f9fa; }
                </style>
            </head>
            <body>
                <h2>Daftar Peminjam Ruang Meeting</h2>
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Fasilitas</th>
                            <th>Peminjam</th>
                            <th>Tanggal</th>
                            <th>Jam</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${rows}
                    </tbody>
                </table>
            </body>
            </html>
        `;

        const printWindow = window.open('', '', 'height=700,width=900');
        printWindow.document.write(html);
        printWindow.document.close();
        printWindow.focus();
        setTimeout(() => { printWindow.print(); printWindow.close(); }, 500);

        const modal = bootstrap.Modal.getInstance(document.getElementById('filterExportPrintModal'));
        modal.hide();
    });

    // Responsive calendar functions
    function adjustCalendarView() {
        const calendar = FullCalendar.getCalendar ? FullCalendar.getCalendar('calendar') : null;
        if (calendar) {
            calendar.updateSize();
        }
    }

    // Handle window resize
    window.addEventListener('resize', function() {
        adjustCalendarView();
    });

    // Handle sidebar toggle (if exists)
    window.triggerCalendarResize = function() {
        setTimeout(() => {
            adjustCalendarView();
        }, 350);
    };
</script>
@endsection