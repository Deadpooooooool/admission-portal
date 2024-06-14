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
                        <input type="hidden" name="admitted" value="0">
                        <input type="checkbox" name="admitted" value="1" {{ $student->admitted ? 'checked' : '' }}>
                        <button type="submit" class="btn btn-primary btn-sm">Update</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection
