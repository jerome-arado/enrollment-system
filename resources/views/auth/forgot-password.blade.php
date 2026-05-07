@extends('layouts.auth')
@section('title', 'Forgot Password')

@section('content')
<div class="auth-card">
    <h2>Forgot your password?</h2>
    <p style="color:var(--stone);font-size:0.88rem;margin-bottom:1.5rem;line-height:1.6;">
        Enter your registered email and we'll send you a link to reset your password.
    </p>

    @if(session('success'))
        <div class="flash flash-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="flash flash-error">{{ session('error') }}</div>
    @endif

    <form action="{{ route('password.forgot.send') }}" method="POST">
        @csrf

        <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
            <label for="email">Email Address</label>
            <input type="email" id="email" name="email"
                   value="{{ old('email') }}"
                   placeholder="you@gmail.com"
                   autofocus autocomplete="email">
            @error('email')
                <p class="field-error">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="btn-auth">📧 Send Reset Link</button>
    </form>
</div>

<p class="auth-footer">
    Remembered it? <a href="{{ route('login') }}">Back to Sign In</a>
</p>
@endsection