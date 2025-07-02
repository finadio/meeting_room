@extends('user.layouts.app')
@section('title', 'Contact Us')

@section('content')
<div class="container mt-5 pt-4">
    <div class="row">
        <div class="col-md-10 offset-md-1">

            <!-- Header -->
            <div class="text-center mb-5">
                <h1 class="display-5 fw-bold text-primary mb-3">Hubungi Kami</h1>
                <p class="lead text-muted">Rajut dan Jalin #KolaborasiDalamHarmoni Bersama BPR MSA!</p>
                <p class="text-muted">#AndaTidakSendiri</p>
            </div>

            <!-- Alert -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class='bx bx-check-circle me-2'></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class='bx bx-error-circle me-2'></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Contact Info -->
            <div class="row mb-5">
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="contact-card card border-0 shadow-lg h-100">
                        <div class="card-body text-center p-4">
                            <i class='bx bx-map text-primary fs-2 mb-3'></i>
                            <h5 class="card-title fw-bold">Alamat</h5>
                            <p class="card-text text-muted">Jalan C. Simanjuntak No. 26<br>Kota Yogyakarta 55223</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="contact-card card border-0 shadow-lg h-100">
                        <div class="card-body text-center p-4">
                            <i class='bx bx-phone text-success fs-2 mb-3'></i>
                            <h5 class="card-title fw-bold">Telepon</h5>
                            <p><a href="tel:0274549400" class="text-decoration-none text-dark">0274-549400</a></p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="contact-card card border-0 shadow-lg h-100">
                        <div class="card-body text-center p-4">
                            <i class='bx bxl-whatsapp text-success fs-2 mb-3'></i>
                            <h5 class="card-title fw-bold">WhatsApp</h5>
                            <p><a href="https://wa.me/6285172024202" class="text-decoration-none text-dark" target="_blank">0851-7202-4202</a></p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="contact-card card border-0 shadow-lg h-100">
                        <div class="card-body text-center p-4">
                            <i class='bx bx-envelope text-primary fs-2 mb-3'></i>
                            <h5 class="card-title fw-bold">Email</h5>
                            <p><a href="mailto:bprmadani@gmail.com" class="text-decoration-none text-dark">bprmadani@gmail.com</a></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Jam Operasional -->
            <div class="row mb-5">
                <div class="col-md-6 offset-md-3">
                    <div class="card border-0 shadow-lg text-center">
                        <div class="card-body p-4">
                            <i class='bx bx-time text-warning fs-2 mb-2'></i>
                            <h5 class="card-title fw-bold">Jam Operasional</h5>
                            <p class="text-muted mb-0">Senin–Jumat: 08.00–17.00</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Kontak -->
            <div class="row">
                <div class="col-md-8 offset-md-2">
                    <div class="card border-0 shadow-lg">
                        <div class="card-body p-5">
                            <h3 class="text-center mb-4 fw-bold text-primary">Kirim Pesan</h3>
                            <form id="contactForm" action="{{ route('contact.submit') }}" method="POST">
                                @csrf
                                <input type="hidden" name="user_id" value="{{ auth()->id() }}">

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="name" class="form-label fw-bold">Nama <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control form-control-lg @error('name') is-invalid @enderror" id="name" name="name" placeholder="Nama Anda" required value="{{ old('name') }}">
                                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="email" class="form-label fw-bold">Email <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control form-control-lg @error('email') is-invalid @enderror" id="email" name="email" placeholder="Email Anda" required value="{{ old('email') }}">
                                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="subject" class="form-label fw-bold">Subjek <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-control-lg @error('subject') is-invalid @enderror" id="subject" name="subject" placeholder="Subjek Pesan" required value="{{ old('subject') }}">
                                    @error('subject')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="mb-4">
                                    <label for="message" class="form-label fw-bold">Pesan <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('message') is-invalid @enderror" id="message" name="message" rows="6" placeholder="Tulis pesan Anda..." required>{{ old('message') }}</textarea>
                                    @error('message')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="text-center">
                                    <button type="submit" class="btn-msa btn-lg px-5">
                                        <i class='bx bx-send me-2'></i>Kirim Pesan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sosial Media -->
            <div class="row mt-5 mb-4">
                <div class="col-md-12 text-center">
                    <h5 class="fw-bold mb-3">Ikuti Kami</h5>
                    <div class="social-links mb-4">
                        <a href="#" class="social-link me-3"><i class='bx bxl-facebook-circle'></i></a>
                        <a href="#" class="social-link me-3"><i class='bx bxl-instagram-alt'></i></a>
                        <a href="#" class="social-link me-3"><i class='bx bxl-twitter'></i></a>
                        <a href="#" class="social-link me-3"><i class='bx bxl-youtube'></i></a>
                        <a href="#" class="social-link"><i class='bx bxl-tiktok'></i></a>
                    </div>
                    <div class="disclaimer">
                        <p class="small text-muted mb-2"><strong>Bank MSA</strong> berizin & diawasi oleh <strong>OJK</strong>, serta peserta penjaminan <strong>LPS</strong>.</p>
                        <p class="small text-muted">#KolaborasiDalamHarmoni</p>
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

    document.addEventListener('DOMContentLoaded', function () {
        @if(session('success'))
        clearForm();
        @endif
    });
</script>
@endsection

@section('styles')
<style>
    .btn-msa {
        background: linear-gradient(135deg, #2563eb 0%, #3b82f6 100%);
        color: white;
        border: none;
        padding: 12px 30px;
        border-radius: 50px;
        font-weight: 600;
        font-size: 1.1rem;
        transition: 0.3s;
    }

    .btn-msa:hover {
        background: linear-gradient(135deg, #f59e0b 0%, #fbbf24 100%);
        color: white;
        transform: translateY(-2px);
    }

    .contact-card {
        border-radius: 15px;
        background: white;
        transition: 0.3s;
    }

    .contact-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 25px rgba(0,0,0,0.15);
    }

    .social-link {
        width: 50px;
        height: 50px;
        background: #2563eb;
        color: white;
        border-radius: 50%;
        display: inline-flex;
        justify-content: center;
        align-items: center;
        font-size: 1.5rem;
        transition: 0.3s;
    }

    .social-link:hover {
        background: #f59e0b;
        color: white;
        transform: scale(1.1);
    }
</style>
@endsection
