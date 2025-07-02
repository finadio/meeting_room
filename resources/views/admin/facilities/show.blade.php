@extends('admin.layouts.admin_dashboard')
@section('title', 'Facility Details')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center py-3 mb-4">
            <h4 class="font-weight-bold mb-0">Facility Details</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.facilities.index') }}">Facilities</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $facility->name }}</li>
                </ol>
            </nav>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="facility-image-container">
                            @if($facility->image_path)
                                <img src="{{ asset('storage/facility_images/' . basename($facility->image_path)) }}" 
                                     alt="{{ $facility->name }}" 
                                     class="img-fluid rounded mb-3 facility-image"
                                     onerror="this.src='{{ asset('img/img-1.jpg') }}'">
                            @else
                                <img src="{{ asset('img/img-1.jpg') }}" 
                                     class="img-fluid rounded mb-3 facility-image" 
                                     alt="{{ $facility->name }}">
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="facility-info">
                            <h5 class="card-title text-primary mb-3">{{ $facility->name }}</h5>
                            
                            @if($facility->description)
                                <div class="mb-3">
                                    <h6 class="text-muted">Description</h6>
                                    <p class="card-text">{{ $facility->description }}</p>
                                </div>
                            @endif

                            <hr>

                            <dl class="row">
                                @if($facility->location)
                                    <dt class="col-sm-5"><i class="bx bx-map text-info mr-2"></i>Location:</dt>
                                    <dd class="col-sm-7">{{ $facility->location }}</dd>
                                @endif

                                @if(isset($facility->price_per_hour))
                                    <dt class="col-sm-5"><i class="bx bx-money text-success mr-2"></i>Price per Hour:</dt>
                                    <dd class="col-sm-7">Rs. {{ number_format($facility->price_per_hour, 2) }}</dd>
                                @endif

                                @if(isset($facility->facility_type))
                                    <dt class="col-sm-5"><i class="bx bx-category text-warning mr-2"></i>Facility Type:</dt>
                                    <dd class="col-sm-7">
                                        <span class="badge badge-info">{{ $facility->facility_type }}</span>
                                    </dd>
                                @endif

                                @if($facility->opening_time)
                                    <dt class="col-sm-5"><i class="bx bx-time text-primary mr-2"></i>Opening Time:</dt>
                                    <dd class="col-sm-7">{{ \Carbon\Carbon::parse($facility->opening_time)->format('g:i A') }}</dd>
                                @endif

                                @if($facility->closing_time)
                                    <dt class="col-sm-5"><i class="bx bx-time text-danger mr-2"></i>Closing Time:</dt>
                                    <dd class="col-sm-7">{{ \Carbon\Carbon::parse($facility->closing_time)->format('g:i A') }}</dd>
                                @endif

                                @if($facility->contact_person)
                                    <dt class="col-sm-5"><i class="bx bx-user text-secondary mr-2"></i>Contact Person:</dt>
                                    <dd class="col-sm-7">{{ $facility->contact_person }}</dd>
                                @endif

                                @if($facility->contact_email)
                                    <dt class="col-sm-5"><i class="bx bx-envelope text-info mr-2"></i>Contact Email:</dt>
                                    <dd class="col-sm-7">
                                        <a href="mailto:{{ $facility->contact_email }}">{{ $facility->contact_email }}</a>
                                    </dd>
                                @endif

                                @if($facility->contact_phone)
                                    <dt class="col-sm-5"><i class="bx bx-phone text-success mr-2"></i>Contact Phone:</dt>
                                    <dd class="col-sm-7">
                                        <a href="tel:{{ $facility->contact_phone }}">{{ $facility->contact_phone }}</a>
                                    </dd>
                                @endif

                                @if(isset($facility->status))
                                    <dt class="col-sm-5"><i class="bx bx-check-circle mr-2"></i>Status:</dt>
                                    <dd class="col-sm-7">
                                        <span class="badge {{ $facility->status === 'active' ? 'badge-success' : 'badge-secondary' }}">
                                            {{ ucfirst($facility->status) }}
                                        </span>
                                    </dd>
                                @endif

                                @if($facility->created_at)
                                    <dt class="col-sm-5"><i class="bx bx-calendar text-muted mr-2"></i>Created:</dt>
                                    <dd class="col-sm-7">{{ $facility->created_at->format('M d, Y g:i A') }}</dd>
                                @endif
                            </dl>
                        </div>
                    </div>
                </div>

                @if(isset($facility->map_coordinates) && $facility->map_coordinates)
                    <div class="mt-4">
                        <h6 class="text-muted mb-3"><i class="bx bx-map-pin mr-2"></i>Location on Map</h6>
                        <div id="map" style="height: 400px; border-radius: 8px;" class="border"></div>
                    </div>
                @endif

                <div class="mt-4 d-flex justify-content-between">
                    <div>
                        <a href="{{ route('admin.facilities.edit', $facility->id) }}" 
                           class="btn btn-warning mr-2">
                            <i class="bx bx-edit mr-1"></i>Edit Facility
                        </a>
                        
                        @if(isset($facility->status) && $facility->status === 'active')
                            <button type="button" class="btn btn-outline-secondary mr-2" 
                                    onclick="toggleStatus('{{ $facility->id }}', 'inactive')">
                                <i class="bx bx-pause mr-1"></i>Deactivate
                            </button>
                        @else
                            <button type="button" class="btn btn-outline-success mr-2" 
                                    onclick="toggleStatus('{{ $facility->id }}', 'active')">
                                <i class="bx bx-play mr-1"></i>Activate
                            </button>
                        @endif
                    </div>
                    
                    <div>
                        <button type="button" class="btn btn-secondary" onclick="goBack()">
                            <i class="bx bx-arrow-back mr-1"></i>Back
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmModalLabel">Confirm Action</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to change the status of this facility?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="confirmAction">Confirm</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <style>
        .facility-image {
            max-height: 300px;
            object-fit: cover;
            width: 100%;
        }
        
        .facility-image-container {
            position: relative;
        }
        
        .card {
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }
        
        .breadcrumb {
            background-color: transparent;
            padding: 0;
        }
        
        .breadcrumb-item + .breadcrumb-item::before {
            content: ">";
        }
        
        dt {
            font-weight: 600;
        }
        
        dd a {
            color: #007bff;
            text-decoration: none;
        }
        
        dd a:hover {
            text-decoration: underline;
        }
        
        .badge {
            font-size: 0.85em;
        }
        
        #map {
            border: 1px solid #dee2e6;
        }
    </style>
@endsection

@section('scripts')
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script>
        function goBack() {
            window.history.back();
        }

        function toggleStatus(facilityId, newStatus) {
            $('#confirmModal').modal('show');
            
            $('#confirmAction').off('click').on('click', function() {
                // Create a form and submit it
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/facilities/${facilityId}/toggle-status`;
                
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                
                const methodField = document.createElement('input');
                methodField.type = 'hidden';
                methodField.name = '_method';
                methodField.value = 'PATCH';
                
                const statusField = document.createElement('input');
                statusField.type = 'hidden';
                statusField.name = 'status';
                statusField.value = newStatus;
                
                form.appendChild(csrfToken);
                form.appendChild(methodField);
                form.appendChild(statusField);
                document.body.appendChild(form);
                form.submit();
                
                $('#confirmModal').modal('hide');
            });
        }

        @if(isset($facility->map_coordinates) && $facility->map_coordinates)
        document.addEventListener('DOMContentLoaded', function () {
            try {
                var coordinates = [{{ $facility->map_coordinates }}];
                
                // Validate coordinates
                if (coordinates.length !== 2 || isNaN(coordinates[0]) || isNaN(coordinates[1])) {
                    console.error('Invalid coordinates provided');
                    document.getElementById('map').innerHTML = '<div class="alert alert-warning">Invalid map coordinates</div>';
                    return;
                }
                
                var map = L.map('map').setView(coordinates, 15);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                    maxZoom: 19
                }).addTo(map);

                var marker = L.marker(coordinates).addTo(map);
                
                // Add popup to marker
                marker.bindPopup(`
                    <div>
                        <strong>{{ $facility->name }}</strong><br>
                        {{ $facility->location ?? 'No location specified' }}
                    </div>
                `).openPopup();
                
            } catch (error) {
                console.error('Error initializing map:', error);
                document.getElementById('map').innerHTML = '<div class="alert alert-danger">Error loading map</div>';
            }
        });
        @endif
    </script>
@endsection