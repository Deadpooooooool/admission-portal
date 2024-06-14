@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-center align-items-center" style="height: 60vh;">
    <div class="card" style="width: 400px;">
        <div class="card-body">
            <h1 class="mb-4 text-center">Admin Login</h1>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin_login_submit') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                </div>

                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>

                <button type="submit" class="btn btn-primary btn-block mt-4">Login</button>
            </form>
        </div>
    </div>
</div>
@endsection
