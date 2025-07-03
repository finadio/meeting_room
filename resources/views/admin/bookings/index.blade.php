@extends('admin.layouts.admin_dashboard')

@section('title', 'Bookings')

@section('content')
    <div class="container-fluid dashboard-content">
        <h2 class="mb-4 dashboard-welcome-title">Bookings Management</h2>

        @if(session('success'))
            <div class="alert alert-success mt-4 animated fadeIn" role="alert">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger mt-4 animated fadeIn" role="alert">
                {{ session('error') }}
            </div>
        @endif

        {{-- BAGIAN SEARCH BAR BARU --}}
        <div class="row mb-4">
            <div class="col-md-6">
                <form action="{{ route('admin.bookings.index') }}" method="GET" class="dashboard-search-form">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Cari Booking (Nama User, Ruangan, Status, Judul Meeting, No. HP, Email...)" value="{{ request('search') }}">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="submit">
                                <i class='bx bx-search'></i> Cari
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-md-6 text-right">
                <a href="{{ route('admin.bookings.create') }}" class="btn btn-success">
                    <i class='bx bx-plus-medical'></i> Add Booking Baru
                </a>
            </div>
        </div>
        {{-- AKHIR BAGIAN SEARCH BAR BARU --}}

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Booking List</h6>
            </div>
            <div class="card-body">
                @if($bookings->isEmpty())
                    <div class="alert alert-info">
                        No booking at this time.
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="bookingsTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>S.N</th>
                                    <th>User Booking</th>
                                    <th>Facility Name</th>
                                    <th>Booking Date</th>
                                    <th>Booking Time</th>
                                    <th>Booking End</th>
                                    <th>Status</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($bookings as $booking)
                                @php
                                    $currentTime = now()->setTimezone('Asia/Jakarta');
                                    $bookingDate = $booking->booking_date->format('Y-m-d');
                                    $bookingEndTime = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $bookingDate . ' ' . $booking->booking_end);
                                    $timeLeft = $currentTime->diffInMinutes($bookingEndTime->setTimezone('Asia/Jakarta'));
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
                                            <span class="badge badge-success">{{ $booking->status }}</span>
                                        @elseif($booking->status == 'Menunggu Konfirmasi')
                                            <span class="badge badge-info">{{ $booking->status }}</span>
                                        @elseif($booking->status == 'Ditolak')
                                            <span class="badge badge-danger">{{ $booking->status }}</span>
                                        @else
                                            <span class="badge badge-secondary">{{ $booking->status }}</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.bookings.show', $booking) }}" class="btn btn-info btn-sm" title="View">
                                            <i class='bx bx-show'></i>
                                        </a>

                                        @if($booking->is_checked_in == 0 && $booking->status == 'Disetujui')
                                            <form action="{{ route('admin.checkIn', $booking->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-success btn-sm" title="Check In">Check In</button>
                                            </form>
                                        @endif

                                        @if($booking->is_checked_in == 1 && $booking->status != 'Selesai')
                                            <form action="{{ route('admin.checkOut', $booking->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-danger btn-sm" title="Check Out">Check Out</button>
                                            </form>
                                        @endif

                                        @if($timeLeft <= 10 && $booking->check_in && !$booking->check_out && !$booking->wa_sent)
                                            <form action="{{ route('admin.sendWhatsApp', $booking->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-warning btn-sm" title="Send WhatsApp">Send WA</button>
                                            </form>
                                        @endif
                                        
                                        {{-- Tombol Approve/Reject --}}
                                        @if($booking->status === 'Menunggu Konfirmasi')
                                            <form action="{{ route('admin.bookings.approve', $booking->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-primary btn-sm" title="Approve">Approve</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-center">
                        {{ $bookings->links('vendor.pagination.bootstrap-4') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection