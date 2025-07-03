@extends('user.layouts.app')
@section('title', 'Premium Meeting Rooms - BPR MSA')
@section('content')
    <!-- Hero Section -->
    <div class="container-fluid hero-section">
        <div class="row">
            <div class="col-md-12">
                <div class="hero-container">
                    <img src="{{ asset('img/background.png') }}" alt="Premium Meeting Rooms" class="hero-image" style="height: 70vh; min-height: 500px; object-fit: cover;">
                    <div class="hero-content">
                        <h1 class="hero-title">Ruang Meeting Premium</h1>
                        <p class="hero-subtitle">Solusi meeting profesional dengan fasilitas terbaik untuk bisnis Anda</p>
                        <a href="{{ route('user.booking.index') }}" class="hero-cta">
                            <i class='bx bxs-calendar'></i>
                            <span>Reservasi Sekarang</span>
                            <i class='bx bxs-right-arrow-alt'></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- How it works section -->
    <section class="how-it-works">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Cara Reservasi</h2>
                <p class="section-subtitle">Proses reservasi yang mudah dan cepat dalam 3 langkah sederhana</p>
            </div>
            
            <div class="row">
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="process-card">
                        <div class="process-icon">
                            <i class='bx bx-user-plus'></i>
                        </div>
                        <div class="process-content">
                            <h3>Daftar Akun</h3>
                            <p>Buat akun profesional Anda untuk akses ke semua fasilitas meeting room premium kami.</p>
                            <a href="{{ route('register') }}" class="process-link">
                                Daftar Sekarang
                                <i class='bx bxs-right-arrow-alt'></i>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="process-card">
                        <div class="process-icon">
                            <i class='bx bx-building-house'></i>
                        </div>
                        <div class="process-content">
                            <h3>Pilih Ruangan</h3>
                            <p>Pilih ruang meeting yang sesuai dengan kebutuhan tim dan agenda bisnis Anda.</p>
                            <a href="{{ route('user.booking.index') }}" class="process-link">
                                Lihat Ruangan
                                <i class='bx bxs-right-arrow-alt'></i>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="process-card">
                        <div class="process-icon">
                            <i class='bx bx-calendar-check'></i>
                        </div>
                        <div class="process-content">
                            <h3>Konfirmasi Booking</h3>
                            <p>Konfirmasi reservasi Anda dan nikmati pengalaman meeting yang produktif.</p>
                            <a href="{{ route('user.booking.index') }}" class="process-link">
                                Mulai Booking
                                <i class='bx bxs-right-arrow-alt'></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Facilities section -->
    <section class="featured-facilities">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Ruang Meeting Premium</h2>
                <p class="section-subtitle">Fasilitas meeting room terbaik dengan teknologi modern dan kenyamanan maksimal</p>
            </div>
            
            <div class="row justify-content-center">
                @php $counter = 0 @endphp
                @forelse($facilities as $facility)
                    @if($counter < 3)
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="facility-card">
                                <div class="facility-image">
                                    <a href="{{ route('user.booking.show', ['facilityId' => $facility->id]) }}">
                                        <img src="{{ asset('img/meeting_lobby.jpeg') }}" 
                                            alt="{{ $facility->name }}" 
                                            loading="lazy"
                                            onerror="console.log('Image failed to load:', this.src); this.style.border='2px solid red';">
                                    </a>

                                </div>
                                
                                <div class="facility-content">
                                    <h3 class="facility-title">
                                        <a href="{{ route('user.booking.show', ['facilityId' => $facility->id]) }}">
                                            {{ $facility->name }}
                                        </a>
                                    </h3>
                                    <p class="facility-location">
                                        <i class='bx bx-map-pin'></i>
                                        {{ $facility->location }}
                                    </p>
                                    <div class="facility-actions">
                                        <a href="{{ route('user.booking.show', ['facilityId' => $facility->id]) }}" 
                                           class="booking-btn">
                                            <i class='bx bx-calendar'></i>
                                            Reservasi
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @php $counter++ @endphp
                    @else
                        @break
                    @endif
                @empty
                    <div class="col-12">
                        <div class="empty-state">
                            <i class='bx bx-building-house'></i>
                            <h3>Tidak ada ruang meeting tersedia</h3>
                            <p>Mohon cek kembali nanti atau hubungi tim kami untuk informasi lebih lanjut.</p>
                        </div>
                    </div>
                @endforelse
            </div>
            
            <div class="text-center mt-5">
                <a href="{{ route('user.booking.index') }}" class="view-all-btn">
                    Lihat Semua Ruangan
                    <i class='bx bxs-right-arrow-alt'></i>
                </a>
            </div>
        </div>
    </section>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/user_dashboard.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        h1, h2, h3, h4, h5, h6, .btn, .hero-title, .section-title {
            font-family: 'Poppins', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif !important;
        }
    </style>
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
        $(document).ready(function() {
            console.log('Dashboard loaded');
            const images = document.querySelectorAll('.facility-image img');
            images.forEach((img, index) => {
                console.log('Image ' + index + ':', img.src);
                img.addEventListener('load', function() {
                    console.log('Image ' + index + ' loaded successfully');
                });
                img.addEventListener('error', function() {
                    console.log('Image ' + index + ' failed to load');
                });
            });


        });
    </script>
@endsection
