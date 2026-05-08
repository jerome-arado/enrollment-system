@extends('layouts.app')
@section('title', 'All Students')

@section('content')
<div class="page">
    <div class="section-header flex items-center justify-between">
        <div>
            <h1>Registered Students</h1>
            <p>All student accounts in the system.</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline btn-sm">← Enrollments</a>
    </div>

    {{-- Filter & Search Row --}}
    <form method="GET" action="{{ route('admin.students') }}" class="filter-row" id="studentFilterForm">
        <div class="form-group" style="flex-grow: 1;">
            <label>Search</label>
            <div style="display: flex; gap: 0.5rem; align-items: center;">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Name or email…" style="flex-grow: 1;">
                <button type="submit" class="btn btn-outline" style="margin-top:0;">🔍 Search</button>
            </div>
        </div>

        <div class="form-group">
            <label>Course</label>
            <select name="course" id="courseFilter">
                <option value="">All Courses</option>
                <option value="BSIT" {{ request('course') === 'BSIT' ? 'selected' : '' }}>BSIT</option>
                <option value="BSIS" {{ request('course') === 'BSIS' ? 'selected' : '' }}>BSIS</option>
                <option value="BSCS" {{ request('course') === 'BSCS' ? 'selected' : '' }}>BSCS</option>
            </select>
        </div>

        <div class="form-group">
            <label>Enrollment Status</label>
            <select name="status" id="statusFilter">
                <option value="">All Students</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="enrolled" {{ request('status') === 'enrolled' ? 'selected' : '' }}>Enrolled</option>
                <option value="disapproved" {{ request('status') === 'disapproved' ? 'selected' : '' }}>Disapproved</option>
                <option value="no_application" {{ request('status') === 'no_application' ? 'selected' : '' }}>No Application Yet</option>
            </select>
        </div>

        @if(request()->hasAny(['search', 'course', 'status']))
            <div class="form-group" style="justify-content: flex-end;">
                <a href="{{ route('admin.students') }}" class="btn btn-outline" style="margin-top:auto;">✕ Clear</a>
            </div>
        @endif
    </form>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Photo</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Course</th>
                    <th>Enrollment Status</th>
                    <th>Registered</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $student)
                    @php
                        $profilePic = $student->enrollment?->profile_picture;
                        $initials = collect(explode(' ', $student->name))
                            ->map(fn($w) => strtoupper(substr($w, 0, 1)))
                            ->take(2)->implode('');
                    @endphp
                    <tr>
                        <td>
                            <div style="width:40px; height:40px; border-radius:50%; background:var(--clay); display:flex; align-items:center; justify-content:center; overflow:hidden;">
                                @if($profilePic && Storage::disk('public')->exists($profilePic))
                                    <img src="{{ asset('storage/' . $profilePic) }}" alt="avatar" style="width:100%; height:100%; object-fit:cover;">
                                @else
                                    <span style="font-size:0.8rem; font-weight:700; color:#fff;">{{ $initials }}</span>
                                @endif
                            </div>
                        </td>
                        <td style="font-weight:600;color:var(--bark);">{{ $student->name }}</td>
                        <td style="font-size:0.85rem;color:var(--stone);">{{ $student->email }}</td>
                        <td style="font-size:0.85rem;color:var(--stone);">
                            {{ $student->enrollment ? $student->enrollment->course : '—' }}
                        </td>
                        <td>
                            @if($student->enrollment)
                                <span class="badge badge-{{ $student->enrollment->status }}">
                                    {{ $student->enrollment->status_label }}
                                </span>
                                — <a href="{{ route('admin.enrollment.show', $student->enrollment) }}"
                                     style="color:var(--clay); font-size:0.8rem; text-decoration:none;">
                                    View →
                                </a>
                            @else
                                <span style="color:var(--stone-light); font-size:0.82rem;">No application</span>
                            @endif
                        </td>
                        <td style="font-size:0.82rem;color:var(--stone);">
                            {{ $student->created_at->format('M j, Y') }}
                        </td>
                        <td>
                            <div style="display:flex; gap:0.4rem;">
                                <a href="{{ route('admin.students.show', $student) }}" class="btn btn-outline btn-sm">
                                    👁️ View
                                </a>
                                <form action="{{ route('admin.students.destroy', $student) }}" method="POST"
                                      onsubmit="return confirm('Are you sure? This will delete the student account and all associated data.')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">🗑 Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align:center;padding:2rem;color:var(--stone);">
                            No students found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($students->hasPages())
        <div class="pagination-wrap">
            {{ $students->links('pagination.custom') }}
        </div>
    @endif
</div>

<script>
    // Auto-submit when course or status dropdown changes
    const courseFilter = document.getElementById('courseFilter');
    const statusFilter = document.getElementById('statusFilter');
    const filterForm = document.getElementById('studentFilterForm');

    function submitForm() {
        filterForm.submit();
    }

    if (courseFilter) courseFilter.addEventListener('change', submitForm);
    if (statusFilter) statusFilter.addEventListener('change', submitForm);
</script>
@endsection