@extends('admin.layouts.admin_dashboard')
@section('title', 'Manajemen Kalender')

@section('content')
<div class="custom-page-header">
    <div class="header-icon-wrapper">
        <i class="bx bx-calendar"></i>
    </div>
    <h1 class="header-title-main">Manajemen Kalender</h1>
    <div class="header-underline"></div>
</div>
<div class="admin-calendar-wrapper">
    <div class="container-fluid px-4">
        <div class="row">
            <div class="col-lg-8 col-md-12 mb-4">
                <div class="calendar-main-card">
                    <div class="calendar-header-section">
                        <div class="header-content">
                            <div class="header-icon">
                                <i class="bx bx-calendar"></i>
                            </div>
                            <div class="header-text">
                                <h3 class="header-title">Manajemen Kalender</h3>
                                <p class="header-subtitle">Kelola dan pantau semua pemesanan ruang meeting</p>
                            </div>
                        </div>
                        <div class="header-actions">
                            <button class="btn btn-primary btn-sm" onclick="exportCalendar()">
                                <i class="bx bx-download"></i> Export
                            </button>
                            <button class="btn btn-outline-primary btn-sm" onclick="printCalendar()">
                                <i class="bx bx-printer"></i> Print
                            </button>
                        </div>
                    </div>
                    
                    <div class="calendar-content">
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
            
            <div class="col-lg-4 col-md-12">
                <div class="stats-card mb-4">
                    <div class="stats-header">
                        <h5><i class="bx bx-bar-chart-alt-2"></i> Statistik Hari Ini</h5>
                    </div>
                    <div class="stats-content">
                        <div class="stat-item">
                            <div class="stat-icon bg-primary">
                                <i class="bx bx-calendar-check"></i>
                            </div>
                            <div class="stat-info">
                                <span class="stat-number">{{ $todayBookings ?? 0 }}</span>
                                <span class="stat-label">Booking Hari Ini</span>
                            </div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-icon bg-success">
                                <i class="bx bx-check-circle"></i>
                            </div>
                            <div class="stat-info">
                                <span class="stat-number">{{ $approvedBookings ?? 0 }}</span>
                                <span class="stat-label">Disetujui</span>
                            </div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-icon bg-warning">
                                <i class="bx bx-time"></i>
                            </div>
                            <div class="stat-info">
                                <span class="stat-number">{{ $pendingBookings ?? 0 }}</span>
                                <span class="stat-label">Menunggu</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="booking-list-card">
                    <div class="booking-list-header">
                        <h5><i class="bx bx-list-ul"></i> Daftar Pemesanan</h5>
                        <div class="header-actions">
                            <button class="btn btn-sm btn-outline-secondary" onclick="refreshList()">
                                <i class="bx bx-refresh"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="search-filter-section">
                        <div class="search-form"> {{-- Hapus action dan method dari form --}}
                            <div class="input-group">
                                <input type="text" id="searchInput" class="form-control" placeholder="Cari pemesanan..." value="{{ request('search') }}">
                                <button type="button" class="btn btn-primary" onclick="performSearch()"> {{-- Ubah type submit ke button --}}
                                    <i class="bx bx-search"></i>
                                </button>
                                <button type="button" class="btn btn-outline-secondary" onclick="clearSearch()" id="clearSearchBtn" style="display: {{ request('search') ? 'block' : 'none' }};"> {{-- Tampilkan/sembunyikan reset --}}
                                    <i class="bx bx-x"></i>
                                </button>
                            </div>
                        </div>
                        
                        <div class="filter-controls mt-3">
                            <select class="form-select form-select-sm" id="statusFilter">
                                <option value="">Semua Status</option>
                                <option value="Disetujui">Disetujui</option>
                                <option value="Menunggu Konfirmasi">Menunggu Konfirmasi</option>
                                <option value="Ditolak">Ditolak</option>
                                <option value="Selesai">Selesai</option>
                            </select>
                        </div>
                    </div>

                    <div class="booking-list-content" id="bookingListContent">
                        </div>
                    <div id="bookingPagination" class="booking-pagination mt-3 d-flex justify-content-center"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="bookingDetailModal" tabindex="-1" aria-labelledby="bookingDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bookingDetailModalLabel">Detail Pemesanan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="bookingDetailContent">
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
        color: #fff;
        font-size: 2.3rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        letter-spacing: 1px;
        text-shadow: 0 2px 8px rgba(44,62,80,0.10);
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
    .calendar-container { background: #fff; border-radius: 15px; box-shadow: none; padding: 0; }
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
    .fc-toolbar { background: #f8fafc; padding: 1rem; border-radius: 10px 10px 0 0; border-bottom: 1px solid #e2e8f0; }
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
</style>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales-all.min.js'></script>
<script>
    // Global variables
    let calendar;
    let allBookings = @json($bookedDates);
    let filteredBookings = [...allBookings]; // Inisialisasi awal

    // Pagination untuk daftar booking
    let bookingsPerPage = 5;
    let currentBookingPage = 1;

    document.addEventListener('DOMContentLoaded', function() {
        // Ambil search dan status dari URL saat load awal untuk apply filter default
        const urlParams = new URLSearchParams(window.location.search);
        const initialSearchQuery = urlParams.get('search') || '';
        const initialStatusFilter = urlParams.get('status') || '';

        // Set nilai input filter sesuai URL
        document.getElementById('searchInput').value = initialSearchQuery;
        document.getElementById('statusFilter').value = initialStatusFilter;
        if (initialSearchQuery) {
            document.getElementById('clearSearchBtn').style.display = 'block';
        }

        initializeCalendar();
        initializeFilters();
        performSearch(); // Panggil performSearch untuk menerapkan filter awal
    });

    // Initialize FullCalendar
    function initializeCalendar() {
        const calendarEl = document.getElementById('calendar');
        
        // Initial events for FullCalendar are all events before any filtering
        // Pastikan initialEventsForCalendar dibuat dari allBookings (data lengkap)
        const initialEventsForCalendar = allBookings.map(booking => {
            const startTime = booking.bookingDate + 'T' + booking.bookingTime;
            const endTime = booking.booking_end ? booking.bookingDate + 'T' + booking.booking_end : new Date(new Date(startTime).getTime() + (booking.bookingHours * 60 * 60 * 1000)).toISOString().slice(0, 19);

            return {
                id: booking.id || Math.random().toString(36).substr(2, 9),
                title: `${booking.facilityName} - ${booking.userName}`,
                start: startTime,
                end: endTime,
                extendedProps: {
                    facilityName: booking.facilityName,
                    userName: booking.userName,
                    bookingStatus: booking.bookingStatus,
                    bookingAmount: booking.bookingAmount,
                    bookingPaymentMethod: booking.bookingPaymentMethod,
                    bookingHours: booking.bookingHours,
                    bookingDate: booking.bookingDate,
                    bookingTime: booking.bookingTime,
                    booking_end: booking.booking_end
                },
                classNames: [`fc-event-${booking.bookingStatus.toLowerCase().replace(/\s/g, '-')}`]
            };
        });

        calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'id',
            events: initialEventsForCalendar, // FullCalendar diinisialisasi dengan SEMUA event
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
            // Perbaikan untuk tidak menampilkan sisa minggu dari bulan berikutnya
            fixedWeekCount: false, // <<--- PENGATURAN KUNCI UNTUK MENGHILANGKAN BARIS KOSONG
            views: {
                dayGridMonth: {
                    titleFormat: { year: 'numeric', month: 'long' }
                }
            },
            eventClick: function(info) {
                showBookingDetail(info.event);
            }
        });

        calendar.render();
    }

    // Initialize search and filter functionality
    function initializeFilters() {
        const searchInput = document.getElementById('searchInput');
        const statusFilter = document.getElementById('statusFilter');
        const clearSearchBtn = document.getElementById('clearSearchBtn');

        if (searchInput) {
            searchInput.addEventListener('input', function() {
                if (this.value.length > 0) {
                    clearSearchBtn.style.display = 'block';
                } else {
                    clearSearchBtn.style.display = 'none';
                }
                performSearch(); // Langsung filter saat input berubah
            });

            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    performSearch();
                }
            });
        }

        if (statusFilter) {
            statusFilter.addEventListener('change', performSearch); // Panggil performSearch saat filter status berubah
        }

        if (clearSearchBtn) {
            clearSearchBtn.addEventListener('click', clearSearch);
        }
    }

    // Perform search and filter
    function performSearch() {
        const searchQuery = document.getElementById('searchInput').value.toLowerCase();
        const statusFilterValue = document.getElementById('statusFilter').value;

        filteredBookings = [...allBookings]; // Reset dari data mentah

        // Apply search filter
        if (searchQuery) {
            filteredBookings = filteredBookings.filter(booking => {
                return (
                    (booking.userName && booking.userName.toLowerCase().includes(searchQuery)) ||
                    (booking.facilityName && booking.facilityName.toLowerCase().includes(searchQuery)) ||
                    (booking.bookingStatus && booking.bookingStatus.toLowerCase().includes(searchQuery)) ||
                    (booking.bookingDate && booking.bookingDate.toLowerCase().includes(searchQuery)) ||
                    (booking.bookingTime && booking.bookingTime.toLowerCase().includes(searchQuery))
                );
            });
        }

        // Apply status filter
        if (statusFilterValue) {
            filteredBookings = filteredBookings.filter(booking => booking.bookingStatus === statusFilterValue);
        }

        // Sort by date and time
        filteredBookings.sort((a, b) => {
            const dateA = new Date(a.bookingDate + 'T' + a.bookingTime);
            const dateB = new Date(b.bookingDate + 'T' + b.bookingTime);
            return dateA - dateB;
        });

        currentBookingPage = 1; // Reset ke halaman 1 setiap kali filter/search berubah
        updateCalendarEvents(); // Update event kalender
        updateBookingList();
    }

    // Clear search
    function clearSearch() {
        document.getElementById('searchInput').value = '';
        document.getElementById('statusFilter').value = '';
        document.getElementById('clearSearchBtn').style.display = 'none';
        
        filteredBookings = [...allBookings]; // Reset ke data asli
        currentBookingPage = 1;
        updateCalendarEvents(); // Update event kalender
        updateBookingList();
    }

    // Update calendar events with filtered data
    function updateCalendarEvents() { // Fungsi ini yang akan dipanggil saat filter berubah
        if (calendar) {
            calendar.removeAllEvents(); // Hapus semua event yang ada
            
            const eventsToAdd = filteredBookings.map(booking => {
                const startTime = booking.bookingDate + 'T' + booking.bookingTime;
                const endTime = booking.booking_end ? booking.bookingDate + 'T' + booking.booking_end : new Date(new Date(startTime).getTime() + (booking.bookingHours * 60 * 60 * 1000)).toISOString().slice(0, 19);

                return {
                    id: booking.id || Math.random().toString(36).substr(2, 9),
                    title: `${booking.facilityName} - ${booking.userName}`,
                    start: startTime,
                    end: endTime,
                    extendedProps: {
                        facilityName: booking.facilityName,
                        userName: booking.userName,
                        bookingStatus: booking.bookingStatus,
                        bookingAmount: booking.bookingAmount,
                        bookingPaymentMethod: booking.bookingPaymentMethod,
                        bookingHours: booking.bookingHours,
                        bookingDate: booking.bookingDate,
                        bookingTime: booking.bookingTime,
                        booking_end: booking.booking_end
                    },
                    classNames: [`fc-event-${booking.bookingStatus.toLowerCase().replace(/\s/g, '-')}`]
                };
            });
            calendar.addEventSource(eventsToAdd); // Tambahkan event yang sudah difilter
        }
    }

    // Update booking list
    function updateBookingList() {
        const bookingListContent = document.getElementById('bookingListContent');
        const bookingPagination = document.getElementById('bookingPagination');
        if (!bookingListContent) return;

        if (filteredBookings.length === 0) {
            bookingListContent.innerHTML = `
                <div class="empty-state">
                    <i class="bx bx-calendar-x"></i>
                    <p>Tidak ada pemesanan yang ditemukan.</p>
                </div>
            `;
            if (bookingPagination) bookingPagination.innerHTML = '';
            return;
        }

        // PAGINATION LOGIC
        const totalPages = Math.ceil(filteredBookings.length / bookingsPerPage);
        if (currentBookingPage > totalPages && totalPages > 0) currentBookingPage = totalPages;
        if (currentBookingPage < 1 && totalPages > 0) currentBookingPage = 1;
        const startIdx = (currentBookingPage - 1) * bookingsPerPage;
        const endIdx = startIdx + bookingsPerPage;
        const pageBookings = filteredBookings.slice(startIdx, endIdx);

        const bookingItemsHTML = pageBookings.map((booking, index) => {
            const globalIndex = allBookings.findIndex(b => b.id === booking.id && b.bookingDate === booking.bookingDate && b.bookingTime === booking.bookingTime);
            const statusClass = getStatusClass(booking.bookingStatus);
            const formattedDate = new Date(booking.bookingDate).toLocaleDateString('id-ID', {
                day: '2-digit', month: 'short', year: 'numeric'
            });
            const formattedTime = new Date('2000-01-01T' + booking.bookingTime).toLocaleTimeString('id-ID', {
                hour: '2-digit', minute: '2-digit'
            });
            return `
                <div class="booking-item" data-status="${booking.bookingStatus}" data-original-id="${booking.id}">
                    <div class="booking-item-header">
                        <span class="booking-number">#${globalIndex !== -1 ? globalIndex + 1 : 'N/A'}</span>
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
                        <button class="btn btn-sm btn-outline-primary" onclick="viewBookingDetailFromList(${allBookings.indexOf(booking)})">
                            <i class="bx bx-show"></i>
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

        // PAGINATION BUTTONS
        if (bookingPagination) {
            if (totalPages <= 1) {
                bookingPagination.innerHTML = '';
            } else {
                let pagHTML = '<nav><ul class="pagination pagination-sm">';
                pagHTML += `<li class="page-item${currentBookingPage === 1 ? ' disabled' : ''}">
                                <button class="page-link" onclick="changeBookingPage(${currentBookingPage - 1})" aria-label="Previous">&laquo;</button>
                             </li>`;
                for (let i = 1; i <= totalPages; i++) {
                    pagHTML += `<li class="page-item${i === currentBookingPage ? ' active' : ''}">
                                    <button class="page-link" onclick="changeBookingPage(${i})">${i}</button>
                                </li>`;
                }
                pagHTML += `<li class="page-item${currentBookingPage === totalPages ? ' disabled' : ''}">
                                <button class="page-link" onclick="changeBookingPage(${currentBookingPage + 1})" aria-label="Next">&raquo;</button>
                             </li>`;
                pagHTML += '</ul></nav>';
                bookingPagination.innerHTML = pagHTML;
            }
        }
    }

    // Get status class for styling
    function getStatusClass(status) {
        switch (status) {
            case 'Disetujui': return 'status-approved';
            case 'Menunggu Konfirmasi': return 'status-pending';
            case 'Ditolak': return 'status-rejected';
            case 'Selesai': return 'status-completed';
            default: return 'status-default';
        }
    }

    // Show booking detail modal (dipanggil dari eventClick FullCalendar)
    function showBookingDetail(event) {
        const props = event.extendedProps;
        const modal = new bootstrap.Modal(document.getElementById('bookingDetailModal'));

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

        const statusClass = getStatusClass(props.bookingStatus);

        document.getElementById('bookingDetailContent').innerHTML = `
            <div class="row">
                <div class="col-md-6">
                    <h6><i class="bx bx-building"></i> Informasi Fasilitas</h6>
                    <p><strong>Nama Fasilitas:</strong> ${props.facilityName}</p>
                    <p><strong>Status:</strong> <span class="status-badge ${statusClass}">${props.bookingStatus}</span></p>
                </div>
                <div class="col-md-6">
                    <h6><i class="bx bx-user"></i> Informasi Pengguna</h6>
                    <p><strong>Nama Pengguna:</strong> ${props.userName}</p>
                    <p><strong>Metode Pembayaran:</strong> ${props.bookingPaymentMethod}</p>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-6">
                    <h6><i class="bx bx-calendar"></i> Jadwal</h6>
                    <p><strong>Tanggal:</strong> ${formattedDate}</p>
                    <p><strong>Waktu:</strong> ${formattedTime} (${props.bookingHours} Jam)</p>
                </div>
                <div class="col-md-6">
                    <h6><i class="bx bx-money"></i> Pembayaran</h6>
                    <p><strong>Jumlah:</strong> Rp ${props.bookingAmount ? props.bookingAmount.toLocaleString('id-ID') : 'N/A'}</p>
                </div>
            </div>
        `;

        modal.show();
    }

    // Fungsi untuk ganti halaman booking
    function changeBookingPage(page) {
        if (page < 1 || page > Math.ceil(filteredBookings.length / bookingsPerPage)) return;
        currentBookingPage = page;
        updateBookingList();
    }

    // Fungsi untuk menampilkan detail booking dari daftar (memakai data dari allBookings)
    function viewBookingDetailFromList(originalIndex) {
        // Karena kita sudah punya `allBookings`, kita bisa langsung ambil dari situ
        const booking = allBookings[originalIndex];
        if (booking) {
            const mockEvent = { extendedProps: booking };
            showBookingDetail(mockEvent);
        }
    }

    // Export calendar to CSV
    function exportCalendar() {
        const csvContent = generateCSV();
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

    // Generate CSV content
    function generateCSV() {
        const headers = ['Tanggal', 'Waktu', 'Fasilitas', 'Pengguna', 'Status', 'Durasi (Jam)', 'Jumlah'];
        const rows = filteredBookings.map(booking => [
            new Date(booking.bookingDate).toLocaleDateString('id-ID'),
            new Date('2000-01-01T' + booking.bookingTime).toLocaleTimeString('id-ID', {hour: '2-digit', minute: '2-digit'}),
            booking.facilityName,
            booking.userName,
            booking.bookingStatus,
            booking.bookingHours,
            booking.bookingAmount || 0
        ]);
        
        return [headers, ...rows].map(row => row.join(',')).join('\n');
    }

    // Print calendar
    function printCalendar() {
        const printWindow = window.open('', '_blank');
        const calendarElement = document.getElementById('calendar');

        printWindow.document.write(`
            <html>
                <head>
                    <title>Cetak Kalender</title>
                    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
                    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
                    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
                    <style>
                        body { font-family: Arial, sans-serif; margin: 20px; }
                        .fc { font-size: 10px; } /* Ukuran font lebih kecil untuk cetak */
                        .fc-toolbar { background: #f0f0f0; padding: 10px; border-radius: 5px; }
                        .fc-event { background-color: #1E40AF !important; color: white !important; border: none !important; font-size: 0.7em; }
                        .fc-day-today { background: #e0f0ff !important; }
                        .fc-daygrid-day-number { color: #333; }
                        h2 { text-align: center; margin-bottom: 20px; }
                        /* Sembunyikan sidebar dan search/filter di mode cetak */
                        .admin-calendar-wrapper .col-lg-4, .search-filter-section, .header-actions {
                            display: none !important;
                        }
                        /* Pastikan kalender mengambil lebar penuh */
                        .admin-calendar-wrapper .col-lg-8 {
                            width: 100% !important;
                            max-width: 100% !important;
                            flex: 0 0 100% !important;
                        }
                    </style>
                </head>
                <body>
                    <h2>Manajemen Kalender - ${new Date().toLocaleDateString('id-ID')}</h2>
                    <div style="width: 100%;">${calendarElement.innerHTML}</div>
                    <div style="margin-top: 30px;">
                        <h3>Daftar Pemesanan:</h3>
                        <div class="booking-items">
                            ${document.getElementById('bookingListContent').innerHTML}
                        </div>
                    </div>
                </body>
            </html>
        `);
        
        printWindow.document.close();
        printWindow.print();
    }

    function refreshList() {
        location.reload();
    }
</script>
@endsection