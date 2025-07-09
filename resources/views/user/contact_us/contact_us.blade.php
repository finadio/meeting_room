@extends('user.layouts.app')
@section('title', 'Contact Us')

@section('content')
<main>
    <div class="contactus-hero-bg py-5">
        <div class="container">
            <div class="contactus-card mx-auto">
                <div class="row g-0">
                    <!-- Kiri: Info Kontak -->
                    <div class="col-md-6 p-4 d-flex flex-column justify-content-center">
                        <h2 class="contactus-title mb-3" style="font-size:2.1rem; font-weight:800; color:#23395d; letter-spacing:-1px;">Hubungi Kami</h2>
                        <div class="mb-2 d-flex align-items-center">
                            <div class="contactus-icon me-2"><i class='bx bx-map'></i></div>
                            <div><b>Alamat:</b> Jalan C. Simanjuntak No. 26, Kota Yogyakarta 55223</div>
                        </div>
                        <div class="mb-2 d-flex align-items-center">
                            <div class="contactus-icon me-2"><i class='bx bx-phone'></i></div>
                            <div><b>Telepon:</b> 0274-549400</div>
                        </div>
                        <div class="mb-2 d-flex align-items-center">
                            <div class="contactus-icon me-2"><i class='bx bxl-whatsapp'></i></div>
                            <div><b>WhatsApp Business:</b> 0851-7202-4202</div>
                        </div>
                        <div class="mb-2 d-flex align-items-center">
                            <div class="contactus-icon me-2"><i class='bx bx-envelope'></i></div>
                            <div><b>Email:</b> bprmadani@gmail.com</div>
                        </div>
                        <div class="mb-2 d-flex align-items-center">
                            <div class="contactus-icon me-2"><i class='bx bx-time'></i></div>
                            <div><b>Jam Operasional:</b> Senin–Jumat: 08.00–17.00</div>
                        </div>
                        <!-- Tombol Media Sosial -->
                        <div class="mt-3 d-flex align-items-center gap-2">
                            <a href="https://instagram.com/bprmsa.official" class="btn-social-media ig" title="Instagram" target="_blank" rel="noopener noreferrer"><i class='bx bxl-instagram'></i></a>
                            <a href="https://facebook.com/bprmsa.official" class="btn-social-media fb" title="Facebook" target="_blank" rel="noopener noreferrer"><i class='bx bxl-facebook'></i></a>
                            <a href="https://wa.me/6285172024202" class="btn-social-media wa" title="WhatsApp" target="_blank" rel="noopener noreferrer"><i class='bx bxl-whatsapp'></i></a>
                            <a href="https://twitter.com/bprmsa" class="btn-social-media tw" title="Twitter" target="_blank" rel="noopener noreferrer"><i class='bx bxl-twitter'></i></a>
                            <a href="https://youtube.com/@bprmsa" class="btn-social-media" title="YouTube" target="_blank" rel="noopener noreferrer"><i class='bx bxl-youtube'></i></a>
                            <a href="https://tiktok.com/@bprmsa" class="btn-social-media" title="TikTok" target="_blank" rel="noopener noreferrer"><i class='bx bxl-tiktok'></i></a>
                        </div>
                    </div>
                    <!-- Kanan: Form -->
                    <div class="col-md-6 p-4 position-relative">
                        <form id="contactForm" action="javascript:void(0);" class="contactus-form bg-white rounded-4 p-3 p-md-4 shadow-sm position-relative z-2">
                            <div class="mb-3">
                                <label for="name" class="form-label">Nama Anda</label>
                                <input type="text" class="form-control rounded-3" id="name" name="name" placeholder="Nama Anda">
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email Anda</label>
                                <input type="email" class="form-control rounded-3" id="email" name="email" placeholder="Email Anda">
                            </div>
                            <div class="mb-3">
                                <label for="subject" class="form-label">Subjek</label>
                                <select class="form-control rounded-3" id="subject" name="subject" required>
                                    <option value="">Pilih Subjek</option>
                                    <option value="booking">Booking Ruang Meeting</option>
                                    <option value="inquiry">Inquiry Harga</option>
                                    <option value="support">Technical Support</option>
                                    <option value="partnership">Partnership</option>
                                    <option value="other">Lainnya</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="message" class="form-label">Pesan Anda</label>
                                <textarea class="form-control rounded-3" id="message" name="message" rows="3" placeholder="Pesan Anda"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary rounded-3 px-4 py-2 fw-semibold">Kirim</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- FAQ Section -->
    <div class="contactus-faq-section py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="contactus-faq-header text-center mb-5">
                        <h2 style="font-size:2.2rem; font-weight:800; color:#23395d; margin-bottom:0.5rem;">Pertanyaan yang Sering Diajukan</h2>
                        <p style="font-size:1.08rem; color:#64748B; margin-bottom:0;">Temukan jawaban untuk pertanyaan umum tentang layanan kami</p>
                    </div>
                    <div class="contactus-faq-grid d-flex flex-column gap-4">
                        <div class="contactus-faq-item p-4 bg-white rounded-4 shadow-sm mb-3">
                            <h5 style="color:#1a202c; font-weight:700; font-size:1.13rem; margin-bottom:0.7rem;">Bagaimana cara booking ruang meeting?</h5>
                            <p style="color:#64748B; margin:0; font-size:1.02rem;">Anda dapat booking melalui website kami atau hubungi tim customer service. Pilih ruang, tentukan waktu, dan lakukan pembayaran. Konfirmasi akan dikirim via email.</p>
                        </div>
                        <div class="contactus-faq-item p-4 bg-white rounded-4 shadow-sm mb-3">
                            <h5 style="color:#1a202c; font-weight:700; font-size:1.13rem; margin-bottom:0.7rem;">Apakah bisa booking untuk hari yang sama?</h5>
                            <p style="color:#64748B; margin:0; font-size:1.02rem;">Ya, bisa! Selama slot masih tersedia. Kami merekomendasikan booking minimal 2 jam sebelum waktu meeting untuk memastikan persiapan yang optimal.</p>
                        </div>
                        <div class="contactus-faq-item p-4 bg-white rounded-4 shadow-sm mb-3">
                            <h5 style="color:#1a202c; font-weight:700; font-size:1.13rem; margin-bottom:0.7rem;">Apa saja fasilitas yang tersedia?</h5>
                            <p style="color:#64748B; margin:0; font-size:1.02rem;">Semua ruang dilengkapi dengan proyektor, whiteboard, AC, WiFi berkecepatan tinggi, dan sistem audio. Beberapa ruang juga memiliki fasilitas video conference.</p>
                        </div>
                        <div class="contactus-faq-item p-4 bg-white rounded-4 shadow-sm mb-3">
                            <h5 style="color:#1a202c; font-weight:700; font-size:1.13rem; margin-bottom:0.7rem;">Bagaimana jika ada kendala teknis?</h5>
                            <p style="color:#64748B; margin:0; font-size:1.02rem;">Tim teknis kami standby 24/7 untuk membantu Anda. Hubungi hotline atau tekan tombol bantuan di dalam ruang meeting.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

@section('styles')
<style>
    /* Critical CSS agar halaman tidak pernah polos */
    html, body {
        background: #f4f6fa !important;
        color: #1E293B !important;
        font-family: 'Poppins', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif !important;
        min-height: 100vh;
    }
    .contactus-hero-bg {
        background: #f4f6fa;
        min-height: 100vh;
        padding-top: 1.2rem !important;
    }
    .contactus-card {
        background: #fff;
        border-radius: 18px;
        box-shadow: 0 6px 32px rgba(30,64,175,0.10);
        max-width: 950px;
        overflow: hidden;
        border: 1.5px solid #e2e8f0;
    }
    .contactus-header {
        background: #23395d;
        border-top-left-radius: 18px;
        border-top-right-radius: 18px;
        min-height: 56px;
    }
    .contactus-title {
        font-size: 2.3rem;
        font-weight: 800;
        color: #23395d;
        margin-bottom: 0.5rem;
        letter-spacing: -1px;
    }
    .contactus-desc {
        color: #334155;
        font-size: 1.1rem;
        margin-bottom: 1.2rem;
    }
    .contactus-icon {
        width: 36px;
        height: 36px;
        background: #e6eefb;
        color: #23395d;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
        margin-top: 2px;
    }
    .contactus-form {
        border: 1.5px solid #e2e8f0;
        min-height: 320px;
    }
    .contactus-form .form-label {
        font-weight: 500;
        color: #23395d;
        font-size: 1rem;
    }
    .contactus-form .form-control {
        font-size: 1rem;
        border-radius: 10px;
        border: 1.5px solid #dbeafe;
        background: #f8fafc;
        color: #23395d;
        box-shadow: none;
        transition: border-color 0.18s;
    }
    .contactus-form .form-control:focus {
        border-color: #3B82F6;
        background: #fff;
        box-shadow: 0 0 0 2px #3B82F633;
    }
    .contactus-form .btn-primary {
        background: #3B82F6;
        border: none;
        font-weight: 600;
        font-size: 1.1rem;
        box-shadow: 0 2px 8px rgba(30,64,175,0.08);
        transition: background 0.18s;
    }
    .contactus-form .btn-primary:hover {
        background: #23395d;
    }
    .contactus-mashaka-logo-inside {
        right: -30px;
        bottom: -18px;
        width: 210px;
        max-width: 60%;
        z-index: 2;
        pointer-events: none;
        user-select: none;
    }
    @media (max-width: 991px) {
        .contactus-card { max-width: 98vw; }
        .contactus-mashaka-logo-inside { width: 120px; right: -10px; bottom: -8px; }
    }
    @media (max-width: 767px) {
        .contactus-mashaka-logo-inside { display: none; }
    }
    .btn-social-media {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        font-size: 1.3rem;
        color: #23395d;
        background: #f1f5f9;
        border: 1.5px solid #e2e8f0;
        margin-right: 8px;
        transition: transform 0.18s, box-shadow 0.18s, background 0.18s, color 0.18s;
        box-shadow: 0 2px 8px rgba(30,64,175,0.06);
        text-decoration: none;
    }
    .btn-social-media:hover {
        background: #e2e8f0;
        color: #1E40AF;
        transform: scale(1.10);
        box-shadow: 0 4px 16px rgba(30,64,175,0.10);
        text-decoration: none;
    }
    @media (max-width: 767px) {
        .btn-social-media { margin-right: 5px; }
    }
</style>
@endsection

@section('scripts')
<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('.contactus-form');
        if(form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                // Kirim form secara AJAX (opsional, atau bisa submit biasa jika backend sudah handle flash message)
                // Untuk demo, langsung tampilkan pop up
                Swal.fire({
                    icon: 'success',
                    title: 'Pesan Terkirim!',
                    text: 'Terima kasih, pesan Anda sudah kami terima.',
                    confirmButtonText: 'OK'
                }).then(() => {
                    form.reset();
                });
            });
        }
    });
</script>
@endsection