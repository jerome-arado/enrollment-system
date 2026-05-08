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
        <form action="{{ route('student.enroll.store') }}" method="POST" enctype="multipart/form-data" id="enrollmentForm">
            @csrf

            {{-- Profile Picture (optional) --}}
            <div class="form-group">
                <label>Profile Picture <span style="font-weight:400;text-transform:none;letter-spacing:0;">(optional, max 2MB)</span></label>
                <div class="file-input-wrap">
                    <label class="file-label" id="file-label">
                        📷 Choose a photo (JPEG, PNG, WebP)
                    </label>
                    <input type="file" name="profile_picture" id="profile_picture"
                           accept="image/jpeg,image/jpg,image/png,image/webp"
                           onchange="updateFileName(this, 'file-label')">
                </div>
                @error('profile_picture')
                    <p class="field-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="divider"></div>

            {{-- Personal Information --}}
            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="e.g. Juan Andres dela Cruz">
                @error('name') <p class="field-error">{{ $message }}</p> @enderror
            </div>

            {{-- Age & Birthdate with validation --}}
            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:1rem;">
                <div class="form-group {{ $errors->has('age') ? 'has-error' : '' }}">
                    <label for="age">Age</label>
                    <input type="number" id="age" name="age" value="{{ old('age') }}" min="15" max="80" placeholder="18">
                    <div id="ageError" class="field-error" style="display:none;"></div>
                    @error('age') <p class="field-error">{{ $message }}</p> @enderror
                </div>

                <div class="form-group {{ $errors->has('birthdate') ? 'has-error' : '' }}">
                    <label for="birthdate">Birthdate</label>
                    <input type="date" id="birthdate" name="birthdate" value="{{ old('birthdate') }}" max="{{ date('Y-m-d') }}">
                    <div id="birthdateError" class="field-error" style="display:none;"></div>
                    @error('birthdate') <p class="field-error">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="form-group {{ $errors->has('address') ? 'has-error' : '' }}">
                <label for="address">Home Address</label>
                <textarea id="address" name="address" placeholder="House/Unit No., Street, Barangay, City, Province">{{ old('address') }}</textarea>
                @error('address') <p class="field-error">{{ $message }}</p> @enderror
            </div>

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

            {{-- Required Documents Section --}}
            <h3 style="font-family:var(--font); font-size:1rem; margin:0 0 1rem; color:var(--bark);">📄 Required Documents (all are required)</h3>

            @php
                $documents = [
                    'form137' => 'Form 137 / SF9',
                    'birth_cert' => 'Birth Certificate (PSA)',
                    'good_moral' => 'Good Moral Certificate',
                    'medical' => 'Medical Certificate',
                ];
            @endphp

            @foreach($documents as $field => $label)
                <div class="form-group {{ $errors->has($field) ? 'has-error' : '' }}">
                    <label>{{ $label }} <span style="font-weight:400;text-transform:none;letter-spacing:0;">(PDF, DOC, DOCX, max 5MB)</span></label>
                    <div class="file-input-wrap">
                        <label class="file-label" id="label-{{ $field }}">
                            📁 Choose file
                        </label>
                        <input type="file" name="{{ $field }}" id="{{ $field }}"
                               accept=".pdf,.doc,.docx,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document"
                               onchange="updateFileName(this, 'label-{{ $field }}')">
                    </div>
                    @error($field) <p class="field-error">{{ $message }}</p> @enderror
                </div>
            @endforeach

            {{-- Special case for ID picture: image format --}}
            <div class="form-group {{ $errors->has('id_picture') ? 'has-error' : '' }}">
                <label>2x2 ID Picture <span style="font-weight:400;text-transform:none;letter-spacing:0;">(JPEG, PNG, WebP, max 2MB)</span></label>
                <div class="file-input-wrap">
                    <label class="file-label" id="label-id_picture">
                        🖼️ Choose image
                    </label>
                    <input type="file" name="id_picture" id="id_picture"
                           accept="image/jpeg,image/jpg,image/png,image/webp"
                           onchange="updateFileName(this, 'label-id_picture')">
                </div>
                @error('id_picture') <p class="field-error">{{ $message }}</p> @enderror
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
function updateFileName(input, labelId) {
    const label = document.getElementById(labelId);
    if (input.files && input.files[0]) {
        label.textContent = '✅ ' + input.files[0].name;
    } else {
        if (labelId.includes('profile_picture')) {
            label.textContent = '📷 Choose a photo (JPEG, PNG, WebP)';
        } else if (labelId.includes('id_picture')) {
            label.textContent = '🖼️ Choose image';
        } else {
            label.textContent = '📁 Choose file';
        }
    }
}

// ── Age & Birthdate validation ──────────────────────────────
function calculateAge(birthdate) {
    if (!birthdate) return null;
    const today = new Date();
    const birth = new Date(birthdate);
    let age = today.getFullYear() - birth.getFullYear();
    const monthDiff = today.getMonth() - birth.getMonth();
    if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birth.getDate())) {
        age--;
    }
    return age;
}

function showAgeError(message) {
    const errorDiv = document.getElementById('ageError');
    if (message) {
        errorDiv.textContent = message;
        errorDiv.style.display = 'block';
        document.getElementById('age').classList.add('has-error');
    } else {
        errorDiv.style.display = 'none';
        document.getElementById('age').classList.remove('has-error');
    }
}

function showBirthdateError(message) {
    const errorDiv = document.getElementById('birthdateError');
    if (message) {
        errorDiv.textContent = message;
        errorDiv.style.display = 'block';
        document.getElementById('birthdate').classList.add('has-error');
    } else {
        errorDiv.style.display = 'none';
        document.getElementById('birthdate').classList.remove('has-error');
    }
}

function validateAgeAndBirthdate() {
    const ageInput = document.getElementById('age');
    const birthdateInput = document.getElementById('birthdate');
    const age = parseInt(ageInput.value);
    const birthdate = birthdateInput.value;

    if (!birthdate) {
        showBirthdateError('');
        showAgeError('');
        return true;
    }

    const calculatedAge = calculateAge(birthdate);
    if (calculatedAge === null) return true;

    if (isNaN(age) || age !== calculatedAge) {
        showAgeError(`Age must be ${calculatedAge} (based on your birthdate).`);
        showBirthdateError(`Birthdate corresponds to age ${calculatedAge}.`);
        return false;
    } else {
        showAgeError('');
        showBirthdateError('');
        return true;
    }
}

// Auto-fill age when birthdate changes
document.getElementById('birthdate').addEventListener('change', function() {
    const birthdate = this.value;
    if (birthdate) {
        const calculatedAge = calculateAge(birthdate);
        if (calculatedAge !== null) {
            document.getElementById('age').value = calculatedAge;
            validateAgeAndBirthdate();
        }
    } else {
        document.getElementById('age').value = '';
    }
});

// Validate on manual age change
document.getElementById('age').addEventListener('input', function() {
    validateAgeAndBirthdate();
});

// Prevent form submission if mismatch
document.getElementById('enrollmentForm').addEventListener('submit', function(e) {
    if (!validateAgeAndBirthdate()) {
        e.preventDefault();
        alert('Please fix the age/birthdate mismatch before submitting.');
    }
});

// Initial validation (if old values exist)
document.addEventListener('DOMContentLoaded', function() {
    if (document.getElementById('birthdate').value) {
        validateAgeAndBirthdate();
    }
});
</script>
@endsection