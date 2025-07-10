@extends('admin.layouts.admin_dashboard')

@section('title', 'Detail Agenda Harian')

@section('content')
<div class="booking-page-wrapper position-relative" style="min-height: 100vh;">
    <div class="container-fluid px-4">
        <div class="row justify-content-center">
            <div class="col-lg-8 mx-auto">
                {{-- HERO SECTION: Detail Agenda Harian --}}
                <div class="booking-card mb-5" style="background:#fff;border-radius:24px;box-shadow:0 8px 32px rgba(44,62,80,0.08);border:1px solid #e9ecef;">
                    <div class="booking-header text-center position-relative" style="background:linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);padding:32px 20px 24px 20px;border-radius:24px 24px 0 0;overflow:hidden;">
                        <div style="content:'';position:absolute;top:-50%;left:-50%;width:200%;height:200%;background:radial-gradient(circle,rgba(255,255,255,0.1) 0%,transparent 70%);animation:rotate 8s linear infinite;z-index:1;"></div>
                        <div class="header-icon mx-auto mb-2" style="background:linear-gradient(135deg,#4facfe 0%,#00f2fe 100%);width:60px;height:60px;display:flex;align-items:center;justify-content:center;border-radius:50%;box-shadow:0 10px 25px rgba(79,172,254,0.3);position:relative;z-index:2;">
                            <i class="bx bx-detail" style="font-size:2rem;color:#fff;"></i>
                        </div>
                        <h2 class="header-title mb-1" style="color:#fff;font-size:1.5rem;font-weight:700;position:relative;z-index:2;text-shadow:2px 2px 4px rgba(0,0,0,0.3);">Detail Agenda Harian</h2>
                        <div style="width:60px;height:3px;background:linear-gradient(90deg,#4facfe,#00f2fe);border-radius:2px;margin:12px auto 0 auto;position:relative;z-index:2;"></div>
                        <style>@keyframes rotate{from{transform:rotate(0deg);}to{transform:rotate(360deg);}}</style>
                    </div>
                    <div class="booking-content" style="padding:28px 24px 24px 24px;">
                        <div class="mb-2" style="font-size:1.08rem;">
                            <strong>Tanggal:</strong> {{ $agenda->tanggal->format('d-m-Y') }}<br>
                            <strong>OOTD Cewek:</strong> {{ $agenda->ootd_cewek }}<br>
                            <strong>OOTD Cowok:</strong> {{ $agenda->ootd_cowok }}<br>
                            <strong>Status Kirim:</strong> 
                            <span class="badge" style="font-size:0.95em;padding:6px 14px;border-radius:8px;letter-spacing:0.5px;
                                @if($agenda->status_kirim=='terkirim')background:#38ef7d;color:#fff;
                                @elseif($agenda->status_kirim=='gagal')background:#ff416c;color:#fff;
                                @else background:#e9ecef;color:#4A6DFB;@endif">
                                {{ ucfirst($agenda->status_kirim) }}
                            </span><br>
                            <strong>Waktu Kirim:</strong> {{ $agenda->waktu_kirim ? $agenda->waktu_kirim->format('H:i d-m-Y') : '-' }}<br>
                        </div>
                    </div>
                </div>

                {{-- SUBSECTION: Agenda Manual --}}
                <div class="booking-card mb-5" style="background:#fff;border-radius:24px;box-shadow:0 8px 32px rgba(44,62,80,0.08);border:1px solid #e9ecef;">
                    <div class="px-4 pt-4 pb-0">
                        <h4 class="mb-3" style="color:#4A6DFB;font-weight:700;">Agenda Manual</h4>
                    </div>
                    <div class="booking-content" style="padding:8px 24px 24px 24px;">
                        @if($agenda->agenda_manual && count($agenda->agenda_manual))
                            <ol class="pl-3" style="font-size:1.08rem;">
                                @foreach($agenda->agenda_manual as $item)
                                    <li class="mb-2">
                                        <strong>{{ $item['jam'] ?? '-' }}</strong> - {{ $item['judul'] ?? '-' }} @if(!empty($item['lokasi'])) <span class="text-muted">📍{{ $item['lokasi'] }}</span>@endif
                                    </li>
                                @endforeach
                            </ol>
                        @else
                            <em class="text-muted">Tidak ada agenda manual.</em>
                        @endif
                    </div>
                </div>

                {{-- SUBSECTION: Agenda Booking --}}
                <div class="booking-card mb-5" style="background:#fff;border-radius:24px;box-shadow:0 8px 32px rgba(44,62,80,0.08);border:1px solid #e9ecef;">
                    <div class="px-4 pt-4 pb-0">
                        <h4 class="mb-3" style="color:#4A6DFB;font-weight:700;">Agenda Booking ({{ $agenda->tanggal->format('d-m-Y') }})</h4>
                    </div>
                    <div class="booking-content" style="padding:8px 24px 24px 24px;">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0" style="background:#fff;border-radius:16px;overflow:hidden;">
                                <thead style="background:#f4f7fd;">
                                    <tr style="font-weight:600;font-size:1.05rem;color:#4A6DFB;">
                                        <th>Jam</th>
                                        <th>Judul</th>
                                        <th>Ruangan</th>
                                        <th>Pemesan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($bookings as $b)
                                        <tr>
                                            <td>{{ $b->booking_time }} - {{ $b->booking_end }}</td>
                                            <td>{{ $b->meeting_title ?? '-' }}</td>
                                            <td>{{ $b->facility->name ?? '-' }}</td>
                                            <td>{{ $b->user->name ?? '-' }}</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="4" class="text-center text-muted">Tidak ada agenda booking.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- SUBSECTION: Preview Pesan Jarkoman --}}
                <div class="booking-card mb-5" style="background:#fff;border-radius:24px;box-shadow:0 8px 32px rgba(44,62,80,0.08);border:1px solid #e9ecef;">
                    <div class="px-4 pt-4 pb-0">
                        <h4 class="mb-3" style="color:#4A6DFB;font-weight:700;">Preview Pesan Jarkoman</h4>
                    </div>
                    <div class="booking-content" style="padding:8px 24px 24px 24px;">
                        @if(session('success'))
                            <div class="custom-alert success-alert mb-3" style="background:#38ef7d;color:#fff;border-radius:12px;">
                                <div class="alert-icon"><i class="bx bx-check-circle"></i></div>
                                <div class="alert-content"><strong>Berhasil!</strong> <span>{{ session('success') }}</span></div>
                                <button type="button" class="alert-close" data-bs-dismiss="alert"><i class="bx bx-x"></i></button>
                            </div>
                        @endif
                        @if(session('error'))
                            <div class="custom-alert danger-alert mb-3" style="background:#ff416c;color:#fff;border-radius:12px;">
                                <div class="alert-icon"><i class="bx bx-error-circle"></i></div>
                                <div class="alert-content"><strong>Error!</strong> <span>{{ session('error') }}</span></div>
                                <button type="button" class="alert-close" data-bs-dismiss="alert"><i class="bx bx-x"></i></button>
                            </div>
                        @endif
                        <textarea class="form-control mb-3" rows="10" readonly id="pesanJarkoman" style="background:#f8f9fa;border-radius:12px;border:1px solid #e9ecef;font-size:1.08rem;">{{ $pesan }}</textarea>
                        <button class="btn btn-outline-primary mr-2" onclick="copyPesan()"><i class="bx bx-copy"></i> Copy Pesan</button>
                        <form method="POST" action="{{ route('admin.send_message.send', $agenda->id) }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-success"><i class="bx bxl-whatsapp"></i> Kirim ke Bu Fitri (WA)</button>
                        </form>
                        <a href="{{ route('admin.send_message.index') }}" class="btn btn-secondary ml-2"><i class="bx bx-arrow-back"></i> Kembali</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
function copyPesan() {
    var pesan = document.getElementById('pesanJarkoman');
    pesan.select();
    pesan.setSelectionRange(0, 99999);
    document.execCommand('copy');
    alert('Pesan berhasil dicopy!');
}
</script>
@endsection 