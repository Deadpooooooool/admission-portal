@extends('layouts.admin')

@section('content')
    <h1 class="mb-4">Admitted Students</h1>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Gender</th>
                <th>Age</th>
                <th>Address</th>
                <th>GPS Coordinates</th>
                <th>Free Bus Fare</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($students as $student)
            @if ($student->admitted)
            <tr>
                <td>{{ $student->name }}</td>
                <td>{{ $student->email }}</td>
                <td>{{ $student->gender }}</td>
                <td>{{ $student->age }}</td>
                <td>{{ $student->address }}</td>
                <td>{{ $student->gps_coordinates }}</td>
                <td>{{ $student->free_bus_fare ? 'Yes' : 'No' }}</td>
            </tr>
            @endif
            @endforeach
        </tbody>
    </table>
@endsection
