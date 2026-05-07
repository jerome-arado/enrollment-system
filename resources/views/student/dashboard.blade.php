@extends('layouts.app')
@section('title', 'Student Dashboard')

@section('content')
<div class="page-narrow">
    <div class="section-header">
        <h1>My Enrollment</h1>
        <p>Track and manage your enrollment application.</p>
    </div>

    @if(session('success'))
        <div class="flash flash-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="flash flash-error">{{ session('error') }}</div>
    @endif
    @if(session('info'))
        <div class="flash flash-info">{{ session('info') }}</div>
    @endif

    @if($enrollment)
        {{-- Status Card --}}
        <div class="status-card {{ $enrollment->status }}">
            <span class="status-icon">
                @if($enrollment->isEnrolled()) ✅
                @elseif($enrollment->isDisapproved()) ❌
                @else ⏳
                @endif
            </span>
            <h2>{{ $enrollment->status_label }}</h2>
            <p class="text-sm" style="color: var(--stone);">
                @if($enrollment->isPending())
                    Your application is being reviewed. We'll notify you by email.
                @elseif($enrollment->isEnrolled())
                    Congratulations! You are officially enrolled.
                @else
                    Your application was not approved.
                    @if($enrollment->remarks)
                        Please review the remarks below.
                    @endif
                @endif
            </p>
        </div>

        {{-- Enrollment Details --}}
        <div class="card">
            <div class="flex items-center justify-between mb-2">
                <h3 style="font-family: var(--font); font-size:1.1rem; font-weight:normal; color:var(--bark);">
                    Enrollment Details
                </h3>
                <span class="badge badge-{{ $enrollment->status }}">{{ $enrollment->status_label }}</span>
            </div>

            <div class="divider"></div>

            <div style="display:flex; gap:1.5rem; align-items:flex-start; flex-wrap:wrap;">
                @if($enrollment->profile_picture)
                    <img src="{{ asset('storage/' . $enrollment->profile_picture) }}"
                         alt="Profile" class="avatar avatar-lg"
                         style="border-radius: var(--radius); border: 2px solid var(--clay-pale);">
                @else
                    <div style="width:90px;height:90px;border-radius:var(--radius);background:var(--clay-pale);display:flex;align-items:center;justify-content:center;color:var(--clay);font-size:2rem;flex-shrink:0;">
                        👤
                    </div>
                @endif

                <div class="detail-grid" style="flex:1;">
                    <div class="detail-item">
                        <label>Full Name</label>
                        <div class="value">{{ $enrollment->name }}</div>
                    </div>
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
            </div>

            @if($enrollment->remarks)
                <div class="divider"></div>
                <div style="background:var(--warn-pale); padding:0.9rem 1rem; border-radius:var(--radius-sm); border-left: 3px solid var(--warn);">
                    <label style="color:var(--warn); text-transform:none; letter-spacing:0; font-size:0.8rem;">Admin Remarks</label>
                    <p style="margin-top:0.2rem; color:var(--ink);">{{ $enrollment->remarks }}</p>
                </div>
            @endif

            @if(!$enrollment->isEnrolled())
                <div class="divider"></div>
                <div class="flex gap-1">
                    <a href="{{ route('student.enroll.edit') }}" class="btn btn-outline btn-sm">
                        Edit Application
                    </a>
                    <form action="{{ route('student.enroll.destroy') }}" method="POST"
                          onsubmit="return confirm('Withdraw your enrollment application? This cannot be undone.')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">
                            Withdraw
                        </button>
                    </form>
                </div>
            @endif
        </div>

    @else
        {{-- No enrollment yet --}}
        <div class="card" style="text-align:center; padding: 3rem 2rem;">
            <div style="font-size:3rem; margin-bottom:1rem;">📋</div>
            <h2 style="font-family:var(--font); font-size:1.4rem; font-weight:normal; color:var(--bark); margin-bottom:0.5rem;">
                No Enrollment Yet
            </h2>
            <p style="color:var(--stone); margin-bottom:2rem; font-size:0.9rem;">
                You haven't submitted an enrollment form. Fill it out to begin your application.
            </p>
            <a href="{{ route('student.enroll') }}" class="btn btn-primary">
                Start Enrollment
            </a>
        </div>
    @endif
</div>
@endsection