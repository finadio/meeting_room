@extends('admin.layouts.admin_dashboard')

@section('title', 'Kirim Agenda Harian')

@section('content')
<div class="booking-page-wrapper position-relative" style="min-height: 100vh;">
    <div class="container-fluid px-4">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                {{-- HERO SECTION: Agenda Booking Hari Ini --}}
                <div class="booking-card mb-5" style="background:#fff;border-radius:24px;box-shadow:0 8px 32px rgba(44,62,80,0.08);border:1px solid #e9ecef;">
                    <div class="booking-header text-center position-relative" style="background:linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);padding:32px 20px 24px 20px;border-radius:24px 24px 0 0;overflow:hidden;">
                        <div style="content:'';position:absolute;top:-50%;left:-50%;width:200%;height:200%;background:radial-gradient(circle,rgba(255,255,255,0.1) 0%,transparent 70%);animation:rotate 8s linear infinite;z-index:1;"></div>
                        <div class="header-icon mx-auto mb-2" style="background:linear-gradient(135deg,#4facfe 0%,#00f2fe 100%);width:60px;height:60px;display:flex;align-items:center;justify-content:center;border-radius:50%;box-shadow:0 10px 25px rgba(79,172,254,0.3);position:relative;z-index:2;">
                            <i class="bx bx-calendar-event" style="font-size:2rem;color:#fff;"></i>
                        </div>
                        <h2 class="header-title mb-1" style="color:#fff;font-size:1.5rem;font-weight:700;position:relative;z-index:2;text-shadow:2px 2px 4px rgba(0,0,0,0.3);">Agenda Booking Hari Ini</h2>
                        <div style="width:60px;height:3px;background:linear-gradient(90deg,#4facfe,#00f2fe);border-radius:2px;margin:12px auto 0 auto;position:relative;z-index:2;"></div>
                        <div class="text-light mt-2" style="font-size:1.1rem;opacity:0.9;position:relative;z-index:2;">{{ now()->isoFormat('dddd, D MMMM Y') }}</div>
                        <style>@keyframes rotate{from{transform:rotate(0deg);}to{transform:rotate(360deg);}}</style>
                    </div>
                    <div class="booking-content" style="padding:28px 24px 24px 24px;">
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
                                    @php
                                        $todayBookings = $bookings->where('booking_date', now()->toDateString());
                                    @endphp
                                    @forelse($todayBookings as $b)
                                        <tr style="vertical-align:middle;">
                                            <td>{{ $b->booking_time }} - {{ $b->booking_end }}</td>
                                            <td>{{ $b->meeting_title ?? '-' }}</td>
                                            <td>{{ $b->facility->name ?? '-' }}</td>
                                            <td>{{ $b->user->name ?? '-' }}</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="4" class="text-center text-muted">Tidak ada agenda booking hari ini.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- SUBSECTION: Tambah/Edit Agenda Harian --}}
                <div class="booking-card mb-5" style="background:#fff;border-radius:24px;box-shadow:0 8px 32px rgba(44,62,80,0.08);border:1px solid #e9ecef;">
                    <div class="px-4 pt-4 pb-0">
                        <h4 class="mb-3" style="color:#4A6DFB;font-weight:700;">Tambah / Edit Agenda Harian</h4>
                    </div>
                    <div class="booking-content" style="padding:8px 24px 24px 24px;">
                        @if(session('success'))
                            <div class="custom-alert success-alert mb-4" style="background:#38ef7d;color:#fff;border-radius:12px;">
                                <div class="alert-icon"><i class="bx bx-check-circle"></i></div>
                                <div class="alert-content"><strong>Berhasil!</strong> <span>{{ session('success') }}</span></div>
                                <button type="button" class="alert-close" data-bs-dismiss="alert"><i class="bx bx-x"></i></button>
                            </div>
                        @endif
                        <form id="agendaForm" method="POST" action="{{ route('admin.send_message.store') }}">
                            @csrf
                            <div class="mb-4">
                                <div id="mingguan-list">
                                    <div class="row mingguan-item mb-3 p-3" style="background:#f8f9fa;border-radius:14px;box-shadow:0 2px 8px rgba(44,62,80,0.03);">
                                        <div class="col-md-2 mb-2">
                                            <label class="font-weight-bold">Tanggal</label>
                                            <input type="date" name="ootd[0][date]" class="form-control rounded-lg" required>
                                        </div>
                                        <div class="col-md-4 mb-2">
                                            <label class="font-weight-bold">OOTD Cewek</label>
                                            <input type="text" name="ootd[0][cewek]" class="form-control rounded-lg" placeholder="Contoh: Kebaya Merah">
                                        </div>
                                        <div class="col-md-4 mb-2">
                                            <label class="font-weight-bold">OOTD Cowok</label>
                                            <input type="text" name="ootd[0][cowok]" class="form-control rounded-lg" placeholder="Contoh: Surjan/Lurik">
                                        </div>
                                        <div class="col-md-2 mb-2 d-flex align-items-end">
                                            <button type="button" class="btn btn-outline-danger btn-remove-mingguan w-100" style="display:none"><i class="bx bx-trash"></i> Hapus Hari</button>
                                        </div>
                                        <div class="col-12">
                                            <div class="agenda-manual-list"></div>
                                            <button type="button" class="btn btn-sm btn-outline-primary btn-add-agenda-manual mt-2"><i class="bx bx-plus"></i> Tambah Agenda Manual</button>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-outline-success mt-3" id="btn-add-mingguan"><i class="bx bx-plus"></i> Tambah Hari</button>
                            </div>
                            <button type="submit" class="btn btn-primary font-weight-bold px-4 py-2" style="border-radius:10px;font-size:1.1rem;background:#4A6DFB;border:none;"><i class="bx bx-save"></i> Simpan Agenda</button>
                        </form>
                    </div>
                </div>

                {{-- SUBSECTION: History Agenda Harian --}}
                <div class="booking-card mb-5" style="background:#fff;border-radius:24px;box-shadow:0 8px 32px rgba(44,62,80,0.08);border:1px solid #e9ecef;">
                    <div class="px-4 pt-4 pb-0">
                        <h4 class="mb-3" style="color:#4A6DFB;font-weight:700;">History Agenda Harian</h4>
                    </div>
                    <div class="booking-content" style="padding:8px 24px 24px 24px;">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0" style="background:#fff;border-radius:16px;overflow:hidden;">
                                <thead style="background:#f4f7fd;">
                                    <tr style="font-weight:600;font-size:1.05rem;color:#4A6DFB;">
                                        <th>Tanggal</th>
                                        <th>OOTD Cewek</th>
                                        <th>OOTD Cowok</th>
                                        <th>Status Kirim</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($history as $item)
                                        <tr style="vertical-align:middle;">
                                            <td>{{ $item->tanggal->format('d-m-Y') }}</td>
                                            <td>{{ $item->ootd_cewek }}</td>
                                            <td>{{ $item->ootd_cowok }}</td>
                                            <td>
                                                <span class="badge" style="font-size:0.95em;padding:6px 14px;border-radius:8px;letter-spacing:0.5px;
                                                    @if($item->status_kirim=='terkirim')background:#38ef7d;color:#fff;
                                                    @elseif($item->status_kirim=='gagal')background:#ff416c;color:#fff;
                                                    @else background:#e9ecef;color:#4A6DFB;@endif">
                                                    {{ ucfirst($item->status_kirim) }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.send_message.show', $item->id) }}" class="btn btn-info btn-sm px-3 py-1" style="border-radius:8px;background:#4A6DFB;border:none;"><i class="bx bx-search"></i> Lihat Detail</a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="5" class="text-center text-muted">Belum ada data</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
let mingguanIndex = 1;
// Tambah hari baru
document.getElementById('btn-add-mingguan').addEventListener('click', function() {
    const mingguanList = document.getElementById('mingguan-list');
    const newItem = document.createElement('div');
    newItem.className = 'row mingguan-item mb-3 p-3';
    newItem.style.background = '#f8f9fa';
    newItem.style.borderRadius = '14px';
    newItem.style.boxShadow = '0 2px 8px rgba(44,62,80,0.03)';
    newItem.innerHTML = `
        <div class="col-md-2 mb-2">
            <label class="font-weight-bold">Tanggal</label>
            <input type="date" name="ootd[${mingguanIndex}][date]" class="form-control rounded-lg" required>
        </div>
        <div class="col-md-4 mb-2">
            <label class="font-weight-bold">OOTD Cewek</label>
            <input type="text" name="ootd[${mingguanIndex}][cewek]" class="form-control rounded-lg" placeholder="Contoh: Kebaya Merah">
        </div>
        <div class="col-md-4 mb-2">
            <label class="font-weight-bold">OOTD Cowok</label>
            <input type="text" name="ootd[${mingguanIndex}][cowok]" class="form-control rounded-lg" placeholder="Contoh: Surjan/Lurik">
        </div>
        <div class="col-md-2 mb-2 d-flex align-items-end">
            <button type="button" class="btn btn-outline-danger btn-remove-mingguan w-100" style="display:none"><i class="bx bx-trash"></i> Hapus Hari</button>
        </div>
        <div class="col-12">
            <div class="agenda-manual-list"></div>
            <button type="button" class="btn btn-sm btn-outline-primary btn-add-agenda-manual mt-2"><i class="bx bx-plus"></i> Tambah Agenda Manual</button>
        </div>
    `;
    mingguanList.appendChild(newItem);
    mingguanIndex++;
    updateRemoveHariButtons();
});
// Hapus hari
document.getElementById('mingguan-list').addEventListener('click', function(e) {
    if (e.target.classList.contains('btn-remove-mingguan')) {
        e.target.closest('.mingguan-item').remove();
        updateRemoveHariButtons();
    }
});
function updateRemoveHariButtons() {
    const items = document.querySelectorAll('.mingguan-item');
    items.forEach((item, idx) => {
        const btn = item.querySelector('.btn-remove-mingguan');
        btn.style.display = (items.length > 1) ? 'inline-block' : 'none';
    });
}
// Dynamic agenda manual per hari
document.getElementById('mingguan-list').addEventListener('click', function(e) {
    if (e.target.classList.contains('btn-add-agenda-manual')) {
        const parent = e.target.closest('.mingguan-item');
        const agendaList = parent.querySelector('.agenda-manual-list');
        const hariIdx = Array.from(document.querySelectorAll('.mingguan-item')).indexOf(parent);
        const agendaIdx = agendaList.querySelectorAll('.agenda-manual-item').length;
        const newAgenda = document.createElement('div');
        newAgenda.className = 'row agenda-manual-item mb-2';
        newAgenda.innerHTML = `
            <div class="col-md-2">
                <input type="hidden" name="agenda[${hariIdx}_${agendaIdx}][date]" value="" class="agenda-date-ref">
                <input type="time" name="agenda[${hariIdx}_${agendaIdx}][jam]" class="form-control rounded-lg" placeholder="Jam">
            </div>
            <div class="col-md-6">
                <input type="text" name="agenda[${hariIdx}_${agendaIdx}][judul]" class="form-control rounded-lg" placeholder="Judul Agenda">
            </div>
            <div class="col-md-3">
                <input type="text" name="agenda[${hariIdx}_${agendaIdx}][lokasi]" class="form-control rounded-lg" placeholder="Lokasi (opsional)">
            </div>
            <div class="col-md-1 d-flex align-items-center">
                <button type="button" class="btn btn-sm btn-outline-danger btn-remove-agenda-manual"><i class="bx bx-trash"></i></button>
            </div>
        `;
        agendaList.appendChild(newAgenda);
    }
});
document.getElementById('mingguan-list').addEventListener('click', function(e) {
    if (e.target.classList.contains('btn-remove-agenda-manual')) {
        e.target.closest('.agenda-manual-item').remove();
    }
});
</script>
@endsection 