@extends('layouts.app')

@section('content')
    <h1 class="mb-4">Admission Form</h1>
    <form action="{{ route('submit_admission') }}" method="POST" enctype="multipart/form-data">
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
                <option value="male">Male</option>
                <option value="female">Female</option>
                <option value="other">Other</option>
            </select>
        </div>
        
        <div class="form-group">
            <label for="age">Age:</label>
            <input type="number" class="form-control" id="age" name="age" required>
        </div>
        
        <div class="form-group">
            <label for="address">Address:</label>
            <textarea class="form-control" id="address" name="address" required onblur="getCoordinates()"></textarea>
        </div>
        
        <div class="form-group">
            <label for="tc_file">Transfer Certificate:</label>
            <input type="file" class="form-control-file" id="tc_file" name="tc_file" required>
        </div>
        
        <div class="form-group">
            <label for="mark_sheet_file">Mark Sheet:</label>
            <input type="file" class="form-control-file" id="mark_sheet_file" name="mark_sheet_file" required>
        </div>
        
        <input type="hidden" id="gps_coordinates" name="gps_coordinates">
        
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>

    <script>
        function getCoordinates() {
            var address = document.getElementById('address').value;
            var geocoder = new google.maps.Geocoder();
            geocoder.geocode({'address': address}, function(results, status) {
                if (status == 'OK') {
                    var lat = results[0].geometry.location.lat();
                    var lng = results[0].geometry.location.lng();
                    document.getElementById('gps_coordinates').value = lat + "," + lng;
                } else {
                    alert('Geocode was not successful for the following reason: ' + status);
                }
            });
        }
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_GOOGLE_MAPS_API_KEY&callback=initMap" async defer></script>
@endsection
