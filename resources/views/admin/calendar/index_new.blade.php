@extends('admin.layouts.admin_dashboard')
@section('title', 'Calendar Management')

@section('content')
<div class="admin-calendar-wrapper">
    <div class="container-fluid px-4">
        <div class="row">
            <!-- Main Calendar Section -->
            <div class="col-lg-8 col-md-12 mb-4" id="calendar-col">
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
            
            <!-- Sidebar Section -->
            <div class="col-lg-4 col-md-12" id="sidebar-col">
                <!-- Quick Stats -->
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

                <!-- Booking List -->
                <div class="booking-list-card">
                    <div class="booking-list-header">
                        <h5><i class="bx bx-list-ul"></i> Daftar Pemesanan</h5>
                        <div class="header-actions">
                            <button class="btn btn-sm btn-outline-secondary" onclick="refreshList()">
                                <i class="bx bx-refresh"></i>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Search and Filter -->
                    <div class="search-filter-section">
                        <div class="search-form">
                            <div class="input-group">
                                <input type="text" id="searchInput" class="form-control" placeholder="Cari pemesanan..." value="{{ request('search') }}">
                                <button type="button" class="btn btn-outline-primary" onclick="performSearch()">
                                    <i class="bx bx-search"></i>
                                </button>
                                <button type="button" class="btn btn-outline-secondary" onclick="clearSearch()" id="clearSearchBtn" style="display: none;">
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
                        <!-- Booking items will be loaded here -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Tombol Toggle Sidebar -->
<div class="text-end my-2">
    <button class="btn btn-secondary btn-sm" id="toggleSidebarBtn" onclick="toggleSidebarAuto()">Tampilkan/Sembunyikan Sidebar</button>
</div>

<!-- Booking Detail Modal -->
<div class="modal fade" id="bookingDetailModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Pemesanan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="bookingDetailContent">
                <!-- Content will be loaded here -->
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
    /* Admin Calendar Wrapper */
    .admin-calendar-wrapper {
        min-height: 100vh;
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        padding: 2rem 0;
    }

    /* Main Calendar Card */
    .calendar-main-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        overflow: hidden;
        height: 100%;
    }

    .calendar-header-section {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        color: white;
    }

    .header-content {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .header-icon {
        background: rgba(255,255,255,0.2);
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .header-icon i {
        font-size: 1.5rem;
    }

    .header-title {
        margin: 0;
        font-size: 1.5rem;
        font-weight: 700;
    }

    .header-subtitle {
        margin: 0;
        opacity: 0.9;
        font-size: 0.9rem;
    }

    .header-actions {
        display: flex;
        gap: 0.5rem;
    }

    .calendar-content {
        padding: 2rem;
    }

    .calendar-container {
        background: #f8f9fa;
        border-radius: 12px;
        padding: 1.5rem;
    }

    /* Stats Card */
    .stats-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        overflow: hidden;
    }

    .stats-header {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        color: white;
        padding: 1rem 1.5rem;
    }

    .stats-header h5 {
        margin: 0;
        font-size: 1rem;
        font-weight: 600;
    }

    .stats-content {
        padding: 1.5rem;
    }

    .stat-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .stat-item:last-child {
        margin-bottom: 0;
    }

    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
    }

    .stat-info {
        flex: 1;
    }

    .stat-number {
        display: block;
        font-size: 1.5rem;
        font-weight: 700;
        color: #2d3748;
    }

    .stat-label {
        font-size: 0.875rem;
        color: #718096;
    }

    /* Booking List Card */
    .booking-list-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        overflow: hidden;
        height: 600px;
        display: flex;
        flex-direction: column;
    }

    .booking-list-header {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        color: white;
        padding: 1rem 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .booking-list-header h5 {
        margin: 0;
        font-size: 1rem;
        font-weight: 600;
    }

    .search-filter-section {
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #e2e8f0;
    }

    .booking-list-content {
        flex: 1;
        overflow-y: auto;
        padding: 1rem 1.5rem;
    }

    .booking-items {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .booking-item {
        background: #f8f9fa;
        border-radius: 12px;
        padding: 1rem;
        border-left: 4px solid #e2e8f0;
        transition: all 0.3s ease;
    }

    .booking-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    .booking-item-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.5rem;
    }

    .booking-number {
        font-weight: 600;
        color: #4a5568;
    }

    .status-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
    }

    .status-approved {
        background: #c6f6d5;
        color: #22543d;
    }

    .status-pending {
        background: #fef5e7;
        color: #744210;
    }

    .status-rejected {
        background: #fed7d7;
        color: #742a2a;
    }

    .status-completed {
        background: #e2e8f0;
        color: #2d3748;
    }

    .facility-name {
        margin: 0 0 0.25rem 0;
        font-weight: 600;
        color: #2d3748;
    }

    .user-name {
        margin: 0 0 0.5rem 0;
        color: #718096;
        font-size: 0.875rem;
    }

    .booking-details {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }

    .detail-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.875rem;
        color: #4a5568;
    }

    .booking-item-actions {
        margin-top: 0.75rem;
        display: flex;
        justify-content: flex-end;
    }

    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
        color: #718096;
    }

    .empty-state i {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }

    /* FullCalendar Customization */
    .fc .fc-toolbar-title {
        color: #2d3748;
        font-size: 1.25rem;
        font-weight: 700;
    }

    .fc .fc-button {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        border-radius: 8px;
        padding: 0.5rem 1rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .fc .fc-button:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }

    .fc .fc-button-primary:not(:disabled).fc-button-active {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    }

    .fc .fc-daygrid-event {
        border-radius: 6px;
        font-weight: 500;
        padding: 2px 6px;
    }

    .fc-event-disetujui {
        background-color: #38ef7d !important;
        border-color: #38ef7d !important;
    }

    .fc-event-menunggu-konfirmasi {
        background-color: #fbbf24 !important;
        border-color: #fbbf24 !important;
    }

    .fc-event-ditolak {
        background-color: #f56565 !important;
        border-color: #f56565 !important;
    }

    .fc-event-selesai {
        background-color: #a0aec0 !important;
        border-color: #a0aec0 !important;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .calendar-header-section {
            flex-direction: column;
            gap: 1rem;
            text-align: center;
        }

        .header-actions {
            width: 100%;
            justify-content: center;
        }

        .booking-list-card {
            height: auto;
            max-height: 500px;
        }
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
    let filteredBookings = [...allBookings];

    // Initialize everything when DOM is loaded
    document.addEventListener('DOMContentLoaded', function() {
        initializeCalendar();
        initializeFilters();
        updateBookingList();
    });

    // Initialize FullCalendar
    function initializeCalendar() {
        const calendarEl = document.getElementById('calendar');
        
        const eventsForCalendar = filteredBookings.map(booking => {
            const startTime = booking.bookingDate + 'T' + booking.bookingTime;
            const endTime = new Date(new Date(startTime).getTime() + (booking.bookingHours * 60 * 60 * 1000)).toISOString().slice(0, 19);

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
                    bookingTime: booking.bookingTime
                },
                classNames: [`fc-event-${booking.bookingStatus.toLowerCase().replace(/\s/g, '-')}`]
            };
        });

        calendar = new FullCalendar.Calendar(calendarEl, {
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
            eventClick: function(info) {
                // Cek tipe view aktif
                if (calendar.view.type === 'timeGridWeek' || calendar.view.type === 'timeGridDay') {
                    // View minggu/hari: tampilkan detail booking yang diklik
                    showBookingDetail(info.event);
                } else {
                    // View bulan: tampilkan daftar booking di hari itu
                    showBookingListForDate(info.event.start);
                }
            }
        });

        calendar.render();
    }

    // Initialize search and filter functionality
    function initializeFilters() {
        const searchInput = document.getElementById('searchInput');
        const statusFilter = document.getElementById('statusFilter');
        const clearSearchBtn = document.getElementById('clearSearchBtn');

        // Search input event
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                if (this.value.length > 0) {
                    clearSearchBtn.style.display = 'block';
                } else {
                    clearSearchBtn.style.display = 'none';
                }
            });

            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    performSearch();
                }
            });
        }

        // Status filter event
        if (statusFilter) {
            statusFilter.addEventListener('change', function() {
                performSearch();
            });
        }

        // Clear search event
        if (clearSearchBtn) {
            clearSearchBtn.addEventListener('click', clearSearch);
        }
    }

    // Perform search and filter
    function performSearch() {
        const searchQuery = document.getElementById('searchInput').value;
        const statusFilter = document.getElementById('statusFilter').value;
        
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
            return dateA - dateB;
        });

        filteredBookings = filtered;
        
        // Update both calendar and list
        updateCalendar();
        updateBookingList();
    }

    // Clear search
    function clearSearch() {
        document.getElementById('searchInput').value = '';
        document.getElementById('statusFilter').value = '';
        document.getElementById('clearSearchBtn').style.display = 'none';
        
        filteredBookings = [...allBookings];
        updateCalendar();
        updateBookingList();
    }

    // Update calendar with filtered events
    function updateCalendar() {
        if (calendar) {
            calendar.removeAllEvents();
            
            const eventsForCalendar = filteredBookings.map(booking => {
                const startTime = booking.bookingDate + 'T' + booking.bookingTime;
                const endTime = new Date(new Date(startTime).getTime() + (booking.bookingHours * 60 * 60 * 1000)).toISOString().slice(0, 19);

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
                        bookingTime: booking.bookingTime
                    },
                    classNames: [`fc-event-${booking.bookingStatus.toLowerCase().replace(/\s/g, '-')}`]
                };
            });

            calendar.addEventSource(eventsForCalendar);
        }
    }

    // Update booking list
    function updateBookingList() {
        const bookingListContent = document.getElementById('bookingListContent');
        if (!bookingListContent) return;

        if (filteredBookings.length === 0) {
            bookingListContent.innerHTML = `
                <div class="empty-state">
                    <i class="bx bx-calendar-x"></i>
                    <p>Tidak ada pemesanan yang ditemukan.</p>
                </div>
            `;
            return;
        }

        const bookingItemsHTML = filteredBookings.map((booking, index) => {
            const statusClass = getStatusClass(booking.bookingStatus);
            const formattedDate = new Date(booking.bookingDate).toLocaleDateString('id-ID', {
                day: '2-digit',
                month: 'short',
                year: 'numeric'
            });
            const formattedTime = new Date('2000-01-01T' + booking.bookingTime).toLocaleTimeString('id-ID', {
                hour: '2-digit',
                minute: '2-digit'
            });

            return `
                <div class="booking-item" data-status="${booking.bookingStatus}" data-index="${index}">
                    <div class="booking-item-header">
                        <span class="booking-number">#${index + 1}</span>
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
                        <button class="btn btn-sm btn-outline-primary" onclick="viewBooking(${index})">
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

    // Show booking detail modal
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

    // Show booking list for a specific date
    function showBookingListForDate(dateObj) {
        // Ambil tanggal dalam format yyyy-mm-dd
        const date = dateObj.toISOString().slice(0, 10);
        // Filter booking di tanggal tersebut
        const bookingsOnDate = filteredBookings.filter(booking => booking.bookingDate === date);
        let html = `<h5>Daftar Booking (${bookingsOnDate.length})</h5>`;
        if (bookingsOnDate.length === 0) {
            html += '<p>Tidak ada booking pada tanggal ini.</p>';
        } else {
            html += '<ul class="list-group">';
            bookingsOnDate.forEach(booking => {
                html += `<li class="list-group-item d-flex justify-content-between align-items-center">
                    <span><strong>${booking.facilityName}</strong> (${booking.bookingTime} - ${booking.bookingHours} jam)</span>
                    <span class="badge bg-success">${booking.bookingStatus}</span>
                </li>`;
            });
            html += '</ul>';
        }
        document.getElementById('bookingDetailContent').innerHTML = html;
        const modal = new bootstrap.Modal(document.getElementById('bookingDetailModal'));
        modal.show();
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
        const headers = ['No', 'Tanggal', 'Waktu', 'Fasilitas', 'Pengguna', 'Status', 'Durasi (Jam)', 'Jumlah'];
        const rows = filteredBookings.map((booking, idx) => [
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

    // Refresh list
    function refreshList() {
        location.reload();
    }

    // View booking from list
    function viewBooking(index) {
        if (filteredBookings[index]) {
            const booking = filteredBookings[index];
            
            const mockEvent = {
                extendedProps: {
                    facilityName: booking.facilityName,
                    userName: booking.userName,
                    bookingStatus: booking.bookingStatus,
                    bookingAmount: booking.bookingAmount,
                    bookingPaymentMethod: booking.bookingPaymentMethod,
                    bookingHours: booking.bookingHours,
                    bookingDate: booking.bookingDate,
                    bookingTime: booking.bookingTime
                }
            };
            
            showBookingDetail(mockEvent);
        }
    }

    function toggleSidebar(show) {
        const calendarCol = document.getElementById('calendar-col');
        if (show) {
            calendarCol.classList.remove('col-lg-12');
            calendarCol.classList.add('col-lg-8');
        } else {
            calendarCol.classList.remove('col-lg-8');
            calendarCol.classList.add('col-lg-12');
        }
    }

    function toggleSidebarAuto() {
        const sidebar = document.getElementById('sidebar-col');
        const calendarCol = document.getElementById('calendar-col');
        if (sidebar.style.display === 'none') {
            sidebar.style.display = '';
            calendarCol.classList.remove('col-lg-12');
            calendarCol.classList.add('col-lg-8');
            document.body.classList.remove('sidebar-collapsed');
            document.documentElement.classList.remove('sidebar-collapsed');
        } else {
            sidebar.style.display = 'none';
            calendarCol.classList.remove('col-lg-8');
            calendarCol.classList.add('col-lg-12');
            document.body.classList.add('sidebar-collapsed');
            document.documentElement.classList.add('sidebar-collapsed');
        }
    }
</script>
@endsection 