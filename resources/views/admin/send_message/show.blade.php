@extends('admin.layouts.admin_dashboard')

@section('title', 'Detail Agenda Harian')



@section('content')
<div class="booking-page-wrapper position-relative" style="min-height: 100vh; background: linear-gradient(120deg, #f4f7fd 0%, #e9f0fb 100%);">
    <div class="container-fluid px-4" style="max-width: 1600px;">
        <div class="row justify-content-center">
            <div class="col-lg-11 mx-auto">
                {{-- HERO SECTION: Detail Agenda Harian --}}
                <div class="booking-card mb-5" style="background:rgba(255,255,255,0.85);backdrop-filter:blur(8px);border-radius:32px;box-shadow:0 8px 32px rgba(44,62,80,0.10);border:1px solid #e3e8ee;">
                    <div class="booking-header text-center position-relative" style="background:linear-gradient(135deg, #2A3B52 0%, #3B5998 100%);padding:40px 40px 32px 40px;border-radius:32px 32px 0 0;overflow:hidden;">
                        <div style="content:'';position:absolute;top:-50%;left:-50%;width:200%;height:200%;background:radial-gradient(circle,rgba(255,255,255,0.10) 0%,transparent 70%);animation:rotate 8s linear infinite;z-index:1;"></div>
                        <div class="header-icon mx-auto mb-2" style="background:linear-gradient(135deg,#4facfe 0%,#00f2fe 100%);width:70px;height:70px;display:flex;align-items:center;justify-content:center;border-radius:50%;box-shadow:0 10px 25px rgba(79,172,254,0.3);position:relative;z-index:2;">
                            <i class="bx bx-detail" style="font-size:2.2rem;color:#fff;"></i>
                        </div>
                        <h2 class="header-title mb-1" style="color:#fff;font-size:2rem;font-weight:700;position:relative;z-index:2;text-shadow:2px 2px 4px rgba(0,0,0,0.3);">Detail Agenda Harian</h2>
                        <div style="width:60px;height:4px;background:#4facfe;border-radius:2px;margin:12px auto 0 auto;position:relative;z-index:2;"></div>
                        <style>@keyframes rotate{from{transform:rotate(0deg);}to{transform:rotate(360deg);}}</style>
                    </div>
                </div>                            
                    {{-- SUBSECTION: Preview Pesan Jarkoman --}}
                    <div class="booking-card mb-5" style="background:rgba(255,255,255,0.85);backdrop-filter:blur(8px);border-radius:28px;box-shadow:0 8px 32px rgba(44,62,80,0.10);border:1px solid #e3e8ee;">
                        <div class="px-5 pt-5 pb-0 d-flex align-items-center gap-2">
                            <i class="bx bx-message-square-detail" style="color:#4A6DFB;font-size:1.3rem;"></i>
                            <h5 class="mb-0" style="color:#1e3c72;font-weight:700;letter-spacing:0.5px;">Preview Pesan WhatsApp</h5>
                        </div>
                        <div class="booking-content" style="padding:24px 40px 40px 40px;">
                            @if(session('success'))
                                <div class="alert alert-success mb-3" style="border-radius:12px;border:none;padding:16px 20px;">
                                    <div class="d-flex align-items-center">
                                        <i class="bx bx-check-circle me-2" style="font-size:1.2rem;"></i>
                                        <strong>Berhasil!</strong> <span class="ms-2">{{ session('success') }}</span>
                                    </div>
                                </div>
                            @endif
                            @if(session('error'))
                                <div class="alert alert-danger mb-3" style="border-radius:12px;border:none;padding:16px 20px;">
                                    <div class="d-flex align-items-center">
                                        <i class="bx bx-error-circle me-2" style="font-size:1.2rem;"></i>
                                        <strong>Error!</strong> <span class="ms-2">{{ session('error') }}</span>
                                    </div>
                                </div>
                            @endif
                            {{-- Menggunakan $pesanPreview yang sudah digabungkan dari controller --}}
                            <textarea class="form-control mb-3" rows="12" readonly id="pesanJarkoman" style="background:#f8f9fa;border-radius:12px;border:1px solid #e9ecef;font-size:1.08rem;min-height:300px;">{{ $pesanPreview }}</textarea>
                            <div class="d-flex flex-wrap align-items-center" style="gap: 20px;">
                                <button class="btn btn-primary custom-btn-spacing" onclick="copyPesan()"><i class="bx bx-copy"></i> Copy Pesan</button>
                                <button type="button" class="btn btn-success custom-btn-spacing" id="sendButton" onclick="sendMessage()"><i class="bx bxl-whatsapp"></i> Kirim ke Bu Fitri (WA)</button>
                                <a href="{{ route('admin.send_message.index') }}" class="btn btn-secondary custom-btn-spacing"><i class="bx bx-arrow-back"></i> Kembali</a>
                            </div>
                        </div>
                    </div>
                    {{-- SUBSECTION: OOTD --}}
                    <div class="booking-card mb-5" style="background:rgba(255,255,255,0.85);backdrop-filter:blur(8px);border-radius:28px;box-shadow:0 8px 32px rgba(44,62,80,0.10);border:1px solid #e3e8ee;">
                        <div class="booking-content" style="padding:40px 40px 40px 40px;">
                            <div class="row mb-2">
                                <div class="col-lg-6 col-md-6 mb-3">
                                    <i class="bx bx-message-square-detail" style="color:#4A6DFB;font-size:1.3rem;"></i>
                                    <h5 class="mb-0" style="color:#1e3c72;font-weight:700;letter-spacing:0.5px;">OOTD Hari ini </h5>
                                </div>
                                <div class="col-lg-6 col-md-6 mb-3">
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="label-icon mr-2" style="background:#e3f2fd;border-radius:8px;padding:8px 10px 8px 8px;display:flex;align-items:center;justify-content:center;"><i class="bx bx-calendar" style="color:#4A6DFB;font-size:1.2rem;"></i></div>
                                        <span class="font-weight-bold" style="color:#2A3B52;">Tanggal</span>
                                    </div>
                                    <div class="display-value-text" style="font-size:1.08rem;">{{ $agenda->tanggal->format('d-m-Y') }}</div>
                                </div>

                                <div class="col-lg-6 col-md-6 mb-3">
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="label-icon mr-2" style="background:#e3f2fd;border-radius:8px;padding:8px 10px 8px 8px;display:flex;align-items:center;justify-content:center;"><i class="bx bx-female" style="color:#4A6DFB;font-size:1.2rem;"></i></div>
                                        <span class="font-weight-bold" style="color:#2A3B52;">OOTD Cewek</span>
                                    </div>
                                    <div class="display-value-text" style="font-size:1.08rem;">{{ $agenda->ootd_cewek }}</div>
                                </div>
                                <div class="col-lg-6 col-md-6 mb-3">
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="label-icon mr-2" style="background:#e3f2fd;border-radius:8px;padding:8px 10px 8px 8px;display:flex;align-items:center;justify-content:center;"><i class="bx bx-male" style="color:#4A6DFB;font-size:1.2rem;"></i></div>
                                        <span class="font-weight-bold" style="color:#2A3B52;">OOTD Cowok</span>
                                    </div>
                                    <div class="display-value-text" style="font-size:1.08rem;">{{ $agenda->ootd_cowok }}</div>
                                </div>
                                <div class="col-lg-6 col-md-6 mb-3">
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="label-icon mr-2" style="background:#e3f2fd;border-radius:8px;padding:8px 10px 8px 8px;display:flex;align-items:center;justify-content:center;"><i class="bx bx-send" style="color:#4A6DFB;font-size:1.2rem;"></i></div>
                                        <span class="font-weight-bold" style="color:#2A3B52;">Status Kirim</span>
                                    </div>
                                    <span class="badge" style="font-size:0.95em;padding:7px 16px;border-radius:12px;letter-spacing:0.5px;display:inline-flex;align-items:center;gap:4px;
                                            @if($agenda->status_kirim=='terkirim')background:#e6f9f0;color:#11998e;
                                            @elseif($agenda->status_kirim=='gagal')background:#ffeaea;color:#ff416c;
                                            @else background:#f4f7fd;color:#4A6DFB;@endif">
                                            @if($agenda->status_kirim=='terkirim')<i class="bx bx-check-circle"></i>@elseif($agenda->status_kirim=='gagal')<i class="bx bx-x-circle"></i>@else<i class="bx bx-time"></i>@endif
                                            {{ ucfirst($agenda->status_kirim) }}
                                    </span>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="label-icon mr-2" style="background:#e3f2fd;border-radius:8px;padding:8px 10px 8px 8px;display:flex;align-items:center;justify-content:center;"><i class="bx bx-time-five" style="color:#4A6DFB;font-size:1.2rem;"></i></div>
                                        <span class="font-weight-bold" style="color:#2A3B52;">Waktu Kirim</span>
                                    </div>
                                    <div class="display-value-text" style="font-size:1.08rem;">{{ $agenda->waktu_kirim ? $agenda->waktu_kirim->format('H:i d-m-Y') : '-' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                {{-- SUBSECTION: Agenda Manual --}}
                <div class="booking-card mb-5" style="background:rgba(255,255,255,0.85);backdrop-filter:blur(8px);border-radius:28px;box-shadow:0 8px 32px rgba(44,62,80,0.10);border:1px solid #e3e8ee;">
                    <div class="px-5 pt-5 pb-0 d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bx bx-list-ul" style="color:#4A6DFB;font-size:1.3rem;"></i>
                            <h5 class="mb-0" style="color:#1e3c72;font-weight:700;letter-spacing:0.5px;">Agenda Manual</h5>
                        </div>
                        <a href="{{ route('admin.send_message.edit', $agenda->id) }}" class="btn btn-warning btn-sm">
                            <i class="bx bx-edit"></i> Edit Agenda
                        </a>
                    </div>
                    <div class="booking-content" style="padding:24px 40px 40px 40px;">
                        <div class="alert alert-info mb-4" style="border-radius:12px;border:none;padding:16px 20px;">
                            <div class="d-flex align-items-center">
                                <i class="bx bx-info-circle me-2" style="font-size:1.2rem;"></i>
                                <div>
                                    <strong>Koneksi Tabel:</strong> Agenda manual terhubung dengan tabel agenda harian dan dapat diedit melalui halaman edit agenda harian.
                                </div>
                            </div>
                        </div>
                        @if($agendaManuals && count($agendaManuals))
                            <ol class="pl-3" style="font-size:1.08rem;">
                                @foreach($agendaManuals as $item)
                                    <li class="mb-2">
                                        <strong>{{ $item->jam ?? '-' }}</strong> - {{ $item->judul ?? '-' }}
                                        @if(!empty($item->lokasi))
                                            <span class="text-muted">(Lokasi: {{ $item->lokasi }})</span>
                                        @endif
                                    </li>
                                @endforeach
                            </ol>
                        @else
                            <em class="text-muted">Tidak ada agenda manual.</em>
                        @endif
                        <div class="mt-3">
                            <small class="text-muted">
                                <i class="bx bx-info-circle"></i> 
                                Agenda manual dapat diedit melalui halaman edit agenda harian.
                            </small>
                        </div>
                    </div>
                </div>

                {{-- SUBSECTION: Agenda Booking --}}
                <div class="booking-card mb-5" style="background:rgba(255,255,255,0.85);backdrop-filter:blur(8px);border-radius:28px;box-shadow:0 8px 32px rgba(44,62,80,0.10);border:1px solid #e3e8ee;">
                    <div class="px-5 pt-5 pb-0 d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bx bx-calendar" style="color:#4A6DFB;font-size:1.3rem;"></i>
                            <h5 class="mb-0" style="color:#1e3c72;font-weight:700;letter-spacing:0.5px;">Agenda Booking ({{ $agenda->tanggal->format('d-m-Y') }})</h5>
                        </div>
                        <a href="{{ route('admin.bookings.index') }}" class="btn btn-primary btn-sm">
                            <i class="bx bx-calendar-check"></i> Manajemen Booking
                        </a>
                    </div>
                    <div class="booking-content" style="padding:24px 40px 40px 40px;">
                        <div class="alert alert-info mb-4" style="border-radius:12px;border:none;padding:16px 20px;">
                            <div class="d-flex align-items-center">
                                <i class="bx bx-info-circle me-2" style="font-size:1.2rem;"></i>
                                <div>
                                    <strong>Koneksi Tabel:</strong> Data booking diambil dari tabel manajemen booking sesuai tanggal agenda harian ({{ $agenda->tanggal->format('d-m-Y') }}). 
                                    Hanya booking yang sudah disetujui yang ditampilkan.
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0" style="background:rgba(255,255,255,0.97);border-radius:18px;overflow:hidden;font-size:1.08rem;width:100%;">
                                <thead style="background:#f4f7fd;font-weight:700;letter-spacing:0.5px;">
                                    <tr style="font-weight:600;font-size:1.05rem;color:#4A6DFB;">
                                        <th><i class="bx bx-time-five mr-1"></i>Jam</th>
                                        <th><i class="bx bx-book-content mr-1"></i>Judul</th>
                                        <th><i class="bx bx-door-open mr-1"></i>Ruangan</th>
                                        <th><i class="bx bx-user mr-1"></i>Pemesan</th>
                                        <th><i class="bx bx-check-circle mr-1"></i>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $zebra = false; @endphp
                                    @forelse($bookings as $b)
                                        <tr style="vertical-align:middle;background:{{ ($zebra = !$zebra) ? '#f8fafc' : '#fff' }};transition:background 0.2s;border-bottom:1px solid #e3e8ee;">
                                            <td>{{ $b->booking_time }} - {{ $b->booking_end }}</td>
                                            <td>{{ $b->meeting_title ?? '-' }}</td>
                                            <td>{{ $b->facility->name ?? '-' }}</td>
                                            <td>{{ $b->user->name ?? '-' }}</td>
                                            <td>
                                                <span class="badge-status" style="background:#e6f9f0;color:#11998e;">
                                                    <i class="bx bx-check-circle"></i> Disetujui
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="5" class="text-center text-muted">Tidak ada agenda booking untuk tanggal {{ $agenda->tanggal->format('d-m-Y') }}.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3">
                            <small class="text-muted">
                                <i class="bx bx-info-circle"></i> 
                                Menampilkan booking yang sudah disetujui untuk tanggal {{ $agenda->tanggal->format('d-m-Y') }}. 
                                <a href="{{ route('admin.bookings.index') }}" class="text-primary">Lihat semua booking</a>
                            </small>
                        </div>
                    </div>
                </div>

             </div>
        </div>
    </div>
</div>
<div id="confirmModal" class="custom-modal-backdrop" style="display:none;">
  <div class="custom-modal">
    <div class="custom-modal-header">
      <span class="custom-modal-title">Konfirmasi Pengiriman</span>
    </div>
    <div class="custom-modal-body">
      <p>Apakah Anda yakin ingin mengirim pesan agenda harian ke Bu Fitri?</p>
    </div>
    <div class="custom-modal-footer">
      <button id="confirmSendBtn" class="btn btn-success" style="min-width:110px;padding:10px 20px;">Ya, Kirim</button>
      <button id="cancelSendBtn" class="btn btn-secondary" style="min-width:90px;padding:10px 20px;">Batal</button>
    </div>
  </div>
</div>

<script>
function copyPesan() {
    var pesan = document.getElementById('pesanJarkoman');
    pesan.select();
    pesan.setSelectionRange(0, 99999);
    document.execCommand('copy');
    
    // Buat toast notification
    showToast('Pesan berhasil dicopy!', 'success');
}

function showToast(message, type = 'info') {
    // Hapus toast yang sudah ada
    const existingToast = document.querySelector('.custom-toast');
    if (existingToast) {
        existingToast.remove();
    }
    
    // Buat toast baru
    const toast = document.createElement('div');
    toast.className = 'custom-toast';
    toast.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
        padding: 16px 20px;
        border-radius: 12px;
        color: white;
        font-weight: 600;
        font-size: 0.95rem;
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255,255,255,0.2);
        display: flex;
        align-items: center;
        gap: 10px;
        max-width: 350px;
        animation: slideInRight 0.3s ease-out;
        ${type === 'success' ? 'background: linear-gradient(135deg, #38ef7d 0%, #11998e 100%);' : 
          type === 'error' ? 'background: linear-gradient(135deg, #ff416c 0%, #ff4b2b 100%);' : 
          'background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);'}
    `;
    
    const icon = type === 'success' ? 'bx-check-circle' : type === 'error' ? 'bx-x-circle' : 'bx-info-circle';
    
    toast.innerHTML = `
        <i class="bx ${icon}" style="font-size: 1.2rem;"></i>
        <span>${message}</span>
        <button onclick="this.parentElement.remove()" style="background: none; border: none; color: white; margin-left: auto; cursor: pointer; padding: 0; font-size: 1.1rem;">
            <i class="bx bx-x"></i>
        </button>
    `;
    
    document.body.appendChild(toast);
    
    // Auto remove setelah 3 detik
    setTimeout(() => {
        if (toast.parentElement) {
            toast.style.animation = 'slideOutRight 0.3s ease-in';
            setTimeout(() => {
                if (toast.parentElement) {
                    toast.remove();
                }
            }, 300);
        }
    }, 3000);
}

// Fungsi untuk menampilkan modal konfirmasi
function showConfirmModal(onConfirm) {
    const modal = document.getElementById('confirmModal');
    modal.style.display = 'flex';
    // Fokus ke tombol konfirmasi
    setTimeout(() => { document.getElementById('confirmSendBtn').focus(); }, 100);
    // Event tombol
    const confirmBtn = document.getElementById('confirmSendBtn');
    const cancelBtn = document.getElementById('cancelSendBtn');
    // Bersihkan event sebelumnya
    confirmBtn.onclick = null;
    cancelBtn.onclick = null;
    // Konfirmasi
    confirmBtn.onclick = function() {
        modal.style.display = 'none';
        onConfirm();
    };
    // Batal
    cancelBtn.onclick = function() {
        modal.style.display = 'none';
    };
}

// Ubah fungsi sendMessage()
function sendMessage() {
    showConfirmModal(function() {
        const sendButton = document.getElementById('sendButton');
        const originalText = sendButton.innerHTML;
        sendButton.setAttribute('disabled', 'disabled');
        sendButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Mengirim ke Bu Fitri...';
        fetch('{{ route("admin.send_message.send", $agenda->id) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({})
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast('Pesan berhasil dikirim ke Bu Fitri via WhatsApp!', 'success');
                if (data.status) {
                    setTimeout(() => { window.location.reload(); }, 3000);
                }
            } else {
                showToast(data.message || 'Gagal mengirim pesan!', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Terjadi kesalahan saat mengirim pesan! Silakan coba lagi.', 'error');
        })
        .finally(() => {
            sendButton.removeAttribute('disabled');
            sendButton.innerHTML = originalText;
        });
    });
}

// Tambahkan CSS untuk animasi
const style = document.createElement('style');
style.textContent = `
    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes slideOutRight {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);
</script>
<style>
.custom-modal-backdrop {
  position: fixed;
  top: 0; left: 0; right: 0; bottom: 0;
  background: rgba(30, 44, 72, 0.35);
  z-index: 99999;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: background 0.2s;
}
.custom-modal {
  background: #fff;
  border-radius: 18px;
  box-shadow: 0 8px 32px rgba(44,62,80,0.18);
  min-width: 340px;
  max-width: 95vw;
  padding: 0 0 18px 0;
  animation: modalPopIn 0.25s cubic-bezier(.4,2,.6,1) both;
}
.custom-modal-header {
  padding: 18px 24px 0 24px;
}
.custom-modal-title {
  font-size: 1.18rem;
  font-weight: 700;
  color: #1e3c72;
}
.custom-modal-body {
  padding: 16px 24px 0 24px;
  color: #222;
  font-size: 1.05rem;
}
.custom-modal-footer {
  padding: 18px 24px 0 24px;
  display: flex;
  gap: 16px;
  justify-content: flex-end;
}
@keyframes modalPopIn {
  from { transform: scale(0.85); opacity: 0; }
  to { transform: scale(1); opacity: 1; }
}
.custom-btn-spacing {
    border-radius: 8px !important;
    font-weight: 600;
    padding: 10px 22px !important;
    margin-right: 0px !important;
}
.d-flex.flex-wrap .custom-btn-spacing:not(:last-child) {
    margin-right: 16px !important;
}
</style>
@endsection
