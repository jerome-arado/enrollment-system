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
                           onchange="updateFileName(this)">
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

            <div class="flex gap-1">
                <button type="submit" class="btn btn-primary">💾 Save Changes</button>
                <a href="{{ route('student.dashboard') }}" class="btn btn-outline">Cancel</a>
            </div>
        </form>
    </div>
</div>

<script>
function updateFileName(input) {
    const label = document.getElementById('file-label');
    label.textContent = input.files[0] ? '✅ ' + input.files[0].name : '📷 Replace photo';
}
</script>
@endsection