@extends('admin.layouts.admin_dashboard')
@section('title', 'Create Facility')

@section('content')
    <div class="container-fluid px-4">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-md-12">
                <div class="card shadow-lg border-0 rounded-lg">
                    <div class="card-header bg-gradient-primary text-white">
                        <h4 class="mb-0 text-center font-weight-bold">
                            <i class="bx bx-building-house me-2"></i>
                            Create Facility
                        </h4>
                    </div>

                    <div class="card-body p-4">
                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="bx bx-error-circle me-2"></i>
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <form action="{{ route('admin.facilities.store') }}" method="post" enctype="multipart/form-data" class="facility-form">
                            @csrf

                            <!-- Basic Information Section -->
                            <div class="form-section mb-4">
                                <h6 class="section-title">
                                    <i class="bx bx-info-circle me-2"></i>
                                    Basic Information
                                </h6>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="name" class="form-label">
                                            <i class="bx bx-rename me-1"></i>
                                            Name
                                        </label>
                                        <input type="text" id="name" name="name" class="form-control custom-input" placeholder="Enter facility name" required>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="facility_type" class="form-label">
                                            <i class="bx bx-category me-1"></i>
                                            Facility Type
                                        </label>
                                        <select id="facility_type" name="facility_type" class="form-select custom-select">
                                            <option value="indoor">Indoor</option>
                                            <option value="outdoor">Outdoor</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label for="description" class="form-label">
                                            <i class="bx bx-detail me-1"></i>
                                            Description
                                        </label>
                                        <textarea id="description" name="description" class="form-control custom-textarea" rows="4" placeholder="Enter facility description"></textarea>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label for="location" class="form-label">
                                            <i class="bx bx-map me-1"></i>
                                            Location
                                        </label>
                                        <input type="text" id="location" name="location" class="form-control custom-input" placeholder="Enter facility location" onchange="updateMap()">
                                    </div>
                                </div>
                            </div>

                            <!-- Operating Hours Section -->
                            <div class="form-section mb-4">
                                <h6 class="section-title">
                                    <i class="bx bx-time me-2"></i>
                                    Operating Hours
                                </h6>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="opening_time" class="form-label">
                                            <i class="bx bx-time-five me-1"></i>
                                            Opening Time
                                        </label>
                                        <input type="time" id="opening_time" name="opening_time" class="form-control custom-input">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="closing_time" class="form-label">
                                            <i class="bx bx-alarm me-1"></i>
                                            Closing Time
                                        </label>
                                        <input type="time" id="closing_time" name="closing_time" class="form-control custom-input">
                                    </div>
                                </div>
                            </div>

                            <!-- Contact Information Section -->
                            <div class="form-section mb-4">
                                <h6 class="section-title">
                                    <i class="bx bx-user me-2"></i>
                                    Contact Information
                                </h6>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="contact_person" class="form-label">
                                            <i class="bx bx-user-circle me-1"></i>
                                            Contact Person
                                        </label>
                                        <input type="text" id="contact_person" name="contact_person" class="form-control custom-input" placeholder="Enter contact person name">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="contact_phone" class="form-label">
                                            <i class="bx bx-phone me-1"></i>
                                            Contact Phone
                                        </label>
                                        <input type="tel" id="contact_phone" name="contact_phone" class="form-control custom-input" placeholder="Enter phone number">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label for="contact_email" class="form-label">
                                            <i class="bx bx-envelope me-1"></i>
                                            Contact Email
                                        </label>
                                        <input type="email" id="contact_email" name="contact_email" class="form-control custom-input" placeholder="Enter email address">
                                    </div>
                                </div>
                            </div>

                            <!-- Media Section -->
                            <div class="form-section mb-4">
                                <h6 class="section-title">
                                    <i class="bx bx-image me-2"></i>
                                    Facility Image
                                </h6>

                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label for="image" class="form-label">
                                            <i class="bx bx-image-add me-1"></i>
                                            Upload Image
                                        </label>
                                        <div class="file-upload-wrapper">
                                            <input type="file" id="image" name="image" class="form-control-file custom-file-input" accept="image/*">
                                            <div class="file-upload-info">
                                                <i class="bx bx-cloud-upload"></i>
                                                <span>Choose an image file</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit & Back Buttons -->
                            <div class="row mt-4">
                                <div class="col-md-6 mb-2">
                                    <button type="submit" class="btn btn-primary btn-lg w-100 custom-btn">
                                        <i class="bx bx-save me-2"></i>
                                        Create Facility
                                    </button>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <button type="button" class="btn btn-secondary btn-lg w-100 custom-btn" onclick="goBack()">
                                        <i class="bx bx-arrow-back me-2"></i>
                                        Back
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function goBack() {
            window.history.back();
        }
    </script>
@endsection

@section('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>

    <style>
        .bg-gradient-primary {
            background: linear-gradient(135deg, #3C91E6 0%, #2d7dd2 100%);
            border-radius: 0.375rem 0.375rem 0 0 !important;
        }

        .card {
            border-radius: 0.5rem;
            transition: all 0.3s ease;
            margin-top: 20px;
            margin-bottom: 20px;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(60, 145, 230, 0.15) !important;
        }

        .form-section {
            background: #f8f9fa;
            border-radius: 0.5rem;
            padding: 20px;
            border-left: 4px solid #3C91E6;
        }

        .section-title {
            color: #3C91E6;
            font-weight: 600;
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 2px solid #e3e6f0;
        }

        .custom-input, .custom-select, .custom-textarea {
            border: 2px solid #e3e6f0;
            border-radius: 0.5rem;
            padding: 12px 15px;
            font-size: 14px;
            background-color: #fff;
            transition: all 0.3s ease;
        }

        .custom-input:focus, .custom-select:focus, .custom-textarea:focus {
            border-color: #3C91E6;
            box-shadow: 0 0 0 0.2rem rgba(60, 145, 230, 0.25);
        }

        .form-label {
            font-weight: 600;
            color: #5a5c69;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .custom-btn {
            padding: 15px 30px;
            font-weight: 600;
            font-size: 16px;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
        }

        .custom-btn:hover {
            transform: translateY(-2px);
        }

        .alert {
            border-radius: 0.5rem;
            border: none;
            padding: 15px 20px;
            margin-bottom: 25px;
            font-weight: 500;
        }

        .alert-danger {
            background: linear-gradient(135deg, #e74a3b 0%, #c0392b 100%);
            color: white;
        }

        .file-upload-wrapper {
            position: relative;
            border: 2px dashed #3C91E6;
            border-radius: 0.5rem;
            padding: 30px;
            text-align: center;
            background: #f8f9fa;
            transition: all 0.3s ease;
        }

        .file-upload-wrapper:hover {
            background: #e9ecef;
            border-color: #2d7dd2;
        }

        .custom-file-input {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
        }

        .file-upload-info {
            color: #3C91E6;
            font-weight: 500;
        }

        .file-upload-info i {
            font-size: 2rem;
            display: block;
            margin-bottom: 10px;
        }

        .facility-form {
            animation: fadeInUp 0.5s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .container-fluid {
            padding-top: 20px;
            padding-bottom: 20px;
        }

        @media (max-width: 768px) {
            .container-fluid {
                padding-left: 15px;
                padding-right: 15px;
            }

            .card-body {
                padding: 20px !important;
            }

            .form-section {
                padding: 15px;
            }

            .custom-input, .custom-select, .custom-textarea {
                padding: 10px 12px;
            }
        }
    </style>
@endsection
