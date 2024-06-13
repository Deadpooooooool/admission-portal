@extends('layouts.app')

@section('content')
    <h1>Check Admission Status</h1>

    <form action="{{ route('status') }}" method="GET">
        @csrf
        <div class="form-group">
            <label for="email">Enter your email address:</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <button type="submit" class="btn btn-primary">Check Status</button>
    </form>

    @if($errors->any())
        <div class="alert alert-danger mt-3">
            {{ $errors->first() }}
        </div>
    @endif

    @isset($student)
        <div class="mt-3">
            <h2>Admission Status for {{ $student->name }}</h2>
            <p><strong>Email:</strong> {{ $student->email }}</p>
            <p><strong>Admitted:</strong> {{ $student->admitted ? 'Yes' : 'No' }}</p>
            <p><strong>Free Bus Fare:</strong> {{ $student->free_bus_fare ? 'Eligible' : 'Not Eligible' }}</p>
        </div>
    @endisset
@endsection
