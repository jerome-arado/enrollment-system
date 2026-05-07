@extends('layouts.app')
@section('title', 'Enrollment Form')

@section('content')
<div class="page-narrow">
    <div class="section-header">
        <h1>Enrollment Form</h1>
        <p>Fill in your information accurately. All fields marked are required.</p>
    </div>

    @if ($errors->any())
        <div class="flash flash-error">
            Please correct the errors below before submitting.
        </div>
    @endif

    <div class="card">
        <form action="{{ route('student.enroll.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- Profile Picture --}}
            <div class="form-group">
                <label>Profile Picture <span style="font-weight:400;text-transform:none;letter-spacing:0;">(optional, max 2MB)</span></label>
                <div class="file-input-wrap">
                    <label class="file-label" id="file-label">
                        📷 Choose a photo (JPEG, PNG, WebP)
                    </label>
                    <input type="file" name="profile_picture" id="profile_picture"
                           accept="image/jpeg,image/jpg,image/png,image/webp"
                           onchange="updateFileName(this)">
                </div>
                @error('profile_picture')
                    <p class="field-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="divider"></div>

            {{-- Name --}}
            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name"
                       value="{{ old('name') }}"
                       placeholder="e.g. Juan Andres dela Cruz">
                @error('name') <p class="field-error">{{ $message }}</p> @enderror
            </div>

            {{-- Age & Birthdate row --}}
            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:1rem;">
                <div class="form-group {{ $errors->has('age') ? 'has-error' : '' }}">
                    <label for="age">Age</label>
                    <input type="number" id="age" name="age"
                           value="{{ old('age') }}"
                           min="15" max="80" placeholder="18">
                    @error('age') <p class="field-error">{{ $message }}</p> @enderror
                </div>

                <div class="form-group {{ $errors->has('birthdate') ? 'has-error' : '' }}">
                    <label for="birthdate">Birthdate</label>
                    <input type="date" id="birthdate" name="birthdate"
                           value="{{ old('birthdate') }}"
                           max="{{ date('Y-m-d') }}">
                    @error('birthdate') <p class="field-error">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Address --}}
            <div class="form-group {{ $errors->has('address') ? 'has-error' : '' }}">
                <label for="address">Home Address</label>
                <textarea id="address" name="address"
                          placeholder="House/Unit No., Street, Barangay, City, Province">{{ old('address') }}</textarea>
                @error('address') <p class="field-error">{{ $message }}</p> @enderror
            </div>

            {{-- Course & Year --}}
            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:1rem;">
                <div class="form-group {{ $errors->has('course') ? 'has-error' : '' }}">
                    <label for="course">Course</label>
                    <select id="course" name="course">
                        <option value="" disabled {{ old('course') ? '' : 'selected' }}>Select course</option>
                        @foreach(['BSIT' => 'BS Information Technology', 'BSIS' => 'BS Information Systems', 'BSCS' => 'BS Computer Science'] as $val => $label)
                            <option value="{{ $val }}" {{ old('course') === $val ? 'selected' : '' }}>
                                {{ $val }} — {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @error('course') <p class="field-error">{{ $message }}</p> @enderror
                </div>

                <div class="form-group {{ $errors->has('year') ? 'has-error' : '' }}">
                    <label for="year">Year Level</label>
                    <select id="year" name="year">
                        <option value="" disabled {{ old('year') ? '' : 'selected' }}>Select year</option>
                        @foreach(['1st', '2nd', '3rd', '4th'] as $yr)
                            <option value="{{ $yr }}" {{ old('year') === $yr ? 'selected' : '' }}>
                                {{ $yr }} Year
                            </option>
                        @endforeach
                    </select>
                    @error('year') <p class="field-error">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="divider"></div>

            <div class="flex gap-1">
                <button type="submit" class="btn btn-primary">
                    📬 Submit Application
                </button>
                <a href="{{ route('student.dashboard') }}" class="btn btn-outline">Cancel</a>
            </div>
        </form>
    </div>
</div>

<script>
function updateFileName(input) {
    const label = document.getElementById('file-label');
    if (input.files && input.files[0]) {
        label.textContent = '✅ ' + input.files[0].name;
    } else {
        label.textContent = '📷 Choose a photo (JPEG, PNG, WebP)';
    }
}
</script>
@endsection