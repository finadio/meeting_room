@extends('admin.layouts.admin_dashboard')
@section('title', 'Calendar Management')

@section('content')
<div class="booking-page-wrapper">
    <div class="container-fluid px-4">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="booking-card">
                    <div class="booking-header">
                        <div class="header-icon">
                            <i class="bx bx-calendar"></i>
                        </div>
                        <h3 class="header-title">Manajemen Kalender</h3>
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

                        <div class="calendar-wrapper">
                            <div id='calendar'></div>
                        </div>

                        <div class="sub-card mt-5">
                            <div class="sub-card-header">
                                <i class="bx bx-list-ul"></i>
                                <h5 class="sub-card-title">Daftar Pemesanan</h5>
                            </div>
                            <div class="sub-card-body table-container">
                                {{-- Search Bar for Booking List --}}
                                {{-- Menambahkan kelas mb-5 untuk margin-bottom yang lebih besar agar ada spasi jelas --}}
                                <div class="table-actions-controls mb-5"> 
                                    <form action="{{ route('admin.calendar') }}" method="GET" class="search-form">
                                        <div class="search-input-wrapper">
                                            <input type="text" name="search" class="form-control-enhanced search-input" placeholder="Cari pemesanan..." value="{{ request('search') }}">
                                            <button type="submit" class="search-button">
                                                <i class="bx bx-search"></i>
                                            </button>
                                            @if(request('search'))
                                                <a href="{{ route('admin.calendar') }}" class="clear-search">
                                                    <i class="bx bx-x-circle"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </form>
                                    {{-- Tidak ada tombol 'Add Booking' di sini karena ini adalah list, bukan titik pembuatan --}}
                                </div>

                                @if($bookedDates->isEmpty())
                                    <div class="alert alert-info text-center m-4 p-4 rounded-lg shadow-sm">
                                        Tidak ada pemesanan dalam kalender.
                                    </div>
                                @else
                                    <table class="modern-table">
                                        <thead>
                                            <tr>
                                                <th>S.N</th>
                                                <th>Nama Pengguna</th>
                                                <th>Nama Fasilitas</th>
                                                <th>Tanggal</th>
                                                <th>Waktu Mulai</th>
                                                <th>Jam</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($bookedDates as $key => $booking)
                                            <tr>
                                                <td data-label="S.N">{{ $key + 1 }}</td>
                                                <td data-label="Nama Pengguna" class="text-truncate" style="max-width: 120px;">{{ $booking['userName'] }}</td>
                                                <td data-label="Nama Fasilitas" class="text-truncate" style="max-width: 120px;">{{ $booking['facilityName'] }}</td>
                                                <td data-label="Tanggal">{{ \Carbon\Carbon::parse($booking['bookingDate'])->format('d F Y') }}</td>
                                                <td data-label="Waktu Mulai">{{ \Carbon\Carbon::parse($booking['bookingTime'])->format('h:i A') }}</td>
                                                <td data-label="Jam">{{ $booking['bookingHours'] }} Jam</td>
                                                <td data-label="Status">
                                                    @php
                                                        $statusClass = '';
                                                        switch ($booking['bookingStatus']) {
                                                            case 'Disetujui': $statusClass = 'badge-success-custom'; break;
                                                            case 'Menunggu Konfirmasi': $statusClass = 'badge-info-custom'; break;
                                                            case 'Ditolak': $statusClass = 'badge-danger-custom'; break;
                                                            case 'Selesai': $statusClass = 'badge-secondary-custom'; break;
                                                            default: $statusClass = 'badge-secondary-custom'; break;
                                                        }
                                                    @endphp
                                                    <span class="badge {{ $statusClass }}">{{ $booking['bookingStatus'] }}</span>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
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
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
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

    /* Calendar Specific Styles */
    .calendar-wrapper {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        padding: 20px;
        max-width: 100%; /* Ensure it fits parent */
        margin: 0 auto;
    }

    /* FullCalendar overrides for better theme integration */
    .fc .fc-toolbar-title {
        color: #1e3c72;
        font-size: 1.5rem;
        font-weight: 700;
    }

    .fc .fc-button {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        border-radius: 8px;
        padding: 8px 15px;
        font-weight: 600;
        box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);
        transition: all 0.2s ease;
    }

    .fc .fc-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
    }

    .fc .fc-button-primary:not(:disabled).fc-button-active {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        box-shadow: inset 0 2px 5px rgba(0, 0, 0, 0.2);
    }

    .fc .fc-daygrid-day-number {
        color: #4a5568;
        font-weight: 600;
    }

    .fc .fc-col-header-cell-cushion {
        color: #1e3c72;
        font-weight: 700;
        padding: 10px 0;
    }

    .fc .fc-daygrid-event {
        background-color: #4facfe; /* Default event background */
        border: none;
        border-radius: 4px;
        font-weight: 500;
        padding: 2px 5px;
        font-size: 0.85em;
    }

    /* Event status specific colors (mirroring badges) */
    .fc-event-disetujui { /* Approved */
        background-color: #38ef7d !important;
    }
    .fc-event-menunggu-konfirmasi { /* Pending Confirmation */
        background-color: #4facfe !important;
    }
    .fc-event-ditolak { /* Rejected */
        background-color: #ff416c !important;
    }
    .fc-event-selesai { /* Completed */
        background-color: #6c757d !important;
    }

    .fc-event-custom { /* Custom class if needed for internal event content */
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* Sub-card for Booking List (same as dashboard sub-card) */
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

    /* Table Specific Styles (re-used for booking list) */
    .table-container {
        overflow-x: auto; /* Ensures table is scrollable on small screens */
        background: #fff;
        border-radius: 16px;
        box-shadow: none; /* Remove redundant shadow from inner table-container */
        padding: 0; /* Remove redundant padding from inner table-container */
    }

    .modern-table {
        width: 100%;
        border-collapse: separate; /* Use separate to allow border-radius on cells */
        border-spacing: 0 10px; /* Space between rows */
        margin-bottom: 0;
    }

    .modern-table thead th {
        background: linear-gradient(135deg, #e0e7ff 0%, #c3dafe 100%); /* Light blue gradient for header */
        color: #1e3c72;
        padding: 15px 20px;
        font-weight: 700;
        text-align: left;
        border-bottom: none;
        position: sticky;
        top: 0;
        z-index: 10;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .modern-table thead th:first-child {
        border-top-left-radius: 12px;
        border-bottom-left-radius: 12px;
    }

    .modern-table thead th:last-child {
        border-top-right-radius: 12px;
        border-bottom-right-radius: 12px;
    }

    .modern-table tbody tr {
        background: #fdfefe; /* Slightly off-white for rows */
        border-radius: 12px;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05); /* Subtle shadow for each row */
    }

    .modern-table tbody tr:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.1);
    }

    .modern-table tbody td {
        padding: 15px 20px;
        vertical-align: middle;
        border-top: none; /* Remove default table borders */
        font-size: 0.9rem;
        color: #333;
    }

    .modern-table tbody tr:first-child td {
        border-top: none; /* No top border for the first row's cells */
    }

    .modern-table tbody td:first-child {
        border-bottom-left-radius: 12px;
        border-top-left-radius: 12px;
    }

    .modern-table tbody td:last-child {
        border-bottom-right-radius: 12px;
        border-top-right-radius: 12px;
    }
    
    /* Badges (re-used for booking list) */
    .badge-primary-custom {
        background-color: #667eea;
        color: white;
        padding: 6px 10px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.8em;
    }
    .badge-info-custom {
        background-color: #4facfe; /* A light blue for info status */
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

    /* Search Bar Styles */
    .table-actions-controls {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px; /* Sesuaikan atau tambahkan jika perlu lebih banyak spasi */
        flex-wrap: wrap; /* Allow wrapping on smaller screens */
        gap: 15px; /* Space between search and button */
    }

    .search-form {
        flex-grow: 1; /* Allow search bar to take available space */
        max-width: 400px; /* Limit max width of search bar */
    }

    .search-input-wrapper {
        position: relative;
        display: flex;
        align-items: center;
    }

    .search-input {
        width: 100%;
        padding: 12px 40px 12px 20px; /* Adjust padding for icon */
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        font-size: 1rem;
        background: white;
        transition: all 0.3s ease;
        outline: none;
    }

    .search-input:focus {
        border-color: #4facfe;
        box-shadow: 0 0 0 4px rgba(79, 172, 254, 0.1);
    }

    .search-button {
        position: absolute;
        right: 10px;
        background: none;
        border: none;
        color: #a0aec0;
        font-size: 1.2rem;
        cursor: pointer;
        transition: color 0.3s ease;
    }

    .search-button:hover {
        color: #667eea;
    }

    .clear-search {
        position: absolute;
        right: 40px; /* Adjust position to not overlap with search button */
        background: none;
        border: none;
        color: #a0aec0;
        font-size: 1.2rem;
        cursor: pointer;
        transition: color 0.3s ease;
    }

    .clear-search:hover {
        color: #ff416c;
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

        .calendar-wrapper {
            padding: 15px;
        }

        .fc .fc-toolbar-title {
            font-size: 1.2rem;
        }
        .fc .fc-button {
            padding: 6px 10px;
            font-size: 0.8rem;
        }

        /* Responsive table for booking list */
        .modern-table {
            display: block;
            width: 100%;
            white-space: nowrap;
        }

        .modern-table thead, .modern-table tbody, .modern-table th, .modern-table td, .modern-table tr {
            display: block;
        }

        .modern-table thead tr {
            position: absolute;
            top: -9999px;
            left: -9999px;
        }

        .modern-table tbody tr {
            margin-bottom: 20px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .modern-table td {
            border: none;
            position: relative;
            padding-left: 50%; /* Space for the label */
            text-align: right;
            border-bottom: 1px solid #eee;
            font-size: 0.9rem;
            white-space: normal; /* Allow text to wrap within the cell */
        }

        .modern-table td:before {
            content: attr(data-label); /* Use data-label for content */
            position: absolute;
            left: 0;
            width: 45%;
            padding-left: 15px;
            font-weight: bold;
            text-align: left;
            color: #1e3c72;
            font-size: 0.85rem;
        }

        /* Specific labels for mobile */
        .modern-table td:nth-of-type(1):before { content: "S.N:"; }
        .modern-table td:nth-of-type(2):before { content: "Nama Pengguna:"; }
        .modern-table td:nth-of-type(3):before { content: "Nama Fasilitas:"; }
        .modern-table td:nth-of-type(4):before { content: "Tanggal:"; }
        .modern-table td:nth-of-type(5):before { content: "Waktu Mulai:"; }
        .modern-table td:nth-of-type(6):before { content: "Jam:"; }
        .modern-table td:nth-of-type(7):before { content: "Status:"; }
        
        .modern-table tbody td:last-child {
            border-bottom: none; /* Remove border for last cell in row */
        }

        /* Adjust search form on smaller screens */
        .table-actions-controls {
            flex-direction: column;
            align-items: stretch;
        }
        .search-form {
            max-width: 100%; /* Take full width */
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
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales-all.min.js'></script> {{-- For localization --}}
<script>
    // Alert close functionality
    document.querySelectorAll('.alert-close').forEach(button => {
        button.addEventListener('click', function() {
            this.closest('.custom-alert').style.display = 'none';
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        // The bookedDates variable should be passed from the controller,
        // and filtered based on the search query if any.
        var bookedDates = @json($bookedDates);

        // Filter bookedDates based on search query in JavaScript
        const urlParams = new URLSearchParams(window.location.search);
        const searchQuery = urlParams.get('search');
        let filteredBookedDates = bookedDates;

        if (searchQuery) {
            const lowerCaseSearchQuery = searchQuery.toLowerCase();
            filteredBookedDates = bookedDates.filter(booking => {
                return (
                    booking.userName.toLowerCase().includes(lowerCaseSearchQuery) ||
                    booking.facilityName.toLowerCase().includes(lowerCaseSearchQuery) ||
                    booking.bookingStatus.toLowerCase().includes(lowerCaseSearchQuery)
                    // Add other fields you want to be searchable
                );
            });
        }
        
        // Sort bookedDates (or filteredBookedDates) by bookingDate and bookingTime in ascending order for calendar display
        filteredBookedDates.sort((a, b) => {
            const dateA = new Date(a.bookingDate + 'T' + a.bookingTime);
            const dateB = new Date(b.bookingDate + 'T' + b.bookingTime);
            return dateA - dateB;
        });

        // Transform bookedDates into FullCalendar event objects
        const eventsForCalendar = filteredBookedDates.map(booking => {
            // FullCalendar expects 'start' and 'end' properties as ISO8601 strings or Date objects
            const startTime = booking.bookingDate + 'T' + booking.bookingTime;
            const endTime = new Date(new Date(startTime).getTime() + (booking.bookingHours * 60 * 60 * 1000)).toISOString().slice(0, 19); // Calculate end time

            return {
                title: `${booking.facilityName} - ${booking.userName} (${booking.bookingStatus})`,
                start: startTime,
                end: endTime,
                // Add custom properties if you want to access them in eventClick
                extendedProps: {
                    facilityName: booking.facilityName,
                    userName: booking.userName,
                    bookingStatus: booking.bookingStatus,
                    bookingAmount: booking.bookingAmount,
                    bookingPaymentMethod: booking.bookingPaymentMethod,
                    bookingHours: booking.bookingHours
                },
                // Apply class names based on status for styling
                classNames: [`fc-event-${booking.bookingStatus.toLowerCase().replace(/\s/g, '-')}`]
            };
        });


        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'id', // Set locale to Indonesian
            events: eventsForCalendar, // Use the transformed events array
            eventContent: function(arg) {
                // Custom rendering for event content, e.g., icons or specific formatting
                return { html: '<div class="fc-event-custom">' + arg.event.title + '</div>' };
            },
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,dayGridDay' // Fixed typo: dayGridDay
            },
            height: 'auto', // Adjust height automatically
            contentHeight: 'auto', // Adjust content height automatically
            eventDisplay: 'block', // Display events as blocks
            eventTextColor: '#fff', // White text for events
            dayMaxEvents: true, // Allow "more" link when too many events
            views: {
                dayGridMonth: {
                    titleFormat: { year: 'numeric', month: 'long' } // Full month name
                }
            },
            eventClick: function(info) {
                // Example: Display more details in an alert or a custom modal
                const props = info.event.extendedProps;
                alert(`Detail Pemesanan:
Nama Fasilitas: ${props.facilityName}
Nama Pengguna: ${props.userName}
Status: ${props.bookingStatus}
Waktu: ${info.event.start.toLocaleTimeString()} - ${info.event.end.toLocaleTimeString()} (${props.bookingHours} Jam)
Metode Pembayaran: ${props.bookingPaymentMethod}
`);
                info.jsEvent.preventDefault(); // Don't let the browser navigate
            },
            dateClick: function(info) {
                // Example: When a date is clicked, show a prompt or redirect
                // alert('Date clicked: ' + info.dateStr);
            }
        });

        calendar.render();
    });
</script>
@endsection