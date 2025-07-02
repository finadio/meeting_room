@extends('admin.layouts.admin_dashboard')
@section('title', 'Edit Facility')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center py-3 mb-4">
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bx bx-check-circle mr-2"></i>
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bx bx-error-circle mr-2"></i>
                {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="bx bx-edit mr-2"></i>Edit Facility Information
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.facilities.update', $facility->id) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name" class="font-weight-semibold">
                                    <i class="bx bx-buildings text-primary mr-1"></i>Facility Name <span class="text-danger">*</span>
                                </label>
                                <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" 
                                       value="{{ old('name', $facility->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="facility_type" class="font-weight-semibold">
                                    <i class="bx bx-category text-info mr-1"></i>Facility Type
                                </label>
                                <select id="facility_type" name="facility_type" class="form-control @error('facility_type') is-invalid @enderror">
                                    <option value="">Select Type</option>
                                    <option value="indoor" @if(old('facility_type', $facility->facility_type) == 'indoor') selected @endif>Indoor</option>
                                    <option value="outdoor" @if(old('facility_type', $facility->facility_type) == 'outdoor') selected @endif>Outdoor</option>
                                </select>
                                @error('facility_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="description" class="font-weight-semibold">
                            <i class="bx bx-detail text-secondary mr-1"></i>Description
                        </label>
                        <textarea id="description" name="description" class="form-control @error('description') is-invalid @enderror" 
                                  rows="4">{{ old('description', $facility->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="location" class="font-weight-semibold">
                            <i class="bx bx-map text-danger mr-1"></i>Location
                        </label>
                        <input type="text" id="location" name="location" class="form-control @error('location') is-invalid @enderror" 
                               value="{{ old('location', $facility->location) }}">
                        @error('location')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- <div class="form-group">
                        <label for="map_coordinates">Map Coordinates:</label>
                        <input type="hidden" id="map_coordinates" name="map_coordinates" class="form-control" value="{{ $facility->map_coordinates }}">
                    </div> -->
                    <!-- <div id="map" style="height: 300px;"></div> -->

                    <div class="form-group">
                        <label for="image" class="font-weight-semibold">
                            <i class="bx bx-image text-success mr-1"></i>Facility Image
                        </label>
                        @if($facility->image_path)
                            <div class="mb-2">
                                <small class="text-muted">Current image:</small>
                                <div class="current-image-preview mt-1">
                                    <img src="{{ asset('storage/facility_images/' . basename($facility->image_path)) }}" 
                                         alt="Current facility image" class="img-thumbnail" style="max-height: 100px;">
                                </div>
                            </div>
                        @endif
                        <input type="file" id="image" name="image" class="form-control-file @error('image') is-invalid @enderror" 
                               accept="image/*">
                        <small class="form-text text-muted">Leave empty to keep current image. Accepted formats: JPG, PNG, GIF (Max: 2MB)</small>
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- <div class="form-group">
                        <label for="price_per_hour">Price per Hour:</label>
                        <input type="text" id="price_per_hour" name="price_per_hour" class="form-control" value="{{ $facility->price_per_hour }}">
                    </div> -->

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="opening_time" class="font-weight-semibold">
                                    <i class="bx bx-time text-success mr-1"></i>Opening Time
                                </label>
                                <input type="time" id="opening_time" name="opening_time" 
                                       class="form-control @error('opening_time') is-invalid @enderror" 
                                       value="{{ old('opening_time', $facility->opening_time) }}">
                                @error('opening_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="closing_time" class="font-weight-semibold">
                                    <i class="bx bx-time text-danger mr-1"></i>Closing Time
                                </label>
                                <input type="time" id="closing_time" name="closing_time" 
                                       class="form-control @error('closing_time') is-invalid @enderror" 
                                       value="{{ old('closing_time', $facility->closing_time) }}">
                                @error('closing_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">
                    <h6 class="font-weight-bold text-muted mb-3">
                        <i class="bx bx-user-circle mr-2"></i>Contact Information
                    </h6>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="contact_person" class="font-weight-semibold">
                                    <i class="bx bx-user text-primary mr-1"></i>Contact Person
                                </label>
                                <input type="text" id="contact_person" name="contact_person" 
                                       class="form-control @error('contact_person') is-invalid @enderror" 
                                       value="{{ old('contact_person', $facility->contact_person) }}">
                                @error('contact_person')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="contact_email" class="font-weight-semibold">
                                    <i class="bx bx-envelope text-info mr-1"></i>Contact Email
                                </label>
                                <input type="email" id="contact_email" name="contact_email" 
                                       class="form-control @error('contact_email') is-invalid @enderror" 
                                       value="{{ old('contact_email', $facility->contact_email) }}">
                                @error('contact_email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="contact_phone" class="font-weight-semibold">
                                    <i class="bx bx-phone text-success mr-1"></i>Contact Phone
                                </label>
                                <input type="tel" id="contact_phone" name="contact_phone" 
                                       class="form-control @error('contact_phone') is-invalid @enderror" 
                                       value="{{ old('contact_phone', $facility->contact_phone) }}">
                                @error('contact_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">
                    <div class="d-flex justify-content-between">
                        <div>
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="bx bx-save mr-2"></i>Update Facility
                            </button>
                            <button type="button" class="btn btn-secondary btn-lg" onclick="goBack()">
                                <i class="bx bx-arrow-back mr-2"></i>Back
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function goBack() {
            window.history.back();
        }
    </script>
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <!-- <script>
        document.addEventListener('DOMContentLoaded', function () {
            var coordinates = [{{ $facility->map_coordinates }}];
            var map = L.map('map').setView(coordinates, 15);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            var marker = L.marker(coordinates, { draggable: true }).addTo(map);

            marker.on('dragend', function (event) {
                var position = marker.getLatLng();
                document.getElementById('map_coordinates').value = position.lat + ',' + position.lng;
            });

            window.updateMap = function () {
                var locationInput = document.getElementById('location').value;

                if (locationInput) {
                    fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(locationInput)}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data && data.length > 0) {
                                var newCoordinates = [parseFloat(data[0].lat), parseFloat(data[0].lon)];
                                map.setView(newCoordinates, 15);
                                marker.setLatLng(newCoordinates);
                                document.getElementById('map_coordinates').value = newCoordinates.join(',');
                            }
                        })
                        .catch(error => {
                            console.error('Error fetching coordinates:', error);
                        });
                }
            };
        });
    </script> -->
@endsection

@section('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <style>
        .card {
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }
        
        .card-header {
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .breadcrumb {
            background-color: transparent;
            padding: 0;
        }
        
        .breadcrumb-item + .breadcrumb-item::before {
            content: ">";
        }
        
        .form-group label {
            color: #495057;
            margin-bottom: 0.5rem;
        }
        
        .font-weight-semibold {
            font-weight: 600;
        }
        
        .form-control:focus {
            border-color: #80bdff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }
        
        .current-image-preview {
            display: inline-block;
            padding: 0.25rem;
            background-color: #f8f9fa;
            border-radius: 0.25rem;
        }
        
        .btn-lg {
            padding: 0.75rem 1.5rem;
            font-size: 1.1rem;
        }
        
        .alert {
            border-radius: 0.5rem;
        }
        
        .text-danger {
            color: #dc3545 !important;
        }
        
        .is-invalid {
            border-color: #dc3545;
        }
        
        .invalid-feedback {
            display: block;
        }
        
        hr {
            border-top: 1px solid #dee2e6;
        }
        
        .card-body {
            padding: 2rem;
        }
    </style>
@endsection