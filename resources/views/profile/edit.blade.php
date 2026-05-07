@extends('layouts.app')
@section('title', 'Edit Profile')

@section('content')
<div class="page-sm">

    <div class="section-header">
        <h1>Account Settings</h1>
        <p>Manage your profile picture, personal information, and security.</p>
    </div>

    {{-- Tabs --}}
    <div class="profile-tabs">
        <a href="{{ route('profile.edit') }}"
           class="profile-tab {{ request()->routeIs('profile.edit') ? 'active' : '' }}">
            👤 Profile
        </a>
        <a href="{{ route('profile.password') }}"
           class="profile-tab {{ request()->routeIs('profile.password') ? 'active' : '' }}">
            🔑 Password
        </a>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="flash flash-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="flash flash-error">{{ session('error') }}</div>
    @endif
    @if($errors->has('profile_picture'))
        <div class="flash flash-error">{{ $errors->first('profile_picture') }}</div>
    @endif

    {{-- ── Profile Picture Card ──────────────────────────── --}}
    <div class="card">
        <h3 class="card-title">Profile Picture</h3>
        <p class="text-muted text-sm" style="margin-bottom:1.5rem;">
            This photo appears on your enrollment record and in the navigation bar.
            @if(!$user->enrollment)
                <br><span style="color:var(--warn);">⚠ Submit an enrollment form first to enable picture upload.</span>
            @endif
        </p>

        <div class="avatar-edit-row">

            {{-- Current avatar display --}}
            <div class="avatar-display" id="avatarDisplay">
                @php
                    $pic = $user->enrollment?->profile_picture;
                    $initials = collect(explode(' ', $user->name))
                        ->map(fn($w) => strtoupper(substr($w, 0, 1)))
                        ->take(2)->implode('');
                @endphp

                @if($pic)
                    <img src="{{ asset('storage/' . $pic) }}"
                         alt="Profile picture"
                         id="avatarPreview"
                         class="avatar-large">
                @else
                    <div class="avatar-initials" id="avatarInitials">{{ $initials }}</div>
                    <img id="avatarPreview" class="avatar-large" style="display:none;" alt="Preview">
                @endif

                <div class="avatar-overlay" id="avatarOverlay" onclick="triggerFileInput()">
                    <span>📷</span>
                    <span>Change</span>
                </div>
            </div>

            {{-- Upload form --}}
            <div class="avatar-actions">
                @if($user->enrollment)
                    <form action="{{ route('profile.picture.update') }}"
                          method="POST"
                          enctype="multipart/form-data"
                          id="pictureForm">
                        @csrf

                        <div class="file-drop-zone" id="dropZone"
                             onclick="triggerFileInput()"
                             ondragover="handleDragOver(event)"
                             ondragleave="handleDragLeave(event)"
                             ondrop="handleDrop(event)">
                            <input type="file"
                                   name="profile_picture"
                                   id="pictureInput"
                                   accept="image/jpeg,image/jpg,image/png,image/webp"
                                   style="display:none;"
                                   onchange="handleFileSelect(this)">
                            <div class="drop-zone-inner" id="dropZoneInner">
                                <span class="drop-icon">🖼</span>
                                <p class="drop-text">Click to browse or drag & drop</p>
                                <p class="drop-hint">JPEG, PNG, WebP — max 2 MB</p>
                            </div>
                        </div>

                        <div id="selectedFile" style="display:none; margin-top:0.75rem;">
                            <div class="selected-file-info">
                                <span id="fileName"></span>
                                <span id="fileSize" class="text-muted text-sm"></span>
                            </div>
                            <div class="flex gap-1" style="margin-top:0.75rem;">
                                <button type="submit" class="btn btn-primary btn-sm">
                                    💾 Save Picture
                                </button>
                                <button type="button" class="btn btn-outline btn-sm" onclick="cancelSelect()">
                                    Cancel
                                </button>
                            </div>
                        </div>
                    </form>

                    @if($pic)
                        <div class="divider" style="margin:1.25rem 0;"></div>
                        <form action="{{ route('profile.picture.remove') }}"
                              method="POST"
                              onsubmit="return confirm('Remove your profile picture?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-outline btn-sm"
                                    style="color:var(--error); border-color:rgba(184,84,80,0.35);">
                                🗑 Remove Picture
                            </button>
                        </form>
                    @endif
                @else
                    <div style="padding:1.25rem; background:var(--sand); border-radius:var(--radius-sm); border:1.5px dashed var(--stone-light); text-align:center;">
                        <p class="text-muted text-sm">
                            Profile picture upload is available after submitting an enrollment form.
                        </p>
                        <a href="{{ route('student.enroll') }}" class="btn btn-outline btn-sm" style="margin-top:0.75rem;">
                            📝 Start Enrollment
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- ── Personal Info Card ─────────────────────────────── --}}
    <div class="card mt-3">
        <h3 class="card-title">Personal Information</h3>

        <form action="{{ route('profile.update') }}" method="POST">
            @csrf @method('PUT')

            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name"
                       value="{{ old('name', $user->name) }}"
                       placeholder="Your full name">
                @error('name') <p class="field-error">{{ $message }}</p> @enderror
            </div>

            <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email"
                       value="{{ old('email', $user->email) }}"
                       placeholder="you@gmail.com">
                @error('email') <p class="field-error">{{ $message }}</p> @enderror
            </div>

            <div class="divider"></div>

            <div class="flex gap-1">
                <button type="submit" class="btn btn-primary">💾 Save Changes</button>
                <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : route('student.dashboard') }}"
                   class="btn btn-outline">Cancel</a>
            </div>
        </form>
    </div>

    {{-- ── Enrollment badge (students only) ──────────────── --}}
    @if($user->isStudent() && $user->enrollment)
        <div class="card mt-3" style="border-top: 3px solid var(--clay);">
            <p class="text-sm text-muted" style="margin-bottom:0.75rem;">Current enrollment application:</p>
            <div class="flex gap-1 items-center">
                <span class="badge badge-{{ $user->enrollment->status }}">
                    {{ $user->enrollment->status_label }}
                </span>
                <a href="{{ route('student.dashboard') }}" class="btn btn-outline btn-sm">
                    View Enrollment →
                </a>
            </div>
        </div>
    @endif
</div>

{{-- ── Extra CSS for this page ──────────────────────────── --}}
@push('styles')
<style>
    .card-title {
        font-family: var(--font);
        font-size: 1.05rem;
        font-weight: normal;
        color: var(--bark);
        margin-bottom: 0.35rem;
    }

    /* Avatar display block */
    .avatar-edit-row {
        display: flex;
        gap: 2rem;
        align-items: flex-start;
        flex-wrap: wrap;
    }

    .avatar-display {
        position: relative;
        flex-shrink: 0;
        width: 110px;
        height: 110px;
        cursor: pointer;
    }

    .avatar-large {
        width: 110px;
        height: 110px;
        border-radius: var(--radius);
        object-fit: cover;
        border: 3px solid var(--clay-pale);
        display: block;
        transition: filter 0.2s ease;
    }

    .avatar-initials {
        width: 110px;
        height: 110px;
        border-radius: var(--radius);
        background: var(--bark);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        font-weight: 700;
        color: var(--clay-light);
        letter-spacing: 0.04em;
        border: 3px solid var(--clay-pale);
        transition: filter 0.2s ease;
    }

    .avatar-overlay {
        position: absolute;
        inset: 0;
        border-radius: var(--radius);
        background: rgba(61,47,34,0.55);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 0.2rem;
        opacity: 0;
        transition: opacity 0.2s ease;
        color: #fff;
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.04em;
        text-transform: uppercase;
        backdrop-filter: blur(2px);
    }

    .avatar-overlay span:first-child { font-size: 1.4rem; }

    .avatar-display:hover .avatar-overlay { opacity: 1; }
    .avatar-display:hover .avatar-large,
    .avatar-display:hover .avatar-initials { filter: brightness(0.7); }

    /* Drop zone */
    .avatar-actions { flex: 1; min-width: 200px; }

    .file-drop-zone {
        border: 2px dashed var(--stone-light);
        border-radius: var(--radius-sm);
        padding: 1.5rem 1rem;
        text-align: center;
        cursor: pointer;
        transition: border-color 0.2s ease, background 0.2s ease;
        background: var(--sand);
    }

    .file-drop-zone:hover,
    .file-drop-zone.drag-over {
        border-color: var(--clay);
        background: var(--clay-pale);
    }

    .drop-zone-inner { pointer-events: none; }
    .drop-icon  { font-size: 1.75rem; display: block; margin-bottom: 0.4rem; }
    .drop-text  { font-size: 0.88rem; color: var(--stone); font-weight: 600; margin-bottom: 0.2rem; }
    .drop-hint  { font-size: 0.76rem; color: var(--stone-light); }

    .selected-file-info {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.6rem 0.9rem;
        background: var(--moss-pale);
        border-radius: var(--radius-sm);
        border: 1px solid var(--moss-light);
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--moss);
    }

    /* Tabs shared */
    .profile-tabs {
        display: flex;
        border-bottom: 2px solid var(--clay-pale);
        margin-bottom: 2rem;
    }
    .profile-tab {
        padding: 0.7rem 1.4rem;
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--stone);
        text-decoration: none;
        border-bottom: 2px solid transparent;
        margin-bottom: -2px;
        transition: color 0.18s, border-color 0.18s;
    }
    .profile-tab:hover { color: var(--clay); }
    .profile-tab.active { color: var(--clay); border-color: var(--clay); }

    @media (max-width: 480px) {
        .avatar-edit-row { flex-direction: column; align-items: center; }
        .avatar-actions  { width: 100%; }
    }
</style>
@endpush

{{-- ── JS for live preview & drag-drop ─────────────────── --}}
<script>
function triggerFileInput() {
    document.getElementById('pictureInput').click();
}

function handleFileSelect(input) {
    if (!input.files || !input.files[0]) return;

    const file = input.files[0];

    // Live preview
    const reader = new FileReader();
    reader.onload = function(e) {
        const preview = document.getElementById('avatarPreview');
        const initials = document.getElementById('avatarInitials');

        preview.src = e.target.result;
        preview.style.display = 'block';
        if (initials) initials.style.display = 'none';
    };
    reader.readAsDataURL(file);

    // Show file info
    const sizeKb = (file.size / 1024).toFixed(0);
    const sizeMb = (file.size / 1024 / 1024).toFixed(2);
    document.getElementById('fileName').textContent = '✅ ' + file.name;
    document.getElementById('fileSize').textContent = sizeKb > 1024
        ? sizeMb + ' MB' : sizeKb + ' KB';
    document.getElementById('selectedFile').style.display = 'block';
    document.getElementById('dropZone').style.display = 'none';
}

function cancelSelect() {
    document.getElementById('pictureInput').value = '';
    document.getElementById('selectedFile').style.display = 'none';
    document.getElementById('dropZone').style.display = 'block';

    // Revert preview
    @if(!$pic)
    const preview = document.getElementById('avatarPreview');
    const initials = document.getElementById('avatarInitials');
    preview.src = '';
    preview.style.display = 'none';
    if (initials) initials.style.display = 'flex';
    @endif
}

// Drag and drop
function handleDragOver(e) {
    e.preventDefault();
    document.getElementById('dropZone').classList.add('drag-over');
}

function handleDragLeave(e) {
    document.getElementById('dropZone').classList.remove('drag-over');
}

function handleDrop(e) {
    e.preventDefault();
    document.getElementById('dropZone').classList.remove('drag-over');

    const files = e.dataTransfer.files;
    if (files.length > 0) {
        const input = document.getElementById('pictureInput');
        // Create a DataTransfer to assign dropped file to input
        const dt = new DataTransfer();
        dt.items.add(files[0]);
        input.files = dt.files;
        handleFileSelect(input);
    }
}
</script>
@endsection