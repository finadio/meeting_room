@extends('admin.layouts.admin_dashboard')
@section('title', 'Manajemen Kalender')

@section('content')
<div class="custom-page-header mb-4">
    <div class="header-icon-wrapper">
        <i class="bx bx-calendar"></i>
    </div>
    <h1 class="header-title-main">Manajemen Kalender</h1>
    <div class="header-underline"></div>
</div>
<div class="admin-calendar-wrapper">
    <div class="container-fluid px-4">
        <div class="row g-4">
            <!-- Kalender -->
            <div class="col-lg-8 col-md-12 mb-4" id="calendar-col">
                <div class="calendar-main-card shadow-sm">
                    <div class="calendar-header-section d-flex align-items-center justify-content-between">
                        <div class="header-content d-flex align-items-center gap-3">
                            <div class="header-icon bg-light rounded-circle d-flex align-items-center justify-content-center" style="width:48px;height:48px;">
                                <i class="bx bx-calendar fs-4 text-primary"></i>
                            </div>
                            <div class="header-text">
                                <h3 class="header-title mb-0">Manajemen Kalender</h3>
                                <p class="header-subtitle mb-0">Kelola dan pantau semua pemesanan ruang meeting</p>
                            </div>
                        </div>
                        <div class="header-actions d-flex gap-2">
                            <button class="btn btn-primary btn-sm" onclick="exportCalendar()">
                                <i class="bx bx-download"></i> Export
                            </button>
                            <button class="btn btn-outline-primary btn-sm" onclick="printCalendar()">
                                <i class="bx bx-printer"></i> Print
                            </button>
                        </div>
                    </div>
                    <div class="calendar-content p-3">
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
                        <div class="calendar-container">
                            <div id='calendar'></div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Sidebar Statistik & Daftar Booking -->
            <div class="col-lg-4 col-md-12" id="sidebar-col">
                <div class="stats-card mb-4 shadow-sm">
                    <div class="stats-header bg-gradient bg-primary text-white rounded-top p-3">
                        <h5 class="mb-0"><i class="bx bx-bar-chart-alt-2"></i> Statistik Hari Ini</h5>
                    </div>
                    <div class="stats-content p-3">
                        <div class="stat-item d-flex align-items-center gap-3 mb-3">
                            <div class="stat-icon bg-primary d-flex align-items-center justify-content-center rounded" style="width:40px;height:40px;">
                                <i class="bx bx-calendar-check"></i>
                            </div>
                            <div class="stat-info">
                                <span class="stat-number fw-bold">{{ $todayBookings ?? 0 }}</span>
                                <span class="stat-label d-block">Booking Hari Ini</span>
                            </div>
                        </div>
                        <div class="stat-item d-flex align-items-center gap-3 mb-3">
                            <div class="stat-icon bg-success d-flex align-items-center justify-content-center rounded" style="width:40px;height:40px;">
                                <i class="bx bx-check-circle"></i>
                            </div>
                            <div class="stat-info">
                                <span class="stat-number fw-bold">{{ $approvedBookings ?? 0 }}</span>
                                <span class="stat-label d-block">Disetujui</span>
                            </div>
                        </div>
                        <div class="stat-item d-flex align-items-center gap-3">
                            <div class="stat-icon bg-warning d-flex align-items-center justify-content-center rounded" style="width:40px;height:40px;">
                                <i class="bx bx-time"></i>
                            </div>
                            <div class="stat-info">
                                <span class="stat-number fw-bold">{{ $pendingBookings ?? 0 }}</span>
                                <span class="stat-label d-block">Menunggu</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="booking-list-card shadow-sm">
                    <div class="booking-list-header bg-gradient bg-primary text-white rounded-top p-3 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="bx bx-list-ul"></i> Daftar Pemesanan</h5>
                        <div class="header-actions">
                            <button class="btn btn-sm btn-outline-light" onclick="refreshList()">
                                <i class="bx bx-refresh"></i>
                            </button>
                        </div>
                    </div>
                    <div class="search-filter-section p-3 border-bottom">
                        <div class="search-form mb-2">
                            <div class="input-group">
                                <input type="text" id="searchInput" class="form-control" placeholder="Cari pemesanan..." value="{{ request('search') }}">
                                <button type="button" class="btn btn-primary" onclick="performSearch()">
                                    <i class="bx bx-search"></i>
                                </button>
                                <button type="button" class="btn btn-outline-secondary" onclick="clearSearch()" id="clearSearchBtn" style="display: {{ request('search') ? 'block' : 'none' }};">
                                    <i class="bx bx-x"></i>
                                </button>
                            </div>
                        </div>
                        <div class="filter-controls mt-2">
                            <select class="form-select form-select-sm" id="statusFilter">
                                <option value="">Semua Status</option>
                                <option value="Disetujui">Disetujui</option>
                                <option value="Menunggu Konfirmasi">Menunggu Konfirmasi</option>
                                <option value="Ditolak">Ditolak</option>
                                <option value="Selesai">Selesai</option>
                            </select>
                        </div>
                    </div>
                    <div class="booking-list-content p-3" id="bookingListContent"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Tombol Toggle Sidebar -->
<div class="text-end my-2">
    <button class="btn btn-secondary btn-sm" id="toggleSidebarBtn" onclick="toggleSidebarAuto()">Tampilkan/Sembunyikan Sidebar</button>
</div>
<!-- Modal Detail Booking -->
<div class="modal fade" id="bookingDetailModal" tabindex="-1" aria-labelledby="bookingDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bookingDetailModalLabel">Detail Pemesanan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="bookingDetailContent"></div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
<style>
    /* Custom Page Header (di luar wrapper utama untuk efek penuh) */
    .custom-page-header {
        background: linear-gradient(90deg, #234080 0%, #2952a3 100%);
        border-radius: 40px 40px 0 0;
        margin: 0 auto 2rem auto;
        padding: 48px 0 32px 0;
        text-align: center;
        position: relative;
        box-shadow: 0 8px 32px rgba(44,62,80,0.08);
        max-width: 98vw;
        overflow: hidden;
        z-index: 10;
    }
    .custom-page-header::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.10) 0%, transparent 70%);
        animation: rotate 8s linear infinite;
        z-index: 0;
    }
    @keyframes rotate {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    .header-icon-wrapper {
        width: 90px;
        height: 90px;
        background: linear-gradient(135deg, #1ecbff 0%, #00bfff 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 18px auto;
        box-shadow: 0 4px 24px rgba(30,203,255,0.15);
    }
    .header-icon-wrapper i {
        color: #fff;
        font-size: 2.8rem;
    }
    .header-title-main {
        color: white;
        font-size: 1.8rem;
        font-weight: 700;
        margin: 0;
        position: relative;
        z-index: 2;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        letter-spacing: 1px;
        transition: color 0.3s, transform 0.3s, text-shadow 0.3s;
        cursor: pointer;
    }
    .header-title-main:hover {
        color: #1ecbff;
        transform: scale(1.04);
        text-shadow: 0 4px 16px rgba(30,203,255,0.18);
    }
    .header-underline {
        width: 80px;
        height: 6px;
        background: linear-gradient(90deg, #1ecbff 0%, #00bfff 100%);
        border-radius: 3px;
        margin: 0.5rem auto 0 auto;
    }
    /* Responsive Header */
    @media (max-width: 600px) {
        .custom-page-header { padding: 32px 0 20px 0; }
        .header-icon-wrapper { width: 60px; height: 60px; }
        .header-icon-wrapper i { font-size: 1.8rem; }
        .header-title-main { font-size: 1.8rem; }
        .header-underline { width: 40px; height: 4px; }
    }


    /* === RAPIH & CLEAN === */
    .admin-calendar-wrapper {
        background: #f8fafc;
        min-height: 100vh;
        padding: 2rem 0 3rem 0;
        padding-top: calc(2rem + 40px);
    }
    /* Main Card */
    .calendar-main-card, .stats-card, .booking-list-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(30,64,175,0.06);
        border: 1px solid #e2e8f0;
        margin-bottom: 2rem;
    }
    .calendar-header-section {
        background: none;
        color: #1E293B;
        padding: 1.5rem 2rem 1.2rem 2rem;
        border-bottom: 1px solid #e2e8f0;
        border-radius: 16px 16px 0 0;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
    }
    .header-content { display: flex; align-items: center; gap: 1.2rem; }
    .header-icon { background: #e0f2fe; width: 48px; height: 48px; border-radius: 50%; display: flex; align-items: center; justify-content: center; }
    .header-icon i { font-size: 1.5rem; color: #1E40AF; }
    .header-title { margin: 0; font-size: 1.3rem; font-weight: 700; color: #1E293B; }
    .header-subtitle { margin: 0; opacity: 0.8; font-size: 0.95rem; color: #64748B; }
    .header-actions { display: flex; gap: 0.5rem; }
    .header-actions .btn { font-size: 0.95rem; padding: 0.35rem 1rem; border-radius: 8px; }
    .calendar-content { padding: 1.5rem 2rem; }
    .calendar-container { background: #fff; border-radius: 15px; box-shadow: none; padding: 0; overflow: hidden; }
    .fc-toolbar .fc-button { margin-right: 8px !important; margin-bottom: 4px !important; }
    .fc-toolbar .fc-button:last-child { margin-right: 0 !important; }
    /* Stats Card */
    .stats-header { background: linear-gradient(135deg, #1E40AF 0%, #3B82F6 100%); color: white; padding: 1rem 1.5rem; border-radius: 16px 16px 0 0; }
    .stats-content { padding: 1.2rem 1.5rem; }
    .stat-item { display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem; }
    .stat-item:last-child { margin-bottom: 0; }
    .stat-icon.bg-primary { background: #1E40AF !important; }
    .stat-icon.bg-success { background: #22c55e !important; }
    .stat-icon.bg-warning { background: #fbbf24 !important; }
    .stat-icon { width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: white; font-size: 1.2rem; }
    .stat-info .stat-number { color: #1E293B; font-size: 1.2rem; font-weight: 700; }
    .stat-label { color: #64748B; font-size: 0.95rem; }
    /* Booking List Card */
    .booking-list-card { height: auto; display: flex; flex-direction: column; }
    .booking-list-header { background: linear-gradient(135deg, #1E40AF 0%, #3B82F6 100%); color: white; padding: 1rem 1.5rem; border-radius: 16px 16px 0 0; display: flex; justify-content: space-between; align-items: center; }
    .booking-list-header h5 { margin: 0; font-size: 1.05rem; font-weight: 600; }
    .search-filter-section {
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }
    .search-filter-section .search-form {
        width: 100%;
    }
    .search-filter-section .filter-controls {
        width: 100%;
    }

    .booking-list-content { background: #fff; border-radius: 0 0 16px 16px; padding: 1.2rem 1.5rem; flex: 1; overflow-y: auto; }
    .booking-items { display: flex; flex-direction: column; gap: 1rem; }
    .booking-item { background: #f8fafc; border-radius: 10px; border: 1px solid #e2e8f0; padding: 1rem 1.2rem; display: flex; flex-direction: column; gap: 0.5rem; transition: box-shadow 0.2s; }
    .booking-item:hover { box-shadow: 0 2px 8px rgba(30,64,175,0.08); }
    .booking-item-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.2rem; }
    .booking-number { font-weight: 600; color: #64748B; font-size: 0.95rem; }
    .status-badge { font-size: 0.8rem; font-weight: 600; border-radius: 12px; padding: 0.2rem 0.8rem; text-transform: uppercase; letter-spacing: 0.5px; }
    .status-approved { background: #bbf7d0; color: #166534; }
    .status-pending { background: #fef08a; color: #92400e; }
    .status-rejected { background: #fecaca; color: #991b1b; }
    .status-completed { background: #e0e7ef; color: #334155; }
    .facility-name { margin: 0 0 0.1rem 0; font-weight: 600; color: #1E293B; font-size: 1.05rem; }
    .user-name { margin: 0 0 0.2rem 0; color: #64748B; font-size: 0.95rem; }
    .booking-details { display: flex; flex-direction: row; gap: 1.2rem; font-size: 0.93rem; color: #475569; }
    .detail-item { display: flex; align-items: center; gap: 0.4rem; }
    .booking-item-actions { margin-top: 0.5rem; display: flex; justify-content: flex-end; }
    .booking-item-actions .btn { font-size: 0.9rem; padding: 0.2rem 0.7rem; border-radius: 8px; }
    .empty-state { color: #94A3B8; text-align: center; padding: 2.5rem 1rem; }
    .empty-state i { font-size: 2.2rem; margin-bottom: 1rem; }
    /* FullCalendar Customization */
    .fc { font-family: 'Poppins', sans-serif; background: #fff; border-radius: 15px; }
    .fc-toolbar { background: #f8fafc; padding: 0.5rem 1rem 0.5rem 1rem !important; border-radius: 10px 10px 0 0; border-bottom: 1px solid #e2e8f0; }
    .fc-toolbar-title { font-weight: 600; color: #1E293B; font-size: 1.25rem; }
    .fc-button { background: #1E40AF !important; border-color: #1E40AF !important; font-weight: 500; border-radius: 8px; padding: 0.5rem 1rem; font-size: 0.9rem; }
    .fc-button:hover, .fc-button-active { background: #1E3A8A !important; border-color: #1E3A8A !important; }
    .fc-daygrid-day { border-color: #e2e8f0; }
    .fc-daygrid-day-number { color: #1E293B; font-weight: 500; }
    .fc-day-today { background: rgba(30, 64, 175, 0.1) !important; }
    .fc-day-today .fc-daygrid-day-number { background: #1E40AF; color: white; border-radius: 50%; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; }
    .fc-event-disetujui { background: #22c55e !important; border: none; }
    .fc-event-menunggu-konfirmasi { background: #fbbf24 !important; border: none; color: #92400e !important; }
    .fc-event-ditolak { background: #ef4444 !important; border: none; }
    .fc-event-selesai { background: #64748B !important; border: none; }
    .fc .fc-button { min-width: 70px; }
    .fc-event, .fc-event-main {
      white-space: nowrap !important;
      overflow: hidden !important;
      text-overflow: ellipsis !important;
      max-width: 100% !important;
      min-height: 22px !important;
      font-size: 0.92em !important;
      padding: 1px 6px !important;
      line-height: 1.2 !important;
    }
    /* Responsive Design */
    @media (max-width: 991px) {
        .calendar-content { padding: 1rem 0.5rem; }
        .calendar-header-section { padding: 1rem 1rem 0.8rem 1rem; }
        .stats-content, .booking-list-content { padding: 1rem; }
    }
    @media (max-width: 768px) {
        .calendar-header-section { flex-direction: column; gap: 1rem; text-align: center; }
        .header-actions { width: 100%; justify-content: center; }
        .booking-list-card { max-height: 500px; }
        .calendar-main-card, .stats-card, .booking-list-card { margin-bottom: 1.2rem; }
    }
    @media (max-width: 991px) {
      .fc { font-size: 0.92em; }
      .fc-event, .fc-event-main {
        font-size: 0.92em !important;
        min-height: 22px !important;
        padding: 1px 4px !important;
      }
    }
    @media (max-width: 600px) {
      .fc { font-size: 0.85em; }
      .fc-event, .fc-event-main {
        font-size: 0.85em !important;
        min-height: 18px !important;
        padding: 1px 2px !important;
      }
      .fc-toolbar-title { font-size: 1em !important; }
    }
    .fc-event, .fc-event-main {
      display: flex !important;
      align-items: center !important;
      white-space: nowrap !important;
      overflow: hidden !important;
      text-overflow: ellipsis !important;
      max-width: 100% !important;
      min-height: 26px !important;
      font-size: 1em !important;
      padding: 2px 8px !important;
      line-height: 1.2 !important;
    }
    .fc-daygrid-day-frame { min-height: 60px !important; padding: 2px 2px 2px 2px !important; }
    .fc-daygrid-day-events { margin: 0 !important; }
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
        let filteredBookedDates = bookedDates;

        // Filter berdasarkan search query
        const urlParams = new URLSearchParams(window.location.search);
        const searchQuery = urlParams.get('search');
        if (searchQuery) {
            const lowerCaseSearchQuery = searchQuery.toLowerCase();
            filteredBookedDates = bookedDates.filter(booking => {
                return (
                    (booking.userName && booking.userName.toLowerCase().includes(lowerCaseSearchQuery)) ||
                    (booking.facilityName && booking.facilityName.toLowerCase().includes(lowerCaseSearchQuery)) ||
                    (booking.bookingStatus && booking.bookingStatus.toLowerCase().includes(lowerCaseSearchQuery))
                );
            });
        }
        // Sort
        filteredBookedDates.sort((a, b) => {
            const dateA = new Date(a.bookingDate + 'T' + a.bookingTime);
            const dateB = new Date(b.bookingDate + 'T' + b.bookingTime);
            return dateA - dateB;
        });
        // Transform ke event FullCalendar
        const eventsForCalendar = filteredBookedDates.map(booking => {
            const startTime = booking.bookingDate + 'T' + booking.bookingTime;
            const endTime = new Date(new Date(startTime).getTime() + (booking.bookingHours * 60 * 60 * 1000)).toISOString().slice(0, 19);
            // Format singkat: jam mulai + singkatan ruangan
            const jam = booking.bookingTime ? booking.bookingTime.substring(0,5) : '';
            // Ambil singkatan ruangan (2 kata pertama)
            let singkatan = booking.facilityName.split(' ').slice(0,2).join(' ');
            if (singkatan.length > 12) singkatan = singkatan.substring(0,12) + '.';
            const shortTitle = `${jam} ${singkatan}`;
            // Tooltip detail
            const tooltip = `${booking.facilityName} - ${booking.userName}\nTanggal: ${booking.bookingDate}\nJam: ${booking.bookingTime} (${booking.bookingHours} jam)\nStatus: ${booking.bookingStatus}`;
            return {
                title: shortTitle,
                start: startTime,
                end: endTime,
                extendedProps: booking,
                classNames: [`fc-event-${booking.bookingStatus.toLowerCase().replace(/\s/g, '-')}`],
                description: tooltip
            };
        });
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'id',
            events: eventsForCalendar,
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,dayGridDay'
            },
            height: 'auto',
            contentHeight: 'auto',
            eventDisplay: 'block',
            eventTextColor: '#fff',
            dayMaxEvents: true,
            views: {
                dayGridMonth: {
                    titleFormat: { year: 'numeric', month: 'long' }
                }
            },
            fixedWeekCount: false,
            eventClick: function(info) {
                showBookingDetail(info.event);
            },
            eventMouseEnter: function(info) {
                // Tooltip custom pakai title bawaan browser
                info.el.setAttribute('title', info.event.extendedProps.facilityName + ' - ' + info.event.extendedProps.userName + '\nTanggal: ' + info.event.extendedProps.bookingDate + '\nJam: ' + info.event.extendedProps.bookingTime + ' (' + info.event.extendedProps.bookingHours + ' jam)\nStatus: ' + info.event.extendedProps.bookingStatus);
            }
        });
        calendar.render();
        // Daftar booking list
        updateBookingList();
        // Filter dan search
        document.getElementById('searchInput').addEventListener('input', function() {
            performSearch();
        });
        document.getElementById('statusFilter').addEventListener('change', function() {
            performSearch();
        });
        document.getElementById('clearSearchBtn').addEventListener('click', function() {
            document.getElementById('searchInput').value = '';
            document.getElementById('statusFilter').value = '';
            performSearch();
        });
        function performSearch() {
            const searchVal = document.getElementById('searchInput').value.toLowerCase();
            const statusVal = document.getElementById('statusFilter').value;
            filteredBookedDates = bookedDates;
            if (searchVal) {
                filteredBookedDates = filteredBookedDates.filter(booking => {
                    return (
                        (booking.userName && booking.userName.toLowerCase().includes(searchVal)) ||
                        (booking.facilityName && booking.facilityName.toLowerCase().includes(searchVal)) ||
                        (booking.bookingStatus && booking.bookingStatus.toLowerCase().includes(searchVal))
                    );
                });
            }
            if (statusVal) {
                filteredBookedDates = filteredBookedDates.filter(booking => booking.bookingStatus === statusVal);
            }
            // Sort
            filteredBookedDates.sort((a, b) => {
                const dateA = new Date(a.bookingDate + 'T' + a.bookingTime);
                const dateB = new Date(b.bookingDate + 'T' + b.bookingTime);
                return dateA - dateB;
            });
            // Update calendar dan list
            calendar.removeAllEvents();
            const events = filteredBookedDates.map(booking => {
                const startTime = booking.bookingDate + 'T' + booking.bookingTime;
                const endTime = new Date(new Date(startTime).getTime() + (booking.bookingHours * 60 * 60 * 1000)).toISOString().slice(0, 19);
                return {
                    title: `${booking.facilityName} - ${booking.userName}`,
                    start: startTime,
                    end: endTime,
                    extendedProps: booking,
                    classNames: [`fc-event-${booking.bookingStatus.toLowerCase().replace(/\s/g, '-')}`]
                };
            });
            calendar.addEventSource(events);
            updateBookingList();
        }
        function updateBookingList() {
            const bookingListContent = document.getElementById('bookingListContent');
            if (!bookingListContent) return;
            if (!filteredBookedDates || filteredBookedDates.length === 0) {
                bookingListContent.innerHTML = `<div class="empty-state"><i class="bx bx-calendar-x"></i><p>Tidak ada pemesanan yang ditemukan.</p></div>`;
                return;
            }
            const bookingItemsHTML = filteredBookedDates.map((booking, index) => {
                const statusClass = getStatusClass(booking.bookingStatus);
                const formattedDate = new Date(booking.bookingDate).toLocaleDateString('id-ID', {day: '2-digit', month: 'short', year: 'numeric'});
                const formattedTime = new Date('2000-01-01T' + booking.bookingTime).toLocaleTimeString('id-ID', {hour: '2-digit', minute: '2-digit'});
                return `<div class="booking-item" data-status="${booking.bookingStatus}"><div class="booking-item-header"><span class="booking-number">#${index+1}</span><span class="status-badge ${statusClass}">${booking.bookingStatus}</span></div><div class="booking-item-body"><h6 class="facility-name">${booking.facilityName}</h6><p class="user-name">${booking.userName}</p><div class="booking-details"><span class="detail-item"><i class="bx bx-calendar"></i> ${formattedDate}</span><span class="detail-item"><i class="bx bx-time"></i> ${formattedTime} (${booking.bookingHours} Jam)</span></div></div></div>`;
            }).join('');
            bookingListContent.innerHTML = `<div class="booking-items">${bookingItemsHTML}</div>`;
        }
        function getStatusClass(status) {
            switch (status) {
                case 'Disetujui': return 'status-approved';
                case 'Menunggu Konfirmasi': return 'status-pending';
                case 'Ditolak': return 'status-rejected';
                case 'Selesai': return 'status-completed';
                default: return 'status-default';
            }
        }
        function showBookingDetail(event) {
            const props = event.extendedProps;
            const modal = new bootstrap.Modal(document.getElementById('bookingDetailModal'));
            const formattedDate = new Date(props.bookingDate).toLocaleDateString('id-ID', {weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'});
            const formattedTime = new Date('2000-01-01T' + props.bookingTime).toLocaleTimeString('id-ID', {hour: '2-digit', minute: '2-digit'});
            const statusClass = getStatusClass(props.bookingStatus);
            document.getElementById('bookingDetailContent').innerHTML = `<div class="row"><div class="col-md-6"><h6><i class="bx bx-building"></i> Informasi Fasilitas</h6><p><strong>Nama Fasilitas:</strong> ${props.facilityName}</p><p><strong>Status:</strong> <span class="status-badge ${statusClass}">${props.bookingStatus}</span></p></div><div class="col-md-6"><h6><i class="bx bx-user"></i> Informasi Pengguna</h6><p><strong>Nama Pengguna:</strong> ${props.userName}</p><p><strong>Metode Pembayaran:</strong> ${props.bookingPaymentMethod}</p></div></div><div class="row mt-3"><div class="col-md-6"><h6><i class="bx bx-calendar"></i> Jadwal</h6><p><strong>Tanggal:</strong> ${formattedDate}</p><p><strong>Waktu:</strong> ${formattedTime} (${props.bookingHours} Jam)</p></div><div class="col-md-6"><h6><i class="bx bx-money"></i> Pembayaran</h6><p><strong>Jumlah:</strong> Rp ${props.bookingAmount ? props.bookingAmount.toLocaleString('id-ID') : 'N/A'}</p></div></div>`;
            modal.show();
        }

        function exportCalendar() {
            // Export data booking yang sedang difilter ke CSV
            const headers = ['Tanggal', 'Waktu', 'Fasilitas', 'Pengguna', 'Status', 'Durasi (Jam)', 'Jumlah'];
            const rows = filteredBookedDates.map(booking => [
                new Date(booking.bookingDate).toLocaleDateString('id-ID'),
                new Date('2000-01-01T' + booking.bookingTime).toLocaleTimeString('id-ID', {hour: '2-digit', minute: '2-digit'}),
                booking.facilityName,
                booking.userName,
                booking.bookingStatus,
                booking.bookingHours,
                booking.bookingAmount || 0
            ]);
            const csvContent = [headers, ...rows].map(row => row.join(',')).join('\n');
            const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
            const link = document.createElement('a');
            const url = URL.createObjectURL(blob);
            link.setAttribute('href', url);
            link.setAttribute('download', `calendar_export_${new Date().toISOString().split('T')[0]}.csv`);
            link.style.visibility = 'hidden';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }

        function toggleSidebarAuto() {
            const sidebar = document.getElementById('sidebar-col');
            const calendarCol = document.getElementById('calendar-col');
            if (sidebar.style.display === 'none') {
                sidebar.style.display = '';
                calendarCol.classList.remove('col-lg-12');
                calendarCol.classList.add('col-lg-8');
            } else {
                sidebar.style.display = 'none';
                calendarCol.classList.remove('col-lg-8');
                calendarCol.classList.add('col-lg-12');
            }
        }

        function printCalendar() {
            const headers = ['No', 'Tanggal', 'Waktu', 'Fasilitas', 'Pengguna', 'Status', 'Durasi (Jam)', 'Jumlah'];
            const rows = filteredBookedDates.map((booking, idx) => [
                idx + 1,
                new Date(booking.bookingDate).toLocaleDateString('id-ID'),
                new Date('2000-01-01T' + booking.bookingTime).toLocaleTimeString('id-ID', {hour: '2-digit', minute: '2-digit'}),
                booking.facilityName,
                booking.userName,
                booking.bookingStatus,
                booking.bookingHours,
                booking.bookingAmount || 0
            ]);
            let html = '<table border="1" style="width:100%;border-collapse:collapse"><thead><tr>';
            headers.forEach(h => html += `<th>${h}</th>`);
            html += '</tr></thead><tbody>';
            rows.forEach(row => {
                html += '<tr>' + row.map(cell => `<td>${cell}</td>`).join('') + '</tr>';
            });
            html += '</tbody></table>';
            const printWindow = window.open('', '_blank');
            printWindow.document.write('<html><head><title>Print Calendar</title></head><body>');
            printWindow.document.write(html);
            printWindow.document.write('</body></html>');
            printWindow.document.close();
            printWindow.print();
        }
    });
</script>
@endsection