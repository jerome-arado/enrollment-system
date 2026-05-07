@extends('layouts.auth')
@section('title', 'Reset Password')

@section('content')
<div class="auth-card">
    <h2>Set a new password</h2>
    <p style="color:var(--stone);font-size:0.88rem;margin-bottom:1.5rem;">
        Choose a strong password at least 8 characters long.
    </p>

    @if ($errors->has('token'))
        <div class="flash flash-error">{{ $errors->first('token') }}</div>
    @endif

    <form action="{{ route('password.reset') }}" method="POST">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <input type="hidden" name="email" value="{{ $email }}">

        <div class="form-group">
            <label>Email</label>
            <input type="email" value="{{ $email }}" disabled
                   style="background:var(--sand); color:var(--stone);">
        </div>

        <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
            <label for="password">New Password</label>
            <input type="password" id="password" name="password"
                   placeholder="At least 8 characters"
                   autocomplete="new-password" autofocus>
            @error('password') <p class="field-error">{{ $message }}</p> @enderror
        </div>

        <div class="form-group">
            <label for="password_confirmation">Confirm Password</label>
            <input type="password" id="password_confirmation" name="password_confirmation"
                   placeholder="Repeat new password"
                   autocomplete="new-password">
        </div>

        <button type="submit" class="btn-auth">🔒 Reset Password</button>
    </form>
</div>

<p class="auth-footer">
    <a href="{{ route('login') }}">← Back to Sign In</a>
</p>
@endsection