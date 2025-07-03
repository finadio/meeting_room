@extends('admin.layouts.admin_dashboard')
@section('title', 'Edit User')

@section('content')
<div class="user-edit-wrapper">
    <div class="container">
        <!-- Page Header -->
        <div class="page-header">
            <div class="header-content">
                <div class="header-icon">
                    <i class="bx bx-edit-alt"></i>
                </div>
                <div class="header-text">
                    <h4 class="page-title">Edit User</h4>
                    <p class="page-subtitle">Update user information</p>
                </div>
            </div>
        </div>

        <!-- Main Form Card -->
        <div class="form-card">
            <!-- Alert Messages -->
            @if(session('success'))
                <div class="custom-alert success-alert">
                    <div class="alert-icon">
                        <i class="bx bx-check-circle"></i>
                    </div>
                    <div class="alert-content">
                        <strong>Success!</strong>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button type="button" class="alert-close">
                        <i class="bx bx-x"></i>
                    </button>
                </div>
            @endif

            <!-- Form Content -->
            <div class="form-content">
                <form action="{{ route('admin.users.update', $user) }}" method="post" enctype="multipart/form-data" class="user-form">
                    @csrf
                    @method('put')

                    <div class="form-grid">
                        <!-- Personal Information Section -->
                        <div class="form-section">
                            <div class="section-header">
                                <i class="bx bx-user"></i>
                                <h5>Personal Information</h5>
                            </div>
                            
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name" class="form-label">
                                            <i class="bx bx-user"></i>
                                            <span>Full Name</span>
                                        </label>
                                        <input type="text" name="name" id="name" value="{{ $user->name }}" class="form-control">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email" class="form-label">
                                            <i class="bx bx-envelope"></i>
                                            <span>Email Address</span>
                                        </label>
                                        <input type="email" name="email" id="email" value="{{ $user->email }}" class="form-control">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="contact_number" class="form-label">
                                            <i class="bx bx-phone"></i>
                                            <span>Nomor HP</span>
                                        </label>
                                        <input type="text" name="contact_number" id="contact_number" value="{{ $user->contact_number }}" class="form-control">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="user_type" class="form-label">
                                            <i class="bx bx-shield"></i>
                                            <span>User Type</span>
                                        </label>
                                        <select name="user_type" id="user_type" class="form-control">
                                            <option value="manager" {{ $user->user_type == 'futsal_manager' ? 'selected' : '' }}>Manager</option>
                                            <option value="user" {{ $user->user_type == 'user' ? 'selected' : '' }}>User</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Profile Picture Section -->
                        <div class="form-section">
                            <div class="section-header">
                                <i class="bx bx-image"></i>
                                <h5>Profile Picture</h5>
                            </div>
                            
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="profile_picture" class="form-label">
                                            <i class="bx bx-image"></i>
                                            <span>Update Profile Picture</span>
                                        </label>
                                        <div class="file-upload-wrapper">
                                            <input type="file" name="profile_picture" id="profile_picture" class="file-input">
                                            <label for="profile_picture" class="file-upload-btn">
                                                <i class="bx bx-cloud-upload"></i>
                                                <span class="upload-text">Choose New Image</span>
                                            </label>
                                            <div class="file-info">
                                                <span class="file-name">No file selected</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="form-actions">
                        <button type="submit" class="btn-primary">
                            <i class="bx bx-save"></i>
                            <span>Update User</span>
                        </button>
                        
                        <a href="{{ route('admin.users.index') }}" class="btn-secondary">
                            <i class="bx bx-arrow-back"></i>
                            <span>Back</span>
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// File upload handling
document.getElementById('profile_picture').addEventListener('change', function(e) {
    const fileName = e.target.files[0]?.name || 'No file selected';
    document.querySelector('.file-name').textContent = fileName;
    
    if (e.target.files[0]) {
        document.querySelector('.upload-text').textContent = 'Change Image';
    } else {
        document.querySelector('.upload-text').textContent = 'Choose New Image';
    }
});

// Alert close functionality
document.querySelectorAll('.alert-close').forEach(button => {
    button.addEventListener('click', function() {
        this.closest('.custom-alert').style.display = 'none';
    });
});
</script>
@endsection

@section('styles')
<link rel="stylesheet" href="{{ asset('css/admin_user_management.css') }}">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>

<style>
    /* Page Wrapper */
    .user-edit-wrapper {
        min-height: 100vh;
        background: rgba(255, 255, 255, 0.95);
        padding: 30px 0;
    }

    /* Page Header */
    .page-header {
        background: #1e3a8a 0%;
        border-radius: 16px;
        padding: 30px;
        margin-bottom: 30px;
        box-shadow: 0 10px 30px rgba(30, 58, 138, 0.2);
    }

    .header-content {
        display: flex;
        align-items: center;
    }

    .header-icon {
        background: rgba(255, 255, 255, 0.2);
        width: 60px;
        height: 60px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 20px;
    }

    .header-icon i {
        font-size: 1.8rem;
        color: #fbbf24;
    }

    .page-title {
        color: white;
        font-size: 1.8rem;
        font-weight: 700;
        margin: 0;
    }

    .page-subtitle {
        color: rgba(255, 255, 255, 0.8);
        font-size: 1rem;
        margin: 5px 0 0 0;
    }

    /* Form Card */
    .form-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        border-top: 4px solid #1e3a8a;
    }

    .form-content {
        padding: 40px;
    }

    /* Alerts */
    .custom-alert {
        display: flex;
        align-items: flex-start;
        padding: 15px 20px;
        border-radius: 12px;
        margin-bottom: 25px;
        border: none;
    }

    .success-alert {
        background: linear-gradient(135deg, #10b981 0%, #34d399 100%);
        color: white;
    }

    .alert-icon {
        font-size: 1.2rem;
        margin-right: 12px;
        width: 30px;
        height: 30px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .alert-content {
        flex: 1;
    }

    .alert-content strong {
        display: block;
        margin-bottom: 5px;
    }

    .alert-close {
        background: none;
        border: none;
        color: white;
        cursor: pointer;
        padding: 5px;
        border-radius: 4px;
        transition: background-color 0.3s;
    }

    .alert-close:hover {
        background: rgba(255, 255, 255, 0.2);
    }

    /* Form Sections */
    .form-section {
        margin-bottom: 35px;
    }

    .section-header {
        display: flex;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 12px;
        border-bottom: 2px solid #e5e7eb;
    }

    .section-header i {
        font-size: 1.3rem;
        color: #1e3a8a;
        margin-right: 10px;
        background: rgba(30, 58, 138, 0.1);
        padding: 8px;
        border-radius: 8px;
    }

    .section-header h5 {
        color: #1e3a8a;
        font-weight: 600;
        margin: 0;
        font-size: 1.1rem;
    }

    /* Form Groups */
    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        display: flex;
        align-items: center;
        margin-bottom: 8px;
        font-weight: 600;
        color: #374151;
        font-size: 0.9rem;
    }

    .form-label i {
        color: #3b82f6;
        margin-right: 8px;
        font-size: 1rem;
    }

    .form-control {
        width: 100%;
        padding: 12px 16px;
        border: 2px solid #e5e7eb;
        border-radius: 10px;
        font-size: 0.95rem;
        background: white;
        transition: all 0.3s ease;
        outline: none;
    }

    .form-control:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .form-control::placeholder {
        color: #9ca3af;
    }

    /* File Upload */
    .file-input {
        display: none;
    }

    .file-upload-wrapper {
        position: relative;
    }

    .file-upload-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 12px 16px;
        border: 2px dashed #d1d5db;
        border-radius: 10px;
        background: #f9fafb;
        cursor: pointer;
        transition: all 0.3s ease;
        margin: 0;
    }

    .file-upload-btn:hover {
        border-color: #3b82f6;
        background: rgba(59, 130, 246, 0.05);
    }

    .file-upload-btn i {
        font-size: 1.2rem;
        color: #3b82f6;
        margin-right: 8px;
    }

    .upload-text {
        color: #374151;
        font-weight: 500;
    }

    .file-info {
        margin-top: 6px;
    }

    .file-name {
        font-size: 0.85rem;
        color: #6b7280;
    }

    /* Action Buttons */
    .form-actions {
        display: flex;
        gap: 15px;
        justify-content: center;
        margin-top: 30px;
        padding-top: 25px;
        border-top: 1px solid #e5e7eb;
    }

    .btn-primary, .btn-secondary {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 12px 24px;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.95rem;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.3s ease;
        min-width: 140px;
    }

    .btn-primary {
        background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(30, 58, 138, 0.3);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(30, 58, 138, 0.4);
        color: white;
        text-decoration: none;
    }

    .btn-secondary {
        background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(107, 114, 128, 0.3);
    }

    .btn-secondary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(107, 114, 128, 0.4);
        color: white;
        text-decoration: none;
    }

    .btn-primary i, .btn-secondary i {
        margin-right: 6px;
        font-size: 1rem;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .user-edit-wrapper {
            padding: 20px 0;
        }

        .form-content {
            padding: 25px 20px;
        }

        .page-header {
            padding: 20px;
        }

        .header-content {
            flex-direction: column;
            text-align: center;
        }

        .header-icon {
            margin-right: 0;
            margin-bottom: 15px;
        }

        .form-actions {
            flex-direction: column;
        }

        .btn-primary, .btn-secondary {
            width: 100%;
        }
    }
</style>
@endsection