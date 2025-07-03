@extends('admin.layouts.admin_dashboard')

@section('title', 'Bookings')

@section('content')
    <div class="container-fluid dashboard-content">
        {{-- Header Halaman Seperti Facilities Management --}}
        <div class="page-header-management"> {{-- Kelas baru untuk header --}}
            <h2 class="page-title">Bookings Management</h2> {{-- Judul halaman --}}
            <a href="{{ route('admin.bookings.create') }}" class="btn btn-primary page-header-button"> {{-- Tombol Add Booking Baru --}}
                <i class='bx bx-plus'></i> Add Booking Baru
            </a>
        </div>

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

        {{-- Tabel Booking --}}
        <div class="card dashboard-card-item p-0 mt-4"> {{-- Gunakan dashboard-card-item dan p-0 --}}
            <div class="card-body p-0">
                @if($bookings->isEmpty())
                    <div class="alert alert-info text-center m-4">
                        No booking at this time.
                    </div>
                @else
                    <div class="table-responsive admin-table-container">
                        <table class="table table-hover admin-table"> {{-- Hapus table-bordered dan table-striped --}}
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
                                            <span class="badge badge-success admin-badge-status">{{ $booking->status }}</span>
                                        @elseif($booking->status == 'Menunggu Konfirmasi')
                                            <span class="badge badge-info admin-badge-status">{{ $booking->status }}</span>
                                        @elseif($booking->status == 'Ditolak')
                                            <span class="badge badge-danger admin-badge-status">{{ $booking->status }}</span>
                                        @else
                                            <span class="badge badge-secondary admin-badge-status">{{ $booking->status }}</span>
                                        @endif
                                    </td>
                                    <td class="admin-table-actions">
                                        <a href="{{ route('admin.bookings.show', $booking) }}" class="btn btn-sm btn-action-view" title="View">
                                            <i class='bx bx-show'></i>
                                        </a>

                                        @if($booking->is_checked_in == 0 && $booking->status == 'Disetujui')
                                            <form action="{{ route('admin.checkIn', $booking->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-action-checkin" title="Check In">Check In</button>
                                            </form>
                                        @endif

                                        @if($booking->is_checked_in == 1 && $booking->status != 'Selesai')
                                            <form action="{{ route('admin.checkOut', $booking->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-action-checkout" title="Check Out">Check Out</button>
                                            </form>
                                        @endif

                                        @if($timeLeft <= 10 && $booking->check_in && !$booking->check_out && !$booking->wa_sent)
                                            <form action="{{ route('admin.sendWhatsApp', $booking->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-action-whatsapp" title="Send WhatsApp">Send WA</button>
                                            </form>
                                        @endif
                                        
                                        {{-- Tombol Approve/Reject --}}
                                        @if($booking->status === 'Menunggu Konfirmasi')
                                            <form action="{{ route('admin.bookings.approve', $booking->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-action-approve" title="Approve">Approve</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer d-flex justify-content-center">
                        {{ $bookings->links('vendor.pagination.bootstrap-4') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection