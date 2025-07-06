@extends('user.layouts.app')
@section('title', 'Calendar - BPR MSA')
@section('content')
    <!-- Header Section -->
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="calendar-header text-center mb-4">
                    <h1 class="calendar-title">Kalender Booking</h1>
                    <p class="calendar-subtitle">Lihat jadwal booking ruang meeting Anda</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Calendar Section -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-8 col-md-12 mb-4">
                <div class="calendar-container">
                    <div class="card shadow-sm border-0">
                        <div class="card-body p-4">
                            <div id="calendar"></div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Booking List Sidebar -->
            <div class="col-lg-4 col-md-12">
                <div class="booking-list-container">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-primary text-white">
                            <h5 class="card-title mb-0">
                                <i class='bx bx-calendar-check'></i>
                                Daftar Booking
                            </h5>
                        </div>
                        
                        <!-- Search and Filter Section -->
                        <div class="search-filter-section p-3 border-bottom">
                            <div class="search-box mb-3">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-white border-right-0">
                                            <i class='bx bx-search text-muted'></i>
                                        </span>
                                    </div>
                                    <input type="text" 
                                           class="form-control border-left-0" 
                                           id="bookingSearch" 
                                           placeholder="Cari ruangan atau tanggal...">
                                </div>
                            </div>
                            
                            <div class="filter-section">
                                <div class="row">
                                    <div class="col-6">
                                        <select class="form-control form-control-sm" id="facilityFilter">
                                            <option value="">Semua Ruangan</option>
                                            @php
                                                $facilities = collect($bookedDates)->pluck('facilityName')->unique()->sort();
                                            @endphp
                                            @foreach($facilities as $facility)
                                                <option value="{{ $facility }}">{{ $facility }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-6">
                                        <select class="form-control form-control-sm" id="dateFilter">
                                            <option value="">Semua Tanggal</option>
                                            <option value="today">Hari Ini</option>
                                            <option value="week">Minggu Ini</option>
                                            <option value="month">Bulan Ini</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card-body p-0">
                            @if(count($bookedDates) > 0)
                                <div class="booking-list" id="bookingList">
                                    @php $counter = 1 @endphp
                                    @foreach($bookedDates as $booking)
                                        <div class="booking-item" 
                                             data-facility="{{ $booking['facilityName'] }}"
                                             data-date="{{ $booking['bookingDate'] }}"
                                             data-time="{{ $booking['bookingTime'] }}">
                                            <div class="booking-header">
                                                <span class="booking-number">{{ $counter++ }}</span>
                                                <h6 class="booking-facility">{{ $booking['facilityName'] }}</h6>
                                            </div>
                                            <div class="booking-details">
                                                <p class="booking-date">
                                                    <i class='bx bx-calendar'></i>
                                                    {{ \Carbon\Carbon::parse($booking['bookingDate'])->format('d M Y') }}
                                                </p>
                                                <p class="booking-time">
                                                    <i class='bx bx-time'></i>
                                                    {{ \Carbon\Carbon::parse($booking['bookingTime'])->format('H:i') }}
                                                </p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                
                                <!-- No Results Message -->
                                <div class="no-results text-center py-4" id="noResults" style="display: none;">
                                    <i class='bx bx-search-alt'></i>
                                    <p class="text-muted mt-2">Tidak ada booking yang ditemukan</p>
                                </div>
                            @else
                                <div class="empty-booking text-center py-4">
                                    <i class='bx bx-calendar-x'></i>
                                    <p class="text-muted mt-2">Belum ada booking</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
    <script>
        const bookingSearch = document.getElementById('bookingSearch');
        const facilityFilter = document.getElementById('facilityFilter');
        const dateFilter = document.getElementById('dateFilter');
        const bookingList = document.getElementById('bookingList');
        const noResults = document.getElementById('noResults');

        // Fungsi untuk memformat tanggal ke YYYY-MM-DD
        function formatDate(date) {
            const d = new Date(date);
            let month = '' + (d.getMonth() + 1);
            let day = '' + d.getDate();
            const year = d.getFullYear();

            if (month.length < 2) month = '0' + month;
            if (day.length < 2) day = '0' + day;

            return [year, month, day].join('-');
        }

        function filterBookings() {
            const search = bookingSearch.value.toLowerCase();
            const facility = facilityFilter.value;
            const dateType = dateFilter.value;
            let found = 0;

            const today = new Date();
            // Setujui tanggal ke awal hari untuk perbandingan yang akurat
            today.setHours(0, 0, 0, 0);

            const startOfWeek = new Date(today);
            startOfWeek.setDate(today.getDate() - today.getDay()); // Minggu dimulai dari Minggu (0)
            startOfWeek.setHours(0, 0, 0, 0);

            const endOfWeek = new Date(startOfWeek);
            endOfWeek.setDate(startOfWeek.getDate() + 6);
            endOfWeek.setHours(23, 59, 59, 999);

            const startOfMonth = new Date(today.getFullYear(), today.getMonth(), 1);
            startOfMonth.setHours(0, 0, 0, 0);

            const endOfMonth = new Date(today.getFullYear(), today.getMonth() + 1, 0);
            endOfMonth.setHours(23, 59, 59, 999);

            // Daftar nama bulan (lowercase) untuk pencarian
            const monthNames = [
                "januari", "februari", "maret", "april", "mei", "juni",
                "juli", "agustus", "september", "oktober", "november", "desember",
                "january", "february", "march", "april", "may", "june",
                "july", "august", "september", "october", "november", "december"
            ];

            Array.from(bookingList.getElementsByClassName('booking-item')).forEach(function(item) {
                const facilityName = item.getAttribute('data-facility').toLowerCase();
                const userName = item.getAttribute('data-user-name') ? item.getAttribute('data-user-name').toLowerCase() : '';
                const email = item.getAttribute('data-email') ? item.getAttribute('data-email').toLowerCase() : '';
                const contactNumber = item.getAttribute('data-contact-number') ? item.getAttribute('data-contact-number').toLowerCase() : '';
                const status = item.getAttribute('data-status') ? item.getAttribute('data-status').toLowerCase() : '';
                const meetingTitle = item.getAttribute('data-meeting-title') ? item.getAttribute('data-meeting-title').toLowerCase() : '';
                const groupName = item.getAttribute('data-group-name') ? item.getAttribute('data-group-name').toLowerCase() : '';

                const bookingDateString = item.getAttribute('data-date'); // e.g., "2025-07-02"
                const bookingTimeString = item.getAttribute('data-time'); // e.g., "10:00 - 11:00"
                const bookingDate = new Date(bookingDateString);
                bookingDate.setHours(0, 0, 0, 0); // Set to start of day for accurate comparison

                let show = true;

                // Logika Pencarian Umum (berlaku untuk semua input teks)
                if (search) {
                    let isMatch = false;

                    // Cek di kolom-kolom yang relevan
                    if (facilityName.includes(search) ||
                        userName.includes(search) ||
                        email.includes(search) ||
                        contactNumber.includes(search) ||
                        status.includes(search) ||
                        meetingTitle.includes(search) ||
                        groupName.includes(search) ||
                        bookingDateString.includes(search) || // Mencari di string tanggal (misal "2025-07-02" cocok dengan "07")
                        bookingTimeString.includes(search)
                    ) {
                        isMatch = true;
                    }

                    // Cek apakah search adalah nama bulan
                    const monthIndex = bookingDate.getMonth(); // 0 for Jan, 1 for Feb, etc.
                    if (monthNames[monthIndex] === search || monthNames[monthIndex + 12] === search) { // Cek id dan nama
                        isMatch = true;
                    }
                    // Anda juga bisa menambahkan logika untuk nomor bulan
                    if (parseInt(search) === (monthIndex + 1)) { // Jika search adalah nomor bulan (misal "7" untuk July)
                        isMatch = true;
                    }


                    if (!isMatch) {
                        show = false;
                    }
                }

                // Filter Fasilitas
                if (facility && facility !== item.getAttribute('data-facility')) {
                    show = false;
                }

                // Filter Tanggal
                if (dateType) {
                    if (dateType === 'today') {
                        if (bookingDate.toDateString() !== today.toDateString()) show = false;
                    } else if (dateType === 'week') {
                        if (bookingDate < startOfWeek || bookingDate > endOfWeek) show = false;
                    } else if (dateType === 'month') {
                        if (bookingDate < startOfMonth || bookingDate > endOfMonth) show = false;
                    }
                    // Jika dateType adalah tanggal spesifik (dari datepicker atau input tanggal lainnya)
                    else {
                        const filterDate = new Date(dateType);
                        filterDate.setHours(0, 0, 0, 0);
                        if (bookingDate.toDateString() !== filterDate.toDateString()) show = false;
                    }
                }

                if (show) {
                    item.style.display = '';
                    found++;
                } else {
                    item.style.display = 'none';
                }
            });

            if (found === 0) {
                noResults.style.display = '';
            } else {
                noResults.style.display = 'none';
            }
        }

        // Panggil filterBookings saat input berubah
        bookingSearch.addEventListener('keyup', filterBookings);
        facilityFilter.addEventListener('change', filterBookings);
        dateFilter.addEventListener('change', filterBookings);

        // Panggil filterBookings saat halaman dimuat untuk menerapkan filter awal
        document.addEventListener('DOMContentLoaded', filterBookings);
    </script>
@endsection

@section('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fullcalendar/core/main.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Calendar Header */
        .calendar-header {
            background: linear-gradient(135deg, #1E40AF 0%, #3B82F6 100%);
            color: white;
            padding: 2rem 0;
            border-radius: 15px;
            margin-bottom: 2rem;
        }

        .calendar-title {
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
        }

        .calendar-subtitle {
            font-family: 'Poppins', sans-serif;
            font-weight: 400;
            font-size: 1.1rem;
            opacity: 0.9;
            margin-bottom: 0;
        }

        /* Calendar Container */
        .calendar-container {
            background: white;
            border-radius: 15px;
            overflow: hidden;
        }

        .calendar-container .card {
            border: none;
            border-radius: 15px;
        }

        /* FullCalendar Customization */
        .fc {
            font-family: 'Poppins', sans-serif;
        }

        .fc-toolbar {
            background: #f8fafc;
            padding: 1rem;
            border-radius: 10px 10px 0 0;
            border-bottom: 1px solid #e2e8f0;
        }

        .fc-toolbar-title {
            font-weight: 600;
            color: #1E293B;
            font-size: 1.25rem;
        }

        .fc-button {
            background: #1E40AF !important;
            border-color: #1E40AF !important;
            font-weight: 500;
            border-radius: 8px;
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
        }

        .fc-button:hover {
            background: #1E3A8A !important;
            border-color: #1E3A8A !important;
        }

        .fc-button-active {
            background: #1E3A8A !important;
            border-color: #1E3A8A !important;
        }

        .fc-daygrid-day {
            border-color: #e2e8f0;
        }

        .fc-daygrid-day-number {
            color: #1E293B;
            font-weight: 500;
        }

        .fc-day-today {
            background: rgba(30, 64, 175, 0.1) !important;
        }

        .fc-day-today .fc-daygrid-day-number {
            background: #1E40AF;
            color: white;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .booking-event {
            background: #1E40AF !important;
            border-color: #1E40AF !important;
            border-radius: 6px;
            font-size: 0.85rem;
            font-weight: 500;
        }

        /* Search and Filter Section */
        .search-filter-section {
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
        }

        .search-box .input-group-text {
            border: 1px solid #d1d5db;
            border-right: none;
        }

        .search-box .form-control {
            border: 1px solid #d1d5db;
            border-left: none;
            font-size: 0.9rem;
        }

        .search-box .form-control:focus {
            box-shadow: none;
            border-color: #1E40AF;
        }

        .filter-section .form-control {
            font-size: 0.85rem;
            border: 1px solid #d1d5db;
        }

        .filter-section .form-control:focus {
            box-shadow: none;
            border-color: #1E40AF;
        }

        .no-results {
            color: #94A3B8;
        }

        .no-results i {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }

        /* Booking List */
        .booking-list-container {
            height: fit-content;
        }

        .booking-list-container .card {
            border: none;
            border-radius: 15px;
            overflow: hidden;
        }

        .booking-list-container .card-header {
            background: linear-gradient(135deg, #1E40AF 0%, #3B82F6 100%);
            border: none;
            padding: 1rem 1.5rem;
        }

        .booking-list-container .card-title {
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            font-size: 1.1rem;
        }

        .booking-list {
            max-height: 500px;
            overflow-y: auto;
        }

        .booking-item {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #e2e8f0;
            transition: all 0.3s ease;
        }

        .booking-item:last-child {
            border-bottom: none;
        }

        .booking-item:hover {
            background: #f8fafc;
        }
        .booking-item:hover .booking-facility,
        .booking-item:hover .booking-details p {
            color: #1E293B !important;
        }

        .booking-header {
            display: flex;
            align-items: center;
            margin-bottom: 0.5rem;
        }

        .booking-number {
            background: #1E40AF;
            color: white;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            font-weight: 600;
            margin-right: 0.75rem;
        }

        .booking-facility {
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            font-size: 0.95rem;
            color: #1E293B;
            margin: 0;
        }

        .booking-details {
            margin-left: 2.5rem;
        }

        .booking-details p {
            font-size: 0.85rem;
            color: #64748B;
            margin-bottom: 0.25rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .booking-details i {
            color: #1E40AF;
            font-size: 1rem;
        }

        .empty-booking {
            color: #94A3B8;
        }

        .empty-booking i {
            font-size: 3rem;
            margin-bottom: 1rem;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .calendar-title {
                font-size: 2rem;
            }

            .calendar-subtitle {
                font-size: 1rem;
            }

            .fc-toolbar {
                flex-direction: column;
                gap: 1rem;
            }

            .fc-toolbar-chunk {
                display: flex;
                justify-content: center;
            }

            .booking-list-container {
                margin-top: 2rem;
            }

            .booking-list {
                max-height: 300px;
            }
        }

        @media (max-width: 576px) {
            .calendar-title {
                font-size: 1.75rem;
            }

            .fc-toolbar-title {
                font-size: 1.1rem;
            }

            .fc-button {
                padding: 0.4rem 0.8rem;
                font-size: 0.8rem;
            }

            .booking-item {
                padding: 0.75rem 1rem;
            }

            .booking-facility {
                font-size: 0.9rem;
            }
        }

        /* List view event biru, dot biru, tanpa efek hover */
        .fc-list-event {
            background: #1E40AF !important;
            color: #fff !important;
            border-radius: 20px !important;
            border: none !important;
        }
        .fc-list-event-dot {
            background: #3B82F6 !important;
        }
        .fc-list-event:hover {
            background: #1E40AF !important;
        }
    </style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // ... existing calendar code ...
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        height: 'auto',
        aspectRatio: 1.35,
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,listWeek'
        },
        buttonText: {
            today: 'Hari Ini',
            month: 'Bulan',
            week: 'Minggu',
            list: 'Daftar'
        },
        locale: 'id',
        events: @json($bookedDates),
        eventDisplay: 'block',
        eventColor: '#1E40AF',
        eventTextColor: '#ffffff',
        eventRender: function(info) {
            if (info.event.extendedProps.status === 'booked') {
                info.el.classList.add('booking-event');
            }
        },
        dayMaxEvents: true,
        moreLinkClick: 'popover',
        eventClick: function(info) {
            // Handle event click
            console.log('Event clicked:', info.event.title);
        }
    });
    calendar.render();

    // Search & Filter Logic
    const bookingSearch = document.getElementById('bookingSearch');
    const facilityFilter = document.getElementById('facilityFilter');
    const dateFilter = document.getElementById('dateFilter');
    const bookingList = document.getElementById('bookingList');
    const noResults = document.getElementById('noResults');

    function filterBookings() {
        const search = bookingSearch.value.toLowerCase();
        const facility = facilityFilter.value;
        const dateType = dateFilter.value;
        let found = 0;
        const today = new Date();
        const startOfWeek = new Date(today);
        startOfWeek.setDate(today.getDate() - today.getDay());
        const endOfWeek = new Date(startOfWeek);
        endOfWeek.setDate(startOfWeek.getDate() + 6);
        const startOfMonth = new Date(today.getFullYear(), today.getMonth(), 1);
        const endOfMonth = new Date(today.getFullYear(), today.getMonth() + 1, 0);

        Array.from(bookingList.getElementsByClassName('booking-item')).forEach(function(item) {
            const facilityName = item.getAttribute('data-facility').toLowerCase();
            const bookingDate = item.getAttribute('data-date');
            const bookingTime = item.getAttribute('data-time');
            let show = true;

            // Search
            if (search && !(facilityName.includes(search) || bookingDate.includes(search) || bookingTime.includes(search))) {
                show = false;
            }
            // Facility filter
            if (facility && facility !== item.getAttribute('data-facility')) {
                show = false;
            }
            // Date filter
            if (dateType) {
                const dateObj = new Date(bookingDate);
                if (dateType === 'today') {
                    if (dateObj.toDateString() !== today.toDateString()) show = false;
                } else if (dateType === 'week') {
                    if (dateObj < startOfWeek || dateObj > endOfWeek) show = false;
                } else if (dateType === 'month') {
                    if (dateObj < startOfMonth || dateObj > endOfMonth) show = false;
                }
            }
            if (show) {
                item.style.display = '';
                found++;
            } else {
                item.style.display = 'none';
            }
        });
        if (found === 0) {
            noResults.style.display = '';
        } else {
            noResults.style.display = 'none';
        }
    }

    bookingSearch.addEventListener('input', filterBookings);
    facilityFilter.addEventListener('change', filterBookings);
    dateFilter.addEventListener('change', filterBookings);
});
</script>
@endsection
