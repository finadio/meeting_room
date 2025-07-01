@extends('admin.layouts.admin_dashboard')
@section('title', 'Edit Facility')

@section('content')
    <div class="container">
        <h4 class="font-weight-bold py-3 mb-4">Edit Facility</h4>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('admin.facilities.update', $facility->id) }}" method="post" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" class="form-control" value="{{ $facility->name }}" required>
            </div>

            <div class="form-group">
                <label for="description">Description:</label>
                <textarea id="description" name="description" class="form-control">{{ $facility->description }}</textarea>
            </div>

            <div class="form-group">
                <label for="location">Location:</label>
                <input type="text" id="location" name="location" class="form-control" value="{{$facility->location}}">
            </div>

            <!-- <div class="form-group">
                <label for="map_coordinates">Map Coordinates:</label>
                <input type="hidden" id="map_coordinates" name="map_coordinates" class="form-control" value="{{ $facility->map_coordinates }}">
            </div> -->
            <!-- <div id="map" style="height: 300px;"></div> -->

            <div class="form-group">
                <label for="image">Facility Image:</label>
                <input type="file" id="image" name="image" class="form-control" value="{{$facility->image}}">
            </div>

            <!-- <div class="form-group">
                <label for="price_per_hour">Price per Hour:</label>
                <input type="text" id="price_per_hour" name="price_per_hour" class="form-control" value="{{ $facility->price_per_hour }}">
            </div> -->

            <div class="form-group ">
                <label for="facility_type" class="col-sm-2 col-form-label">Facility Type:</label>
                    <select id="facility_type" name="facility_type" class="form-control">
                        <option value="indoor" @if($facility->facility_type == 'indoor') selected @endif>Indoor</option>
                        <option value="outdoor" @if($facility->facility_type == 'outdoor') selected @endif>Outdoor</option>
                    </select>
            </div>

            <div class="form-group">
                <label for="opening_time">Opening Time:</label>
                <input type="time" id="opening_time" name="opening_time" class="form-control" value="{{ $facility->opening_time }}">
            </div>

            <div class="form-group">
                <label for="closing_time">Closing Time:</label>
                <input type="time" id="closing_time" name="closing_time" class="form-control" value="{{ $facility->closing_time }}">
            </div>

            <div class="form-group">
                <label for="contact_person">Contact Person:</label>
                <input type="text" id="contact_person" name="contact_person" class="form-control" value="{{ $facility->contact_person }}">
            </div>

            <div class="form-group">
                <label for="contact_email">Contact Email:</label>
                <input type="email" id="contact_email" name="contact_email" class="form-control" value="{{ $facility->contact_email }}">
            </div>

            <div class="form-group">
                <label for="contact_phone">Contact Phone:</label>
                <input type="tel" id="contact_phone" name="contact_phone" class="form-control" value="{{ $facility->contact_phone }}">
            </div>

            <button type="submit" class="btn btn-success">Update Facility</button>
            <button type="button" class="btn btn-secondary" onclick="goBack()">Back</button>
        </form>
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
@endsection

