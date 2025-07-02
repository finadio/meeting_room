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
                        <div class="card-body p-0">
                            @if(count($bookedDates) > 0)
                                <div class="booking-list">
                                    @php $counter = 1 @endphp
                                    @foreach($bookedDates as $booking)
                                        <div class="booking-item">
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
        document.addEventListener('DOMContentLoaded', function() {
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
        });
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
    </style>
@endsection
