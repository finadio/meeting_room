@extends('user.layouts.app')

@section('title', 'My Bookings')
    <link rel="icon" href="{{ asset('bookmeet.png') }}" type="image/x-icon">

    <!-- Favicon untuk layar retina (misalnya 32x32, 64x64) -->
    <link rel="icon" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" sizes="64x64" href="{{ asset('favicon-64x64.png') }}">

    <!-- Ikon untuk aplikasi web (Web App) -->
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">
@section('content')
    <div class="container mt-5">
        <div class="text-center mb-4">
            <h2>My Bookings</h2>
        </div>
       
        <div class="card border-0 shadow">
            {{-- Pesan sukses dan error yang tampil di luar kolom action --}}
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card-body">
                @if ($bookings->isEmpty())
                    <div class="alert alert-info">
                        No bookings found.
                        <a href="{{ route('user.booking.index') }}" class="alert-link">Book ruang meeting Now</a>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>S.N</th>
                                <th>Ruang Meeting</th>
                                <th>Tanggal Booking</th>
                                <th>Jam mulai</th>
                                <th>Jam akhir</th>
                                <th>Status</th>
                                <th>Rating</th>
                                <th>Review</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($bookings as $booking)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <div>
                                            <strong>{{ $booking->facility->name }}</strong>
                                        </div>
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($booking->booking_date)->format('F j, Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($booking->booking_time)->format('h:i a') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($booking->booking_end)->format('h:i a') }}</td>
                                    <td style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                        @if($booking->status == 'Disetujui')
                                            <span class="badge bg-success text-white">{{ $booking->status }}</span>
                                        @elseif($booking->status == 'Menunggu Konfirmasi')
                                            <span class="badge bg-info text-white">{{ $booking->status }}</span>
                                        @elseif($booking->status == 'Ditolak')
                                            <span class="badge bg-danger text-white">{{ $booking->status }}</span>
                                        @else
                                            <span class="badge bg-secondary text-white">{{ $booking->status }}</span>
                                        @endif
                                    </td>
                                    <td>@ratingStars($booking->ratings)</td>
                                    <td>{{ $booking->reviews }}</td>
                                    <td>
                                        {{-- Tombol Check In --}}
                                        @if($booking->is_checked_in == 0 && $booking->status == 'Disetujui' && (!$booking->check_out || !$booking->previous_booking))
                                            <form action="{{ route('checkIn', $booking->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-success">Check In</button>
                                            </form>
                                        @endif

                                        {{-- Tombol Check Out --}}
                                        @if($booking->is_checked_in == 1)
                                            <form action="{{ route('checkOut', $booking->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-danger">Check Out</button>
                                            </form>
                                        @endif

                                        {{-- Form permintaan extend waktu --}}
                                        @php
                                            $endTime = \Carbon\Carbon::parse($booking->booking_end);
                                            $currentTime = \Carbon\Carbon::now();
                                            $timeDifference = $endTime->diffInMinutes($currentTime, false); // Use false to get signed difference

                                            $nextBooking = \App\Models\Booking::where('facility_id', $booking->facility_id)
                                                ->where('booking_date', $booking->booking_date)
                                                ->where('booking_time', '>', $booking->booking_end)
                                                ->orderBy('booking_time', 'asc')
                                                ->first();

                                            $canExtend = ($timeDifference >= -10 && $timeDifference < 0) && !$booking->check_out && !$nextBooking;
                                        @endphp

                                        @if ($canExtend)
                                            <form action="{{ route('user.requestExtend', $booking->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-warning">Request Extend</button>
                                            </form>
                                        @endif

                                        {{-- Ratings and Reviews Form --}}
                                        @if ($booking->status == 'Selesai' && !$booking->hasReviews())
                                            <form action="{{ route('user.bookings.storeReview', $booking->id) }}" method="POST" class="mt-2">
                                                @csrf
                                                <div class="form-group">
                                                    <label for="rating_{{ $booking->id }}">Rating:</label>
                                                    <select name="rating" id="rating_{{ $booking->id }}" class="form-control" required>
                                                        <option value="">Select Rating</option>
                                                        <option value="1">1 Star</option>
                                                        <option value="2">2 Stars</option>
                                                        <option value="3">3 Stars</option>
                                                        <option value="4">4 Stars</option>
                                                        <option value="5">5 Stars</option>
                                                    </select>
                                                    @error('rating')
                                                        <div class="alert alert-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="form-group">
                                                    <label for="review_{{ $booking->id }}">Review:</label>
                                                    <textarea name="review" id="review_{{ $booking->id }}" class="form-control"></textarea>
                                                    @error('review')
                                                        <div class="alert alert-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <button type="submit" class="btn btn-primary">Submit Review</button>
                                            </form>
                                        @elseif ($booking->status == 'Selesai' && $booking->hasReviews())
                                            <span class="badge bg-success text-white">Reviewed</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{ $bookings->links('vendor.pagination.bootstrap-4') }}
                @endif
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <style>
        .table-responsive {
            overflow-x: auto;
        }
        
        .custom-star {
            color: yellow;
            font-size: 20px;
        }

        .card-body {
            overflow-x: auto;
        }

        .table td, .table th {
            white-space: nowrap;
            max-width: 200px;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Media query untuk responsif */
        @media (max-width: 768px) {
            .table td, .table th {
                font-size: 12px;
            }
            .table {
                font-size: 10px;
            }
            .card-body {
                padding: 10px;
            }
            .btn {
                padding: 5px 10px;
            }
        }
 

    </style>
@endsection
