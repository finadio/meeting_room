@extends('admin.layouts.admin_dashboard')
@section('title', 'User Management')

@section('content')
<div class="booking-page-wrapper">
    <div class="container-fluid px-4">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="booking-card">
                    <div class="booking-header">
                        <div class="header-icon">
                            <i class="bx bx-group"></i>
                        </div>
                        <h3 class="header-title">Manajemen Pengguna</h3>
                        <div class="header-decoration"></div>
                    </div>
                    
                    <div class="booking-content">
                        @if(session('success'))
                            <div class="custom-alert success-alert">
                                <div class="alert-icon">
                                    <i class="bx bx-check-circle"></i>
                                </div>
                                <div class="alert-content">
                                    <strong>Berhasil!</strong>
                                    <span>{{ session('success') }}</span>
                                </div>
                                <button type="button" class="alert-close" data-bs-dismiss="alert">
                                    <i class="bx bx-x"></i>
                                </button>
                            </div>
                        @endif
                        
                        @if(session('error'))
                            <div class="custom-alert danger-alert">
                                <div class="alert-icon">
                                    <i class="bx bx-error-circle"></i>
                                </div>
                                <div class="alert-content">
                                    <strong>Error!</strong>
                                    <span>{{ session('error') }}</span>
                                </div>
                                <button type="button" class="alert-close" data-bs-dismiss="alert">
                                    <i class="bx bx-x"></i>
                                </button>
                            </div>
                        @endif

                        @if (session('message'))
                            <div class="custom-alert danger-alert">
                                <div class="alert-icon">
                                    <i class="bx bx-error-circle"></i>
                                </div>
                                <div class="alert-content">
                                    <strong>Pesan!</strong>
                                    <span>{{ session('message') }}</span>
                                </div>
                                <button type="button" class="alert-close" data-bs-dismiss="alert">
                                    <i class="bx bx-x"></i>
                                </button>
                            </div>
                        @endif

                        <div class="table-container">
                            <div class="table-actions-controls">
                                <form action="{{ route('admin.users.index') }}" method="GET" class="search-form">
                                    <div class="search-input-wrapper">
                                        <input type="text" name="search" class="form-control-enhanced search-input" placeholder="Cari pengguna..." value="{{ request('search') }}">
                                        <button type="submit" class="search-button">
                                            <i class="bx bx-search"></i>
                                        </button>
                                    </div>
                                </form>
                                
                                <a href="{{ route('admin.users.create') }}" class="btn-submit">
                                    <div class="btn-content">
                                        <i class="bx bx-user-plus"></i>
                                        <span>Tambah Pengguna</span>
                                    </div>
                                    <div class="btn-ripple"></div>
                                </a>
                            </div>

                            <table class="modern-table">
                                <thead>
                                    <tr>
                                        <th>S.N</th>
                                        <th>ID Pengguna</th>
                                        <th>Gambar</th>
                                        <th>Nama</th>
                                        <th>Email</th>
                                        <th>Tipe Pengguna</th>
                                        <th>Status</th>
                                        <th>Verifikasi</th>
                                        <th>Terakhir Aktif</th>
                                        <th>Terdaftar Pada</th>
                                        <th>Tipe Pendaftaran</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($users as $user)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $user->id }}</td>
                                            <td>
                                                @if($user->profile_picture && Storage::exists('public/profile_pictures/' . $user->profile_picture))
                                                    <img src="{{ asset('storage/profile_pictures/' . $user->profile_picture) }}" alt="User Image" class="table-profile-img">
                                                @else
                                                    <img src="https://bootdey.com/img/Content/avatar/avatar1.png" alt="Default User Image" class="table-profile-img">
                                                @endif
                                            </td>
                                            <td class="text-truncate" style="max-width: 150px;">{{ $user->name }}</td>
                                            <td class="text-truncate" style="max-width: 150px;">{{ $user->email }}</td>
                                            <td>
                                                @if($user->user_type === 'user')
                                                    <span class="badge badge-primary-custom">{{ ucfirst($user->user_type) }}</span>
                                                @elseif($user->user_type === 'admin')
                                                    <span class="badge badge-danger-custom">{{ ucfirst($user->user_type) }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($user->isBanned())
                                                    <span class="badge badge-danger-custom">Diblokir</span>
                                                @else
                                                    <span class="badge badge-success-custom">Aktif</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if($user->verified)
                                                    <i class='bx bx-check-circle text-success' style="font-size: 1.2rem;"></i>
                                                @else
                                                    <i class='bx bx-x-circle text-danger' style="font-size: 1.2rem;"></i>
                                                @endif
                                            </td>
                                            <td class="text-truncate" style="max-width: 120px;">
                                                @if($user->last_active)
                                                    {{ \Carbon\Carbon::parse($user->last_active)->diffForHumans(null, true) }} lalu
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                            <td class="text-truncate" style="max-width: 120px;">{{ \Carbon\Carbon::parse($user->created_at)->format('d F Y') }}</td>
                                            <td>
                                                {{ $user->register_type ?? 'Administrator' }}
                                            </td>
                                            <td class="actions-cell">
                                                <div class="action-buttons-group">
                                                    <a href="{{ route('admin.users.show', $user) }}" class="btn-action view" title="Lihat">
                                                        <i class='bx bx-show'></i>
                                                    </a>
                                                    <a href="{{ route('admin.users.edit', $user->id) }}" class="btn-action edit" title="Edit">
                                                        <i class='bx bx-edit'></i>
                                                    </a>
                                                    @if($user->isBanned())
                                                        <form action="{{ route('admin.users.unban', $user) }}" method="post" style="display: inline-block;">
                                                            @csrf
                                                            <button type="submit" class="btn-action unban" title="Buka Blokir" onclick="return confirm('Apakah Anda yakin ingin membuka blokir pengguna ini?')">
                                                                <i class='bx bx-check'></i>
                                                            </button>
                                                        </form>
                                                    @else
                                                        <form action="{{ route('admin.users.ban', $user) }}" method="post" style="display: inline-block;">
                                                            @csrf
                                                            <button type="submit" class="btn-action ban" title="Blokir" onclick="return confirm('Apakah Anda yakin ingin memblokir pengguna ini?')">
                                                                <i class='bx bx-block'></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="12" class="text-center">
                                                @if(request('search'))
                                                    Tidak ada pengguna yang ditemukan untuk pencarian: "{{ request('search') }}"
                                                @else
                                                    Tidak ada data pengguna.
                                                @endif
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            <div class="pagination-wrapper">
                                {{ $users->links('vendor.pagination.bootstrap-4') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
<style>
    /* Page Wrapper */
    .booking-page-wrapper {
        min-height: 100vh;
        background: rgba(255, 255, 255, 0.95);
        padding: 40px 0;
        position: relative;
    }

    .booking-page-wrapper::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="%23ffffff" opacity="0.05"/><circle cx="75" cy="75" r="1" fill="%23ffffff" opacity="0.05"/><circle cx="50" cy="10" r="1" fill="%23ffffff" opacity="0.03"/><circle cx="10" cy="50" r="1" fill="%23ffffff" opacity="0.03"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
        pointer-events: none;
    }

    /* Main Card */
    .booking-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border-radius: 24px;
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
        overflow: hidden;
        position: relative;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .booking-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 2px;
        background: linear-gradient(90deg, #667eea, #764ba2, #f093fb, #f5576c);
        background-size: 400% 400%;
        animation: gradient-flow 3s ease infinite;
    }

    @keyframes gradient-flow {
        0%, 100% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
    }

    /* Header */
    .booking-header {
        background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        padding: 30px;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .booking-header::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
        animation: rotate 8s linear infinite;
    }

    @keyframes rotate {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }

    .header-icon {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        width: 70px;
        height: 70px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        position: relative;
        z-index: 2;
        box-shadow: 0 10px 25px rgba(79, 172, 254, 0.3);
    }

    .header-icon i {
        font-size: 2rem;
        color: white;
    }

    .header-title {
        color: white;
        font-size: 1.8rem;
        font-weight: 700;
        margin: 0;
        position: relative;
        z-index: 2;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
    }

    .header-decoration {
        width: 60px;
        height: 4px;
        background: linear-gradient(90deg, #4facfe, #00f2fe);
        margin: 15px auto 0;
        border-radius: 2px;
        position: relative;
        z-index: 2;
    }

    /* Content */
    .booking-content {
        padding: 40px;
    }

    /* Alerts */
    .custom-alert {
        display: flex;
        align-items: center;
        padding: 20px;
        border-radius: 16px;
        margin-bottom: 30px;
        position: relative;
        overflow: hidden;
        border: none;
        animation: slideInDown 0.5s ease-out;
    }

    @keyframes slideInDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .success-alert {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        color: white;
    }

    .danger-alert {
        background: linear-gradient(135deg, #ff416c 0%, #ff4b2b 100%);
        color: white;
    }

    .alert-icon {
        font-size: 1.5rem;
        margin-right: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 50%;
    }

    .alert-content {
        flex: 1;
    }

    .alert-content strong {
        display: block;
        font-size: 1rem;
        margin-bottom: 2px;
    }

    .alert-close {
        background: none;
        border: none;
        color: white;
        font-size: 1.2rem;
        cursor: pointer;
        padding: 5px;
        border-radius: 50%;
        transition: background-color 0.3s ease;
    }

    .alert-close:hover {
        background: rgba(255, 255, 255, 0.2);
    }

    /* Table Specific Styles */
    .table-container {
        overflow-x: auto;
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        padding: 20px;
    }

    .table-actions-controls {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
        gap: 20px;
    }

    /* Search Form */
    .search-form {
        flex: 0 0 auto;
    }

    .search-input-wrapper {
        position: relative;
        display: flex;
        align-items: center;
        min-width: 280px;
    }

    .search-input {
        width: 100%;
        padding: 12px 45px 12px 16px;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        font-size: 0.95rem;
        background: #fff;
        transition: all 0.3s ease;
        color: #2d3748;
    }

    .search-input:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .search-input::placeholder {
        color: #a0aec0;
    }

    .search-button {
        position: absolute;
        right: 8px;
        background: none;
        border: none;
        padding: 8px;
        cursor: pointer;
        color: #a0aec0;
        transition: color 0.3s ease;
        border-radius: 6px;
    }

    .search-button:hover {
        color: #667eea;
        background: rgba(102, 126, 234, 0.1);
    }

    .search-button i {
        font-size: 1.1rem;
    }

    .modern-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0 10px;
        margin-bottom: 20px;
    }

    .modern-table thead th {
        background: linear-gradient(135deg, #e0e7ff 0%, #c3dafe 100%);
        color: #1e3c72;
        padding: 15px 20px;
        font-weight: 700;
        text-align: left;
        border-bottom: none;
        position: sticky;
        top: 0;
        z-index: 10;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .modern-table thead th:first-child {
        border-top-left-radius: 12px;
        border-bottom-left-radius: 12px;
    }

    .modern-table thead th:last-child {
        border-top-right-radius: 12px;
        border-bottom-right-radius: 12px;
    }

    .modern-table tbody tr {
        background: #fdfefe;
        border-radius: 12px;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }

    .modern-table tbody tr:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.1);
    }

    .modern-table tbody td {
        padding: 15px 20px;
        vertical-align: middle;
        border-top: none;
        font-size: 0.9rem;
        color: #333;
    }

    .modern-table tbody td:first-child {
        border-bottom-left-radius: 12px;
        border-top-left-radius: 12px;
    }

    .modern-table tbody td:last-child {
        border-bottom-right-radius: 12px;
        border-top-right-radius: 12px;
    }

    .table-profile-img {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #e2e8f0;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }

    /* Badges */
    .badge-primary-custom {
        background-color: #667eea;
        color: white;
        padding: 6px 10px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.8em;
    }
    .badge-danger-custom {
        background-color: #ff416c;
        color: white;
        padding: 6px 10px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.8em;
    }
    .badge-success-custom {
        background-color: #38ef7d;
        color: white;
        padding: 6px 10px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.8em;
    }

    /* Action Buttons */
    .actions-cell {
        white-space: nowrap;
    }
    .action-buttons-group {
        display: flex;
        gap: 8px;
    }
    .btn-action {
        width: 38px;
        height: 38px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        font-size: 1rem;
        transition: all 0.2s ease;
        text-decoration: none;
        color: white;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
    .btn-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(0,0,0,0.15);
        color: white;
    }

    .btn-action.view { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }
    .btn-action.edit { background: linear-gradient(135deg, #fbc02d 0%, #f57f17 100%); }
    .btn-action.unban { background: linear-gradient(135deg, #38ef7d 0%, #11998e 100%); }
    .btn-action.ban { background: linear-gradient(135deg, #ff416c 0%, #ff4b2b 100%); }

    /* Submit Button */
    .btn-submit {
        background: #667eea 0%;
        border: none;
        border-radius: 16px;
        padding: 14px 28px;
        color: white;
        font-weight: 600;
        font-size: 1rem;
        cursor: pointer;
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        flex-shrink: 0;
    }

    .btn-submit:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 35px rgba(102, 126, 234, 0.4);
        color: white;
        text-decoration: none;
    }

    .btn-submit .btn-content {
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        z-index: 2;
    }

    .btn-submit .btn-content i {
        margin-right: 8px;
        font-size: 1.1rem;
    }

    .btn-submit .btn-ripple {
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        background: rgba(255, 255, 255, 0.3);
        border-radius: 50%;
        transform: translate(-50%, -50%);
        transition: width 0.3s ease, height 0.3s ease;
    }

    /* Pagination */
    .pagination-wrapper {
        margin-top: 30px;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .pagination {
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    .pagination .page-item .page-link {
        border: none;
        padding: 10px 15px;
        margin: 0 2px;
        border-radius: 8px;
        color: #667eea;
        font-weight: 600;
        transition: all 0.3s ease;
        background-color: #f0f4ff;
    }
    .pagination .page-item .page-link:hover {
        background-color: #e0e7ff;
        color: #1e3c72;
    }
    .pagination .page-item.active .page-link {
        background:#667eea 0%;
        color: white;
        box-shadow: 0 4px 10px rgba(102, 126, 234, 0.3);
    }

    /* Responsive Design */
    @media (max-width: 992px) {
        .table-actions-controls {
            flex-direction: column;
            align-items: stretch;
            gap: 15px;
        }
        
        .search-input-wrapper {
            min-width: 100%;
        }
        
        .modern-table thead th,
        .modern-table tbody td {
            padding: 12px 15px;
            font-size: 0.85rem;
        }
    }

    @media (max-width: 768px) {
        .booking-page-wrapper {
            padding: 20px 0;
        }

        .booking-content {
            padding: 30px 15px;
        }

        .booking-header {
            padding: 25px 20px;
        }

        .header-title {
            font-size: 1.5rem;
        }

        .search-input-wrapper {
            min-width: 100%;
        }

        .search-input {
            padding: 10px 40px 10px 12px;
            font-size: 0.9rem;
        }

        .modern-table {
            display: block;
            width: 100%;
            white-space: nowrap;
        }

        .modern-table thead, .modern-table tbody, .modern-table th, .modern-table td, .modern-table tr {
            display: block;
        }

        .modern-table thead tr {
            position: absolute;
            top: -9999px;
            left: -9999px;
        }

        .modern-table tbody tr {
            margin-bottom: 20px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .modern-table td {
            border: none;
            position: relative;
            padding-left: 50%;
            text-align: right;
            border-bottom: 1px solid #eee;
            font-size: 0.9rem;
        }

        .modern-table td:before {
            content: attr(data-label);
            position: absolute;
            left: 0;
            width: 45%;
            padding-left: 15px;
            font-weight: bold;
            text-align: left;
            color: #1e3c72;
            font-size: 0.85rem;
        }

        .modern-table td:nth-of-type(1):before { content: "S.N:"; }
        .modern-table td:nth-of-type(2):before { content: "ID Pengguna:"; }
        .modern-table td:nth-of-type(3):before { content: "Gambar:"; }
        .modern-table td:nth-of-type(4):before { content: "Nama:"; }
        .modern-table td:nth-of-type(5):before { content: "Email:"; }
        .modern-table td:nth-of-type(6):before { content: "Tipe:"; }
        .modern-table td:nth-of-type(7):before { content: "Status:"; }
        .modern-table td:nth-of-type(8):before { content: "Verifikasi:"; }
        .modern-table td:nth-of-type(9):before { content: "Aktif Terakhir:"; }
        .modern-table td:nth-of-type(10):before { content: "Terdaftar:"; }
        .modern-table td:nth-of-type(11):before { content: "Tipe Daftar:"; }
        .modern-table td:nth-of-type(12):before { content: "Aksi:"; }
    }
</style>
@endsection

@section('scripts')
<script>
    // Alert close functionality
    document.querySelectorAll('.alert-close').forEach(button => {
        button.addEventListener('click', function() {
            this.closest('.custom-alert').style.display = 'none';
        });
    });
</script>
@endsection