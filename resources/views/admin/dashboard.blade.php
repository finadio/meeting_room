@extends('admin.layouts.admin_dashboard')

@section('title', 'Dashboard')

@section('content')
    <div class="container-fluid dashboard-content">
        <h2 class="mb-4 dashboard-welcome-title">Welcome, {{ $user->name }}!</h2>

        @if(session('success'))
            <div class="alert alert-success mt-4 animated fadeIn" role="alert">
                {{ session('success') }}
            </div>
        @endif

        <h3 class="section-heading mt-5">Overview</h3>
        <div class="row mt-4 dashboard-stats-row">
            <div class="col-md-6 col-lg-3 mb-4">
                <a href="{{ route('admin.users.index') }}" class="card-link dashboard-stat-card">
                    <div class="card rounded dashboard-card-item card-type-users">
                        <div class="card-body text-left">
                            <div class="dashboard-card-header">
                                <h5 class="card-title text-uppercase">Total Users</h5>
                                <i class='bx bx-user dashboard-card-icon'></i>
                            </div>
                            <p class="card-text">{{ $userCount }}</p>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-6 col-lg-3 mb-4">
                <a href="{{ route('admin.bookings.index') }}" class="card-link dashboard-stat-card">
                    <div class="card rounded dashboard-card-item card-type-bookings">
                        <div class="card-body text-left">
                            <div class="dashboard-card-header">
                                <h5 class="card-title text-uppercase">Total Bookings</h5>
                                <i class='bx bx-calendar dashboard-card-icon'></i>
                            </div>
                            <p class="card-text">{{ $bookingCount }}</p>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-6 col-lg-3 mb-4">
                <a href="{{ route('admin.facilities.index') }}" class="card-link dashboard-stat-card">
                    <div class="card rounded dashboard-card-item card-type-facilities">
                        <div class="card-body text-left">
                            <div class="dashboard-card-header">
                                <h5 class="card-title text-uppercase">Total Facilities</h5>
                                <i class='bx bxs-building dashboard-card-icon'></i>
                            </div>
                            <p class="card-text">{{ $facilityCount }}</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        {{-- BAGIAN USER DISTRIBUTION DAN CALENDAR OVERVIEW DIGABUNG --}}
        <div class="row mt-5"> {{-- Hapus justify-content-center dari sini, biarkan kolomnya sendiri yang justify --}}
            {{-- Kolom untuk User Distribution --}}
            <div class="col-md-6 mb-4"> {{-- Ubah dari col-md-8 col-lg-6 menjadi col-md-6 --}}
                <h3 class="section-heading-in-column">User Distribution</h3> {{-- Ubah kelas heading --}}
                <div class="card p-4 shadow-lg dashboard-chart-card">
                    <canvas id="userChart"></canvas>
                </div>
            </div>

            {{-- Kolom untuk Booking Calendar Overview --}}
            <div class="col-md-6 mb-4"> {{-- Ubah dari col-md-8 col-lg-6 menjadi col-md-6 --}}
                @if(auth()->check() && auth()->user()->user_type === 'admin')
                <h3 class="section-heading-in-column">Booking Calendar Overview</h3> {{-- Ubah kelas heading --}}
                <a href="{{ route('admin.calendar') }}" style="text-decoration: none; color: black;">
                <div id="calendar" class="card p-4 shadow-lg dashboard-calendar-card"></div>
                </a>
                @endif
            </div>
        </div>
        {{-- AKHIR BAGIAN USER DISTRIBUTION DAN CALENDAR OVERVIEW DIGABUNG --}}
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var ctx = document.getElementById('userChart').getContext('2d');
            var userCounts = {!! json_encode($userCounts) !!};
            var userTypes = Object.keys(userCounts);

            var chart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: userTypes,
                    datasets: [{
                        label: 'Types of Users',
                        backgroundColor: ['#A0D9FF', '#FFB7B7', '#D7A7FF'],
                        data: Object.values(userCounts)
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                display: false
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    },
                    plugins: {
                        legend: { display: false },
                        title: {
                            display: true,
                            text: 'User Distribution by Type',
                            font: { size: 16, weight: '600' },
                            color: '#343a40'
                        }
                    }
                }
            });

            document.getElementById('userChart').style.height = '300px';
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                events: @json($bookedDates),
                eventRender: function(info) {
                    if (info.event.extendedProps.status === 'booked') {
                        info.el.classList.add('badge', 'badge-primary', 'badge-pill');
                    }
                },
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                height: 'auto',
                contentHeight: 'auto',
                eventDisplay: 'block',
                eventBackgroundColor: '#A0D9FF',
                eventBorderColor: '#A0D9FF',
                eventTextColor: '#fff',
                dayMaxEvents: true,
                views: {
                    dayGridMonth: {
                        titleFormat: { year: 'numeric', month: 'short' }
                    }
                }
            });
            calendar.render();
        });
    </script>
@endsection