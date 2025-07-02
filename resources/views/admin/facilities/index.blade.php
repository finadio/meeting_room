@extends('admin.layouts.admin_dashboard')
@section('title', 'Facilities')

@section('content')
    <div class="container-fluid px-4">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-lg border-0 rounded-lg">
                    <div class="card-header bg-gradient-primary text-white d-flex justify-content-between align-items-center">
                        <h4 class="mb-0 font-weight-bold">
                            <i class="bx bx-building me-2"></i>
                            Facilities Management
                        </h4>
                        <a href="{{ route('admin.facilities.create') }}" class="btn btn-light btn-add">
                            <i class='bx bx-plus-circle me-1'></i>
                            Add Facility
                        </a>
                    </div>

                    <div class="card-body p-4">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show custom-alert" role="alert">
                                <i class="bx bx-check-circle me-2"></i>
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show custom-alert" role="alert">
                                <i class="bx bx-error-circle me-2"></i>
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table class="table table-hover custom-table">
                                <thead class="table-header">
                                    <tr>
                                        <th class="text-center">S.N</th>
                                        <th class="text-center">ID</th>
                                        <th class="text-center">Image</th>
                                        <th>Name</th>
                                        <th>Description</th>
                                        <th>Location</th>
                                        <th class="text-center">Opening</th>
                                        <th class="text-center">Closing</th>
                                        <th>Contact Person</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Added By</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($facilities as $facility)
                                    <tr class="table-row">
                                        <td class="text-center fw-bold">{{ $loop->iteration }}</td>
                                        <td class="text-center">
                                            <span class="badge bg-info">{{ $facility->id }}</span>
                                        </td>
                                        <td class="text-center">
                                            <div class="image-container">
                                                @if($facility->image_path)
                                                    <img src="{{ asset('storage/facility_images/' . basename($facility->image_path)) }}" alt="Facility Image" class="facility-image">
                                                @else
                                                    <img src="{{ asset('img/img-1.jpg') }}" class="facility-image" alt="{{ $facility->name }}">
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <div class="facility-name">
                                                <strong>{{ $facility->name }}</strong>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="description-cell" title="{{ $facility->description }}">
                                                {{ $facility->description }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="location-cell">
                                                <i class="bx bx-map me-1 text-muted"></i>
                                                {{ $facility->location }}
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <span class="time-badge opening">
                                                <i class="bx bx-time-five me-1"></i>
                                                {{ $facility->opening_time }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="time-badge closing">
                                                <i class="bx bx-alarm me-1"></i>
                                                {{ $facility->closing_time }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="contact-cell">
                                                <i class="bx bx-user me-1 text-muted"></i>
                                                {{ $facility->contact_person }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="email-cell">
                                                <i class="bx bx-envelope me-1 text-muted"></i>
                                                {{ $facility->contact_email }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="phone-cell">
                                                <i class="bx bx-phone me-1 text-muted"></i>
                                                {{ $facility->contact_phone }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="added-by-cell">
                                                {{ $facility->addedBy ? $facility->addedBy->name : 'Nischal Acharya' }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="action-buttons">
                                                <a href="{{ route('admin.facilities.show', $facility) }}" class="btn btn-info btn-sm custom-action-btn" title="View">
                                                    <i class='bx bx-show'></i>
                                                </a>
                                                <a href="{{ route('admin.facilities.edit', $facility->id) }}" class="btn btn-warning btn-sm custom-action-btn" title="Edit">
                                                    <i class='bx bx-edit'></i>
                                                </a>
                                                <form action="{{ route('admin.facilities.destroy', $facility->id) }}" method="post" style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm custom-action-btn" title="Delete" onclick="return confirm('Are you sure you want to delete this facility?')">
                                                        <i class='bx bx-trash'></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-center mt-4">
                            {{ $facilities->links('vendor.pagination.bootstrap-4') }}
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
        .bg-gradient-primary {
            background: linear-gradient(135deg, #3C91E6 0%, #2d7dd2 100%);
        }
        
        .card {
            border-radius: 0.5rem;
            margin-top: 20px;
            margin-bottom: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(60, 145, 230, 0.15);
        }
        
        .btn-add {
            background: rgba(255, 255, 255, 0.9);
            color: #3C91E6;
            border: 2px solid rgba(255, 255, 255, 0.3);
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-add:hover {
            background: white;
            color: #2d7dd2;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(255, 255, 255, 0.3);
        }
        
        .custom-table {
            border-radius: 0.5rem;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }
        
        .table-header {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-bottom: 2px solid #3C91E6;
        }
        
        .table-header th {
            font-weight: 600;
            color: #495057;
            border: none;
            padding: 15px 12px;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .table-row {
            transition: all 0.3s ease;
            border-bottom: 1px solid #e9ecef;
        }
        
        .table-row:hover {
            background: linear-gradient(135deg, #f8f9fa 0%, #e3f2fd 100%);
            transform: scale(1.01);
            box-shadow: 0 2px 8px rgba(60, 145, 230, 0.1);
        }
        
        .table-row td {
            padding: 15px 12px;
            vertical-align: middle;
            border: none;
        }
        
        .facility-image {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 0.375rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }
        
        .facility-image:hover {
            transform: scale(1.1);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }
        
        .image-container {
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        .facility-name {
            font-weight: 600;
            color: #495057;
            font-size: 14px;
        }
        
        .description-cell, .location-cell, .contact-cell, .email-cell, .phone-cell, .added-by-cell {
            max-width: 200px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            font-size: 13px;
            color: #6c757d;
        }
        
        .time-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 500;
            color: white;
        }
        
        .time-badge.opening {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        }
        
        .time-badge.closing {
            background: linear-gradient(135deg, #dc3545 0%, #e83e8c 100%);
        }
        
        .action-buttons {
            display: flex;
            gap: 5px;
            justify-content: center;
        }
        
        .custom-action-btn {
            width: 32px;
            height: 32px;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 0.375rem;
            transition: all 0.3s ease;
            font-size: 14px;
        }
        
        .custom-action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
        
        .custom-alert {
            border-radius: 0.5rem;
            border: none;
            padding: 15px 20px;
            margin-bottom: 20px;
            font-weight: 500;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        
        .alert-success {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            color: #155724;
            border-left: 4px solid #28a745;
        }
        
        .alert-danger {
            background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
            color: #721c24;
            border-left: 4px solid #dc3545;
        }
        
        .badge {
            font-size: 11px;
            padding: 6px 10px;
            border-radius: 12px;
        }
        
        .container-fluid {
            padding-top: 20px;
            padding-bottom: 20px;
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .container-fluid {
                padding-left: 15px;
                padding-right: 15px;
            }
            
            .card-header {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }
            
            .table-responsive {
                font-size: 12px;
            }
            
            .action-buttons {
                flex-direction: column;
                gap: 3px;
            }
            
            .facility-image {
                width: 40px;
                height: 40px;
            }
            
            .description-cell, .location-cell, .contact-cell, .email-cell, .phone-cell, .added-by-cell {
                max-width: 150px;
            }
        }
        
        /* Animation */
        .card {
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
    </style>
@endsection