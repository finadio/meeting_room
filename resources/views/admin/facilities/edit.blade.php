@extends('admin.layouts.admin_dashboard')
@section('title', 'Edit Facility')

@section('content')
<div class="booking-page-wrapper">
    <div class="container-fluid px-4">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10">
                <div class="booking-card">
                    <div class="booking-header">
                        <div class="header-icon">
                            <i class="bx bx-edit"></i>
                        </div>
                        <h3 class="header-title">Edit Informasi Fasilitas</h3>
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

                        @if(session('error'))
                            <div class="custom-alert danger-alert">
                                <div class="alert-icon">
                                    <i class="bx bx-error-circle"></i>
                                </div>
                                <div class="alert-content">
                                    <strong>Error!</strong>
                                    <span>{{ session('error') }}</span>
                                </div>
                                <button type="button" class="alert-close" data-bs-dismiss="alert">
                                    <i class="bx bx-x"></i>
                                </button>
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="custom-alert danger-alert">
                                <div class="alert-icon">
                                    <i class="bx bx-error-circle"></i>
                                </div>
                                <div class="alert-content">
                                    <strong>Error!</strong>
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                                <button type="button" class="alert-close" data-bs-dismiss="alert">
                                    <i class="bx bx-x"></i>
                                </button>
                            </div>
                        @endif

                        <form action="{{ route('admin.facilities.update', $facility->id) }}" method="post" enctype="multipart/form-data" class="booking-form">
                            @csrf
                            @method('PUT')

                            <div class="form-section">
                                <div class="form-group-enhanced">
                                    <label for="name" class="form-label-enhanced">
                                        <div class="label-icon">
                                            <i class="bx bx-building"></i>
                                        </div>
                                        <span>Nama Fasilitas</span>
                                        <span class="required-indicator">*</span>
                                    </label>
                                    <div class="input-wrapper">
                                        <input type="text" id="name" name="name" class="form-control-enhanced" 
                                               value="{{ old('name', $facility->name) }}" placeholder="Masukkan nama fasilitas" required>
                                        <div class="input-border"></div>
                                    </div>
                                </div>

                                <div class="form-group-enhanced">
                                    <label for="type" class="form-label-enhanced">
                                        <div class="label-icon">
                                            <i class="bx bx-category"></i>
                                        </div>
                                        <span>Tipe Fasilitas</span>
                                    </label>
                                    <div class="input-wrapper">
                                        {{-- Diubah dari <select> menjadi <input type="text"> --}}
                                        <input type="text" id="type" name="type" class="form-control-enhanced" 
                                               value="{{ old('type', $facility->type) }}" placeholder="contoh: ruang rapat" required>
                                        <div class="input-border"></div>
                                    </div>
                                </div>
                                
                                <div class="form-group-enhanced">
                                    <label for="description" class="form-label-enhanced">
                                        <div class="label-icon">
                                            <i class="bx bx-detail"></i>
                                        </div>
                                        <span>Deskripsi</span>
                                    </label>
                                    <div class="input-wrapper">
                                        <textarea id="description" name="description" class="form-control-enhanced" 
                                                  rows="4" placeholder="Deskripsi fasilitas">{{ old('description', $facility->description) }}</textarea>
                                        <div class="input-border"></div>
                                    </div>
                                </div>

                                <div class="form-group-enhanced">
                                    <label for="location" class="form-label-enhanced">
                                        <div class="label-icon">
                                            <i class="bx bx-map"></i>
                                        </div>
                                        <span>Lokasi</span>
                                    </label>
                                    <div class="input-wrapper">
                                        <input type="text" id="location" name="location" class="form-control-enhanced" 
                                               value="{{ old('location', $facility->location) }}" placeholder="Masukkan lokasi fasilitas">
                                        <div class="input-border"></div>
                                    </div>
                                </div>

                                <div class="form-group-enhanced">
                                    <label for="price_per_hour" class="form-label-enhanced">
                                        <div class="label-icon">
                                            <i class="bx bx-dollar"></i>
                                        </div>
                                        <span>Harga per Jam</span>
                                        <span class="required-indicator">*</span>
                                    </label>
                                    <div class="input-wrapper">
                                        <input type="number" name="price_per_hour" id="price_per_hour" class="form-control-enhanced" 
                                               value="{{ old('price_per_hour', $facility->price_per_hour) }}" placeholder="Masukkan harga per jam (contoh: 50000)" required>
                                        <div class="input-border"></div>
                                    </div>
                                </div>

                                <div class="form-group-enhanced">
                                    <label for="image" class="form-label-enhanced">
                                        <div class="label-icon">
                                            <i class="bx bx-image"></i>
                                        </div>
                                        <span>Gambar Fasilitas</span>
                                    </label>
                                    <div class="input-wrapper">
                                        <input type="file" id="image" name="image" class="form-control-enhanced" 
                                               accept="image/*">
                                        <div class="input-border"></div>
                                    </div>
                                    @if($facility->image_path)
                                        <div class="current-image-preview mt-3">
                                            <p class="mb-2">Gambar Saat Ini:</p>
                                            <img src="{{ asset('storage/facility_images/' . basename($facility->image_path)) }}" 
                                                 alt="Current facility image" class="img-fluid rounded-lg" style="max-height: 100px;">
                                            <small class="form-text text-muted mt-2">Biarkan kosong untuk mempertahankan gambar saat ini.</small>
                                        </div>
                                    @endif
                                </div>

                                <div class="form-group-enhanced">
                                    <label for="opening_time" class="form-label-enhanced">
                                        <div class="label-icon">
                                            <i class="bx bx-time"></i>
                                        </div>
                                        <span>Waktu Buka</span>
                                    </label>
                                    <div class="input-wrapper">
                                        <input type="time" id="opening_time" name="opening_time" 
                                               class="form-control-enhanced" 
                                               value="{{ old('opening_time', $facility->opening_time) }}">
                                        <div class="input-border"></div>
                                    </div>
                                </div>

                                <div class="form-group-enhanced">
                                    <label for="closing_time" class="form-label-enhanced">
                                        <div class="label-icon">
                                            <i class="bx bx-alarm"></i>
                                        </div>
                                        <span>Waktu Tutup</span>
                                    </label>
                                    <div class="input-wrapper">
                                        <input type="time" id="closing_time" name="closing_time" 
                                               class="form-control-enhanced" 
                                               value="{{ old('closing_time', $facility->closing_time) }}">
                                        <div class="input-border"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-section">
                                <div class="section-header">
                                    <i class="bx bx-user-circle"></i>
                                    <h5>Informasi Kontak</h5>
                                </div>
                                <div class="form-group-enhanced">
                                    <label for="contact_person" class="form-label-enhanced">
                                        <div class="label-icon">
                                            <i class="bx bx-user"></i>
                                        </div>
                                        <span>Kontak Person</span>
                                    </label>
                                    <div class="input-wrapper">
                                        <input type="text" id="contact_person" name="contact_person" 
                                               class="form-control-enhanced" 
                                               value="{{ old('contact_person', $facility->contact_person) }}" placeholder="Nama kontak person">
                                        <div class="input-border"></div>
                                    </div>
                                </div>

                                <div class="form-group-enhanced">
                                    <label for="contact_email" class="form-label-enhanced">
                                        <div class="label-icon">
                                            <i class="bx bx-envelope"></i>
                                        </div>
                                        <span>Email Kontak</span>
                                    </label>
                                    <div class="input-wrapper">
                                        <input type="email" id="contact_email" name="contact_email" 
                                               class="form-control-enhanced" 
                                               value="{{ old('contact_email', $facility->contact_email) }}" placeholder="Email kontak">
                                        <div class="input-border"></div>
                                    </div>
                                </div>

                                <div class="form-group-enhanced">
                                    <label for="contact_phone" class="form-label-enhanced">
                                        <div class="label-icon">
                                            <i class="bx bx-phone"></i>
                                        </div>
                                        <span>Telepon Kontak</span>
                                    </label>
                                    <div class="input-wrapper">
                                        <input type="tel" id="contact_phone" name="contact_phone" 
                                               class="form-control-enhanced" 
                                               value="{{ old('contact_phone', $facility->contact_phone) }}" placeholder="Nomor telepon kontak">
                                        <div class="input-border"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-section submit-section">
                                <div class="button-group">
                                    <button type="submit" class="btn-submit">
                                        <div class="btn-content">
                                            <i class="bx bx-save"></i>
                                            <span>Perbarui Fasilitas</span>
                                        </div>
                                        <div class="btn-ripple"></div>
                                    </button>
                                    <button type="button" class="btn-back" onclick="window.history.back()">
                                        <div class="btn-content">
                                            <i class="bx bx-arrow-back"></i>
                                            <span>Kembali</span>
                                        </div>
                                        <div class="btn-ripple"></div>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
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

    /* Form Sections */
    .form-section {
        margin-bottom: 30px;
    }

    .section-header {
        display: flex;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 12px;
        border-bottom: 2px solid #e5e7eb;
    }

    .section-header i {
        font-size: 1.3rem;
        color: #1e3c72;
        margin-right: 10px;
        background: rgba(30, 58, 138, 0.1);
        padding: 8px;
        border-radius: 8px;
    }

    .section-header h5 {
        color: #1e3c72;
        font-weight: 600;
        margin: 0;
        font-size: 1.1rem;
    }

    .form-group-enhanced {
        position: relative;
        margin-bottom: 20px; /* Added margin for spacing between inputs */
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

    .required-indicator {
        color: #e53e3e;
        margin-left: 4px;
        font-weight: 700;
    }

    .input-wrapper {
        position: relative;
    }

    .form-control-enhanced {
        width: 100%;
        padding: 16px 20px;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        font-size: 1rem;
        background: white;
        transition: all 0.3s ease;
        outline: none;
        position: relative;
        z-index: 1;
    }

    .form-control-enhanced:focus {
        border-color: #4facfe;
        box-shadow: 0 0 0 4px rgba(79, 172, 254, 0.1);
        background: white;
    }

    .form-control-enhanced::placeholder {
        color: #a0aec0;
        font-weight: 400;
    }

    .input-border {
        position: absolute;
        bottom: 0;
        left: 50%;
        width: 0;
        height: 2px;
        background: linear-gradient(90deg, #4facfe, #00f2fe);
        transition: all 0.3s ease;
        border-radius: 1px;
        transform: translateX(-50%);
    }

    .form-control-enhanced:focus + .input-border {
        width: 100%;
    }

    /* Current Profile Picture Display */
    .current-image-preview {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-top: 20px;
        padding: 15px;
        background-color: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
    }

    .current-image-preview p {
        margin-bottom: 0;
        font-weight: 500;
        color: #4a5568;
    }

    .current-image-preview img {
        max-width: 80px;
        height: auto;
        border: 2px solid #cbd5e0;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    /* Submit Section */
    .submit-section {
        text-align: center;
        margin-top: 40px;
    }

    .button-group {
        display: flex;
        gap: 15px;
        justify-content: center;
        align-items: center;
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
        min-width: 200px;
    }

    .btn-submit:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 35px rgba(102, 126, 234, 0.4);
    }

    .btn-submit:active {
        transform: translateY(-1px);
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
        min-width: 200px;
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

    /* Animation */
    .booking-form {
        animation: fadeInUp 0.6s ease-out;
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

        .form-control-enhanced {
            padding: 14px 16px;
        }

        .btn-submit,
        .btn-back {
            padding: 16px 30px;
            min-width: 150px;
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

        .btn-submit,
        .btn-back {
            width: 100%;
        }
    }
</style>
@endsection

@section('scripts')
<script>
    // Alert close functionality
    document.querySelectorAll('.alert-close').forEach(button => {
        button.addEventListener('click', function() {
            this.closest('.custom-alert').style.display = 'none';
        });
    });
</script>
@endsection