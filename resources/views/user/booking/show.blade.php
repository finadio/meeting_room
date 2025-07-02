@extends('user.layouts.app')
@section('title', $facility->name)
@section('content')

    <div class="container mt-5">
        <h2 class="text-center mb-4">{{ $facility->name }}</h2>

        {{-- Pesan sukses dan error --}}
        <div id="alert-container">
            @if(session('success'))
                <div class="alert alert-success mt-3">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger mt-3">
                    {{ session('error') }}
                </div>
            @endif

            @if ($errors->has('date'))
                <div class="alert alert-danger">
                    {{ $errors->first('date') }}
                </div>
            @endif
        </div>


        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="facility-gallery">
                    @if ($facility->image_path)
                        <img src="{{ asset('storage/facility_images/' . basename($facility->image_path)) }}" alt="{{ $facility->name }}" class="facility-image rounded-4">
                    @else
                        <img src="{{ asset('img/img-1.jpg') }}" class="facility-image rounded-4" alt="{{ $facility->name }}">
                    @endif
                </div>
            </div>

            <div class="col-md-6 mb-4">
                <div class="card border-0 shadow">
                    <div class="card-body">
                        <p class="card-text">{{ $facility->description }}</p>
                        <p class="card-text"><strong>Location:</strong> {{ $facility->location }}</p>
                        <p class="card-text">
                            <strong>Opening Time:</strong> {{ \Carbon\Carbon::parse($facility->opening_time)->format('h:i A') }}
                        </p>
                        <p class="card-text">
                            <strong>Closing Time:</strong> {{ \Carbon\Carbon::parse($facility->closing_time)->format('h:i') }} PM
                        </p>
                        <p class="card-text"><strong>Contact Person:</strong> {{ $facility->contact_person }}</p>
                        <p class="card-text"><strong>Contact Email:</strong> {{ $facility->contact_email }}</p>
                        <p class="card-text"><strong>Contact Phone:</strong> {{ $facility->contact_phone }}</p>
                    </div>
                </div>
            </div>

            <div class="booking-form mt-5">
                <h3>Booking Ruang Meeting ini</h3>
                <form action="{{ route('user.booking.confirm', ['facilityId' => $facility->id]) }}" method="post">
                    @csrf
                    <div class="mb-3">
                        <label for="date">Pilih tanggal:</label>
                        <input type="date" id="date" name="date" class="form-control" required min="{{ date('Y-m-d') }}">
                    </div>

                    <div class="mb-3">
                        <label for="time">Jam mulai :</label>
                        <input type="time" id="time" name="time" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="end_time">Jam akhir :</label>
                        <input type="time" id="end_time" name="end_time" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="meeting_title">Judul Meeting</label>
                        <input type="text" class="form-control" id="meeting_title" name="meeting_title" placeholder="Masukkan judul meeting" required>
                    </div>

                    <div class="form-group">
                        <label for="group_name">Instansi/Kelompok</label>
                        <input type="text" class="form-control" id="group_name" name="group_name" placeholder="Masukkan instansi/kelompok" required>
                    </div>

                    <button type="submit" class="book-btn"><i class='bx bx-calendar'></i> Proses Booking</button>
                </form>
            </div>

            {{-- Tambahkan tombol bookmark di halaman show juga --}}
            @auth
                <div class="mt-3 d-flex align-items-center justify-content-center w-100"> {{-- Sesuaikan penempatan --}}
                    <button type="button"
                            class="btn btn-bookmark bookmark-btn"
                            data-facility-id="{{ $facility->id }}"
                            data-bookmarked="{{ auth()->user()->bookmarkedFacilities->contains($facility->id) ? 'true' : 'false' }}"
                            style="transition: transform 0.3s ease-in-out, color 0.3s ease-in-out;"
                            onmouseover="this.style.color='#FF5733'"
                            onmouseout="this.style.color=''; this.style.transform='';">
                        <i class='bx {{ auth()->user()->bookmarkedFacilities->contains($facility->id) ? 'bxs-bookmark' : 'bx-bookmark' }}'></i> Bookmark This Facility
                    </button>
                </div>
            @endauth

            <div class="col-md-12 mt-4">
                @if($ratingsAndReviews->isNotEmpty())
                    <div class="ratings-reviews mt-5">
                        <h3>Ratings and Reviews</h3>
                        @foreach($ratingsAndReviews as $booking)
                            <div class="card mb-3">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="profile-picture">
                                            @if ($booking->user->profile_picture && Storage::exists('public/profile_pictures/' . $booking->user->profile_picture))
                                                <img src="{{ asset('storage/profile_pictures/' . $booking->user->profile_picture) }}" alt="{{ $booking->user->name }}" class="rounded-circle">
                                            @else
                                                <img src="https://bootdey.com/img/Content/avatar/avatar1.png" alt="Default Profile Picture" class="rounded-circle">
                                            @endif
                                        </div>
                                        <div class="review-details ms-3">
                                            <h5 class="card-title">{{ $booking->user->name }}</h5>
                                            <div class="rating-stars">@ratingStars($booking->ratings)</div>
                                            <p class="card-text">{{ $booking->reviews }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p>No ratings and reviews available for this facility.</p>
                @endif
            </div>

        </div>
    </div>

@endsection

@section('scripts')
    {{-- Tambahkan jQuery jika belum ada di layouts/app.blade.php --}}
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script> {{-- Pastikan jQuery dimuat --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <script>
        // ... (Kode JavaScript yang ada seperti datepicker dan timepicker) ...

        // Fungsi untuk menampilkan pesan alert
        function showAlert(type, message) {
            let alertHtml = `<div class="alert alert-${type}">${message}</div>`;
            $('#alert-container').html(alertHtml);
            setTimeout(function() {
                $('#alert-container').empty();
            }, 5000); // 5 detik
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
                            showAlert('success', response.message);
                        } else {
                            button.find('i').removeClass('bx-bookmark').addClass('bxs-bookmark');
                            button.data('bookmarked', true);
                            showAlert('success', response.message);
                        }
                    } else if (response.status === 'warning') {
                        showAlert('warning', response.message);
                    } else {
                        showAlert('danger', response.message || 'Terjadi kesalahan.');
                    }
                },
                error: function(xhr, status, error) {
                    const errorMessage = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Terjadi kesalahan pada server.';
                    showAlert('danger', errorMessage);
                }
            });
        });
    </script>
@endsection

@section('styles')
    <style>
        /* ... (CSS yang sudah ada) ... */

        /* Tambahan styling untuk pesan alert AJAX */
        #alert-container {
            position: fixed;
            top: 70px; /* Di bawah navbar */
            left: 50%;
            transform: translateX(-50%);
            width: 80%;
            max-width: 500px;
            z-index: 1050; /* Pastikan di atas elemen lain */
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
@endsection