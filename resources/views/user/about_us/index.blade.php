@extends('user.layouts.app')
@section('title', 'About Us')
@section('content')
<br>
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <h2 class="text-center mb-5 section-main-title" style="font-size:2.3rem;font-weight:700;color:#1E293B;letter-spacing:-1px;margin-bottom:2.5rem;border-bottom:none;box-shadow:none;background:none;">Tentang Kami</h2>

                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                {{-- Bagian Visi - Gambar Utama Gedung BPR dan Teks Visi --}}
                <div class="row align-items-center mb-5 about-section">
                    <div class="col-md-7 mb-4 mb-md-0">
                        <div class="about-image-frame primary-image-frame">
                            <img src="{{ asset('img/msa1.jpeg') }}" alt="Visi BPR MSA" class="img-fluid d-block mx-auto">
                        </div>
                    </div>
                    <div class="col-md-5">
                        <h3 class="section-subtitle">Visi Kami</h3>
                        <p class="lead">
                            Menjadi "Smart Banking" BPR terbaik di Indonesia.
                        </p>
                    </div>
                </div>

                {{-- Bagian Misi - Teks Misi dan Gambar Tim BPR --}}
                <div class="row align-items-center mb-5 about-section">
                    <div class="col-md-5 order-md-2 mb-4 mb-md-0"> {{-- Gambar Tim BPR --}}
                        <div class="about-image-frame secondary-image-frame">
                            <img src="{{ asset('img/timbpr.png') }}" alt="Misi BPR MSA" class="img-fluid d-block mx-auto">
                        </div>
                    </div>
                    <div class="col-md-7 order-md-1">
                        <h3 class="section-subtitle">Misi Kami</h3>
                        <ol class="lead"> 
                            <li>Terciptanya Good Corporate Governance, berbasis pada Perbankan yang sehat.</li>
                            <li>Menjalankan bisnis perbankan secara prudent (mengutamakan prinsip kehati-hatian) dengan tidak mengesampingkan pertumbuhan bisnis.</li>
                            <li>Menjadi partner bisnis bagi usaha mikro, kecil dan menengah untuk menunjang peningkatan ekonomi regional.</li>
                            <li>Memberikan pelayanan prima untuk memuaskan nasabah.</li>
                            <li>Memberikan keuntungan dan manfaat yang optimal kepada stake holder.</li>
                        </ol>
                    </div>
                </div>

                {{-- Bagian Logo Besar BPR --}}
                <div class="row mb-5 justify-content-center about-section">
                    <div class="col-md-10 text-center">
                        <h3 class="section-subtitle mb-4">Kami Adalah BPR MSA</h3>
                        <img src="{{ asset('img/msa.png') }}" alt="Logo Besar BPR MSA" class="img-fluid d-block mx-auto about-large-logo">
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@section('styles')
<style>
.section-main-title::after {
    display: none !important;
}
</style>
@endsection