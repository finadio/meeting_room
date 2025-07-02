@extends('admin.layouts.admin_dashboard')

@section('content')
<div class="container-fluid px-4">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">
            <div class="card shadow-lg border-0 rounded-lg">
                <div class="card-header bg-gradient-primary text-white">
                    <h3 class="mb-0 text-center">
                        <i class="bx bx-calendar-plus me-2"></i>
                        Buat Booking Ruang
                    </h3>
                </div>
                
                <div class="card-body p-4">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bx bx-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    
                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bx bx-error-circle me-2"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('admin.bookings.store') }}" method="POST" class="booking-form">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="facility_id" class="form-label">
                                    <i class="bx bx-building me-1"></i>
                                    Fasilitas
                                </label>
                                <select name="facility_id" id="facility_id" class="form-select custom-select" required>
                                    <option value="">-- Pilih Fasilitas --</option>
                                    @foreach($facilities as $facility)
                                        <option value="{{ $facility->id }}">{{ $facility->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="date" class="form-label">
                                    <i class="bx bx-calendar me-1"></i>
                                    Tanggal Booking
                                </label>
                                <input type="date" name="date" id="date" class="form-control custom-input" required>
                            </div>
                            
                            <div class="col-md-3 mb-3">
                                <label for="booking_time" class="form-label">
                                    <i class="bx bx-time me-1"></i>
                                    Jam Mulai
                                </label>
                                <input type="time" name="booking_time" id="booking_time" class="form-control custom-input" required>
                            </div>
                            
                            <div class="col-md-3 mb-3">
                                <label for="booking_end" class="form-label">
                                    <i class="bx bx-time-five me-1"></i>
                                    Jam Selesai
                                </label>
                                <input type="time" name="booking_end" id="booking_end" class="form-control custom-input" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="meeting_title" class="form-label">
                                    <i class="bx bx-notepad me-1"></i>
                                    Judul Meeting
                                </label>
                                <input type="text" name="meeting_title" id="meeting_title" class="form-control custom-input" placeholder="Masukkan judul meeting" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-4">
                                <label for="group_name" class="form-label">
                                    <i class="bx bx-group me-1"></i>
                                    Kelompok
                                </label>
                                <input type="text" name="group_name" id="group_name" class="form-control custom-input" placeholder="Masukkan nama kelompok" required>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg custom-btn">
                                <i class="bx bx-save me-2"></i>
                                Buat Booking
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
<style>
    .bg-gradient-primary {
        background: linear-gradient(135deg, #3C91E6 0%, #2d7dd2 100%);
        border-radius: 0.375rem 0.375rem 0 0 !important;
    }
    
    .card {
        border-radius: 0.5rem;
        transition: all 0.3s ease;
    }
    
    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(60, 145, 230, 0.15) !important;
    }
    
    .custom-input, .custom-select {
        border: 2px solid #e3e6f0;
        border-radius: 0.5rem;
        padding: 12px 15px;
        font-size: 14px;
        transition: all 0.3s ease;
        background-color: #fff;
    }
    
    .custom-input:focus, .custom-select:focus {
        border-color: #3C91E6;
        box-shadow: 0 0 0 0.2rem rgba(60, 145, 230, 0.25);
        background-color: #fff;
    }
    
    .form-label {
        font-weight: 600;
        color: #5a5c69;
        margin-bottom: 8px;
        font-size: 14px;
    }
    
    .custom-btn {
        background: linear-gradient(135deg, #3C91E6 0%, #2d7dd2 100%);
        border: none;
        border-radius: 0.5rem;
        padding: 12px 30px;
        font-weight: 600;
        font-size: 16px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(60, 145, 230, 0.3);
    }
    
    .custom-btn:hover {
        background: linear-gradient(135deg, #2d7dd2 0%, #1e6bb8 100%);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(60, 145, 230, 0.4);
    }
    
    .alert {
        border-radius: 0.5rem;
        border: none;
        padding: 15px 20px;
        margin-bottom: 25px;
        font-weight: 500;
    }
    
    .alert-success {
        background: linear-gradient(135deg, #1cc88a 0%, #17a673 100%);
        color: white;
    }
    
    .alert-danger {
        background: linear-gradient(135deg, #e74a3b 0%, #c0392b 100%);
        color: white;
    }
    
    .booking-form {
        animation: fadeInUp 0.5s ease-out;
    }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .container-fluid {
        padding-top: 20px;
        padding-bottom: 20px;
    }
    
    .img-fluid {
        max-width: 100px;
        height: auto;
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .container-fluid {
            padding-left: 15px;
            padding-right: 15px;
        }
        
        .card-body {
            padding: 20px !important;
        }
        
        .custom-input, .custom-select {
            padding: 10px 12px;
        }
    }
</style>
@endsection