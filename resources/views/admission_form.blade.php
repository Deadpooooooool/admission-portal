@extends('layouts.app')

@section('content')
    <h1 class="mb-4">Admission Form</h1>
    <form action="{{ route('admission_form') }}" method="POST" enctype="multipart/form-data" id="admissionForm">
        @csrf
        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        
        <div class="form-group">
            <label for="gender">Gender:</label>
            <select class="form-control" id="gender" name="gender" required>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Other">Other</option>
            </select>
        </div>
        
        <div class="form-group">
            <label for="age">Age:</label>
            <input type="number" class="form-control" id="age" name="age" required>
        </div>
        
        <div class="form-group">
            <label for="address">Address:</label>
            <textarea class="form-control" id="address" name="address" required></textarea>
        </div>
        
        <div class="form-group">
            <label for="tc_file">Transfer Certificate:</label>
            <input type="file" class="form-control-file" id="tc_file" name="tc_file" required>
        </div>
        
        <div class="form-group">
            <label for="marksheet_file">Mark Sheet:</label>
            <input type="file" class="form-control-file" id="marksheet_file" name="marksheet_file" required>
        </div>
        
        <input type="hidden" id="gps_coordinates" name="gps_coordinates">
        
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    document.getElementById('gps_coordinates').value = position.coords.latitude + ',' + position.coords.longitude;
                }, function(error) {
                    console.log('Error occurred. Error code: ' + error.code);
                });
            } else {
                console.log('Geolocation is not supported for this Browser/OS.');
            }
        });

        document.getElementById('admissionForm').addEventListener('submit', function(event) {
            // Implement address to coordinates conversion logic if needed
        });

        document.getElementById('admissionForm').addEventListener('submit', function(event) {
    event.preventDefault();

    const address = document.getElementById('address').value;
    if (!address) {
        this.submit();
        return;
    }

    // Replace 'YOUR_API_KEY' with your actual Google Maps Geocoding API key
    const geocodeUrl = `https://maps.googleapis.com/maps/api/geocode/json?address=${encodeURIComponent(address)}&key=YOUR_API_KEY`;

    fetch(geocodeUrl)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'OK') {
                const location = data.results[0].geometry.location;
                document.getElementById('gps_coordinates').value = location.lat + ',' + location.lng;
            }
            this.submit();
        })
        .catch(error => {
            console.error('Error fetching geocode:', error);
            this.submit();
        });
});

    </script>
@endsection
