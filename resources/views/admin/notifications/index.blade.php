@extends('admin.layouts.admin_dashboard')
@section('title', 'Manajemen Notifikasi') {{-- Menggunakan title yang lebih relevan --}}

@section('content')
<div class="booking-page-wrapper">
    <div class="container-fluid px-4">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="booking-card">
                    <div class="booking-header">
                        <div class="header-icon">
                            <i class="bx bx-bell"></i>
                        </div>
                        <h3 class="header-title">Manajemen Notifikasi</h3>
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

                        <div class="table-container">
                            <div class="table-actions-controls">
                                <form action="{{ route('admin.notifications.index') }}" method="GET" class="search-form" id="notification-search-form">
                                    <div class="search-input-wrapper">
                                        <input type="text" name="search" class="form-control-enhanced search-input" placeholder="Cari notifikasi..." value="{{ request('search') }}">
                                        <button type="submit" class="search-button">
                                            <i class="bx bx-search"></i>
                                        </button>
                                    </div>
                                </form>
                            </div>
                            <div id="notification-table-wrapper">
                                @include('admin.notifications.partials.table', ['notifications' => $notifications])
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL CUSTOM untuk konfirmasi tandai sudah dibaca --}}
<div id="confirmMarkAsReadModal" class="custom-modal-backdrop" style="display:none;">
  <div class="custom-modal">
    <div class="custom-modal-header">
      <span class="custom-modal-title">Konfirmasi Aksi</span>
      <button type="button" class="alert-close" onclick="closeConfirmMarkAsReadModal()"><i class="bx bx-x"></i></button>
    </div>
    <div class="custom-modal-body">
      <p>Apakah Anda yakin ingin menandai notifikasi ini sudah dibaca?</p>
    </div>
    <div class="custom-modal-footer">
      <button id="cancelMarkAsReadBtn" class="btn btn-secondary" style="min-width:90px;padding:10px 20px;" onclick="closeConfirmMarkAsReadModal()">Batal</button>
      <button id="confirmMarkAsReadBtn" class="btn btn-success" style="min-width:110px;padding:10px 20px;">Ya, Tandai</button>
    </div>
  </div>
</div>
@endsection

@section('styles')
{{-- Memastikan Bootstrap dimuat --}}
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" xintegrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
{{-- Memastikan Boxicons dimuat --}}
<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
{{-- Memastikan Font Awesome dimuat untuk ikon notifikasi spesifik --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" xintegrity="sha512-Fo3rlrZj/k7ujTnHg4CGR2D7kSs0V4LLanw2qksYuRlEzO+tcaEPQogQ0KaoGN26/zrn20ImR1DfuLWnOo7aBA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

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
        background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
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
        overflow-x: auto; /* Ensures table is scrollable on small screens */
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
        flex-wrap: wrap; /* Allow wrapping on smaller screens */
        gap: 15px; /* Space between search and button */
    }

    .search-form {
        flex-grow: 1; /* Allow search bar to take available space */
        max-width: 400px; /* Limit max width of search bar */
    }

    .search-input-wrapper {
        position: relative;
        display: flex;
        align-items: center;
    }

    .search-input {
        width: 100%;
        padding: 12px 40px 12px 20px; /* Adjust padding for icon */
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        font-size: 1rem;
        background: white;
        transition: all 0.3s ease;
        outline: none;
    }

    .search-input:focus {
        border-color: #4facfe;
        box-shadow: 0 0 0 4px rgba(79, 172, 254, 0.1);
    }

    .search-input::placeholder {
        color: #a0aec0;
    }

    .search-button {
        position: absolute;
        right: 10px;
        background: none;
        border: none;
        color: #a0aec0;
        font-size: 1.2rem;
        cursor: pointer;
        transition: color 0.3s ease;
    }

    .search-button:hover {
        color: #667eea;
    }

    .clear-search {
        position: absolute;
        right: 40px; /* Adjust position to not overlap with search button */
        background: none;
        border: none;
        color: #a0aec0;
        font-size: 1.2rem;
        cursor: pointer;
        transition: color 0.3s ease;
    }

    .clear-search:hover {
        color: #ff416c;
    }


    .modern-table {
        width: 100%;
        border-collapse: separate; /* Use separate to allow border-radius on cells */
        border-spacing: 0 10px; /* Space between rows */
        margin-bottom: 20px;
    }

    .modern-table thead th {
        background: linear-gradient(135deg, #e0e7ff 0%, #c3dafe 100%); /* Light blue gradient for header */
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
        background: #fdfefe; /* Slightly off-white for rows */
        border-radius: 12px;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05); /* Subtle shadow for each row */
    }

    .modern-table tbody tr:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.1);
    }

    .modern-table tbody td {
        padding: 15px 20px;
        vertical-align: middle;
        border-top: none; /* Remove default table borders */
        font-size: 0.9rem;
        color: #333;
    }

    .modern-table tbody tr:first-child td {
        border-top: none; /* No top border for the first row's cells */
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
        border-radius: 8px; /* Slightly less rounded for facilities */
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
    .badge-info-custom {
        background-color: #4facfe; /* A light blue for info status */
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
    .badge-secondary-custom {
        background-color: #6c757d;
        color: white;
        padding: 6px 10px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.8em;
    }

    /* Action Buttons */
    .actions-cell {
        white-space: nowrap; /* Keep buttons on one line */
    }
    .action-buttons-group {
        display: flex;
        flex-wrap: wrap; /* Allow buttons to wrap if too many */
        gap: 8px; /* Space between buttons */
        justify-content: center; /* Center buttons horizontally */
    }
    .btn-action {
        min-width: 75px; /* Minimum width for action buttons */
        height: 38px;
        padding: 0 10px; /* Adjust padding for text and icon */
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        font-size: 0.9rem;
        transition: all 0.2s ease;
        text-decoration: none;
        color: white; /* Default white for icons and text */
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        border: none;
        cursor: pointer;
    }
    .btn-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(0,0,0,0.15);
        color: white; /* Ensure color stays white on hover */
        text-decoration: none;
    }

    /* Specific Button Colors */
    .btn-action.view { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }
    .btn-action.edit { background: linear-gradient(135deg, #fbc02d 0%, #f57f17 100%); }
    .btn-action.delete { background: linear-gradient(135deg, #dc3545 0%, #e83e8c 100%); }

    .btn-action i {
        margin-right: 5px; /* Space between icon and text for buttons with text */
    }
    /* MODIFIED: Ensure btn-action.success is truly green and its icon white */
    .btn-action.success {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%) !important; /* Force green background */
        border-radius: 10px !important; /* Force rounded corners */
        color: white !important; /* Ensure the button text/icon is white */
        box-shadow: 0 4px 10px rgba(17, 153, 142, 0.3) !important; /* Adjust shadow for green */
    }
    .btn-action.success i.bx-check {
        color: white !important; /* Make the check icon white for high contrast */
    }


    /* Time Badges for Facilities */
    .time-badge {
        display: inline-block;
        padding: 6px 10px;
        border-radius: 8px;
        font-size: 0.8em;
        font-weight: 600;
        color: white;
    }
    .time-badge.time-opening {
        background: linear-gradient(135deg, #1abc9c 0%, #16a085 100%); /* Greenish blue */
    }
    .time-badge.time-closing {
        background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%); /* Reddish orange */
    }

    /* Submit Button (for Add Facility) */
    .btn-submit {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        border-radius: 16px;
        padding: 14px 28px; /* Slightly smaller padding for top button */
        color: white;
        font-weight: 600;
        font-size: 1rem;
        cursor: pointer;
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
        display: inline-flex; /* To align icon and text */
        align-items: center;
        justify-content: center;
        text-decoration: none;
        flex-shrink: 0;
    }

    .btn-submit:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 35px rgba(102, 126, 234, 0.4);
        color: white; /* Ensure text color remains white on hover */
        text-decoration: none;
    }

    .btn-submit:active {
        transform: translateY(-1px);
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
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        box-shadow: 0 4px 10px rgba(102, 126, 234, 0.3);
    }
    .pagination .page-item.disabled .page-link {
        color: #b0b8c6;
        background-color: #f8f9fa;
        cursor: not-allowed;
    }

    /* Responsive Design for Tables */
    @media (max-width: 992px) {
        .modern-table thead th,
        .modern-table tbody td {
            padding: 12px 15px;
            font-size: 0.85rem;
        }

        .btn-submit {
            padding: 12px 20px;
            font-size: 0.9rem;
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

        .header-icon {
            width: 60px;
            height: 60px;
        }

        .header-icon i {
            font-size: 1.5rem;
        }

        .table-container {
            padding: 10px;
        }

        .search-form {
            max-width: 100%;
        }

        .modern-table {
            display: block; /* Make table scrollable horizontally */
            width: 100%;
            white-space: nowrap; /* Prevent text wrapping in cells by default */
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

        /* Specific labels for mobile */
        .modern-table td:nth-of-type(1):before { content: "S.N:"; }
        .modern-table td:nth-of-type(2):before { content: "ID:"; }
        .modern-table td:nth-of-type(3):before { content: "Gambar:"; text-align: center;}
        .modern-table td:nth-of-type(4):before { content: "Nama:"; }
        .modern-table td:nth-of-type(5):before { content: "Deskripsi:"; }
        .modern-table td:nth-of-type(6):before { content: "Lokasi:"; }
        .modern-table td:nth-of-type(7):before { content: "Waktu Buka:"; }
        .modern-table td:nth-of-type(8):before { content: "Waktu Tutup:"; }
        .modern-table td:nth-of-type(9):before { content: "Kontak Person:"; }
        .modern-table td:nth-of-type(10):before { content: "Email:"; }
        .modern-table td:nth-of-type(11):before { content: "Telepon:"; }
        .modern-table td:nth-of-type(12):before { content: "Ditambahkan Oleh:"; }
        .modern-table td:nth-of-type(13):before { content: "Aksi:"; }
        
        .modern-table tbody td:last-child {
            border-bottom: none;
        }

        .action-buttons-group {
            justify-content: flex-end;
        }
    }

    @media (max-width: 576px) {
        .container-fluid {
            padding-left: 15px;
            padding-right: 15px;
        }
        
        .booking-content {
            padding: 25px 15px;
        }
    }

    /* MODAL CUSTOM STYLES - Copied from send_message/show.blade.php for consistency */
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
        display: flex; /* Added for close button alignment */
        justify-content: space-between; /* Added for close button alignment */
        align-items: center; /* Added for close button alignment */
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
    /* End of MODAL CUSTOM STYLES */
</style>
@endsection


@section('scripts')
@parent
<script>
    // Alert close functionality
    document.querySelectorAll('.alert-close').forEach(button => {
        button.addEventListener('click', function() {
            this.closest('.custom-alert').style.display = 'none';
        });
    });

    // MODAL JS for markAsRead confirmation
    let currentNotificationId = null; // Variable to store the ID of the notification being processed

    function showConfirmMarkAsReadModal(notificationId) {
        console.log('showConfirmMarkAsReadModal called for ID:', notificationId); // Debugging
        currentNotificationId = notificationId;
        const modal = document.getElementById('confirmMarkAsReadModal');
        if (modal) {
            modal.style.display = 'flex';
            // Optional: Focus on the confirm button for accessibility
            setTimeout(() => {
                const confirmBtn = document.getElementById('confirmMarkAsReadBtn');
                if (confirmBtn) {
                    confirmBtn.focus();
                }
            }, 100);
            console.log('Modal display set to flex.'); // Debugging
        } else {
            console.error('Modal element #confirmMarkAsReadModal not found.'); // Debugging
        }
    }

    function closeConfirmMarkAsReadModal() {
        console.log('closeConfirmMarkAsReadModal called.'); // Debugging
        const modal = document.getElementById('confirmMarkAsReadModal');
        if (modal) {
            modal.style.display = 'none';
            currentNotificationId = null; // Clear the stored ID
            console.log('Modal display set to none.'); // Debugging
        } else {
            console.error('Modal element #confirmMarkAsReadModal not found for closing.'); // Debugging
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOMContentLoaded fired.'); // Debugging
        const confirmBtn = document.getElementById('confirmMarkAsReadBtn');
        if (confirmBtn) {
            confirmBtn.onclick = function() {
                console.log('Confirm button clicked.'); // Debugging
                if (currentNotificationId !== null) {
                    const form = document.getElementById(`markAsReadForm-${currentNotificationId}`);
                    if (form) {
                        console.log('Submitting form:', form.id); // Debugging
                        form.submit(); // Submit the form
                        closeConfirmMarkAsReadModal(); // Close modal after submission
                    } else {
                        console.error('Form not found for ID:', `markAsReadForm-${currentNotificationId}`); // Debugging
                        // Optionally show an error toast
                    }
                } else {
                    console.warn('currentNotificationId is null when confirm button clicked.'); // Debugging
                }
            };
        } else {
            console.error('Confirm button element #confirmMarkAsReadBtn not found.'); // Debugging
        }
    });

    // AJAX search & pagination for notifications
    function ajaxLoadNotificationTable(url) {
        $.ajax({
            url: url,
            type: 'GET',
            dataType: 'json',
            beforeSend: function() {
                // Optional: show loading spinner
            },
            success: function(response) {
                $('#notification-table-wrapper').html(response.html);
            },
            error: function(xhr) {
                alert('Gagal memuat data. Silakan coba lagi.');
            },
            complete: function() {
                // Optional: hide loading spinner
            }
        });
    }

    // Search submit (prevent default)
    $(document).on('submit', '#notification-search-form', function(e) {
        e.preventDefault();
        var url = $(this).attr('action') + '?' + $(this).serialize();
        ajaxLoadNotificationTable(url);
    });

    // Pagination click
    $(document).on('click', '#notification-table-wrapper .pagination a', function(e) {
        e.preventDefault();
        var url = $(this).attr('href');
        if (url) {
            ajaxLoadNotificationTable(url);
        }
    });

    // Debounce function
    function debounce(func, wait) {
        let timeout;
        return function(...args) {
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(this, args), wait);
        };
    }

    // AJAX search on input (tanpa enter)
    $(document).on('input', '.search-input', debounce(function(e) {
        var form = $(this).closest('form');
        var url = form.attr('action') + '?' + form.serialize();
        ajaxLoadNotificationTable(url);
    }, 400));
</script>
@endsection