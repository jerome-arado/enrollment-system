@extends('layouts.app')
@section('title', 'Admin Dashboard')

@section('content')
<div class="page">
    <div class="section-header flex items-center justify-between">
        <div>
            <h1>Enrollment Management</h1>
            <p>Review and manage all student enrollment applications.</p>
        </div>
        <a href="{{ route('admin.students') }}" class="btn btn-outline btn-sm">
            👥 All Students
        </a>
    </div>

    @if(session('success'))
        <div class="flash flash-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="flash flash-error">{{ session('error') }}</div>
    @endif

    {{-- Stats --}}
    <div class="stats-grid">
        <div class="stat-box">
            <div class="stat-number">{{ $stats['total'] }}</div>
            <div class="stat-label">Total</div>
        </div>
        <div class="stat-box" style="border-top: 3px solid var(--warn);">
            <div class="stat-number" style="color:var(--warn);">{{ $stats['pending'] }}</div>
            <div class="stat-label">Pending</div>
        </div>
        <div class="stat-box" style="border-top: 3px solid var(--success);">
            <div class="stat-number" style="color:var(--success);">{{ $stats['enrolled'] }}</div>
            <div class="stat-label">Enrolled</div>
        </div>
        <div class="stat-box" style="border-top: 3px solid var(--error);">
            <div class="stat-number" style="color:var(--error);">{{ $stats['disapproved'] }}</div>
            <div class="stat-label">Disapproved</div>
        </div>
    </div>

    {{-- Filters with auto‑submit for dropdowns and a search button --}}
    <form method="GET" action="{{ route('admin.dashboard') }}" class="filter-row" id="filterForm">
        {{-- Search field with inline button --}}
        <div class="form-group" style="flex-grow: 1;">
            <label>Search</label>
            <div style="display: flex; gap: 0.5rem; align-items: center;">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Name, email, course…" style="flex-grow: 1;">
                <button type="submit" class="btn btn-outline" style="margin-top:0;">🔍 Search</button>
            </div>
        </div>

        {{-- Course dropdown (auto‑submit) --}}
        <div class="form-group">
            <label>Course</label>
            <select name="course" id="courseSelect">
                <option value="">All Courses</option>
                @foreach(['BSIT', 'BSIS', 'BSCS'] as $c)
                    <option value="{{ $c }}" {{ request('course') === $c ? 'selected' : '' }}>{{ $c }}</option>
                @endforeach
            </select>
        </div>

        {{-- Clear link (resets all filters) --}}
        @if(request()->hasAny(['search', 'status', 'course']))
            <div class="form-group" style="justify-content: flex-end;">
                <a href="{{ route('admin.dashboard') }}" class="btn btn-outline" style="margin-top:auto;">✕ Clear</a>
            </div>
        @endif
    </form>

    {{-- Table --}}
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Student</th>
                    <th>Course / Year</th>
                    <th>Age</th>
                    <th>Submitted</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($enrollments as $enrollment)
                    <tr>
                        <td>
                            <div style="display:flex; align-items:center; gap:0.75rem;">
                                @if($enrollment->profile_picture)
                                    <img src="{{ asset('storage/' . $enrollment->profile_picture) }}"
                                         alt="" class="avatar">
                                @else
                                    <div style="width:40px;height:40px;border-radius:50%;background:var(--clay-pale);display:flex;align-items:center;justify-content:center;font-size:1.1rem;flex-shrink:0;">
                                        👤
                                    </div>
                                @endif
                                <div>
                                    <div style="font-weight:600; color:var(--bark);">{{ $enrollment->name }}</div>
                                    <div style="font-size:0.78rem; color:var(--stone);">{{ $enrollment->user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span style="font-weight:600;">{{ $enrollment->course }}</span><br>
                            <span style="font-size:0.8rem; color:var(--stone);">{{ $enrollment->year }} Year</span>
                        </td>
                        <td>{{ $enrollment->age }}</td>
                        <td style="font-size:0.82rem; color:var(--stone);">
                            {{ $enrollment->created_at->format('M j, Y') }}
                        </td>
                        <td>
                            <span class="badge badge-{{ $enrollment->status }}">
                                {{ $enrollment->status_label }}
                            </span>
                        </td>
                        <td>
                            <div style="display:flex; gap:0.4rem; flex-wrap:wrap;">
                                <a href="{{ route('admin.enrollment.show', $enrollment) }}"
                                   class="btn btn-outline btn-sm">View</a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align:center; padding:2.5rem; color:var(--stone);">
                            No pending enrollments found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($enrollments->hasPages())
        <div class="pagination-wrap">
            {{ $enrollments->links('pagination.custom') }}
        </div>
    @endif
</div>

<script>
    // Auto‑submit the form when status or course dropdown changes
    const statusSelect = document.getElementById('statusSelect');
    const courseSelect = document.getElementById('courseSelect');
    const filterForm = document.getElementById('filterForm');

    function submitForm() {
        filterForm.submit();
    }

    if (statusSelect) statusSelect.addEventListener('change', submitForm);
    if (courseSelect) courseSelect.addEventListener('change', submitForm);
</script>
@endsection