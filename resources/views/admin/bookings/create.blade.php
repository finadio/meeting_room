@extends('admin.layouts.admin_dashboard')

@section('content')
 
<div class="container">
    <h2>Buat Booking Ruang</h2>
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
    <form action="{{ route('admin.bookings.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="facility_id">Fasilitas</label>
            <select name="facility_id" id="facility_id" class="form-control" required>
                @foreach($facilities as $facility)
                    <option value="{{ $facility->id }}">{{ $facility->name }}</option>
                @endforeach
            </select>
        </div>
        
        <div class="form-group">
            <label for="date">Tanggal Booking</label>
            <input type="date" name="date" id="date" class="form-control" required>
        </div>
        
        <div class="form-group">
            <label for="booking_time">Jam Mulai</label>
            <input type="time" name="booking_time" id="booking_time" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="booking_end">Jam Selesai</label>
            <input type="time" name="booking_end" id="booking_end" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="meeting_title">Judul Meeting</label>
            <input type="text" name="meeting_title" id="meeting_title" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="group_name">Kelompok</label>
            <input type="text" name="group_name" id="group_name" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Buat Booking</button>
    </form>
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