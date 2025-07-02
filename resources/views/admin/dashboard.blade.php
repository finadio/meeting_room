@extends('admin.layouts.admin_dashboard')

@section('title', 'Dashboard')

@section('content')
    <div class="container-fluid"> {{-- Gunakan container-fluid untuk lebar penuh --}}
        <h2 class="mb-4 dashboard-welcome-title">Welcome, {{ $user->name }}!</h2> {{-- Tambahkan kelas baru --}}

        @if(session('success'))
            <div class="alert alert-success mt-4" role="alert">
                {{ session('success') }}
            </div>
        @endif


        <h3 class="section-heading mt-5">Overall Statistics</h3> {{-- Judul Bagian Statistik --}}
        <div class="row mt-4"> {{-- Row untuk Statistik --}}
            <div class="col-md-6 col-lg-3 mb-4"> {{-- Tambahkan mb-4 untuk margin bawah di mobile --}}
                <a href="{{ route('admin.users.index') }}" class="card-link dashboard-stat-card"> {{-- Tambahkan kelas baru --}}
                    <div class="card bg-primary text-white rounded shadow-sm"> {{-- Ubah shadow menjadi shadow-sm --}}
                        <div class="card-body text-center">
                            <i class='bx bx-user bx-lg'></i>
                            <h5 class="card-title mt-3">Total Users</h5>
                            <p class="card-text">{{ $userCount }}</p>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-6 col-lg-3 mb-4">
                <a href="{{ route('admin.bookings.index') }}" class="card-link dashboard-stat-card">
                    <div class="card bg-success text-white rounded shadow-sm">
                        <div class="card-body text-center">
                            <i class='bx bx-calendar bx-lg'></i>
                            <h5 class="card-title mt-3">Total Bookings</h5>
                            <p class="card-text">{{ $bookingCount }}</p>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-6 col-lg-3 mb-4">
                <a href="{{ route('admin.facilities.index') }}" class="card-link dashboard-stat-card">
                    <div class="card bg-info text-white rounded shadow-sm">
                        <div class="card-body text-center">
                            <i class='bx bxs-building bx-lg'></i>
                            <h5 class="card-title mt-3">Total Facilities</h5>
                            <p class="card-text">{{ $facilityCount }}</p>
                        </div>
                    </div>
                </a>
            </div>

            </div>

        <h3 class="section-heading mt-5">User Distribution</h3> {{-- Judul Bagian Distribusi Pengguna --}}
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card p-3 shadow-sm"> {{-- Tambahkan card untuk membungkus canvas --}}
                    <canvas id="userChart"></canvas>
                </div>
            </div>
        </div>

        @if(auth()->check() && auth()->user()->user_type === 'admin')
        <h3 class="section-heading mt-5">Booking Calendar Overview</h3> {{-- Judul Bagian Kalender --}}
        <div class="row mt-4">
            <div class="col-md-12">
                <a href="{{ route('admin.calendar') }}" style="text-decoration: none; color: black;">
                <div id="calendar" class="card p-3 shadow-sm"></div> {{-- Tambahkan card untuk membungkus kalender --}}
                </a>
            </div>
        </div>
        @endif
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
                        backgroundColor: ['#3C91E6', '#28a745', '#ffc107'], // Warna sesuai tema Bootstrap
                        data: Object.values(userCounts)
                    }]
                },
                options: {
                    responsive: true, // Pastikan responsif
                    maintainAspectRatio: false, // Penting untuk kontrol ukuran
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    },
                    plugins: {
                        legend: { display: false },
                        title: { display: true, text: 'User Distribution by Type' }
                    }
                }
            });

            // Adjust chart height based on container for responsiveness
            document.getElementById('userChart').style.height = '300px'; // Contoh tinggi
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
                headerToolbar: { // Opsi untuk toolbar header kalender
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                height: 'auto', // Biarkan tinggi kalender menyesuaikan konten
                contentHeight: 'auto' // Penting untuk responsif
            });
            calendar.render();
        });
    </script>
@endsection