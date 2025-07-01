@extends('user.layouts.app')
@section('title', 'Booking')
@section('content')

    <div class="container mt-5">
        <h2 class="text-center mb-4">Pilih Ruang Meeting yang tersedia</h2>

        {{-- Pesan sukses dan error --}}
        <div id="alert-container">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
        </div>


        <form action="{{ route('user.booking.search') }}" method="GET" class="mb-4">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Pilih Ruang Meeting yang tersedia" name="search">
                <div class="input-group-append">
                    <label class="input-group-text" for="sort_by">Sort by:</label>
                    <button class="search-btn" type="submit"><i class='bx bx-search'></i></button>
                </div>
            </div>
        </form>

        <div class="row">
            @forelse($facilities as $facility)
                <div class="col-md-4 mb-4">
                    <div class="card border-5 shadow-sm">
                        <a href="{{ route('user.booking.show', ['facilityId' => $facility->id]) }}">
                            <div class="position-relative">
                                @if($facility->image_path)
                                    <img src="{{ asset('storage/facility_images/' . basename($facility->image_path)) }}" class="card-img-top rounded-4 img-fluid" alt="{{ $facility->name }}">
                                @else
                                    <div class=" text-light text-center">
                                        <img src="{{ asset('img/img-1.jpg') }}" class="card-img-top rounded-4 img-fluid" alt="{{ $facility->name }}">
                                    </div>
                                @endif
                            </div>
                        </a>

                        <div class="card-body">
                            <h5 class="card-title mb-3">{{ $facility->name }}</h5>
                            <p>Location: {{ $facility->location }}</p>

                            <div class="mt-3 d-flex align-items-center justify-content-between">
                                <a href="{{ route('user.booking.show', ['facilityId' => $facility->id]) }}" class="book-btn">
                                    <i class='bx bx-calendar'></i> Book Now
                                </a>

                                @auth
                                    {{-- Mengubah form menjadi button dengan data-attributes untuk AJAX --}}
                                    <button type="button"
                                            class="btn btn-bookmark bookmark-btn" {{-- Tambah kelas 'bookmark-btn' untuk JS --}}
                                            data-facility-id="{{ $facility->id }}"
                                            data-bookmarked="{{ auth()->user()->bookmarkedFacilities->contains($facility->id) ? 'true' : 'false' }}"
                                            style="transition: transform 0.3s ease-in-out, color 0.3s ease-in-out;"
                                            onmouseover="this.style.color='#FF5733'"
                                            onmouseout="this.style.color=''; this.style.transform='';">
                                        <i class='bx {{ auth()->user()->bookmarkedFacilities->contains($facility->id) ? 'bxs-bookmark' : 'bx-bookmark' }}'></i>
                                    </button>
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-md-12">
                    <div class="alert alert-info text-center">
                        Tidak ada ruang meeting tersedia saat ini.
                        <br>
                        <a href="{{ url()->previous() }}" class="search-btn">
                            <i class="bx bx-arrow-back"></i> Back
                        </a>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
@endsection

@section('scripts')
    {{-- Tambahkan jQuery jika belum ada di layouts/app.blade.php --}}
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script> {{-- Pastikan jQuery dimuat --}}

    <script>
        $(document).ready(function() {
            // Fungsi untuk menampilkan pesan alert
            function showAlert(type, message) {
                let alertHtml = `<div class="alert alert-${type}">${message}</div>`;
                $('#alert-container').html(alertHtml);
                // Sembunyikan alert setelah beberapa detik
                setTimeout(function() {
                    $('#alert-container').empty();
                }, 5000); // 5 detik
            }

            // Tangani klik pada tombol bookmark
            $('.bookmark-btn').on('click', function() {
                const button = $(this);
                const facilityId = button.data('facility-id');
                let isBookmarked = button.data('bookmarked');
                const csrfToken = $('meta[name="csrf-token"]').attr('content'); // Ambil CSRF token

                let url = '';
                let method = '';

                if (isBookmarked) {
                    // Jika sudah di-bookmark, berarti akan melakukan unbookmark
                    url = `/user/unbookmark/${facilityId}`;
                    method = 'DELETE'; // Metode HTTP DELETE
                } else {
                    // Jika belum di-bookmark, berarti akan melakukan bookmark
                    url = `/facility/bookmark/${facilityId}`;
                    method = 'POST'; // Metode HTTP POST
                }

                $.ajax({
                    url: url,
                    type: 'POST', // Kirim sebagai POST meskipun method DELETE, Laravel akan menangani _method
                    data: {
                        _token: csrfToken,
                        _method: isBookmarked ? 'DELETE' : 'POST' // Laravel method spoofing
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            // Toggle ikon dan data-bookmarked
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fullcalendar/core/main.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href='https://cdn.jsdelivr.net/npm/boxicons@2.1.0/css/boxicons.min.css' rel='stylesheet'>
@endsection