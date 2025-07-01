@extends('admin.layouts.admin_dashboard')

@section('title', 'Booking Details')

@section('content')
    <div class="container mt-4">
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
        <h4 class="font-weight-bold mb-4">Booking Details</h4>

        <div class="card shadow">
            <div class="card-body">
                <h5 class="card-title">Data Pemesanan Ruangan </h5>
                <ul class="list-group">
                    
                    <li class="list-group-item">
                        <strong>Nama Pemesan :</strong> {{ $booking->user->name }}
                    </li>
                    <li class="list-group-item">
                        <strong>No. HP :</strong> {{ $booking->contact_number }}
                    </li>
                    <li class="list-group-item">
                        <strong>Instansi / Kelompok :</strong> {{ $booking->group_name }}
                    </li>
                    <li class="list-group-item">
                        <strong>Judul Meeting :</strong> {{ $booking->meeting_title  }}
                    </li>
                    <li class="list-group-item">
                        <strong>Ruang di booking :</strong> {{ $booking->facility->name }}
                    </li>
                    <li class="list-group-item">
                        <strong>Tanggal Booking :</strong> {{ \Carbon\Carbon::parse($booking->booking_date)->format('F j, Y') }}
                    </li>
                    <li class="list-group-item">
                        <strong>Jam mulai pakai :</strong> {{ \Carbon\Carbon::parse($booking->booking_time)->format('h:i a') }}
                    </li>
                    <li class="list-group-item">
                        <strong>Jam Akhir Pakai :</strong> {{ \Carbon\Carbon::parse($booking->booking_end)->format('h:i a') }}
                    </li>
                    <li class="list-group-item">
                        <strong>Durasi :</strong> {{ $duration->h }} jam {{ $duration->i }} menit
                    </li>
                    <li class="list-group-item">
                        <strong>Status :</strong> {{ $booking->status }}
                    </li>
                </ul>
            </div>
        </div>

        <div class="mt-4">
            <!-- <a href="{{ route('admin.bookings.index') }}" class="btn btn-primary">Back to Bookings</a> -->
            <a href="{{ route('admin.bookings.index') }}" class="btn btn-primary">Back</a>
        
            @if($booking->status === 'Menunggu Konfirmasi')
                <form action="{{ route('admin.bookings.approve', $booking->id) }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-success">Setujui</button>
                </form>
            @endif
        </div>
    </div>
@endsection

@section('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <style>
        .card {
            border-radius: 15px;
        }

        .list-group-item {
            border: none;
            padding: 0.75rem 1.25rem;
            border-radius: 0;
        }

        .btn-primary {
            background-color: #3498db;
            border-color: #3498db;
        }

        .btn-primary:hover {
            background-color: #2185d0;
            border-color: #2185d0;
        }
    </style>
@endsection
