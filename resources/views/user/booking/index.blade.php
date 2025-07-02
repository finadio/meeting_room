@extends('user.layouts.app')
@section('title', 'Booking')
@section('content')

<div class="container mt-5 pt-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Header Section -->
            <div class="calendar-header text-center mb-4">
                <h1 class="calendar-title">Booking</h1>
                <p class="calendar-subtitle">Pilih Ruang Meeting yang Tersedia</p>
            </div>

            <!-- Alert Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    <i class='bx bx-check-circle me-2'></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                    <i class='bx bx-error-circle me-2'></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Search Bar -->
            <div class="row justify-content-center mb-5">
                <div class="col-lg-8">
                    <div class="search-card">
                        <form action="{{ route('user.booking.search') }}" method="GET">
                            <div class="input-group align-items-stretch">
                                <input type="text" class="form-control" placeholder="Cari ruang meeting..." name="search" style="height:48px;">
                                <button class="btn btn-primary d-flex align-items-center justify-content-center" type="submit" style="height:48px; min-width:48px; padding:0;">
                                    <i class='bx bx-search' style="font-size:1.3rem;"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Facilities Grid -->
            <div class="row">
                @forelse($facilities as $facility)
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="facility-card">
                            <div class="facility-image">
                                <a href="{{ route('user.booking.show', ['facilityId' => $facility->id]) }}">
                                    @php
                                        $imgPath = $facility->image_path && file_exists(public_path($facility->image_path))
                                            ? asset($facility->image_path)
                                            : asset('img/meeting_lobby.jpeg');
                                    @endphp
                                    <img src="{{ $imgPath }}" alt="{{ $facility->name }}">
                                </a>
                                @auth
                                    <form action="{{ route('user.facility.bookmark', ['facility' => $facility->id]) }}" 
                                          method="post" 
                                          class="bookmark-form">
                                        @csrf
                                        <button type="submit" class="bookmark-btn" aria-label="Bookmark {{ $facility->name }}">
                                            @if(in_array($facility->id, $bookmarkedIds))
                                                <i class='bx bxs-bookmark'></i>
                                            @else
                                                <i class='bx bx-bookmark'></i>
                                            @endif
                                        </button>
                                    </form>
                                @endauth
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
        </div>
    </div>
</div>

<x-footer />
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/user_dashboard.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href='https://cdn.jsdelivr.net/npm/boxicons@2.1.0/css/boxicons.min.css' rel='stylesheet'>
    <style>
        /* Booking Page Specific Styles */
        .search-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            border: 1px solid #e2e8f0;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .search-card .form-control {
            border: 2px solid #e2e8f0;
            border-radius: 8px 0 0 8px;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
            background: #f8fafc;
            height: 48px;
        }
        
        .search-card .btn {
            border-radius: 0 8px 8px 0;
            height: 48px;
            min-width: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            padding: 0;
        }
        
        .search-card .btn:focus {
            box-shadow: 0 0 0 3px rgba(35, 57, 93, 0.1);
        }
        
        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
            color: var(--text-secondary);
        }
        
        .empty-state i {
            font-size: 4rem;
            color: var(--text-muted);
            margin-bottom: 1rem;
        }
        
        .empty-state h3 {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: var(--text-primary);
        }
        
        .empty-state p {
            color: var(--text-secondary);
            margin: 0;
        }
        
        .calendar-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: #1E293B;
            margin-bottom: 0.5rem;
            letter-spacing: -1px;
        }
        .calendar-subtitle {
            font-size: 1.1rem;
            color: #64748B;
            margin-bottom: 0;
        }
        
        .text-primary {
            color: #1E293B !important;
        }
        
        .text-muted {
            color: #64748b !important;
        }
        
        .alert {
            border-radius: 8px;
            border: none;
        }
        
        .alert-success {
            background: #d4edda;
            color: #155724;
            border-left: 4px solid #28a745;
        }
        
        .alert-danger {
            background: #f8d7da;
            color: #721c24;
            border-left: 4px solid #dc3545;
        }
        
        @media (max-width: 768px) {
            .container {
                padding: 0 15px;
            }
        }
        
        /* Facility Cards Styling */
        .facility-card {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.10);
            transition: box-shadow 0.3s, transform 0.3s;
            border: 1px solid #e2e8f0;
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        .facility-card:hover {
            transform: translateY(-6px) scale(1.01);
            box-shadow: 0 10px 25px rgba(35, 57, 93, 0.13);
            border-color: #1E40AF;
        }
        .facility-image {
            position: relative;
            height: 180px;
            overflow: hidden;
            background: #f8fafc;
            border-radius: 16px 16px 0 0;
        }
        .facility-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
            border-radius: 16px 16px 0 0;
        }
        .facility-card:hover .facility-image img {
            transform: scale(1.04);
        }
        .bookmark-form {
            position: absolute;
            top: 12px;
            right: 12px;
            z-index: 2;
        }
        .bookmark-btn {
            background: rgba(255, 255, 255, 0.95);
            border: none;
            border-radius: 50%;
            width: 38px;
            height: 38px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #F59E0B;
            font-size: 1.2rem;
            transition: all 0.3s;
            box-shadow: 0 2px 6px rgba(35, 57, 93, 0.08);
        }
        .bookmark-btn:hover {
            background: #F59E0B;
            color: white;
            transform: scale(1.13);
        }
        .facility-content {
            padding: 1.25rem 1.25rem 1rem 1.25rem;
            flex: 1 1 auto;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .facility-title {
            font-size: 1.15rem;
            font-weight: 600;
            margin-bottom: 0.4rem;
            color: #1e293b;
        }
        .facility-title a {
            color: #1e293b;
            text-decoration: none;
            transition: color 0.3s;
        }
        .facility-title a:hover {
            color: #1E40AF;
        }
        .facility-location {
            color: #64748b;
            font-size: 0.97rem;
            margin-bottom: 1.1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .facility-actions {
            margin-top: auto;
        }
        .booking-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: linear-gradient(135deg, #23395d, #1E40AF);
            color: white;
            padding: 0.7rem 1.2rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
            width: 100%;
            justify-content: center;
            border: none;
            box-shadow: 0 2px 4px rgba(35, 57, 93, 0.08);
            font-size: 1rem;
        }
        .booking-btn:hover {
            background: linear-gradient(135deg, #1E40AF, #1E3A8A);
            color: white;
            text-decoration: none;
            transform: translateY(-2px) scale(1.03);
            box-shadow: 0 4px 12px rgba(35, 57, 93, 0.13);
        }
        @media (max-width: 991.98px) {
            .facility-image {
                height: 130px;
            }
        }
        @media (max-width: 767.98px) {
            .facility-card {
                margin-bottom: 1.5rem;
            }
            .facility-image {
                height: 90px;
            }
            .facility-content {
                padding: 1rem;
            }
        }
    </style>
@endsection