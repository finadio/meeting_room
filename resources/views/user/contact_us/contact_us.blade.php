@extends('user.layouts.app')
@section('title', 'Contact Us')
@section('content')
<div class="container mt-5 pt-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Header Section -->
            <div class="text-center mb-5">
                <h1 class="display-4 font-weight-bold text-primary mb-3">Contact Us</h1>
                <p class="lead text-muted">Bank Madani Sejahtera Abadi (Bank MSA)</p>
            </div>

            <!-- Alert Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    <i class='bx bx-check-circle me-2'></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                    <i class='bx bx-error-circle me-2'></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Contact Form -->
            <div class="row justify-content-center mb-5">
                <div class="col-lg-8">
                    <div class="contact-form-card">
                        <div class="card-header">
                            <h4 class="mb-0">
                                <i class='bx bx-message-square-dots me-2'></i>Kirim Pesan
                            </h4>
                        </div>
                        <div class="card-body">
                            <form id="contactForm" action="{{ route('contact.submit') }}" method="post">
                                @csrf
                                <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="name" class="form-label">Nama <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                               id="name" name="name" required placeholder="Nama Anda"
                                               value="{{ old('name') }}">
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                               id="email" name="email" required placeholder="Email Anda"
                                               value="{{ old('email') }}">
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="subject" class="form-label">Subjek <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('subject') is-invalid @enderror" 
                                           id="subject" name="subject" required placeholder="Subjek pesan"
                                           value="{{ old('subject') }}">
                                    @error('subject')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="message" class="form-label">Pesan <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('message') is-invalid @enderror" 
                                              id="message" name="message" rows="5" required 
                                              placeholder="Tulis pesan Anda di sini...">{{ old('message') }}</textarea>
                                    @error('message')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary">
                                        <i class='bx bx-send me-2'></i>Kirim Pesan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Services Section -->
            <div class="row mb-5">
                <div class="col-12">
                    <div class="services-card">
                        <div class="card-header">
                            <h4 class="mb-0">
                                <i class='bx bx-star me-2'></i>Program Unggulan
                            </h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <div class="service-item">
                                        <div class="service-icon">
                                            <i class='bx bx-group'></i>
                                        </div>
                                        <h5>Sobat Mashaka</h5>
                                        <p>Program agen penggerak ekonomi</p>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="service-item">
                                        <div class="service-icon">
                                            <i class='bx bx-wallet'></i>
                                        </div>
                                        <h5>MSA Prime</h5>
                                        <p>Produk tabungan utama</p>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="service-item">
                                        <div class="service-icon">
                                            <i class='bx bx-credit-card'></i>
                                        </div>
                                        <h5>Kredit</h5>
                                        <p>Modal Kerja, Investasi, dll.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Map Section -->
            <div class="row mb-5">
                <div class="col-12">
                    <div class="map-card">
                        <div class="card-header">
                            <h4 class="mb-0">
                                <i class='bx bx-map-pin me-2'></i>Lokasi Kami
                            </h4>
                        </div>
                        <div class="card-body p-0">
                            <iframe 
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d114187.17942643426!2d87.6365509580823!3d26.57316242938736!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x39e59aeb2ac5d359%3A0x8a740efe290d8ed2!2sGauradaha%2057200!5e0!3m2!1sen!2snp!4v1706867196049!5m2!1sen!2snp" 
                                width="100%" 
                                height="400" 
                                style="border: 0;" 
                                allowfullscreen="" 
                                loading="lazy" 
                                referrerpolicy="no-referrer-when-downgrade">
                            </iframe>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Info Section -->
            <div class="row">
                <div class="col-12">
                    <div class="info-card">
                        <div class="card-header">
                            <h4 class="mb-0">
                                <i class='bx bx-info-circle me-2'></i>Informasi Penting
                            </h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="info-item">
                                        <h6><i class='bx bx-info-circle me-2'></i>Perubahan Status</h6>
                                        <p>Sesuai UU Nomor 4 Tahun 2023, Bank Perkreditan Rakyat (BPR) telah berganti nama menjadi Bank Perekonomian Rakyat.</p>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="info-item">
                                        <h6><i class='bx bx-globe me-2'></i>Layanan Digital</h6>
                                        <p>Bank MSA juga hadir di <strong>BliBli.com</strong> untuk layanan marketplace dan menyediakan simulasi kredit di website resmi.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<x-footer />

<script>
    function clearForm() {
        document.getElementById("contactForm").reset();
    }

    document.addEventListener('DOMContentLoaded', function() {
        @if(session('success'))
        clearForm();
        @endif
    });
</script>
@endsection

@section('styles')
<style>
    /* Kembalikan CSS konten, tapi jangan override footer */
    .contact-form-card,
    .services-card,
    .map-card,
    .info-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        border: 1px solid #e2e8f0;
        overflow: hidden;
    }
    .card-header {
        background: linear-gradient(135deg, #23395d, #1E40AF);
        color: white;
        padding: 1.5rem;
        border-bottom: none;
    }
    .card-header h4 {
        margin: 0;
        font-weight: 600;
        font-size: 1.1rem;
    }
    .card-body {
        padding: 2rem;
    }
    .service-item {
        text-align: center;
        padding: 1.5rem;
        background: #f8fafc;
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        transition: all 0.3s ease;
        height: 100%;
    }
    .service-item:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        border-color: #F59E0B;
    }
    .service-icon {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, #23395d, #1E40AF);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem auto;
        transition: all 0.3s ease;
    }
    .service-item:hover .service-icon {
        background: linear-gradient(135deg, #F59E0B, #D97706);
        transform: scale(1.1);
    }
    .service-icon i {
        font-size: 24px;
        color: white;
    }
    .service-item h5 {
        color: #1e293b;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }
    .service-item p {
        color: #64748b;
        font-size: 0.9rem;
        margin: 0;
    }
    .info-item {
        padding: 1.5rem;
        background: #f8fafc;
        border-radius: 8px;
        border-left: 4px solid #23395d;
        height: 100%;
    }
    .info-item h6 {
        color: #1e293b;
        font-weight: 600;
        margin-bottom: 0.75rem;
    }
    .info-item p {
        color: #64748b;
        margin: 0;
        font-size: 0.9rem;
        line-height: 1.5;
    }
    .form-control {
        border: 2px solid #e2e8f0;
        border-radius: 8px;
        padding: 0.75rem 1rem;
        transition: all 0.3s ease;
        background: #f8fafc;
    }
    .form-control:focus {
        border-color: #23395d;
        box-shadow: 0 0 0 3px rgba(35, 57, 93, 0.1);
        background: white;
    }
    .form-control.is-invalid {
        border-color: #dc3545;
    }
    .btn-primary {
        background: linear-gradient(135deg, #23395d, #1E40AF);
        border: none;
        border-radius: 8px;
        padding: 0.75rem 2rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(35, 57, 93, 0.3);
        background: linear-gradient(135deg, #1E40AF, #1E3A8A);
    }
    .alert {
        border-radius: 8px;
        border: none;
    }
    .alert-success {
        background: #d4edda;
        color: #155724;
        border-left: 4px solid #28a745;
    }
    .alert-danger {
        background: #f8d7da;
        color: #721c24;
        border-left: 4px solid #dc3545;
    }
    .display-4 {
        font-weight: 700;
        color: #1E293B !important;
    }
    .lead {
        font-size: 1.1rem;
    }
    .text-primary {
        color: #1E293B !important;
    }
    
    /* Tambahan selector yang lebih spesifik untuk judul Contact Us */
    h1.display-4.text-primary {
        color: #1E293B !important;
    }
    
    .display-4.text-primary {
        color: #1E293B !important;
    }
    .text-muted {
        color: #64748b !important;
    }
    @media (max-width: 768px) {
        .container {
            padding: 0 15px;
        }
        .card-body {
            padding: 1.5rem;
        }
    }
</style>
@endsection
