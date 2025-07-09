@extends('user.layouts.app')
@section('title', 'Kalender Pemesanan')
@section('content')
<!-- Hero Section -->
<div class="hero-section mb-4">
    <div class="container">
        <div class="hero-content py-4 text-center">
            <div class="hero-icon mx-auto mb-3">
                <i class="bx bx-calendar-check"></i>
            </div>
            <h1 class="hero-title mb-2">Kalender Pemesanan</h1>
            <p class="hero-subtitle mb-4">Kelola dan pantau semua pemesanan ruang meeting Anda dengan mudah</p>
            <div class="row justify-content-center g-3">
                <div class="col-6 col-md-3">
                    <div class="stat-card">
                        <i class="bx bx-check-circle"></i>
                        <div class="stat-number">{{ $bookedDates->where('bookingStatus', 'Disetujui')->count() }}</div>
                        <div class="stat-label">Disetujui</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-card">
                        <i class="bx bx-time"></i>
                        <div class="stat-number">{{ $bookedDates->where('bookingStatus', 'Menunggu Konfirmasi')->count() }}</div>
                        <div class="stat-label">Menunggu</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-card">
                        <i class="bx bx-calendar-x"></i>
                        <div class="stat-number">{{ $bookedDates->where('bookingStatus', 'Ditolak')->count() }}</div>
                        <div class="stat-label">Ditolak</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container mb-5">
    <div class="row g-4">
        <!-- Calendar Section -->
        <div class="col-lg-8">
            <div class="card shadow rounded-4 mb-4">
                <div class="card-header bg-white border-0 d-flex align-items-center justify-content-between rounded-top-4">
                    <div class="d-flex align-items-center gap-2">
                        <div class="calendar-header-icon">
                            <i class="bx bx-calendar"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-bold">Kalender Pemesanan</h5>
                            <small class="text-muted">Lihat jadwal pemesanan Anda dalam format kalender</small>
                        </div>
                    </div>
                </div>
                <div class="card-body p-3">
                    <div id="calendar"></div>
                </div>
            </div>
        </div>
        <!-- Booking List Section -->
        <div class="col-lg-4">
            <div class="card shadow rounded-4 mb-4">
                <div class="card-header bg-white border-0 d-flex align-items-center justify-content-between rounded-top-4">
                    <h6 class="mb-0 fw-bold"><i class="bx bx-list-ul"></i> Daftar Pemesanan</h6>
                    <span class="badge bg-primary bg-opacity-10 text-primary">{{ $bookedDates->count() }} Pemesanan</span>
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
                    <div class="d-flex justify-content-center mb-3">
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
@endsection

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
<style>
    body { background: #f8fafc; }
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
    .card {
        border: none;
    }
    .card-header {
        background: #fff;
        border-bottom: 1px solid #f1f5ff;
    }
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
    @media (max-width: 991px) {
        .hero-title { font-size: 2rem; }
        .stat-card { min-width: 90px; padding: 1rem 0.3rem; }
    }
    @media (max-width: 768px) {
        .hero-title { font-size: 1.5rem; }
        .stat-card { min-width: 80px; padding: 0.8rem 0.2rem; }
        .booking-list-content { max-height: 250px; }
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

        // Group bookings by date
        const bookingsByDate = {};
        bookedDates.forEach(booking => {
            if (!bookingsByDate[booking.bookingDate]) {
                bookingsByDate[booking.bookingDate] = [];
            }
            bookingsByDate[booking.bookingDate].push(booking);
        });

        // Generate one event per day with bookings
        const eventsForCalendar = Object.keys(bookingsByDate).map(date => {
            return {
                title: `${bookingsByDate[date].length} Booking`,
                start: date,
                allDay: true,
                extendedProps: {
                    bookings: bookingsByDate[date]
                },
                classNames: ['fc-booking-dot'],
                color: '#10b981' // fallback warna
            };
        });

        // DEBUG: log eventsForCalendar
        console.log('eventsForCalendar:', eventsForCalendar);

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
            eventDisplay: 'auto',
            eventTextColor: '#fff',
            dayMaxEvents: true,
            views: {
                dayGridMonth: {
                    titleFormat: { year: 'numeric', month: 'long' }
                }
            },
            eventContent: function(arg) {
                if (arg.event.extendedProps.bookings && arg.event.extendedProps.bookings.length > 0) {
                    // Tentukan warna bulatan berdasarkan tanggal
                    const today = new Date();
                    today.setHours(0,0,0,0);
                    const eventDate = new Date(arg.event.start);
                    eventDate.setHours(0,0,0,0);
                    let bgColor = '#2563eb'; // biru polos
                    if (eventDate < today) {
                        bgColor = '#6b7280'; // abu-abu gelap
                    }
                    // Bulatan dengan warna dinamis
                    return { html: `<div class='fc-dot-circle' style='background:${bgColor}'>${arg.event.extendedProps.bookings.length}</div>` };
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
    let allBookings = @json($bookedDates);
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
                return (
                    booking.userName.toLowerCase().includes(lowerCaseSearchQuery) ||
                    booking.facilityName.toLowerCase().includes(lowerCaseSearchQuery) ||
                    booking.bookingStatus.toLowerCase().includes(lowerCaseSearchQuery)
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

    // === ADDITIONAL ENHANCEMENTS ===
    
    // Add loading state to search
    function showLoadingState() {
        const bookingListContent = document.getElementById('bookingListContent');
        if (bookingListContent) {
            bookingListContent.innerHTML = `
                <div class="empty-state">
                    <div class="loading-spinner"></div>
                    <p>Mencari pemesanan...</p>
                </div>
            `;
        }
    }

    // Debounce search function for better performance
    let searchTimeout;
    function debouncedSearch() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            performSearch();
        }, 300);
    }

    // Enhanced search with loading state
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                showLoadingState();
                debouncedSearch();
            });
        }

        // Add smooth scrolling to booking list
        const bookingListContent = document.getElementById('bookingListContent');
        if (bookingListContent) {
            bookingListContent.style.scrollBehavior = 'smooth';
        }

        // Add keyboard navigation
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const modal = bootstrap.Modal.getInstance(document.getElementById('bookingDetailModal'));
                if (modal) {
                    modal.hide();
                }
            }
        });

        // Add tooltips for better UX
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Initialize tooltips after dynamic content is loaded
        setTimeout(() => {
            const newTooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            newTooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        }, 1000);

        const loadMoreBtn = document.getElementById('loadMoreBtn');
        if (loadMoreBtn) {
            loadMoreBtn.addEventListener('click', function() {
                currentBookingPage++;
                updateBookingList();
            });
        }
    });

    // Enhanced export function with better formatting
    function exportCalendar() {
        const calendarApi = FullCalendar.getCalendar ? FullCalendar.getCalendar('calendar') : null;
        let events = [];
        
        if (calendarApi && calendarApi.getEvents) {
            events = calendarApi.getEvents();
        } else if (window.calendar && window.calendar.getEvents) {
            events = window.calendar.getEvents();
        } else if (typeof eventsForCalendar !== 'undefined') {
            events = eventsForCalendar;
        }

        let csv = 'Fasilitas,Pengguna,Status,Tanggal,Waktu,Durasi,Jumlah Pembayaran,Metode Pembayaran\n';
        (events.length ? events : eventsForCalendar).forEach(ev => {
            let props = ev.extendedProps || ev;
            const formattedDate = new Date(props.bookingDate).toLocaleDateString('id-ID');
            const formattedTime = new Date('2000-01-01T' + props.bookingTime).toLocaleTimeString('id-ID', {
                hour: '2-digit',
                minute: '2-digit'
            });
            
            csv += `"${props.facilityName}","${props.userName}","${props.bookingStatus}","${formattedDate}","${formattedTime}","${props.bookingHours} jam","Rp ${props.bookingAmount?.toLocaleString() || 'N/A'}","${props.bookingPaymentMethod}"\n`;
        });
        
        const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
        const link = document.createElement('a');
        const url = URL.createObjectURL(blob);
        link.setAttribute('href', url);
        link.setAttribute('download', `pemesanan-kalender-${new Date().toISOString().split('T')[0]}.csv`);
        link.style.visibility = 'hidden';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        
        // Show success notification
        showNotification('Data kalender berhasil di-export!', 'success');
    }

    // Add notification function
    function showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
        notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
        notification.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        document.body.appendChild(notification);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 5000);
    }

    // Enhanced print function
    function printCalendar() {
        showNotification('Mempersiapkan kalender untuk print...', 'info');
        setTimeout(() => {
            window.print();
        }, 500);
    }

    // Tambahkan style bulatan dot
    const style = document.createElement('style');
    style.innerHTML = `
        .fc-booking-dot {
            background: none !important;
            border: none !important;
            box-shadow: none !important;
            padding: 0 !important;
            margin: 0 !important;
            border-radius: 0 !important;
        }
        .fc-booking-dot .fc-dot-circle {
            width: 26px;
            height: 26px;
            border-radius: 50%;
            margin: 0 auto;
            margin-top: 8px;
            box-shadow: 0 2px 6px rgba(37,99,235,0.15);
            cursor: pointer;
            border: 2px solid #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
            font-weight: bold;
            color: #fff;
        }
        /* Reset background, border, dan warna elemen default FullCalendar pada event bulatan, tanpa display:none */
        .fc-booking-dot .fc-event-main,
        .fc-booking-dot .fc-event-main-frame,
        .fc-booking-dot .fc-event-title,
        .fc-booking-dot .fc-event-title-container,
        .fc-booking-dot .fc-event-time,
        .fc-booking-dot .fc-event-dot,
        .fc-booking-dot .fc-event-bg,
        .fc-booking-dot .fc-event-resizer,
        .fc-booking-dot .fc-event-draggable {
            background: none !important;
            border: none !important;
            box-shadow: none !important;
            color: transparent !important;
            padding: 0 !important;
            margin: 0 !important;
            min-width: 0 !important;
            min-height: 0 !important;
            height: auto !important;
            border-radius: 0 !important;
        }
    `;
    document.head.appendChild(style);
</script>
@endsection