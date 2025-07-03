@extends('admin.layouts.admin_dashboard')

@section('title', 'Dashboard')

@section('content')
    <div class="container-fluid dashboard-content">
        {{-- HEADER UTAMA DASHBOARD --}}
        <div class="dashboard-header-main mb-4">
            <h2 class="dashboard-welcome-title">Welcome Back, Administrator!</h2>
            <p class="dashboard-summary-text">Dashboard ringkasan data penting Anda.</p>
        </div>
        {{-- AKHIR HEADER UTAMA --}}

        @if(session('success'))
            <div class="alert alert-success mt-4 animated fadeIn" role="alert">
                {{ session('success') }}
            </div>
        @endif

        {{-- BAGIAN OVERVIEW - KARTU STATISTIK (4 KARTU UTAMA) --}}
        <div class="row mt-4 dashboard-stats-row g-3">
            {{-- Kartu Total Users --}}
            <div class="col-6 col-md-4 col-lg-3 mb-4">
                <a href="{{ route('admin.users.index') }}" class="card-link dashboard-stat-card">
                    <div class="card rounded dashboard-card-item card-type-users"> {{-- Hapus bg-primary text-white --}}
                        <div class="card-body">
                            <div class="stat-icon-square"><i class='bx bx-user'></i></div> {{-- Icon di kotak putih --}}
                            <div class="stat-text-block">
                                <h5 class="stat-label">Total Users</h5>
                                <p class="stat-value">{{ $userCount }}</p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            {{-- Kartu Total Bookings --}}
            <div class="col-6 col-md-4 col-lg-3 mb-4">
                <a href="{{ route('admin.bookings.index') }}" class="card-link dashboard-stat-card">
                    <div class="card rounded dashboard-card-item card-type-bookings">
                        <div class="card-body">
                            <div class="stat-icon-square"><i class='bx bx-calendar'></i></div>
                            <div class="stat-text-block">
                                <h5 class="stat-label">Total Bookings</h5>
                                <p class="stat-value">{{ $bookingCount }}</p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            {{-- Kartu Total Facilities --}}
            <div class="col-6 col-md-4 col-lg-3 mb-4">
                <a href="{{ route('admin.facilities.index') }}" class="card-link dashboard-stat-card">
                    <div class="card rounded dashboard-card-item card-type-facilities">
                        <div class="card-body">
                            <div class="stat-icon-square"><i class='bx bxs-building'></i></div>
                            <div class="stat-text-block">
                                <h5 class="stat-label">Total Facilities</h5>
                                <p class="stat-value">{{ $facilityCount }}</p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            {{-- Kartu New Users --}}
            <div class="col-6 col-md-4 col-lg-3 mb-4">
                <a href="{{ route('admin.users.index') }}" class="card-link dashboard-stat-card">
                    <div class="card rounded dashboard-card-item">
                        <div class="card-body">
                            <div class="stat-icon-square"><i class='bx bx-user-plus'></i></div>
                            <div class="stat-text-block">
                                <h5 class="stat-label">New Users (30 Hari)</h5>
                                <p class="stat-value">{{ $newUsersCount }}</p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        {{-- BAGIAN GRAFIK DAN KALENDER (BERSEBELAHAN) --}}
        <div class="row mt-5 g-3">
            <div class="col-lg-5 mb-4"> {{-- DIUBAH: Lebar kolom grafik menjadi 7/12 --}}
                
                <div class="card p-4 shadow-lg dashboard-chart-card">
                    <canvas id="userChart"></canvas>
                </div>
            </div>

            <div class="col-lg-7 mb-4"> {{-- DIUBAH: Lebar kolom kalender menjadi 5/12 --}}
                @if(auth()->check() && auth()->user()->user_type === 'admin')
                <a href="{{ route('admin.calendar') }}" class="text-decoration-none text-dark">
                <div id="calendar" class="card p-4 shadow-lg dashboard-calendar-card"></div>
                </a>
                @endif
            </div>
        </div>
        {{-- AKHIR BAGIAN GRAFIK DAN KALENDER --}}

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
                        backgroundColor: ['#4A6DFB', '#28A745', '#17A2B8', '#FFC107'], /* Warna chart sesuai tema utama */
                        borderRadius: 5,
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
                                display: false,
                                drawBorder: false
                            },
                            ticks: {
                                color: '#A5A5A5'
                            }
                        },
                        x: {
                            grid: {
                                display: false,
                                drawBorder: false
                            },
                            ticks: {
                                color: '#A5A5A5'
                            }
                        }
                    },
                    plugins: {
                        legend: { display: false },
                        title: {
                            display: true,
                            text: 'User Distribution by Type',
                            font: { size: 16, weight: '600' },
                            color: '#343A40'
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
                eventContent: function(arg) {
                    return { html: '<div class="fc-event-custom">' + arg.event.title + '</div>' };
                },
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                height: 'auto',
                contentHeight: 'auto',
                eventDisplay: 'block',
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