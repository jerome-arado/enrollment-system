@extends('layouts.app')
@section('title', 'Student Details')

@section('content')
<div class="page-narrow">
    <div class="section-header">
        <div class="mb-2">
            <a href="{{ route('admin.students') }}" class="btn btn-outline btn-sm">← Back to Students</a>
        </div>
        <h1>{{ $user->name }}</h1>
        <p>Student account details</p>
    </div>

    <div class="card">
        <div style="display:flex; gap:1.5rem; align-items:center; flex-wrap:wrap; margin-bottom:1.5rem;">
            @php
                $profilePic = $user->enrollment?->profile_picture;
                $initials = collect(explode(' ', $user->name))
                    ->map(fn($w) => strtoupper(substr($w, 0, 1)))
                    ->take(2)->implode('');
            @endphp
            <div style="width:80px; height:80px; border-radius:50%; background:var(--clay); display:flex; align-items:center; justify-content:center; overflow:hidden;">
                @if($profilePic && Storage::disk('public')->exists($profilePic))
                    <img src="{{ asset('storage/' . $profilePic) }}" style="width:100%; height:100%; object-fit:cover;">
                @else
                    <span style="font-size:1.5rem; font-weight:700; color:#fff;">{{ $initials }}</span>
                @endif
            </div>
            <div>
                <h2 style="font-family:var(--font); font-size:1.2rem; color:var(--bark);">{{ $user->name }}</h2>
                <p style="color:var(--stone);">{{ $user->email }}</p>
                <span class="badge badge-{{ $user->enrollment ? $user->enrollment->status : 'pending' }}" style="margin-top:0.3rem; display:inline-block;">
                    {{ $user->enrollment ? $user->enrollment->status_label : 'No Enrollment' }}
                </span>
            </div>
        </div>

        <div class="divider"></div>

        @if($user->enrollment)
            <h3 style="font-family:var(--font); font-size:1rem; margin-bottom:1rem;">Enrollment Details</h3>
            <div class="detail-grid">
                <div class="detail-item"><label>Course / Year</label><div class="value">{{ $user->enrollment->course }}, {{ $user->enrollment->year }} Year</div></div>
                <div class="detail-item"><label>Age / Birthdate</label><div class="value">{{ $user->enrollment->age }} / {{ $user->enrollment->birthdate->format('F j, Y') }}</div></div>
                <div class="detail-item"><label>Address</label><div class="value">{{ $user->enrollment->address }}</div></div>
                <div class="detail-item"><label>Submitted</label><div class="value">{{ $user->enrollment->created_at->format('F j, Y g:i A') }}</div></div>
                @if($user->enrollment->remarks)
                    <div class="detail-item"><label>Remarks</label><div class="value">{{ $user->enrollment->remarks }}</div></div>
                @endif
            </div>

            @if($user->enrollment->documents->count())
                <div class="divider"></div>
                <h3 style="font-family:var(--font); font-size:1rem; margin-bottom:0.5rem;">Uploaded Documents</h3>
                <ul style="margin-top:0.5rem; list-style:none;">
                    @foreach($user->enrollment->documents as $doc)
                        <li style="display:flex; justify-content:space-between; align-items:center; padding:0.5rem 0; border-bottom:1px solid var(--clay-pale);">
                            <span>{{ $doc->icon }} {{ $doc->label }} – {{ $doc->original_name }} ({{ $doc->formatted_size }})</span>
                            <a href="{{ route('documents.download', $doc) }}" class="btn btn-outline btn-sm">⬇ Download</a>
                        </li>
                    @endforeach
                </ul>
            @endif
        @else
            <div style="text-align:center; padding:2rem; background:var(--sand); border-radius:var(--radius-sm);">
                <p>This student has not submitted an enrollment application yet.</p>
                <p class="text-muted text-sm mt-1">No additional data to display.</p>
            </div>
        @endif

        <div class="divider"></div>
        <div class="flex gap-1 justify-between">
            <a href="{{ route('admin.students') }}" class="btn btn-outline">← Back to List</a>
            <form action="{{ route('admin.students.destroy', $user) }}" method="POST"
                  onsubmit="return confirm('Permanently delete this student account?')">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-danger">🗑 Delete Account</button>
            </form>
        </div>
    </div>
</div>
@endsection