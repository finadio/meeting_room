@extends('admin.layouts.admin_dashboard')

@section('title', 'Bookings')

@section('content')
    <div class="container">
        <h4 class="font-weight-bold py-3 mb-4">Bookings</h4>
        <!-- Tombol + Add Booking Baru -->
        <a href="{{ route('admin.bookings.create') }}" class="btn btn-primary mb-4">
            + Add Booking Baru
        </a>
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if($bookings->isEmpty())
            <div class="alert alert-danger">
                No booking at this time.
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                    <tr>
                        <th>S.N</th>
                        <th>User Booking</th>
                        <th>Facility Name</th>
                        <th>Booking Date</th>
                        <th>Booking Time</th>
                        <th>Booking End</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($bookings as $booking)
                        @php
                            $currentTime = now()->setTimezone('Asia/Jakarta');
                            $bookingDate = $booking->booking_date->format('Y-m-d');
                            $bookingEndTime = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $bookingDate . ' ' . $booking->booking_end);
                            $timeLeft = $currentTime->diffInMinutes($bookingEndTime->setTimezone('Asia/Jakarta'));


                            // Output Debugging
                            // dd([
                            //    'Current Time (Jakarta)' => $currentTime,
                            //    'Booking End Time (Jakarta)' => $bookingEndTime,
                            //    'Time Left in Minutes' => $timeLeft
                            //]);
                            $status = '';
                            if ($booking->check_out) {
                                $status = 'Selesai';
                            } elseif (!$booking->check_out && $bookingEndTime->isPast()) {
                                $status = 'Extend';
                            } elseif ($booking->check_in && $currentTime->lessThanOrEqualTo($bookingEndTime)) {
                                $status = 'On Progress';
                            } else {
                                $status = 'On Schedule';
                            }
                        @endphp
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $booking->user->name }}</td>
                            <td>{{ $booking->facility->name }}</td>
                            <td>{{ \Carbon\Carbon::parse($booking->booking_date)->format('F j, Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($booking->booking_time)->format('h:i a') }}</td>
                            <td>{{ \Carbon\Carbon::parse($booking->booking_end)->format('h:i a') }}</td>
                            <td>
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
                            <td>
                                <a href="{{ route('admin.bookings.show', $booking) }}" class="btn btn-info btn-sm" title="View">
                                    <i class='bx bx-show'></i>
                                </a>

                                <!-- Tombol Check In -->
                                @if($booking->is_checked_in == 0 && $booking->status == 'Disetujui')
                                    <form action="{{ route('admin.checkIn', $booking->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-success">Check In</button>
                                    </form>
                                @endif

                                <!-- Tombol Check Out -->
                                @if($booking->is_checked_in == 1 && $booking->status != 'Selesai')
                                    <form action="{{ route('admin.checkOut', $booking->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-danger">Check Out</button>
                                    </form>
                                @endif

                                <!-- Debugging output -->
                                 <!--  Tampilkan sisa waktu untuk debug  -->
                                <!-- <td>{{ $timeLeft }} minutes left</td> 
                                <td>{{ \Carbon\Carbon::parse($booking->booking_end)->toDateTimeString() }}</td>
                                <td>{{ \Carbon\Carbon::parse($booking->booking_end)->setTimezone('Asia/Jakarta')->toDateTimeString() }}</td> -->

                                <!-- Tombol Kirim WhatsApp -->
                                @if($timeLeft <= 10 && $booking->check_in && !$booking->check_out && !$booking->wa_sent)
                                    <form action="{{ route('admin.sendWhatsApp', $booking->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-warning">Send WA</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                {{ $bookings->links('vendor.pagination.bootstrap-4') }}
            </div>
        @endif
    </div>
   
@endsection

@section('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <style>
        .btn-primary {
            background-color: #3C91E6;
            border-color: #3C91E6;
            color: white;
        }

        .img-fluid {
            max-width: 100px;
            height: auto;
        }
    </style>
@endsection

@section('scripts')
    <script>
        console.log("Refresh script loaded"); // Log untuk memastikan script dijalankan pertama kali

        // Refresh halaman setiap 5 menit (300000 milidetik)
        setTimeout(function() {
            console.log("Refreshing the page"); // Log untuk melihat kapan halaman direfresh
            window.location.reload();
        }, 300000);
    </script>
@endsection
