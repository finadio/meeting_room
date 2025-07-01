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

                                        {{-- Form permintaan extend waktu hanya tampil jika tidak ada error dan waktu sudah kurang dari 10 menit --}}
                                        @php
                                            // Pastikan $booking->booking_end adalah objek Carbon
                                            $endTime = \Carbon\Carbon::parse($booking->booking_end);
                                            $currentTime = \Carbon\Carbon::now();

                                            // Hitung selisih waktu antara booking_end dengan waktu sekarang
                                            $timeDifference = $endTime->diffInMinutes($currentTime);
                                            \Log::info('Booking end: ' . $booking->booking_end . ' - Time difference: ' . $timeDifference);

                                            // Cari booking berikutnya dalam ruangan yang sama dan hari yang sama
                                            $nextBooking = \App\Models\Booking::where('facility_id', $booking->facility_id)
                                                ->where('booking_date', $booking->booking_date)
                                                ->where('booking_time', '>', $booking->booking_end)
                                                ->orderBy('booking_time', 'asc')
                                                ->first();

                                            // Jika ada booking berikutnya
                                            if ($nextBooking) {
                                                $nextBookingTime = \Carbon\Carbon::parse($nextBooking->booking_time);
                                                $timeUntilNextBooking = $endTime->diffInMinutes($nextBookingTime);  // Perhitungan waktu hingga booking berikutnya
                                                \Log::info('Next booking time: ' . $nextBooking->booking_time . ' - Time until next booking: ' . $timeUntilNextBooking);
                                            } else {
                                                $timeUntilNextBooking = null;
                                            }

                                            // Periksa apakah waktu kurang dari 10 menit dan belum check-out
                                            $isTimeToExtend = $timeDifference <= 10 && !$booking->check_out ;

                                            \Log::info('Is time to extend: ' . ($isTimeToExtend ? 'Yes' : 'No'));
                                        @endphp

                                        {{-- Tombol extend hanya tampil jika tidak ada error, tidak ada booking berikutnya dan waktu sudah kurang dari 10 menit --}}
                                        @if (!session('error') && $isTimeToExtend)
                                            <form action="{{ route('user.requestExtend', $booking->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-warning">Request Extend</button>
                                            </form>
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
