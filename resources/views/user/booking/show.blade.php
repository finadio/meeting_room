@extends('user.layouts.app')
@section('title', $facility->name)
@section('content')

<div class="container mt-5 pt-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Header Section -->
            <div class="text-center mb-5">
                <h1 class="calendar-title" style="font-size:2.5rem;font-weight:700;color:#1E293B;margin-bottom:0.5rem;letter-spacing:-1px;text-transform:uppercase;">{{ $facility->name }}</h1>
                <p class="lead text-muted">Detail Ruang Meeting</p>
            </div>

            <!-- Alert Messages -->
            <div id="alert-container">
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

                @if ($errors->has('date'))
                    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                        <i class='bx bx-error-circle me-2'></i>
                        {{ $errors->first('date') }}
                        <button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
            </div>

            <!-- Facility Details -->
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
                            <form action="{{ route('user.facility.bookmark', ['facility' => $facility->id]) }}" method="post" class="bookmark-form-detail text-center mt-2 mb-3">
                                @csrf
                                <button type="submit" class="bookmark-btn-detail" aria-label="Bookmark {{ $facility->name }}">
                                    @if(auth()->user()->bookmarkedFacilities->contains($facility->id))
                                        <i class='bx bxs-bookmark'></i><span>Hapus Bookmark</span>
                                    @else
                                        <i class='bx bx-bookmark'></i><span>Tambah Bookmark</span>
                                    @endif
                                </button>
                            </form>
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

            <!-- Booking Form -->
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

            <!-- Reviews Section -->
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

<x-footer />
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/user_dashboard.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href='https://cdn.jsdelivr.net/npm/boxicons@2.1.0/css/boxicons.min.css' rel='stylesheet'>
    <style>
        /* Booking Detail Page Specific Styles */
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
        
        .btn-primary {
            background: linear-gradient(135deg, #23395d, #1E40AF);
            border: none;
            border-radius: 8px;
            padding: 0.75rem 2rem;
            font-weight: 600;
            transition: all 0.3s ease;
            color: white;
            box-shadow: 0 2px 4px rgba(35, 57, 93, 0.1);
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(35, 57, 93, 0.3);
            background: linear-gradient(135deg, #1E40AF, #1E3A8A);
            color: white;
        }
        
        .btn-primary:focus {
            box-shadow: 0 0 0 3px rgba(35, 57, 93, 0.1);
        }
        
        .bookmark-form-detail {
            display: flex;
            justify-content: center;
            align-items: center;
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
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
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

        // Tangani klik pada tombol bookmark
        $('.bookmark-btn').on('click', function() {
            const button = $(this);
            const facilityId = button.data('facility-id');
            let isBookmarked = button.data('bookmarked');
            const csrfToken = $('meta[name="csrf-token"]').attr('content');

            let url = '';
            let method = '';

            if (isBookmarked) {
                url = `/user/unbookmark/${facilityId}`;
                method = 'DELETE';
            } else {
                url = `/facility/bookmark/${facilityId}`;
                method = 'POST';
            }

            $.ajax({
                url: url,
                type: 'POST',
                data: {
                    _token: csrfToken,
                    _method: isBookmarked ? 'DELETE' : 'POST'
                },
                success: function(response) {
                    if (response.status === 'success') {
                        if (isBookmarked) {
                            button.find('i').removeClass('bxs-bookmark').addClass('bx-bookmark');
                            button.data('bookmarked', false);
                            button.html('<i class="bx bx-bookmark"></i><span>Hapus Bookmark</span>');
                            showAlert('success', response.message);
                        } else {
                            button.find('i').removeClass('bx-bookmark').addClass('bxs-bookmark');
                            button.data('bookmarked', true);
                            button.html('<i class="bxs-bookmark"></i><span>Tambah Bookmark</span>');
                            showAlert('success', response.message);
                        }
                    } else if (response.status === 'warning') {
                        showAlert('warning', response.message);
                    } else {
                        showAlert('danger', response.message || 'Terjadi kesalahan.');
                    }
                },
                error: function(xhr, status, error) {
                    showAlert('danger', 'Terjadi kesalahan saat memproses bookmark.');
                }
            });
        });
    </script>
@endsection