@extends('admin.layouts.admin_dashboard')
@section('title', 'Facility Details')

@section('content')
<div class="booking-page-wrapper">
    <div class="container-fluid px-4">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10">
                <div class="booking-card">
                    <div class="booking-header">
                        <div class="header-icon">
                            <i class="bx bx-building-house"></i>
                        </div>
                        <h3 class="header-title">Detail Fasilitas</h3>
                        <div class="header-decoration"></div>
                    </div>
                    
                    <div class="booking-content">
                        @if(session('success'))
                            <div class="custom-alert success-alert">
                                <div class="alert-icon">
                                    <i class="bx bx-check-circle"></i>
                                </div>
                                <div class="alert-content">
                                    <strong>Berhasil!</strong>
                                    <span>{{ session('success') }}</span>
                                </div>
                                <button type="button" class="alert-close" data-bs-dismiss="alert">
                                    <i class="bx bx-x"></i>
                                </button>
                            </div>
                        @endif

                        <div class="row mb-4">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <div class="image-display-wrapper">
                                    @if($facility->image_path && Storage::exists('public/facility_images/' . basename($facility->image_path)))
                                        <img src="{{ asset('storage/facility_images/' . basename($facility->image_path)) }}" 
                                             alt="{{ $facility->name }}" 
                                             class="img-fluid rounded facility-main-image">
                                    @else
                                        <img src="{{ asset('img/img-1.jpg') }}" 
                                             class="img-fluid rounded facility-main-image" 
                                             alt="{{ $facility->name }}">
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h5 class="facility-main-title mb-3">{{ $facility->name }}</h5>
                                
                                @if($facility->description)
                                    <div class="display-group-enhanced">
                                        <label class="form-label-enhanced">
                                            <div class="label-icon">
                                                <i class="bx bx-detail"></i>
                                            </div>
                                            <span>Deskripsi</span>
                                        </label>
                                        <p class="display-value-text">{{ $facility->description }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="detail-grid">
                            @if($facility->location)
                                <div class="display-group-enhanced">
                                    <label class="form-label-enhanced">
                                        <div class="label-icon">
                                            <i class="bx bx-map"></i>
                                        </div>
                                        <span>Lokasi</span>
                                    </label>
                                    <p class="display-value">{{ $facility->location }}</p>
                                </div>
                            @endif

                            @if(isset($facility->price_per_hour))
                                <div class="display-group-enhanced">
                                    <label class="form-label-enhanced">
                                        <div class="label-icon">
                                            <i class="bx bx-money"></i>
                                        </div>
                                        <span>Harga per Jam</span>
                                    </label>
                                    <p class="display-value">Rp. {{ number_format($facility->price_per_hour, 0, ',', '.') }}</p>
                                </div>
                            @endif

                            @if(isset($facility->type)) {{-- Changed from facility_type to type --}}
                                <div class="display-group-enhanced">
                                    <label class="form-label-enhanced">
                                        <div class="label-icon">
                                            <i class="bx bx-category"></i>
                                        </div>
                                        <span>Tipe Fasilitas</span>
                                    </label>
                                    <p class="display-value">
                                        <span class="badge badge-info-custom">{{ ucfirst(str_replace('_', ' ', $facility->type)) }}</span>
                                    </p>
                                </div>
                            @endif

                            @if($facility->opening_time)
                                <div class="display-group-enhanced">
                                    <label class="form-label-enhanced">
                                        <div class="label-icon">
                                            <i class="bx bx-time"></i>
                                        </div>
                                        <span>Waktu Buka</span>
                                    </label>
                                    <p class="display-value">{{ \Carbon\Carbon::parse($facility->opening_time)->format('h:i A') }}</p>
                                </div>
                            @endif

                            @if($facility->closing_time)
                                <div class="display-group-enhanced">
                                    <label class="form-label-enhanced">
                                        <div class="label-icon">
                                            <i class="bx bx-alarm"></i>
                                        </div>
                                        <span>Waktu Tutup</span>
                                    </label>
                                    <p class="display-value">{{ \Carbon\Carbon::parse($facility->closing_time)->format('h:i A') }}</p>
                                </div>
                            @endif

                            @if($facility->contact_person)
                                <div class="display-group-enhanced">
                                    <label class="form-label-enhanced">
                                        <div class="label-icon">
                                            <i class="bx bx-user"></i>
                                        </div>
                                        <span>Kontak Person</span>
                                    </label>
                                    <p class="display-value">{{ $facility->contact_person }}</p>
                                </div>
                            @endif

                            @if($facility->contact_email)
                                <div class="display-group-enhanced">
                                    <label class="form-label-enhanced">
                                        <div class="label-icon">
                                            <i class="bx bx-envelope"></i>
                                        </div>
                                        <span>Email Kontak</span>
                                    </label>
                                    <p class="display-value">
                                        <a href="mailto:{{ $facility->contact_email }}" class="display-link">{{ $facility->contact_email }}</a>
                                    </p>
                                </div>
                            @endif

                            @if($facility->contact_phone)
                                <div class="display-group-enhanced">
                                    <label class="form-label-enhanced">
                                        <div class="label-icon">
                                            <i class="bx bx-phone"></i>
                                        </div>
                                        <span>Telepon Kontak</span>
                                    </label>
                                    <p class="display-value">
                                        <a href="tel:{{ $facility->contact_phone }}" class="display-link">{{ $facility->contact_phone }}</a>
                                    </p>
                                </div>
                            @endif

                            @if(isset($facility->status))
                                <div class="display-group-enhanced">
                                    <label class="form-label-enhanced">
                                        <div class="label-icon">
                                            <i class="bx bx-check-circle"></i>
                                        </div>
                                        <span>Status</span>
                                    </label>
                                    <p class="display-value">
                                        @if($facility->status === 'approved')
                                            <span class="badge badge-success-custom">Aktif</span>
                                        @else
                                            <span class="badge badge-secondary-custom">Tidak Aktif</span>
                                        @endif
                                    </p>
                                </div>
                            @endif

                            @if($facility->created_at)
                                <div class="display-group-enhanced">
                                    <label class="form-label-enhanced">
                                        <div class="label-icon">
                                            <i class="bx bx-calendar"></i>
                                        </div>
                                        <span>Dibuat Pada</span>
                                    </label>
                                    <p class="display-value">{{ $facility->created_at->format('d F Y, H:i A') }}</p>
                                </div>
                            @endif
                        </div>

                        @if(isset($facility->map_coordinates) && $facility->map_coordinates)
                            <div class="sub-card mt-5">
                                <div class="sub-card-header">
                                    <i class="bx bx-map-pin"></i>
                                    <h5 class="sub-card-title">Lokasi di Peta</h5>
                                </div>
                                <div class="sub-card-body">
                                    <div id="map" style="height: 400px; border-radius: 8px;" class="map-container"></div>
                                </div>
                            </div>
                        @endif

                        <div class="form-section submit-section mt-5 pt-4 border-top">
                            <div class="button-group">
                                <a href="{{ route('admin.facilities.edit', $facility->id) }}" 
                                   class="btn-submit">
                                    <div class="btn-content">
                                        <i class="bx bx-edit"></i>
                                        <span>Edit Fasilitas</span>
                                    </div>
                                    <div class="btn-ripple"></div>
                                </a>
                                
                                @if(isset($facility->status) && $facility->status === 'approved')
                                    <button type="button" class="btn-action deactivate" 
                                            onclick="toggleStatus('{{ $facility->id }}', 'denied')">
                                        <div class="btn-content">
                                            <i class="bx bx-pause"></i>
                                            <span>Nonaktifkan</span>
                                        </div>
                                    </button>
                                @else
                                    <button type="button" class="btn-action activate" 
                                            onclick="toggleStatus('{{ $facility->id }}', 'approved')">
                                        <div class="btn-content">
                                            <i class="bx bx-play"></i>
                                            <span>Aktifkan</span>
                                        </div>
                                    </button>
                                @endif
                                
                                <button type="button" class="btn-back" onclick="window.history.back()">
                                    <div class="btn-content">
                                        <i class="bx bx-arrow-back"></i>
                                        <span>Kembali</span>
                                    </div>
                                    <div class="btn-ripple"></div>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmModalLabel">Konfirmasi Aksi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin mengubah status fasilitas ini?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="confirmAction">Konfirmasi</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <style>
        /* Page Wrapper */
        .booking-page-wrapper {
            min-height: 100vh;
            background: rgba(255, 255, 255, 0.95);
            padding: 40px 0;
            position: relative;
        }

        .booking-page-wrapper::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="%23ffffff" opacity="0.05"/><circle cx="75" cy="75" r="1" fill="%23ffffff" opacity="0.05"/><circle cx="50" cy="10" r="1" fill="%23ffffff" opacity="0.03"/><circle cx="10" cy="50" r="1" fill="%23ffffff" opacity="0.03"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            pointer-events: none;
        }

        /* Main Card */
        .booking-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
            overflow: hidden;
            position: relative;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .booking-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, #667eea, #764ba2, #f093fb, #f5576c);
            background-size: 400% 400%;
            animation: gradient-flow 3s ease infinite;
        }

        @keyframes gradient-flow {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }

        /* Header */
        .booking-header {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            padding: 30px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .booking-header::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
            animation: rotate 8s linear infinite;
        }

        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .header-icon {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            width: 70px;
            height: 70px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            position: relative;
            z-index: 2;
            box-shadow: 0 10px 25px rgba(79, 172, 254, 0.3);
        }

        .header-icon i {
            font-size: 2rem;
            color: white;
        }

        .header-title {
            color: white;
            font-size: 1.8rem;
            font-weight: 700;
            margin: 0;
            position: relative;
            z-index: 2;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .header-decoration {
            width: 60px;
            height: 4px;
            background: linear-gradient(90deg, #4facfe, #00f2fe);
            margin: 15px auto 0;
            border-radius: 2px;
            position: relative;
            z-index: 2;
        }

        /* Content */
        .booking-content {
            padding: 40px;
        }

        /* Alerts */
        .custom-alert {
            display: flex;
            align-items: center;
            padding: 20px;
            border-radius: 16px;
            margin-bottom: 30px;
            position: relative;
            overflow: hidden;
            border: none;
            animation: slideInDown 0.5s ease-out;
        }

        @keyframes slideInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .success-alert {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            color: white;
        }

        .danger-alert {
            background: linear-gradient(135deg, #ff416c 0%, #ff4b2b 100%);
            color: white;
        }

        .alert-icon {
            font-size: 1.5rem;
            margin-right: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
        }

        .alert-content {
            flex: 1;
        }

        .alert-content strong {
            display: block;
            font-size: 1rem;
            margin-bottom: 2px;
        }

        .alert-close {
            background: none;
            border: none;
            color: white;
            font-size: 1.2rem;
            cursor: pointer;
            padding: 5px;
            border-radius: 50%;
            transition: background-color 0.3s ease;
        }

        .alert-close:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        /* Facility Image Display */
        .image-display-wrapper {
            background: #f8fafc;
            border: 2px solid #e2e8f0;
            border-radius: 16px;
            padding: 15px;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%; /* Ensure it takes full height of column */
        }

        .facility-main-image {
            max-width: 100%;
            max-height: 250px; /* Limit height to keep aspect ratio on larger images */
            object-fit: contain; /* Use contain to show entire image */
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .facility-main-title {
            font-size: 1.6rem;
            font-weight: 700;
            color: #1e3c72;
            margin-bottom: 20px;
        }

        /* Detail Grid for facility info */
        .detail-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            margin-top: 30px; /* Space from description */
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
        }

        .display-group-enhanced {
            position: relative;
            margin-bottom: 0; /* Handled by grid gap */
        }

        .form-label-enhanced {
            display: flex;
            align-items: center;
            margin-bottom: 12px;
            font-weight: 600;
            color: #2d3748;
            font-size: 0.95rem;
        }

        .label-icon {
            width: 24px;
            height: 24px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 10px;
            box-shadow: 0 2px 4px rgba(102, 126, 234, 0.3);
        }

        .label-icon i {
            color: white;
            font-size: 0.9rem;
        }

        .display-value, .display-value-text {
            padding: 16px 20px;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 1rem;
            background: #f8fafc;
            color: #4a5568;
            margin-bottom: 0;
            word-wrap: break-word;
        }

        .display-value-text {
            line-height: 1.6;
        }

        .display-link {
            color: #4299e1;
            text-decoration: none;
            transition: color 0.2s ease;
        }
        .display-link:hover {
            color: #2b6cb0;
            text-decoration: underline;
        }

        /* Badges */
        .badge-info-custom {
            background-color: #4facfe;
            color: white;
            padding: 6px 10px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.8em;
        }
        .badge-success-custom {
            background-color: #38ef7d;
            color: white;
            padding: 6px 10px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.8em;
        }
        .badge-secondary-custom {
            background-color: #6c757d;
            color: white;
            padding: 6px 10px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.8em;
        }

        /* Sub-card for Map */
        .sub-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            margin-top: 30px;
        }

        .sub-card-header {
            background: linear-gradient(135deg, #e0e7ff 0%, #c3dafe 100%);
            padding: 20px 30px;
            display: flex;
            align-items: center;
            gap: 15px;
            border-bottom: 2px solid #a7b7ff;
        }

        .sub-card-header i {
            font-size: 1.5rem;
            color: #1e3c72;
            background: rgba(30, 60, 114, 0.1);
            padding: 8px;
            border-radius: 8px;
        }

        .sub-card-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: #1e3c72;
            margin: 0;
        }

        .sub-card-body {
            padding: 30px;
        }

        .map-container {
            border: 1px solid #e2e8f0; /* Add border consistent with other inputs */
            border-radius: 12px;
            overflow: hidden; /* Ensures map content respects border-radius */
        }

        /* Action Buttons */
        .form-section.submit-section {
            text-align: center;
            margin-top: 40px;
        }

        .button-group {
            display: flex;
            gap: 15px;
            justify-content: center;
            align-items: center;
            flex-wrap: wrap; /* Allow wrapping on small screens */
        }

        .btn-submit {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 16px;
            padding: 18px 40px;
            color: white;
            font-weight: 600;
            font-size: 1.1rem;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
            min-width: 180px; /* Adjusted minimum width */
            text-decoration: none; /* For anchor tag */
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-submit:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(102, 126, 234, 0.4);
            color: white; /* Ensure text color remains white on hover */
            text-decoration: none;
        }

        .btn-submit:active {
            transform: translateY(-1px);
        }

        /* btn-action for Activate/Deactivate */
        .btn-action {
            border: none;
            border-radius: 16px;
            padding: 18px 40px;
            color: white;
            font-weight: 600;
            font-size: 1.1rem;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1); /* General shadow for actions */
            min-width: 180px; /* Adjusted minimum width */
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-action:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(0,0,0,0.2);
            color: white;
            text-decoration: none;
        }
        
        .btn-action.activate {
            background: linear-gradient(135deg, #38ef7d 0%, #11998e 100%);
            box-shadow: 0 8px 25px rgba(56, 239, 125, 0.3);
        }

        .btn-action.deactivate {
            background: linear-gradient(135deg, #ff416c 0%, #ff4b2b 100%);
            box-shadow: 0 8px 25px rgba(255, 65, 108, 0.3);
        }

        .btn-back {
            background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%);
            border: none;
            border-radius: 16px;
            padding: 18px 40px;
            color: white;
            font-weight: 600;
            font-size: 1.1rem;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
            box-shadow: 0 8px 25px rgba(108, 117, 125, 0.3);
            min-width: 180px; /* Adjusted minimum width */
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-back:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(108, 117, 125, 0.4);
            color: white;
            text-decoration: none;
        }

        .btn-back:active {
            transform: translateY(-1px);
        }

        .btn-content {
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            z-index: 2;
        }

        .btn-content i {
            margin-right: 10px;
            font-size: 1.2rem;
        }

        .btn-ripple {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            transform: translate(-50%, -50%);
            transition: width 0.3s ease, height 0.3s ease;
        }

        .btn-submit:active .btn-ripple,
        .btn-back:active .btn-ripple {
            width: 300px;
            height: 300px;
        }

        /* Modal Overrides */
        #confirmModal .modal-content {
            border-radius: 16px;
            overflow: hidden;
            border: none;
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
        }

        #confirmModal .modal-header {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            color: white;
            border-bottom: none;
            padding: 20px;
        }

        #confirmModal .modal-title {
            font-weight: 700;
            font-size: 1.25rem;
        }

        #confirmModal .modal-header .close {
            color: white;
            opacity: 0.8;
        }

        #confirmModal .modal-body {
            padding: 30px;
            color: #4a5568;
            font-size: 1rem;
        }

        #confirmModal .modal-footer {
            border-top: none;
            padding: 20px;
            background-color: #f8fafc;
            justify-content: center;
            gap: 15px;
        }

        #confirmModal .modal-footer .btn {
            border-radius: 12px;
            padding: 10px 25px;
            font-weight: 600;
        }

        #confirmModal .modal-footer .btn-secondary {
            background-color: #cbd5e0;
            border-color: #cbd5e0;
            color: #2d3748;
        }
        #confirmModal .modal-footer .btn-secondary:hover {
            background-color: #a0aec0;
            border-color: #a0aec0;
        }

        #confirmModal .modal-footer .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
        }
        #confirmModal .modal-footer .btn-primary:hover {
            filter: brightness(1.1);
        }


        /* Responsive Design */
        @media (max-width: 768px) {
            .booking-page-wrapper {
                padding: 20px 0;
            }

            .booking-content {
                padding: 30px 20px;
            }

            .booking-header {
                padding: 25px 20px;
            }

            .header-title {
                font-size: 1.5rem;
            }

            .header-icon {
                width: 60px;
                height: 60px;
            }

            .header-icon i {
                font-size: 1.5rem;
            }

            .detail-grid {
                grid-template-columns: 1fr;
            }

            .facility-main-image {
                max-height: 200px;
            }

            .btn-submit,
            .btn-action,
            .btn-back {
                padding: 16px 30px;
                min-width: 100%;
            }

            .button-group {
                flex-direction: column;
                gap: 10px;
            }
        }

        @media (max-width: 576px) {
            .container-fluid {
                padding-left: 15px;
                padding-right: 15px;
            }

            .booking-content {
                padding: 25px 15px;
            }
        }
    </style>
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script>
        function goBack() {
            window.history.back();
        }

        function toggleStatus(facilityId, newStatus) {
            console.log('toggleStatus called', facilityId, newStatus); // DEBUG
            $('#confirmModal').modal('show');
            
            $('#confirmAction').off('click').on('click', function() {
                // Create a form and submit it
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/facilities/${facilityId}/toggle-status`; // Assuming this route exists or will be created
                
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                
                const methodField = document.createElement('input');
                methodField.type = 'hidden';
                methodField.name = '_method';
                methodField.value = 'PATCH'; // Use PATCH for partial updates
                
                const statusField = document.createElement('input');
                statusField.type = 'hidden';
                statusField.name = 'status';
                statusField.value = newStatus;
                
                form.appendChild(csrfToken);
                form.appendChild(methodField);
                form.appendChild(statusField);
                document.body.appendChild(form);
                form.submit();
                
                $('#confirmModal').modal('hide');
            });
        }

        @if(isset($facility->map_coordinates) && $facility->map_coordinates)
        document.addEventListener('DOMContentLoaded', function () {
            try {
                // Safely parse map_coordinates string
                var coordinatesString = "{{ $facility->map_coordinates }}";
                var coordinates = coordinatesString.split(',').map(Number);
                
                // Validate coordinates
                if (coordinates.length !== 2 || isNaN(coordinates[0]) || isNaN(coordinates[1])) {
                    console.error('Invalid coordinates provided:', coordinatesString);
                    document.getElementById('map').innerHTML = '<div class="alert alert-warning">Invalid map coordinates.</div>';
                    return;
                }
                
                var map = L.map('map').setView(coordinates, 15);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                    maxZoom: 19
                }).addTo(map);

                var marker = L.marker(coordinates).addTo(map);
                
                // Add popup to marker
                marker.bindPopup(`
                    <div>
                        <strong>{{ $facility->name }}</strong><br>
                        {{ $facility->location ?? 'No location specified' }}
                    </div>
                `).openPopup();
                
            } catch (error) {
                console.error('Error initializing map:', error);
                document.getElementById('map').innerHTML = '<div class="alert alert-danger">Error loading map.</div>';
            }
        });
        @endif
    </script>
@endsection