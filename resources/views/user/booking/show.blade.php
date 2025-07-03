<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    {{-- Pastikan ini ada di layout utama Anda, contoh: user.layouts.app --}}
    <meta name="csrf-token" content="{{ csrf_token() }}"> 

    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/css/bootstrap.min.css" rel="stylesheet"> --}}

    <link rel="stylesheet" href="{{ asset('css/user_dashboard.css') }}">

    @yield('styles')

    <style>
        /* Gaya spesifik halaman Booking Detail */
        .facility-image-card {
            position: relative;
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            border: 1px solid #e2e8f0;
            overflow: hidden;
        }
        
        .facility-image-card img {
            width: 100%;
            height: 300px;
            object-fit: cover;
        }
        
        .facility-info-card,
        .booking-form-card,
        .reviews-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            border: 1px solid #e2e8f0;
            overflow: hidden;
        }
        
        .card-header {
            background: linear-gradient(135deg, #23395d, #1E40AF);
            color: white;
            padding: 1.5rem;
            border-bottom: none;
            border-radius: 12px 12px 0 0;
        }
        
        .card-header h4 {
            margin: 0;
            font-weight: 600;
            font-size: 1.1rem;
            display: flex;
            align-items: center;
        }
        
        .card-header h4 i {
            margin-right: 0.5rem;
            font-size: 1.2rem;
        }
        
        .card-body {
            padding: 2rem;
        }
        
        .facility-description {
            color: #64748b;
            line-height: 1.6;
            margin-bottom: 1.5rem;
            font-size: 1rem;
        }
        
        .info-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            margin-bottom: 1.5rem;
            padding: 1rem;
            background: #f8fafc;
            border-radius: 8px;
            border-left: 4px solid #23395d;
            transition: all 0.3s ease;
        }
        
        .info-item:hover {
            background: #f1f5f9;
            transform: translateX(4px);
        }
        
        .info-item i {
            font-size: 1.25rem;
            color: #23395d;
            margin-top: 2px;
            flex-shrink: 0;
            width: 20px;
            text-align: center;
        }
        
        .info-item strong {
            display: block;
            color: #1e293b;
            font-size: 0.875rem;
            margin-bottom: 4px;
            font-weight: 600;
        }
        
        .info-item p {
            color: #64748b;
            margin: 0;
            font-size: 0.95rem;
            line-height: 1.4;
        }
        
        .form-control {
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
            background: #f8fafc;
        }
        
        .form-control:focus {
            border-color: #23395d;
            box-shadow: 0 0 0 3px rgba(35, 57, 93, 0.1);
            background: white;
        }
        
        .booking-btn, .btn-primary {
            background: #3B82F6 !important;
            color: #fff !important;
            border-radius: 10px;
            font-weight: 600;
            border: none;
            padding: 0.85rem 2.2rem;
            font-size: 1.1rem;
            transition: all 0.18s;
            box-shadow: 0 2px 8px rgba(30,64,175,0.08);
        }
        
        .booking-btn:hover, .btn-primary:hover {
            background: var(--primary-dark) !important;
            color: #fff !important;
        }
        
        .bookmark-form-detail {
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative; /* Pastikan ini ada */
            z-index: 1; /* Biarkan z-index container lebih rendah atau default */
        }
        
        .bookmark-btn-detail {
            background: white;
            border: none;
            border-radius: 24px;
            min-width: 0;
            height: 40px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            font-size: 1rem;
            color: #1E40AF;
            transition: background 0.2s;
            padding: 0 18px 0 12px;
            gap: 0.5rem;
            font-weight: 500;
            cursor: pointer;
            
            /* ---- START: Perbaikan untuk tombol bookmark ---- */
            position: relative; /* Penting agar z-index berfungsi */
            z-index: 1000; /* Pastikan ini di atas elemen lain yang mungkin menutupi */
            pointer-events: auto !important; /* Paksa agar event klik aktif */
            /* ---- END: Perbaikan untuk tombol bookmark ---- */
        }
        
        .bookmark-btn-detail i {
            color: #F59E0B;
            font-size: 1.3rem;
            margin-right: 8px;
        }
        
        .bookmark-btn-detail span {
            color: #1e293b;
            font-size: 1rem;
            font-weight: 500;
        }
        
        .bookmark-btn-detail:hover {
            background: #fef3c7;
        }
        
        .review-item {
            padding: 1.5rem;
            background: #f8fafc;
            border-radius: 12px;
            border-left: 4px solid #23395d;
            margin-bottom: 1.5rem;
            transition: all 0.3s ease;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }
        
        .review-item:hover {
            background: #f1f5f9;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }
        
        .review-header {
            margin-bottom: 1rem;
        }
        
        .reviewer-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .reviewer-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #e2e8f0;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        
        .reviewer-name {
            color: #1e293b;
            font-weight: 600;
            margin: 0;
            font-size: 1rem;
        }
        
        .review-content p {
            color: #64748b;
            margin: 0;
            line-height: 1.6;
            font-size: 0.95rem;
        }
        
        .display-4 {
            font-weight: 700;
            color: #1E293B;
        }
        
        .lead {
            font-size: 1.1rem;
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
            
            .card-body {
                padding: 1.5rem;
            }
        }
        
        .facility-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: #1E293B;
            margin-bottom: 0.5rem;
            letter-spacing: -1px;
            text-transform: uppercase;
        }
    </style>
</head>
<body>
    @extends('user.layouts.app')
    @section('title', $facility->name)
    @section('content')
        {{-- <button id="test-click-btn" style="z-index:9999;position:fixed;top:10px;left:10px;">TEST CLICK</button>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('test-click-btn').addEventListener('click', function() {
                alert('TOMBOL TEST BERFUNGSI!');
            });
        });
        </script> --}}

        <div id="alert-container"></div>

        <div class="container mt-5 pt-4">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="text-center mb-5">
                        <h1 class="calendar-title" style="font-size:2.5rem;font-weight:700;color:#1E293B;margin-bottom:0.5rem;letter-spacing:-1px;text-transform:uppercase;">{{ $facility->name }}</h1>
                        <p class="lead text-muted">Detail Ruang Meeting</p>
                    </div>

                    <div id="session-alert-container">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                                <i class='bx bx-check-circle me-2'></i>
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                                <i class='bx bx-error-circle me-2'></i>
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        @if ($errors->has('date'))
                            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                                <i class='bx bx-error-circle me-2'></i>
                                {{ $errors->first('date') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                    </div>

                    <div class="row mb-5">
                        <div class="col-lg-6 mb-4">
                            <div class="facility-image-card position-relative">
                                @php
                                    $imgPath = $facility->image_path && file_exists(public_path($facility->image_path))
                                        ? asset($facility->image_path)
                                        : asset('img/meeting_lobby.jpeg');
                                @endphp
                                <img src="{{ $imgPath }}" alt="{{ $facility->name }}">
                                @if(auth()->check())
                                    <div class="bookmark-form-detail text-center mt-2 mb-3">
                                        <button type="button"
                                                class="bookmark-btn-detail"
                                                data-facility-id="{{ $facility->id }}"
                                                data-bookmarked="{{ auth()->user()->bookmarkedFacilities->contains($facility->id) ? 'true' : 'false' }}"
                                                aria-label="Bookmark {{ $facility->name }}">
                                            @if(auth()->user()->bookmarkedFacilities->contains($facility->id))
                                                <i class='bx bxs-bookmark'></i><span>Hapus Bookmark</span>
                                            @else
                                                <i class='bx bx-bookmark'></i><span>Tambah Bookmark</span>
                                            @endif
                                        </button>
                                        {{-- <button id="test-bookmark-area-btn" style="margin-left:10px;z-index:9999;">TEST AREA</button>
                                        <script>
                                        document.addEventListener('DOMContentLoaded', function() {
                                            var btn = document.getElementById('test-bookmark-area-btn');
                                            if(btn) btn.addEventListener('click', function() {
                                                alert('TOMBOL TEST AREA BERFUNGSI!');
                                            });
                                        });
                                        </script> --}}

                                        {{-- <button id="bookmark-btn-test"
                                                data-facility-id="{{ $facility->id }}"
                                                data-bookmarked="{{ auth()->user()->bookmarkedFacilities->contains($facility->id) ? 'true' : 'false' }}"
                                                style="margin-left:10px;z-index:9999;background:yellow;">
                                            TEST BOOKMARK PURE JS
                                        </button>
                                        <script>
                                        document.addEventListener('DOMContentLoaded', function() {
                                            var btn = document.getElementById('bookmark-btn-test');
                                            if(btn) btn.addEventListener('click', function() {
                                                alert('BOOKMARK BUTTON PURE JS BERFUNGSI!');
                                            });
                                        });
                                        </script> --}}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="col-lg-6 mb-4">
                            <div class="facility-info-card">
                                <div class="card-header">
                                    <h4 class="mb-0">
                                        <i class='bx bx-info-circle me-2'></i>Informasi Ruangan
                                    </h4>
                                </div>
                                <div class="card-body">
                                    <p class="facility-description">{{ $facility->description }}</p>
                                    
                                    <div class="info-item">
                                        <i class='bx bx-map-pin'></i>
                                        <div>
                                            <strong>Lokasi:</strong>
                                            <p>{{ $facility->location }}</p>
                                        </div>
                                    </div>
                                    
                                    <div class="info-item">
                                        <i class='bx bx-time'></i>
                                        <div>
                                            <strong>Jam Operasional:</strong>
                                            <p>{{ \Carbon\Carbon::parse($facility->opening_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($facility->closing_time)->format('H:i') }}</p>
                                        </div>
                                    </div>
                                    
                                    <div class="info-item">
                                        <i class='bx bx-user'></i>
                                        <div>
                                            <strong>Contact Person:</strong>
                                            <p>{{ $facility->contact_person }}</p>
                                        </div>
                                    </div>
                                    
                                    <div class="info-item">
                                        <i class='bx bx-envelope'></i>
                                        <div>
                                            <strong>Email:</strong>
                                            <p>{{ $facility->contact_email }}</p>
                                        </div>
                                    </div>
                                    
                                    <div class="info-item">
                                        <i class='bx bx-phone'></i>
                                        <div>
                                            <strong>Telepon:</strong>
                                            <p>{{ $facility->contact_phone }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row justify-content-center mb-5">
                        <div class="col-lg-8">
                            <div class="booking-form-card">
                                <div class="card-header">
                                    <h4 class="mb-0">
                                        <i class='bx bx-calendar-plus me-2'></i>Form Reservasi
                                    </h4>
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('user.booking.confirm', ['facilityId' => $facility->id]) }}" method="post">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="date" class="form-label">Tanggal <span class="text-danger">*</span></label>
                                                <input type="date" id="date" name="date" class="form-control" required min="{{ date('Y-m-d') }}">
                                            </div>
                                            
                                            <div class="col-md-6 mb-3">
                                                <label for="meeting_title" class="form-label">Judul Meeting <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="meeting_title" name="meeting_title" placeholder="Masukkan judul meeting" required>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="time" class="form-label">Jam Mulai <span class="text-danger">*</span></label>
                                                <input type="time" id="time" name="time" class="form-control" required>
                                            </div>
                                            
                                            <div class="col-md-6 mb-3">
                                                <label for="end_time" class="form-label">Jam Selesai <span class="text-danger">*</span></label>
                                                <input type="time" id="end_time" name="end_time" class="form-control" required>
                                            </div>
                                        </div>

                                        <div class="mb-4">
                                            <label for="group_name" class="form-label">Instansi/Kelompok <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="group_name" name="group_name" placeholder="Masukkan instansi/kelompok" required>
                                        </div>

                                        <div class="text-center">
                                            <button type="submit" class="btn btn-primary btn-lg">
                                                <i class='bx bx-calendar-check me-2'></i>Proses Reservasi
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($ratingsAndReviews->isNotEmpty())
                        <div class="row justify-content-center">
                            <div class="col-lg-8">
                                <div class="reviews-card">
                                    <div class="card-header">
                                        <h4 class="mb-0">
                                            <i class='bx bx-star me-2'></i>Ulasan dan Rating
                                        </h4>
                                    </div>
                                    <div class="card-body">
                                        @foreach($ratingsAndReviews as $booking)
                                            <div class="review-item">
                                                <div class="review-header">
                                                    <div class="reviewer-info">
                                                        @if ($booking->user->profile_picture && Storage::exists('public/profile_pictures/' . $booking->user->profile_picture))
                                                            <img src="{{ asset('storage/profile_pictures/' . $booking->user->profile_picture) }}" alt="{{ $booking->user->name }}" class="reviewer-avatar">
                                                        @else
                                                            <img src="https://bootdey.com/img/Content/avatar/avatar1.png" alt="Default Profile Picture" class="reviewer-avatar">
                                                        @endif
                                                        <div>
                                                            <h6 class="reviewer-name">{{ $booking->user->name }}</h6>
                                                            <div class="rating-stars">@ratingStars($booking->ratings)</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="review-content">
                                                    <p>{{ $booking->reviews }}</p>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endsection

    @section('scripts')
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        {{-- <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script> --}}
        {{-- <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script> --}}
        {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.min.js"></script> --}}

        <script>
            // Fungsi untuk menampilkan pesan alert
            function showAlert(type, message) {
                let alertHtml = `<div class="alert alert-${type} alert-dismissible fade show mb-4" role="alert">
                    <i class='bx bx-${type === 'success' ? 'check' : 'error'}-circle me-2'></i>
                    ${message}
                    <button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button>
                </div>`;
                $('#alert-container').html(alertHtml);
                setTimeout(function() {
                    $('#alert-container').empty();
                }, 5000);
            }

            // Event delegation untuk tombol bookmark detail
            document.addEventListener('click', function(e) {
                const button = e.target.closest('.bookmark-btn-detail');
                if (button) {
                    e.preventDefault();
                    e.stopPropagation();
                    const facilityId = button.getAttribute('data-facility-id');
                    let isBookmarked = button.getAttribute('data-bookmarked') === 'true';
                    const csrfTokenMeta = document.querySelector('meta[name="csrf-token"]');
                    const csrfToken = csrfTokenMeta ? csrfTokenMeta.getAttribute('content') : '';
                    if (button.disabled) return;
                    button.disabled = true;
                    let url = isBookmarked ? `/user/unbookmark/${facilityId}` : `/facility/bookmark/${facilityId}`;
                    let method = isBookmarked ? 'DELETE' : 'POST';
                    fetch(url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: `_token=${encodeURIComponent(csrfToken)}&_method=${method}`
                    })
                    .then(response => {
                        const contentType = response.headers.get("content-type");
                        if (contentType && contentType.indexOf("application/json") !== -1) {
                            return response.json();
                        } else {
                            throw new Error('Server returned non-JSON response or an error occurred. Status: ' + response.status);
                        }
                    })
                    .then(data => {
                        if (data.status === 'success') {
                            if (isBookmarked) {
                                button.setAttribute('data-bookmarked', 'false');
                                button.innerHTML = '<i class="bx bx-bookmark"></i><span>Tambah Bookmark</span>';
                            } else {
                                button.setAttribute('data-bookmarked', 'true');
                                button.innerHTML = '<i class="bx bxs-bookmark"></i><span>Hapus Bookmark</span>';
                            }
                            showAlert('success', data.message);
                        } else {
                            showAlert('danger', data.message || 'Terjadi kesalahan.');
                        }
                    })
                    .catch(err => {
                        console.error("AJAX Error:", err);
                        showAlert('danger', 'Terjadi kesalahan saat memproses bookmark: ' + err.message);
                    })
                    .finally(() => {
                        button.disabled = false;
                    });
                }
            });
        </script>
    @endsection
</body>
</html>