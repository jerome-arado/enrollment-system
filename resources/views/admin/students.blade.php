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

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Enrollment Status</th>
                    <th>Registered</th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $student)
                    <tr>
                        <td style="font-weight:600;color:var(--bark);">{{ $student->name }}</td>
                        <td style="font-size:0.85rem;color:var(--stone);">{{ $student->email }}</td>
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
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="text-align:center;padding:2rem;color:var(--stone);">
                            No students registered yet.
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
@endsection