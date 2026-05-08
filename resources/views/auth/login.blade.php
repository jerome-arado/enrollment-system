@extends('layouts.auth')
@section('title', 'Sign In')

@section('content')
<div class="auth-card">
    <h2>Login your account</h2>

    @if(session('success'))
        <div class="flash flash-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="flash flash-error">{{ session('error') }}</div>
    @endif

    <form action="{{ route('login.post') }}" method="POST">
        @csrf

        <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
            <label for="email">Email Address</label>
            <input type="email" id="email" name="email"
                   value="{{ old('email') }}"
                   placeholder="you@gmail.com"
                   autocomplete="email" autofocus>
            @error('email')
                <p class="field-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
            <label for="password" style="display:flex; justify-content:space-between; align-items:center;">
                <span>Password</span>
                <a href="{{ route('password.forgot') }}"
                   style="font-size:0.78rem; color:var(--clay); text-decoration:none; font-weight:600; text-transform:none; letter-spacing:0;">
                    Forgot password?
                </a>
            </label>
            <input type="password" id="password" name="password"
                   placeholder="••••••••"
                   autocomplete="current-password">
            @error('password')
                <p class="field-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group remember-row">
            <input type="checkbox" id="remember" name="remember">
            <label for="remember" style="text-transform:none;letter-spacing:0;font-size:0.85rem;margin-bottom:0;font-weight:400;">
                Remember me
            </label>
        </div>

        <button type="submit" class="btn-auth">Sign In</button>
    </form>
</div>

<p class="auth-footer">
    New student? <a href="{{ route('register') }}">Create an account</a>
</p>
@endsection