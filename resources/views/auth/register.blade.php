@extends('layouts.auth')
@section('title', 'Create Account')

@section('content')
<div class="auth-card">
    <h2>Create your account</h2>

    <form action="{{ route('register.post') }}" method="POST">
        @csrf

        <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
            <label for="name">Full Name</label>
            <input type="text" id="name" name="name"
                   value="{{ old('name') }}"
                   placeholder="Juan dela Cruz"
                   autocomplete="name" autofocus>
            @error('name')
                <p class="field-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
            <label for="email">Email Address</label>
            <input type="email" id="email" name="email"
                   value="{{ old('email') }}"
                   placeholder="you@example.com"
                   autocomplete="email">
            @error('email')
                <p class="field-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
            <label for="password">Password</label>
            <input type="password" id="password" name="password"
                   placeholder="At least 8 characters"
                   autocomplete="new-password">
            @error('password')
                <p class="field-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group {{ $errors->has('password_confirmation') ? 'has-error' : '' }}">
            <label for="password_confirmation">Confirm Password</label>
            <input type="password" id="password_confirmation" name="password_confirmation"
                   placeholder="Repeat password"
                   autocomplete="new-password">
        </div>

        <button type="submit" class="btn-auth">Create Account</button>
    </form>
</div>

<p class="auth-footer">
    Already have an account? <a href="{{ route('login') }}">Sign in</a>
</p>
@endsection