@extends('layouts.app')
@section('title', 'Enrollment — ' . $enrollment->name)

@section('content')
<div class="page-narrow">
    <div class="section-header">
        <div style="margin-bottom:0.5rem;">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline btn-sm">← Back</a>
        </div>
        <h1>{{ $enrollment->name }}</h1>
        <p>Application submitted {{ $enrollment->created_at->format('F j, Y \a\t g:i A') }}</p>
    </div>

    @if(session('success'))
        <div class="flash flash-success">{{ session('success') }}</div>
    @endif

    {{-- Student Details --}}
    <div class="card">
        <div style="display:flex; gap:1.5rem; align-items:flex-start; flex-wrap:wrap; margin-bottom:1.5rem;">
            @if($enrollment->profile_picture)
                <img src="{{ asset('storage/' . $enrollment->profile_picture) }}"
                     alt="Profile"
                     style="width:100px;height:100px;object-fit:cover;border-radius:var(--radius);border:2px solid var(--clay-pale);flex-shrink:0;">
            @else
                <div style="width:100px;height:100px;border-radius:var(--radius);background:var(--clay-pale);display:flex;align-items:center;justify-content:center;font-size:2.5rem;flex-shrink:0;">
                    👤
                </div>
            @endif

            <div>
                <h2 style="font-family:var(--font);font-size:1.4rem;font-weight:normal;color:var(--bark);">
                    {{ $enrollment->name }}
                </h2>
                <p style="color:var(--stone); font-size:0.9rem;">{{ $enrollment->user->email }}</p>
                <div style="margin-top:0.5rem;">
                    <span class="badge badge-{{ $enrollment->status }}">{{ $enrollment->status_label }}</span>
                </div>
            </div>
        </div>

        <div class="divider"></div>

        <div class="detail-grid">
            <div class="detail-item">
                <label>Age</label>
                <div class="value">{{ $enrollment->age }} years old</div>
            </div>
            <div class="detail-item">
                <label>Birthdate</label>
                <div class="value">{{ $enrollment->birthdate->format('F j, Y') }}</div>
            </div>
            <div class="detail-item">
                <label>Course</label>
                <div class="value">{{ $enrollment->course }}</div>
            </div>
            <div class="detail-item">
                <label>Year Level</label>
                <div class="value">{{ $enrollment->year }} Year</div>
            </div>
            <div class="detail-item" style="grid-column: 1 / -1;">
                <label>Address</label>
                <div class="value">{{ $enrollment->address }}</div>
            </div>
        </div>

        @if($enrollment->remarks)
            <div class="divider"></div>
            <div style="background:var(--warn-pale); padding:0.9rem 1rem; border-radius:var(--radius-sm); border-left: 3px solid var(--warn);">
                <label style="color:var(--warn); text-transform:none; letter-spacing:0;">Previous Remarks</label>
                <p style="margin-top:0.2rem;">{{ $enrollment->remarks }}</p>
            </div>
        @endif
    </div>

    {{-- Status Update --}}
    <div class="card mt-3">
        <h3 style="font-family:var(--font);font-size:1.05rem;font-weight:normal;color:var(--bark);margin-bottom:1.2rem;">
            Update Enrollment Status
        </h3>

        <form action="{{ route('admin.enrollment.status', $enrollment) }}" method="POST">
            @csrf @method('PUT')

            <div class="form-group">
                <label for="status">Decision</label>
                <select id="status" name="status">
                    @foreach(['pending' => 'Pending Review', 'enrolled' => 'Approve (Enrolled)', 'disapproved' => 'Disapprove'] as $val => $label)
                        <option value="{{ $val }}" {{ $enrollment->status === $val ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
                @error('status') <p class="field-error">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <label for="remarks">Remarks <span style="font-weight:400;text-transform:none;letter-spacing:0;">(optional — shown to student)</span></label>
                <textarea id="remarks" name="remarks" placeholder="Add a note for the student…">{{ old('remarks', $enrollment->remarks) }}</textarea>
                @error('remarks') <p class="field-error">{{ $message }}</p> @enderror
            </div>

            <div class="flex gap-1">
                <button type="submit" class="btn btn-primary">💾 Update Status</button>
            </div>
        </form>
    </div>

    {{-- Danger zone --}}
    <div class="card mt-3" style="border: 1px solid rgba(184,84,80,0.25);">
        <h3 style="font-size:0.9rem;color:var(--error);margin-bottom:0.75rem;">Danger Zone</h3>
        <p style="font-size:0.85rem;color:var(--stone);margin-bottom:1rem;">
            Permanently delete this enrollment record. This action cannot be undone.
        </p>
        <form action="{{ route('admin.enrollment.destroy', $enrollment) }}" method="POST"
              onsubmit="return confirm('Permanently delete this enrollment record?')">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-danger btn-sm">🗑 Delete Record</button>
        </form>
    </div>
</div>
@endsection