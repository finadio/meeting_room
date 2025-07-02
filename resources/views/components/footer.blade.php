<footer class="footer">
    <div class="container">
        <div class="row">
            <!-- Kontak & Sosial Media -->
            <div class="col-md-6 col-sm-12 mb-4">
                <div class="footer-nav">
                    <h5>Hubungi Kami</h5>
                    <ul class="list-unstyled">
                        <li><i class='bx bxs-phone'></i> 0274-549400</li>
                        <li><i class='bx bx-envelope'></i> bprmadani@gmail.com</li>
                        <li><i class='bx bx-globe'></i> www.bprmsa.co.id</li>
                    </ul>
                    <h5>Ikuti Kami</h5>
                    <ul class="list-unstyled d-flex">
                        <li>
                            <a href="https://www.instagram.com/bprmsa.official/" target="_blank">
                                <i class='bx bxl-instagram'></i>
                            </a>
                        </li>
                        <li>
                            <a href="https://web.facebook.com/bprmsa.official?_rdc=1&_rdr" target="_blank">
                                <i class='bx bxl-facebook'></i>
                            </a>
                        </li>
                        <li>
                            <a href="https://www.tiktok.com/@bprmsa" target="_blank">
                                <i class='bx bxl-tiktok'></i>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Alamat & Google Maps -->
            <div class="col-md-6 col-sm-12">
                <div class="footer-bottom text-md-right text-sm-center">
                    <h5>Alamat Kantor</h5>
                    <p>Jalan C. Simanjuntak No. 26<br>Kota Yogyakarta 55223</p>
                    <iframe 
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3952.802405099179!2d110.3919133!3d-7.8117617!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7a5795c524ba5b%3A0xa22172ae761fdf30!2sPT%20BPR%20MSA%20Yogyakarta!5e0!3m2!1sen!2sid!4v1719858441234!5m2!1sen!2sid" 
                        width="100%" 
                        height="150" 
                        style="border:0; border-radius:8px;" 
                        allowfullscreen 
                        loading="lazy">
                    </iframe>
                    <p class="mt-3">&copy; {{ date('Y') }} PT BPR MSA Yogyakarta. All rights reserved.</p>
                </div>
            </div>
        </div>
    </div>
</footer>

<style>
    html, body {
    height: 100%;
    margin: 0;
    padding: 0;
}

body {
    display: flex;
    flex-direction: column;
    min-height: 100vh;
}

main {
    flex: 1;
}

.footer {
    background-color: #222;
    color: #fff;
    padding: 40px 0;
}

.footer-nav h5 {
    font-size: 18px;
    margin: 12px 0;
    font-weight: bold;
}

.footer-nav li {
    margin-bottom: 10px;
    font-size: 16px;
}

.footer-nav a {
    color: #fff;
    font-size: 18px;
    text-decoration: none;
    transition: color 0.3s ease;
}

.footer-nav a:hover {
    color: #FF5733;
}

.footer-nav i {
    margin-right: 8px;
    font-size: 20px;
}

.footer-bottom p {
    color: #ccc;
    font-size: 15px;
    margin-top: 10px;
}

.footer-nav .d-flex {
    display: flex;
    gap: 15px;
    margin-top: 10px;
}

.footer-nav .d-flex a {
    font-size: 24px;
}

.footer::after {
    content: "";
    display: block;
    width: 100%;
    height: 10px;
    background: linear-gradient(to bottom, rgba(0, 0, 0, 0.1), rgba(0, 0, 0, 0));
    position: absolute;
    bottom: 0;
    left: 0;
}

@media (max-width: 768px) {
    .footer-nav .d-flex {
        flex-direction: column;
        align-items: flex-start;
    }

    .footer-bottom {
        text-align: center;
    }
}

</style>