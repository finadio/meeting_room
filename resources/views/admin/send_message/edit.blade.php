@extends('admin.layouts.admin_dashboard')

@section('title', 'Edit Agenda Harian')

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
<style>
    .booking-page-wrapper {
        min-height: 100vh;
        background: rgba(255, 255, 255, 0.95);
        padding: 40px 0;
        position: relative;
    }
    .booking-page-wrapper::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="%23ffffff" opacity="0.05"/><circle cx="75" cy="75" r="1" fill="%23ffffff" opacity="0.05"/><circle cx="50" cy="10" r="1" fill="%23ffffff" opacity="0.03"/><circle cx="10" cy="50" r="1" fill="%23ffffff" opacity="0.03"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
        pointer-events: none;
    }
    .booking-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border-radius: 24px;
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
        overflow: hidden;
        position: relative;
        border: 1px solid rgba(255, 255, 255, 0.2);
        margin-bottom: 32px;
    }
    .booking-content {
        padding: 40px;
    }
    .form-group-enhanced {
        margin-bottom: 1.5rem;
    }
    .form-label-enhanced {
        font-weight: 600;
        color: #1e3c72;
        margin-bottom: 0.5rem;
        display: block;
        font-size: 0.95rem;
    }
    .form-control-enhanced {
        border: 2px solid #e3e8ee;
        border-radius: 12px;
        padding: 12px 16px;
        font-size: 1rem;
        transition: all 0.3s ease;
        background: rgba(255, 255, 255, 0.9);
    }
    .form-control-enhanced:focus {
        border-color: #4A6DFB;
        box-shadow: 0 0 0 0.2rem rgba(74, 109, 251, 0.25);
        outline: none;
    }
    .btn-submit {
        background: linear-gradient(135deg, #4A6DFB 0%, #667eea 100%);
        border: none;
        color: white;
        padding: 12px 24px;
        border-radius: 12px;
        font-weight: 600;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(74, 109, 251, 0.3);
        color: white;
        text-decoration: none;
    }
    .btn-small {
        padding: 8px 16px;
        font-size: 0.9rem;
    }
    .btn-remove-agenda {
        background: #ff416c;
        border: none;
        color: white;
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 0.8rem;
        transition: all 0.3s ease;
    }
    .btn-remove-agenda:hover {
        background: #e63946;
        transform: translateY(-1px);
    }
    .agenda-manual-item {
        background: rgba(248, 250, 252, 0.8);
        border-radius: 12px;
        padding: 16px;
        margin-bottom: 12px;
        border: 1px solid #e3e8ee;
    }
</style>
@endsection

@section('content')
<div class="booking-page-wrapper">
    <div class="container-fluid px-4">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="booking-card">
                    <div class="booking-content">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h3 class="mb-0" style="font-weight:700;color:#1e3c72;letter-spacing:0.5px;">Edit Agenda Harian</h3>
                            <a href="{{ route('admin.send_message.show', $agenda->id) }}" class="btn btn-secondary btn-sm">
                                <i class="bx bx-arrow-back"></i> Kembali ke Detail
                            </a>
                        </div>
                        
                        @if(session('success'))
                            <div class="alert alert-success mb-4" style="border-radius:12px;border:none;padding:16px 20px;">
                                <div class="d-flex align-items-center">
                                    <i class="bx bx-check-circle me-2" style="font-size:1.2rem;"></i>
                                    <strong>Berhasil!</strong> <span class="ms-2">{{ session('success') }}</span>
                                </div>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('admin.send_message.update', $agenda->id) }}">
                            @csrf
                            @method('PUT')
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group-enhanced">
                                        <label class="form-label-enhanced">Tanggal</label>
                                        <input type="date" class="form-control-enhanced" value="{{ $agenda->tanggal->format('Y-m-d') }}" readonly>
                                        <small class="text-muted">Tanggal tidak dapat diubah</small>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group-enhanced">
                                        <label class="form-label-enhanced">OOTD Cewek</label>
                                        <input type="text" name="ootd_cewek" class="form-control-enhanced" value="{{ $agenda->ootd_cewek }}" placeholder="Contoh: Kebaya Merah">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group-enhanced">
                                        <label class="form-label-enhanced">OOTD Cowok</label>
                                        <input type="text" name="ootd_cowok" class="form-control-enhanced" value="{{ $agenda->ootd_cowok }}" placeholder="Contoh: Surjan/Lurik">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group-enhanced">
                                <label class="form-label-enhanced">Agenda Manual</label>
                                <div id="agenda-manual-container">
                                    @if($agenda->agendaManuals && count($agenda->agendaManuals))
                                        @foreach($agenda->agendaManuals as $index => $item)
                                            <div class="agenda-manual-item mb-2">
                                                <div class="row g-2 align-items-end">
                                                    <div class="col-12 col-md-4">
                                                        <label class="form-label-enhanced">Jam Mulai</label>
                                                        <input type="time" name="agenda_manual[{{ $index }}][jam]" class="form-control-enhanced" value="{{ $item->jam ?? '' }}" required>
                                                    </div>
                                                    <div class="col-12 col-md-4">
                                                        <label class="form-label-enhanced">Jam Selesai</label>
                                                        <input type="time" name="agenda_manual[{{ $index }}][jam_selesai]" class="form-control-enhanced" value="{{ $item->jam_selesai ?? '' }}">
                                                    </div>
                                                    <div class="col-12 col-md-4">
                                                        <label class="form-label-enhanced">Judul Agenda</label>
                                                        <input type="text" name="agenda_manual[{{ $index }}][judul]" class="form-control-enhanced" value="{{ $item->judul ?? '' }}" placeholder="Judul agenda" required>
                                                    </div>
                                                </div>
                                                <div class="row g-2 align-items-end mt-1">
                                                    <div class="col-10 col-md-11">
                                                        <label class="form-label-enhanced">Lokasi</label>
                                                        <input type="text" name="agenda_manual[{{ $index }}][lokasi]" class="form-control-enhanced" value="{{ $item->lokasi ?? '' }}" placeholder="Lokasi (opsional)">
                                                    </div>
                                                    <div class="col-2 col-md-1 d-flex align-items-end">
                                                        <button type="button" class="btn-remove-agenda" onclick="removeAgendaItem(this)">
                                                            <i class="bx bx-trash"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                                <button type="button" class="btn-submit btn-small" onclick="addAgendaItem()">
                                    <i class="bx bx-plus"></i> Tambah Agenda
                                </button>
                            </div>

                            <div class="d-flex justify-content-end mt-4">
                                <button type="submit" class="btn-submit">
                                    <i class="bx bx-save"></i> Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
let agendaIndex = {{ $agenda->agendaManuals ? count($agenda->agendaManuals) : 0 }};

function addAgendaItem() {
    const container = document.getElementById('agenda-manual-container');
    const newItem = document.createElement('div');
    newItem.className = 'agenda-manual-item';
    newItem.innerHTML = `
        <div class="agenda-manual-item mb-2">
            <div class="row g-2 align-items-end">
                <div class="col-12 col-md-4">
                    <label class="form-label-enhanced">Jam Mulai</label>
                    <input type="time" name="agenda_manual[${agendaIndex}][jam]" class="form-control-enhanced" required>
                </div>
                <div class="col-12 col-md-4">
                    <label class="form-label-enhanced">Jam Selesai</label>
                    <input type="time" name="agenda_manual[${agendaIndex}][jam_selesai]" class="form-control-enhanced">
                </div>
                <div class="col-12 col-md-4">
                    <label class="form-label-enhanced">Judul Agenda</label>
                    <input type="text" name="agenda_manual[${agendaIndex}][judul]" class="form-control-enhanced" placeholder="Judul agenda" required>
                </div>
            </div>
            <div class="row g-2 align-items-end mt-1">
                <div class="col-10 col-md-11">
                    <label class="form-label-enhanced">Lokasi</label>
                    <input type="text" name="agenda_manual[${agendaIndex}][lokasi]" class="form-control-enhanced" placeholder="Lokasi (opsional)">
                </div>
                <div class="col-2 col-md-1 d-flex align-items-end">
                    <button type="button" class="btn-remove-agenda" onclick="removeAgendaItem(this)">
                        <i class="bx bx-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    `;
    container.appendChild(newItem);
    agendaIndex++;
}

function removeAgendaItem(button) {
    button.closest('.agenda-manual-item').remove();
}
</script>
@endsection 