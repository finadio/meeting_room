<footer class="footer">
    <div class="container">
        <div class="footer-content">
            <div class="row">
                <!-- Company Info -->
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="footer-section">
                        <div class="footer-logo">
                            <h4>BPR MSA Yogyakarta</h4>
                        </div>
                        <p class="company-description">
                            Lembaga keuangan terpercaya yang menyediakan solusi meeting room premium untuk kebutuhan bisnis profesional Anda.
                        </p>
                        <div class="social-links">
                            <a href="https://www.instagram.com/bprmsa.official/" target="_blank" aria-label="Instagram">
                                <i class='bx bxl-instagram'></i>
                            </a>
                            <a href="https://web.facebook.com/bprmsa.official?_rdc=1&_rdr" target="_blank" aria-label="Facebook">
                                <i class='bx bxl-facebook'></i>
                            </a>
                            <a href="https://www.tiktok.com/@bprmsa" target="_blank" aria-label="TikTok">
                                <i class='bx bxl-tiktok'></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Contact Info -->
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="footer-section">
                        <h5>Kontak Kami</h5>
                        <div class="contact-info">
                            <div class="contact-item">
                                <i class='bx bxs-phone'></i>
                                <div>
                                    <strong>Telepon</strong>
                                    <p>0274-549400</p>
                                </div>
                            </div>
                            <div class="contact-item">
                                <i class='bx bx-envelope'></i>
                                <div>
                                    <strong>Email</strong>
                                    <p>bprmadani@gmail.com</p>
                                </div>
                            </div>
                            <div class="contact-item">
                                <i class='bx bx-globe'></i>
                                <div>
                                    <strong>Website</strong>
                                    <p>www.bprmsa.co.id</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Office Address & Map -->
                <div class="col-lg-4 col-md-12">
                    <div class="footer-section">
                        <h5>Kantor Pusat</h5>
                        <div class="office-info">
                            <div class="address-info">
                                <i class='bx bx-map-pin'></i>
                                <div>
                                    <strong>Alamat</strong>
                                    <p>Jalan C. Simanjuntak No. 26<br>Kota Yogyakarta 55223</p>
                                </div>
                            </div>
                        </div>
                        <div class="map-container">
                            <iframe 
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3952.802405099179!2d110.3919133!3d-7.8117617!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7a5795c524ba5b%3A0xa22172ae761fdf30!2sPT%20BPR%20MSA%20Yogyakarta!5e0!3m2!1sen!2sid!4v1719858441234!5m2!1sen!2sid" 
                                width="100%" 
                                height="200" 
                                style="border:0; border-radius:12px;" 
                                allowfullscreen 
                                loading="lazy"
                                title="Lokasi BPR MSA Yogyakarta">
                            </iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Footer Bottom -->
        <div class="footer-bottom">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="copyright">
                        &copy; {{ date('Y') }} PT BPR MSA Yogyakarta. All rights reserved.
                    </p>
                </div>
                <div class="col-md-6 text-md-right">
                    <div class="footer-links">
                        <a href="{{ route('about.show') }}">Tentang Kami</a>
                        <a href="{{ route('contact.show') }}">Kontak</a>
                        <a href="{{ route('user.booking.index') }}">Meeting Rooms</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>

<style>
/* Footer Styles */
.footer {
    background: linear-gradient(135deg, #1E293B 0%, #334155 100%);
    color: #F1F5F9;
    padding: 1.5rem 0 0.5rem;
    margin-top: 2rem;
}

.footer-content {
    margin-bottom: 2rem;
}

.footer-section h5 {
    font-size: 1.25rem;
    font-weight: 600;
    color: #FFFFFF;
    margin-bottom: 1.5rem;
    position: relative;
}

.footer-section h5::after {
    content: '';
    position: absolute;
    bottom: -8px;
    left: 0;
    width: 40px;
    height: 3px;
    background: linear-gradient(135deg, #1E40AF, #0F766E);
    border-radius: 2px;
}

/* Company Logo */
.footer-logo {
    margin-bottom: 1rem;
}

.footer-logo h4 {
    font-size: 1.5rem;
    font-weight: 700;
    color: #FFFFFF;
    margin: 0;
}

.company-description {
    color: #CBD5E1;
    line-height: 1.6;
    margin-bottom: 1.5rem;
}

/* Social Links */
.social-links {
    display: flex;
    gap: 12px;
}

.social-links a {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 8px;
    color: #F1F5F9;
    text-decoration: none;
    transition: all 0.3s ease;
    font-size: 1.25rem;
}

.social-links a:hover {
    background: linear-gradient(135deg, #1E40AF, #0F766E);
    color: #FFFFFF;
    transform: translateY(-2px);
}

/* Contact Info */
.contact-info {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.contact-item {
    display: flex;
    align-items: flex-start;
    gap: 12px;
}

.contact-item i {
    font-size: 1.25rem;
    color: #1E40AF;
    margin-top: 2px;
    flex-shrink: 0;
}

.contact-item strong {
    display: block;
    color: #FFFFFF;
    font-size: 0.875rem;
    margin-bottom: 2px;
}

.contact-item p {
    color: #CBD5E1;
    margin: 0;
    font-size: 0.95rem;
}

/* Office Info */
.office-info {
    margin-bottom: 1.5rem;
}

.address-info {
    display: flex;
    align-items: flex-start;
    gap: 12px;
}

.address-info i {
    font-size: 1.25rem;
    color: #1E40AF;
    margin-top: 2px;
    flex-shrink: 0;
}

.address-info strong {
    display: block;
    color: #FFFFFF;
    font-size: 0.875rem;
    margin-bottom: 2px;
}

.address-info p {
    color: #CBD5E1;
    margin: 0;
    font-size: 0.95rem;
    line-height: 1.5;
}

/* Map Container */
.map-container {
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}

/* Footer Bottom */
.footer-bottom {
    border-top: 1px solid rgba(255, 255, 255, 0.1);
    padding-top: 0.75rem;
}

.copyright {
    color: #94A3B8;
    margin: 0;
    font-size: 0.875rem;
}

.footer-links {
    display: flex;
    gap: 1.5rem;
    justify-content: flex-end;
}

.footer-links a {
    color: #CBD5E1;
    text-decoration: none;
    font-size: 0.875rem;
    font-weight: 500;
    transition: color 0.3s ease;
}

.footer-links a:hover {
    color: #1E40AF;
}

/* Responsive Design */
@media (max-width: 768px) {
    .footer {
        padding: 1rem 0 0.5rem;
    }
    
    .footer-section {
        margin-bottom: 2rem;
    }
    
    .footer-links {
        justify-content: center;
        margin-top: 1rem;
    }
    
    .copyright {
        text-align: center;
        margin-bottom: 1rem;
    }
}

@media (max-width: 576px) {
    .social-links {
        justify-content: center;
    }
    
    .footer-links {
        flex-direction: column;
        align-items: center;
        gap: 0.75rem;
    }
}
</style>