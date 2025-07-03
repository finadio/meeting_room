@extends('user.layouts.app')
@section('title', 'Contact Us')

@section('content')
<div class="contactus-hero-bg py-5">
    <div class="container">
        <div class="contactus-card mx-auto position-relative">
            <div class="row g-0 align-items-stretch flex-lg-nowrap flex-wrap">
                <!-- Kiri: Info Kontak -->
                <div class="col-lg-6 p-5 d-flex flex-column justify-content-center position-relative">
                    <h2 class="contactus-title mb-2">Hubungi Kami</h2>
                    <span class="badge badge-support mb-3">24/7 Support</span>
                    <div class="contactus-desc mb-3">Ada pertanyaan atau butuh bantuan?<br>Tim kami siap membantu Anda dalam melakukan reservasi ruang meeting premium.</div>
                    <div class="mb-3 d-flex align-items-center">
                        <div class="contactus-icon me-2"><i class='bx bx-map'></i></div>
                        <div><b>Alamat:</b> Jl. Sukses No.123, Yogyakarta</div>
                    </div>
                    <div class="mb-3 d-flex align-items-center">
                        <div class="contactus-icon me-2"><i class='bx bx-phone'></i></div>
                        <div><b>Telepon:</b> (0274) 123–456</div>
                    </div>
                    <div class="mb-3 d-flex align-items-center">
                        <div class="contactus-icon me-2"><i class='bx bx-envelope'></i></div>
                        <div><b>Email:</b> support@meetingroom.id</div>
                    </div>
                    <div class="mb-3 d-flex align-items-center">
                        <div class="contactus-icon me-2"><i class='bx bx-time'></i></div>
                        <div><b>Jam Operasional:</b> 08.00 – 22.00 WIB</div>
                    </div>
                    <div class="contactus-social-label mb-1 mt-3" style="font-size:0.98rem;color:#64748B;font-weight:500;">Media Sosial Kami</div>
                    <div class="contactus-social mt-2">
                        <a href="#" class="me-2"><i class='bx bxl-instagram'></i></a>
                        <a href="#" class="me-2"><i class='bx bxl-facebook'></i></a>
                        <a href="#" class="me-2"><i class='bx bxl-twitter'></i></a>
                        <a href="#"><i class='bx bxl-whatsapp'></i></a>
                    </div>
                </div>
                <!-- Divider Vertikal -->
                <div class="d-none d-lg-block col-auto px-0">
                    <div class="contactus-divider h-100 mx-2"></div>
                </div>
                <!-- Kanan: Form & Maskot -->
                <div class="col-lg-6 p-5 position-relative d-flex flex-column justify-content-center">
                    <form class="contactus-form bg-white rounded-4 p-4 shadow-sm position-relative z-2">
                        <div class="mb-4">
                            <label for="name" class="form-label">Nama Anda</label>
                            <input type="text" class="form-control rounded-3" id="name" name="name" placeholder="Nama Anda">
                        </div>
                        <div class="mb-4">
                            <label for="email" class="form-label">Email Anda</label>
                            <input type="email" class="form-control rounded-3" id="email" name="email" placeholder="Email Anda">
                        </div>
                        <div class="mb-4">
                            <label for="message" class="form-label">Pesan Anda</label>
                            <textarea class="form-control rounded-3" id="message" name="message" rows="4" placeholder="Pesan Anda"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary rounded-3 px-5 py-3 fw-semibold contactus-btn-big">Kirim</button>
                    </form>
                    <img src="/img/shaka_utama.png" alt="Maskot Mashaka" class="contactus-mashaka-logo-inside position-absolute animated-mashaka">
                </div>
            </div>
        </div>
    </div>
</div>
@section('styles')
<style>
    .contactus-hero-bg {
        background: linear-gradient(135deg, #f4f6fa 60%, #e3eafc 100%);
        min-height: 100vh;
    }
    .contactus-card {
        background: #fff;
        border-radius: 32px;
        box-shadow: 0 8px 32px rgba(30,64,175,0.10);
        max-width: 1100px;
        overflow: hidden;
        border: 1.2px solid #e2e8f0;
        margin-top: 40px;
        margin-bottom: 40px;
        padding: 0 0.5rem;
    }
    .row.g-0.align-items-stretch.flex-lg-nowrap.flex-wrap {
        gap: 0;
    }
    .col-lg-6.p-5 {
        padding: 2.5rem 2.2rem !important;
    }
    .contactus-title {
        font-size: 2.3rem;
        font-weight: 700;
        color: #1E293B;
        margin-bottom: 0.7rem;
        letter-spacing: -1px;
    }
    .badge-support {
        background: #3B82F6;
        color: #fff;
        font-size: 0.98rem;
        font-weight: 600;
        border-radius: 10px;
        padding: 0.32em 0.95em;
        box-shadow: 0 1px 4px rgba(30,64,175,0.08);
        display: inline-block;
        margin-bottom: 0.7rem;
        letter-spacing: 0.5px;
    }
    .contactus-desc {
        color: #334155;
        font-size: 1.08rem;
        margin-bottom: 1.2rem;
        font-weight: 400;
        line-height: 1.6;
    }
    .contactus-icon {
        width: 38px;
        height: 38px;
        background: #e6eefb;
        color: #23395d;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        margin-top: 2px;
        box-shadow: 0 1px 4px rgba(30,64,175,0.06);
        margin-right: 0.7rem;
    }
    .contactus-divider {
        width: 1.2px;
        background: linear-gradient(180deg, #e3eafc 0%, #b6c6e6 100%);
        border-radius: 2px;
        min-height: 90%;
        margin: 2.5rem 0;
        opacity: 0.7;
    }
    .contactus-form {
        border: 1.2px solid #e2e8f0;
        min-height: 400px;
        font-size: 1.08rem;
        background: #fcfdff;
        padding: 2.2rem 1.5rem;
        box-shadow: 0 2px 12px rgba(30,64,175,0.06);
        transition: box-shadow 0.18s;
    }
    .contactus-form .form-label {
        font-weight: 600;
        color: #1E293B;
        font-size: 1.08rem;
        margin-bottom: 0.3rem;
    }
    .contactus-form .form-control {
        font-size: 1.08rem;
        border-radius: 12px;
        border: 1.2px solid #dbeafe;
        background: #f8fafc;
        color: #1E293B;
        box-shadow: none;
        transition: border-color 0.18s, box-shadow 0.18s;
        padding: 0.8em 1em;
        margin-bottom: 0.7rem;
    }
    .contactus-form .form-control:focus {
        border-color: #3B82F6;
        background: #fff;
        box-shadow: 0 0 0 2.5px #3B82F633;
    }
    .contactus-form .btn-primary.contactus-btn-big {
        background: #3B82F6;
        border: none;
        font-weight: 700;
        font-size: 1.08rem;
        box-shadow: 0 2px 8px rgba(30,64,175,0.10);
        transition: background 0.18s, transform 0.18s;
        margin-top: 8px;
        padding: 0.7em 2.2em;
        border-radius: 10px;
    }
    .contactus-form .btn-primary.contactus-btn-big:hover {
        background: #23395d;
        transform: translateY(-2px) scale(1.04);
    }
    .contactus-mashaka-logo-inside {
        right: -40px;
        bottom: -18px;
        width: 270px;
        max-width: 70%;
        z-index: 2;
        pointer-events: none;
        user-select: none;
        filter: drop-shadow(0 6px 24px rgba(30,64,175,0.13));
        transition: width 0.2s, right 0.2s, bottom 0.2s;
    }
    .animated-mashaka {
        animation: mashaka-float 2.8s ease-in-out infinite alternate;
    }
    @keyframes mashaka-float {
        0% { transform: translateY(0) scale(1.00); }
        100% { transform: translateY(-10px) scale(1.03); }
    }
    .contactus-social {
        margin-top: 0.7rem;
        margin-bottom: 0.2rem;
        display: flex;
        gap: 0.2em;
    }
    .contactus-social a {
        color: #23395d;
        font-size: 1.25rem;
        margin-right: 0.1em;
        transition: color 0.18s, transform 0.18s;
        display: inline-block;
        background: #e6eefb;
        border-radius: 50%;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .contactus-social a:hover {
        color: #3B82F6;
        background: #e0edff;
        transform: scale(1.13) rotate(-6deg);
    }
    @media (max-width: 1400px) {
        .contactus-card { max-width: 99vw; }
        .contactus-mashaka-logo-inside { width: 180px; right: -10px; bottom: -8px; }
    }
    @media (max-width: 991px) {
        .contactus-card { max-width: 99vw; }
        .contactus-mashaka-logo-inside { width: 120px; right: -10px; bottom: -8px; }
        .contactus-title { font-size: 2.1rem; }
        .col-lg-6.p-5 { padding: 1.5rem 1rem !important; }
    }
    @media (max-width: 767px) {
        .contactus-mashaka-logo-inside { display: none; }
        .contactus-divider { display: none; }
        .contactus-title { font-size: 1.5rem; }
        .contactus-form { font-size: 1rem; }
        .col-lg-6.p-5 { padding: 1rem 0.5rem !important; }
    }
</style>
@endsection
