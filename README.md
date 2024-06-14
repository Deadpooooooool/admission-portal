### Documentation for Admission Portal Application

## Table of Contents

1. [Introduction](#introduction)
2. [Installation](#installation)
3. [Configuration](#configuration)
4. [Database Migrations](#database-migrations)
5. [Running the Application](#running-the-application)
6. [Features](#features)
7. [Form Submission Details](#form-submission-details)
8. [Admin Panel](#admin-panel)
9. [File Storage](#file-storage)
10. [Troubleshooting](#troubleshooting)
11. [License](#license)

## Introduction

This is a web application for managing student admissions. It allows users to submit admission forms and check their status. Admin users can view submissions, update admission statuses, and manage admitted students.

## Installation

1. Clone the repository:
    ```bash
    git clone https://github.com/yourusername/admission-portal.git
    cd admission-portal
    ```

2. Install dependencies:
    ```bash
    composer install
    npm install
    ```

3. Create a copy of the `.env` file:
    ```bash
    cp .env.example .env
    ```

4. Generate an application key:
    ```bash
    php artisan key:generate
    ```

## Configuration

1. Update the `.env` file with your database and other necessary configurations.
2. Set up your Google Maps Geocoding API key in the `.env` file:
    ```env
    GOOGLE_MAPS_API_KEY=your_google_maps_api_key
    ```

## Database Migrations

1. Run the database migrations and seed the database:
    ```bash
    php artisan migrate --seed
    ```

## Running the Application

1. Start the development server:
    ```bash
    php artisan serve
    ```

2. Start the frontend build process:
    ```bash
    npm run dev
    ```

## Features

### Client Side

1. **Admission Form**: Allows users to submit their admission details including name, email, gender, age, address, and file uploads for transfer certificate and mark sheet.
2. **Status Check**: Users can check their admission status by entering their registered email address.

### Admin Side

1. **Submissions**: Admins can view a list of all submitted forms and update the admission status.
2. **Admissions**: Admins can view a list of admitted students and see their free bus fare eligibility status.

## Form Submission Details

### Admission Form

The admission form collects the following information:
- Name
- Email
- Gender
- Age
- Address
- Transfer Certificate (file upload)
- Mark Sheet (file upload)
- GPS Coordinates (automatically collected or derived from the address)

### Form Validation and Submission

- The form validates required fields before submission.
- The GPS coordinates are collected using the browser's geolocation API or derived from the entered address using the Google Maps Geocoding API.
- The form data is submitted to the backend for storage in the database.

### Example Code for Admission Form View

```blade
@extends('layouts.app')

@section('content')
    <h1 class="mb-4">Admission Form</h1>
    <form action="{{ route('admission_form.submit') }}" method="POST" enctype="multipart/form-data" id="admissionForm">
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
            event.preventDefault();
            const address = document.getElementById('address').value;

            if (!address) {
                this.submit();
                return;
            }

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
```

## Admin Panel

The admin panel allows administrators to manage student submissions and admissions. The following functionalities are available:

### Submissions

Admins can view all student submissions, including details such as name, email, gender, age, address, and uploaded files. They can update the admission status for each student.

### Admissions

Admins can view all admitted students and their free bus fare eligibility status.

### Example Code for Admin Views

```blade
@extends('layouts.admin')

@section('content')
    <h1 class="mb-4">Student Submissions</h1>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Gender</th>
                <th>Age</th>
                <th>Address</th>
                <th>Transfer Certificate</th>
                <th>Mark Sheet</th>
                <th>GPS Coordinates</th>
                <th>Admitted</th>
                <th>Free Bus Fare</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($students as $student)
            <tr>
                <td>{{ $student->name }}</td>
                <td>{{ $student->email }}</td>
                <td>{{ $student->gender }}</td>
                <td>{{ $student->age }}</td>
                <td>{{ $student->address }}</td>
                <td><a href="{{ Storage::url($student->tc_file) }}">View</a></td>
                <td><a href="{{ Storage::url($student->marksheet_file) }}">View</a></td>
                <td>{{ $student->gps_coordinates }}</td>
                <td>{{ $student->admitted ? 'Yes' : 'No' }}</td>
                <td>{{ $student->free_bus_fare ? 'Yes' : 'No' }}</td>
                <td>
                    <form action="{{ route('admin_update_status') }}" method="POST">
                        @csrf
                        <input type="hidden" name="student_id" value="{{ $student->id }}">
                        <input type="checkbox" name="admitted" {{ $student->admitted ? 'checked' : '' }}>
                        <button type="submit" class="btn btn-primary btn-sm">Update</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection
```

## File Storage

Uploaded files (Transfer Certificates and Mark Sheets) are stored in the `storage/app` directory. Ensure the following configuration in `config/filesystems.php`:

```php
'disks' => [
    'local' => [
        'driver' => 'local',
        'root' => storage_path('app'),
        'throw' => false,
    ],

    'public' => [
        'driver' => 'local',
        'root' => storage_path('app/public'),
        'url' => env('APP_URL').'/storage',
        'visibility' => 'public',
        'throw' => false,


    ],
],
```

Also, create a symbolic link to make the storage accessible from the web:

```bash
php artisan storage:link
```

## Troubleshooting

### Common Issues

1. **File Upload Issues**:
    - Ensure the storage directory has the correct permissions.
    - Create a symbolic link using `php artisan storage:link`.

2. **Google Maps Geocoding API**:
    - Ensure the API key is correctly set in the `.env` file.

3. **Database Errors**:
    - Ensure migrations are up-to-date with `php artisan migrate`.

### Logging

Logs can be found in the `storage/logs/laravel.log` file. Use `Log::info` and `Log::error` to debug issues.

## License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for more details.

---

This documentation provides a comprehensive overview of the Admission Portal application, detailing installation, configuration, and usage instructions.
