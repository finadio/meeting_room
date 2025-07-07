@extends('admin.layouts.admin_dashboard')
@section('title', 'Calendar Management')

@section('content')
<!-- HEADER KHUSUS -->
<div class="custom-page-header">
    <div class="header-icon-wrapper">
        <i class="bx bx-calendar"></i>
    </div>
    <h1 class="header-title-main">Manajemen Kalender</h1>
    <div class="header-underline"></div>
</div>
<!-- END HEADER KHUSUS -->
<div class="admin-calendar-wrapper">
    <div class="container-fluid px-4">
        <div class="row">
            <!-- Main Calendar Section -->
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
            
            <!-- Sidebar Section -->
            <div class="col-lg-4 col-md-12">
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
                        <form action="{{ route('admin.calendar') }}" method="GET" class="search-form">
                            <div class="input-group">
                                <input type="text" name="search" class="form-control" placeholder="Cari pemesanan..." value="{{ request('search') }}">
                                <button type="submit" class="btn btn-outline-primary">
                                    <i class="bx bx-search"></i>
                                </button>
                                @if(request('search'))
                                    <a href="{{ route('admin.calendar') }}" class="btn btn-outline-secondary">
                                        <i class="bx bx-x"></i>
                                    </a>
                                @endif
                            </div>
                        </form>
                        
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
                    <div id="bookingPagination" class="booking-pagination mt-3 d-flex justify-content-center"></div>
                </div>
            </div>
        </div>
    </div>
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
    .custom-page-header {
        background: linear-gradient(90deg, #234080 0%, #2952a3 100%);
        border-radius: 40px 40px 0 0;
        margin: 0 auto 2rem auto;
        padding: 48px 0 32px 0;
        text-align: center;
        position: relative;
        box-shadow: 0 8px 32px rgba(44,62,80,0.08);
        max-width: 98vw;
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
    @media (max-width: 600px) {
        .custom-page-header { padding: 32px 0 20px 0; }
        .header-icon-wrapper { width: 60px; height: 60px; }
        .header-title-main { font-size: 1.3rem; }
        .header-underline { width: 40px; height: 4px; }
    }

    /* === RAPIH & CLEAN === */
    .admin-calendar-wrapper {
        background: #f8fafc;
        min-height: 100vh;
        padding: 2rem 0 3rem 0;
    }
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
    // Debug: cek data booking dari backend
    console.log('bookedDates:', typeof bookedDates !== 'undefined' ? bookedDates : 'bookedDates belum didefinisikan');

    // Global variables
    let filteredBookedDates = [];
    
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var bookedDates = @json($bookedDates);

        // Filter bookedDates based on search query
        const urlParams = new URLSearchParams(window.location.search);
        const searchQuery = urlParams.get('search');
        filteredBookedDates = bookedDates;

        if (searchQuery) {
            const lowerCaseSearchQuery = searchQuery.toLowerCase();
            filteredBookedDates = bookedDates.filter(booking => {
                return (
                    booking.userName.toLowerCase().includes(lowerCaseSearchQuery) ||
                    booking.facilityName.toLowerCase().includes(lowerCaseSearchQuery) ||
                    booking.bookingStatus.toLowerCase().includes(lowerCaseSearchQuery)
                );
            });
        }
        
        // Sort bookedDates by date and time
        filteredBookedDates.sort((a, b) => {
            const dateA = new Date(a.bookingDate + 'T' + a.bookingTime);
            const dateB = new Date(b.bookingDate + 'T' + b.bookingTime);
            return dateA - dateB;
        });

        // Transform bookedDates into FullCalendar events
        const eventsForCalendar = filteredBookedDates.map(booking => {
            const startTime = booking.bookingDate + 'T' + booking.bookingTime;
            const endTime = new Date(new Date(startTime).getTime() + (booking.bookingHours * 60 * 60 * 1000)).toISOString().slice(0, 19);

            return {
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
            eventClick: function(info) {
                showBookingDetail(info.event);
            },
            dateClick: function(info) {
                // Handle date click if needed
            }
        });

        calendar.render();

        // Status filter functionality
        const statusFilter = document.getElementById('statusFilter');
        statusFilter.addEventListener('change', function() {
            const selectedStatus = this.value;
            const bookingItems = document.querySelectorAll('.booking-item');
            
            bookingItems.forEach(item => {
                const itemStatus = item.getAttribute('data-status');
                if (!selectedStatus || itemStatus === selectedStatus) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });

    // Utility functions
    function showBookingDetail(event) {
        const props = event.extendedProps;
        const modal = new bootstrap.Modal(document.getElementById('bookingDetailModal'));
        
        document.getElementById('bookingDetailContent').innerHTML = `
            <div class="row">
                <div class="col-md-6">
                    <h6>Informasi Fasilitas</h6>
                    <p><strong>Nama Fasilitas:</strong> ${props.facilityName}</p>
                    <p><strong>Status:</strong> <span class="badge bg-primary">${props.bookingStatus}</span></p>
                </div>
                <div class="col-md-6">
                    <h6>Informasi Pengguna</h6>
                    <p><strong>Nama Pengguna:</strong> ${props.userName}</p>
                    <p><strong>Metode Pembayaran:</strong> ${props.bookingPaymentMethod}</p>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-6">
                    <h6>Jadwal</h6>
                    <p><strong>Tanggal:</strong> ${new Date(props.bookingDate).toLocaleDateString('id-ID')}</p>
                    <p><strong>Waktu:</strong> ${props.bookingTime} (${props.bookingHours} Jam)</p>
                </div>
                <div class="col-md-6">
                    <h6>Pembayaran</h6>
                    <p><strong>Jumlah:</strong> Rp ${props.bookingAmount?.toLocaleString() || 'N/A'}</p>
                </div>
            </div>
        `;
        
        modal.show();
    }

    function exportCalendar() {
        // Generate CSV content from booking data
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
        const rows = filteredBookedDates.map(booking => [
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

    function printCalendar() {
        // Create a new window for printing booking data
        const printWindow = window.open('', '_blank');
        
        // Generate table HTML for printing
        const tableHTML = generatePrintTable();
        
        printWindow.document.write(`
            <html>
                <head>
                    <title>Data Pemesanan Ruang Meeting</title>
                    <style>
                        body { 
                            font-family: Arial, sans-serif; 
                            margin: 20px;
                            font-size: 12px;
                        }
                        table { 
                            width: 100%; 
                            border-collapse: collapse; 
                            margin-top: 20px;
                        }
                        th, td { 
                            border: 1px solid #ddd; 
                            padding: 8px; 
                            text-align: left; 
                        }
                        th { 
                            background-color: #f2f2f2; 
                            font-weight: bold;
                        }
                        .header {
                            text-align: center;
                            margin-bottom: 20px;
                        }
                        .header h1 {
                            margin: 0;
                            color: #333;
                        }
                        .header p {
                            margin: 5px 0;
                            color: #666;
                        }
                        @media print {
                            body { margin: 0; }
                        }
                    </style>
                </head>
                <body>
                    <div class="header">
                        <h1>Data Pemesanan Ruang Meeting</h1>
                        <p>Tanggal Export: ${new Date().toLocaleDateString('id-ID')}</p>
                        <p>Total Data: ${filteredBookedDates.length} pemesanan</p>
                    </div>
                    ${tableHTML}
                </body>
            </html>
        `);
        
        printWindow.document.close();
        printWindow.print();
    }

    // Generate table HTML for printing
    function generatePrintTable() {
        if (!filteredBookedDates || filteredBookedDates.length === 0) {
            return '<p>Tidak ada data pemesanan yang ditemukan.</p>';
        }

        const headers = ['No', 'Tanggal', 'Waktu', 'Fasilitas', 'Pengguna', 'Status', 'Durasi (Jam)', 'Jumlah'];
        const rows = filteredBookedDates.map((booking, index) => [
            index + 1,
            new Date(booking.bookingDate).toLocaleDateString('id-ID'),
            new Date('2000-01-01T' + booking.bookingTime).toLocaleTimeString('id-ID', {hour: '2-digit', minute: '2-digit'}),
            booking.facilityName,
            booking.userName,
            booking.bookingStatus,
            booking.bookingHours,
            `Rp ${(booking.bookingAmount || 0).toLocaleString('id-ID')}`
        ]);

        const tableRows = rows.map(row => 
            '<tr>' + row.map(cell => '<td>' + cell + '</td>').join('') + '</tr>'
        ).join('');

        return `
            <table>
                <thead>
                    <tr>
                        ${headers.map(header => '<th>' + header + '</th>').join('')}
                    </tr>
                </thead>
                <tbody>
                    ${tableRows}
                </tbody>
            </table>
        `;
    }

    function refreshList() {
        // Implement refresh functionality
        location.reload();
    }

    function viewBooking(index) {
        // Implement view booking functionality
        const bookingItems = document.querySelectorAll('.booking-item');
        if (bookingItems[index]) {
            // You can implement a modal or redirect to booking detail page
            alert('Fitur detail booking akan segera tersedia!');
        }
    }

    let bookingsPerPage = 5;
    let currentBookingPage = 1;

    document.addEventListener('DOMContentLoaded', function() {
        initializeCalendar();
        initializeFilters();
        updateBookingList(); // PASTIKAN SELALU DIPANGGIL SAAT LOAD
        console.log('Data booking:', filteredBookedDates);
    });

    function performSearch() {
        const searchQuery = document.getElementById('searchInput').value;
        const statusFilter = document.getElementById('statusFilter').value;
        
        let filtered = [...bookedDates];

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

        filteredBookedDates = filtered;
        currentBookingPage = 1; // Reset ke halaman 1 setiap filter/search
        updateCalendar();
        updateBookingList();
        console.log('Data booking setelah filter:', filteredBookedDates);
    }

    function clearSearch() {
        document.getElementById('searchInput').value = '';
        document.getElementById('statusFilter').value = '';
        document.getElementById('clearSearchBtn').style.display = 'none';
        
        filteredBookedDates = [...bookedDates];
        currentBookingPage = 1;
        updateCalendar();
        updateBookingList();
        console.log('Data booking setelah clear:', filteredBookedDates);
    }

    function updateBookingList() {
        const bookingListContent = document.getElementById('bookingListContent');
        const bookingPagination = document.getElementById('bookingPagination');
        if (!bookingListContent) return;

        if (!filteredBookedDates || filteredBookedDates.length === 0) {
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
        const totalPages = Math.ceil(filteredBookedDates.length / bookingsPerPage);
        if (currentBookingPage > totalPages) currentBookingPage = 1;
        const startIdx = (currentBookingPage - 1) * bookingsPerPage;
        const endIdx = startIdx + bookingsPerPage;
        const pageBookings = filteredBookedDates.slice(startIdx, endIdx);

        const bookingItemsHTML = pageBookings.map((booking, index) => {
            const globalIndex = startIdx + index;
            const statusClass = getStatusClass(booking.bookingStatus);
            const formattedDate = new Date(booking.bookingDate).toLocaleDateString('id-ID', {
                day: '2-digit', month: 'short', year: 'numeric'
            });
            const formattedTime = new Date('2000-01-01T' + booking.bookingTime).toLocaleTimeString('id-ID', {
                hour: '2-digit', minute: '2-digit'
            });
            return `
                <div class="booking-item" data-status="${booking.bookingStatus}" data-index="${globalIndex}">
                    <div class="booking-item-header">
                        <span class="booking-number">#${globalIndex + 1}</span>
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
                        <button class="btn btn-sm btn-outline-primary" onclick="viewBooking(${globalIndex})">
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
                for (let i = 1; i <= totalPages; i++) {
                    pagHTML += `<li class="page-item${i === currentBookingPage ? ' active' : ''}">
                        <button class="page-link" onclick="changeBookingPage(${i})">${i}</button></li>`;
                }
                pagHTML += '</ul></nav>';
                bookingPagination.innerHTML = pagHTML;
            }
        }
    }

    // Fungsi untuk ganti halaman booking
    function changeBookingPage(page) {
        currentBookingPage = page;
        updateBookingList();
    }
</script>
@endsection 