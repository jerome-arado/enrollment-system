@extends('layouts.app')
@section('title', 'Edit Enrollment')

@section('content')
<div class="page-narrow">
    <div class="section-header">
        <h1>Edit Enrollment</h1>
        <p>Update your information below. Submitting will reset status to "Pending" if disapproved.</p>
    </div>

    @if ($errors->any())
        <div class="flash flash-error">Please correct the errors below.</div>
    @endif

    <div class="card">
        <form action="{{ route('student.enroll.update') }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')

            {{-- Profile Picture --}}
            <div class="form-group">
                <label>Profile Picture</label>
                @if($enrollment->profile_picture)
                    <div style="margin-bottom:0.75rem; display:flex; align-items:center; gap:0.75rem;">
                        <img src="{{ asset('storage/' . $enrollment->profile_picture) }}"
                             alt="Current photo" class="avatar" style="border-radius:var(--radius-sm);">
                        <span class="text-muted text-sm">Current photo</span>
                    </div>
                @endif
                <div class="file-input-wrap">
                    <label class="file-label" id="file-label">
                        📷 Replace photo (leave blank to keep current)
                    </label>
                    <input type="file" name="profile_picture" id="profile_picture"
                           accept="image/jpeg,image/jpg,image/png,image/webp"
                           onchange="updateFileName(this, 'file-label')">
                </div>
                @error('profile_picture') <p class="field-error">{{ $message }}</p> @enderror
            </div>

            <div class="divider"></div>

            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" value="{{ old('name', $enrollment->name) }}">
                @error('name') <p class="field-error">{{ $message }}</p> @enderror
            </div>

            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:1rem;">
                <div class="form-group {{ $errors->has('age') ? 'has-error' : '' }}">
                    <label for="age">Age</label>
                    <input type="number" id="age" name="age" value="{{ old('age', $enrollment->age) }}" min="15" max="80">
                    @error('age') <p class="field-error">{{ $message }}</p> @enderror
                </div>
                <div class="form-group {{ $errors->has('birthdate') ? 'has-error' : '' }}">
                    <label for="birthdate">Birthdate</label>
                    <input type="date" id="birthdate" name="birthdate"
                           value="{{ old('birthdate', $enrollment->birthdate->format('Y-m-d')) }}"
                           max="{{ date('Y-m-d') }}">
                    @error('birthdate') <p class="field-error">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="form-group {{ $errors->has('address') ? 'has-error' : '' }}">
                <label for="address">Home Address</label>
                <textarea id="address" name="address">{{ old('address', $enrollment->address) }}</textarea>
                @error('address') <p class="field-error">{{ $message }}</p> @enderror
            </div>

            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:1rem;">
                <div class="form-group {{ $errors->has('course') ? 'has-error' : '' }}">
                    <label for="course">Course</label>
                    <select id="course" name="course">
                        @foreach(['BSIT', 'BSIS', 'BSCS'] as $val)
                            <option value="{{ $val }}" {{ old('course', $enrollment->course) === $val ? 'selected' : '' }}>{{ $val }}</option>
                        @endforeach
                    </select>
                    @error('course') <p class="field-error">{{ $message }}</p> @enderror
                </div>
                <div class="form-group {{ $errors->has('year') ? 'has-error' : '' }}">
                    <label for="year">Year Level</label>
                    <select id="year" name="year">
                        @foreach(['1st', '2nd', '3rd', '4th'] as $yr)
                            <option value="{{ $yr }}" {{ old('year', $enrollment->year) === $yr ? 'selected' : '' }}>{{ $yr }} Year</option>
                        @endforeach
                    </select>
                    @error('year') <p class="field-error">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="divider"></div>

            {{-- ========== DOCUMENTS SECTION (ADD THIS) ========== --}}
            <h3 style="font-family:var(--font); font-size:1rem; margin:0 0 1rem; color:var(--bark);">📄 Required Documents</h3>

            @php
                $docMapping = [
                    'form137'    => 'Form 137 / SF9',
                    'birth_cert' => 'Birth Certificate (PSA)',
                    'good_moral' => 'Good Moral Certificate',
                    'medical'    => 'Medical Certificate',
                    'id_picture' => '2x2 ID Picture',
                ];
            @endphp

            @foreach($docMapping as $field => $label)
                @php
                    $existingDoc = $enrollment->documents->firstWhere('label', $label);
                @endphp
                <div class="form-group">
                    <label>{{ $label }}</label>
                    @if($existingDoc)
                        <div style="display:flex; align-items:center; gap:0.75rem; margin-bottom:0.5rem;">
                            <span style="font-size:0.9rem;">{{ $existingDoc->icon }} {{ $existingDoc->original_name }}</span>
                            <span class="badge badge-doc-{{ $existingDoc->status }}" style="font-size:0.7rem;">
                                {{ $existingDoc->status_label }}
                            </span>
                            <a href="{{ route('documents.download', $existingDoc) }}" class="btn btn-outline btn-sm">
                                ⬇ Download
                            </a>
                            @if($existingDoc->status !== 'approved')
                                <form action="{{ route('student.documents.destroy', $existingDoc) }}" method="POST"
                                      onsubmit="return confirm('Remove this document?')" style="display:inline;">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-danger btn-sm" title="Remove">🗑</button>
                                </form>
                            @endif
                        </div>
                        <div class="file-input-wrap">
                            <label class="file-label" id="label-{{ $field }}">
                                📁 Replace with a new file (optional)
                            </label>
                            <input type="file" name="{{ $field }}" id="{{ $field }}"
                                   accept="@if($field === 'id_picture') image/jpeg,image/jpg,image/png,image/webp @else .pdf,.doc,.docx,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document @endif"
                                   onchange="updateFileName(this, 'label-{{ $field }}')">
                        </div>
                    @else
                        <div class="file-input-wrap">
                            <label class="file-label" id="label-{{ $field }}">
                                📁 Upload (required)
                            </label>
                            <input type="file" name="{{ $field }}" id="{{ $field }}"
                                   accept="@if($field === 'id_picture') image/jpeg,image/jpg,image/png,image/webp @else .pdf,.doc,.docx,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document @endif"
                                   onchange="updateFileName(this, 'label-{{ $field }}')">
                        </div>
                        @error($field) <p class="field-error">{{ $message }}</p> @enderror
                    @endif
                </div>
            @endforeach

            <div class="divider"></div>

            <div class="flex gap-1">
                <button type="submit" class="btn btn-primary">💾 Save Changes</button>
                <a href="{{ route('student.dashboard') }}" class="btn btn-outline">Cancel</a>
            </div>
        </form>
    </div>
</div>

<script>
function updateFileName(input, labelId) {
    const label = document.getElementById(labelId);
    if (input.files && input.files[0]) {
        label.textContent = '✅ ' + input.files[0].name;
    } else {
        if (labelId === 'file-label') {
            label.textContent = '📷 Replace photo (leave blank to keep current)';
        } else if (labelId.includes('id_picture')) {
            label.textContent = '🖼️ Choose image';
        } else {
            label.textContent = '📁 Choose file';
        }
    }
}
</script>

<style>
    .badge-doc-pending  { background: var(--warn-pale);    color: var(--warn);    }
    .badge-doc-approved { background: var(--success-pale); color: var(--success); }
    .badge-doc-rejected { background: var(--error-pale);   color: var(--error);   }
</style>
@endsection