@extends('layouts.app')
@section('title', 'Change Password')

@section('content')
<div class="page-sm">
    <div class="section-header">
        <h1>Account Settings</h1>
        <p>Manage your profile information and security settings.</p>
    </div>

    {{-- Profile Hero --}}
    <div class="profile-hero">
        <div class="profile-hero-avatar">
            @php
                $initials = collect(explode(' ', auth()->user()->name))
                    ->map(fn($w) => strtoupper(substr($w, 0, 1)))
                    ->take(2)->implode('');
            @endphp
            {{ $initials }}
        </div>
        <div>
            <div class="profile-hero-name">{{ auth()->user()->name }}</div>
            <div class="profile-hero-email">{{ auth()->user()->email }}</div>
        </div>
    </div>

    {{-- Tabs --}}
    <div class="profile-tabs">
        <a href="{{ route('profile.edit') }}"
           class="profile-tab {{ request()->routeIs('profile.edit') ? 'active' : '' }}">
            👤 Profile Info
        </a>
        <a href="{{ route('profile.password') }}"
           class="profile-tab {{ request()->routeIs('profile.password') ? 'active' : '' }}">
            🔑 Password
        </a>
    </div>

    @if(session('success'))
        <div class="flash flash-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <h3 style="font-family:var(--font);font-size:1.05rem;font-weight:normal;color:var(--bark);margin-bottom:0.3rem;">
            Change Password
        </h3>
        <p class="text-muted text-sm" style="margin-bottom:1.5rem;">
            Use a strong, unique password at least 8 characters long.
        </p>

        <form action="{{ route('profile.password.update') }}" method="POST">
            @csrf @method('PUT')

            <div class="form-group {{ $errors->has('current_password') ? 'has-error' : '' }}">
                <label for="current_password">Current Password</label>
                <input type="password" id="current_password" name="current_password"
                       placeholder="Enter your current password"
                       autocomplete="current-password">
                @error('current_password') <p class="field-error">{{ $message }}</p> @enderror
            </div>

            <div class="divider"></div>

            <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
                <label for="password">New Password</label>
                <input type="password" id="password" name="password"
                       placeholder="At least 8 characters"
                       autocomplete="new-password">
                @error('password') <p class="field-error">{{ $message }}</p> @enderror
            </div>

            <div class="form-group {{ $errors->has('password_confirmation') ? 'has-error' : '' }}">
                <label for="password_confirmation">Confirm New Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation"
                       placeholder="Repeat new password"
                       autocomplete="new-password">
            </div>

            <div class="divider"></div>

            <button type="submit" class="btn btn-primary">🔒 Update Password</button>
        </form>
    </div>

    <div class="card mt-3" style="border-color: rgba(196,122,74,0.25);">
        <p class="text-sm text-muted">
            🔒 <strong style="color:var(--bark);">Tip:</strong> Never share your password.
            If you've forgotten it, you can use the
            <a href="{{ route('password.forgot') }}" style="color:var(--clay); text-decoration:none; font-weight:600;">
                forgot password
            </a> option on the login page.
        </p>
    </div>
</div>
@endsection