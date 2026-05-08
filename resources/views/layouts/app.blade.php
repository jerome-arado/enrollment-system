<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Enrollment System')</title>
    <style>
        /* ── Design Tokens ─────────────────────────────── */
        :root {
            --clay:       #c47a4a;
            --clay-light: #e8b48a;
            --clay-pale:  #f5e6d8;
            --moss:       #5a7a5c;
            --moss-light: #a8c4a0;
            --moss-pale:  #eaf0e8;
            --bark:       #3d2f22;
            --sand:       #f9f4ee;
            --stone:      #7a6a5a;
            --stone-light:#c4b4a4;
            --cream:      #fefcf8;
            --ink:        #2a2018;
            --error:      #b85450;
            --error-pale: #fdf0ef;
            --warn:       #b87d30;
            --warn-pale:  #fdf4e7;
            --success:    #4a7a5c;
            --success-pale:#eef5ef;
            --radius-sm:  6px;
            --radius:     12px;
            --radius-lg:  20px;
            --shadow-sm:  0 1px 3px rgba(61,47,34,0.08);
            --shadow:     0 4px 16px rgba(61,47,34,0.10);
            --shadow-lg:  0 8px 32px rgba(61,47,34,0.14);
            --font:       'Georgia', 'Times New Roman', serif;
            --font-ui:    system-ui, -apple-system, 'Segoe UI', sans-serif;
            --transition: 0.18s ease;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { font-size: 16px; scroll-behavior: smooth; }
        body {
            font-family: var(--font-ui);
            background: var(--sand);
            color: var(--ink);
            min-height: 100vh;
            line-height: 1.65;
        }
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: var(--clay-pale); }
        ::-webkit-scrollbar-thumb { background: var(--clay-light); border-radius: 4px; }

        /* ── Navigation ─────────────────────────────────── */
        .nav {
            background: var(--bark);
            color: var(--clay-pale);
            padding: 0 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 60px;
            position: sticky;
            top: 0;
            z-index: 200;
            box-shadow: 0 2px 12px rgba(0,0,0,0.25);
        }
        .nav-brand {
            font-family: var(--font);
            font-size: 1.2rem;
            color: var(--clay-light);
            text-decoration: none;
            letter-spacing: 0.02em;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .nav-brand .dot {
            width: 8px; height: 8px;
            background: var(--clay);
            border-radius: 50%;
        }
        .nav-center {
            display: flex;
            align-items: center;
            gap: 1.75rem;
            list-style: none;
        }
        .nav-center a {
            color: var(--stone-light);
            text-decoration: none;
            font-size: 0.875rem;
            letter-spacing: 0.03em;
            transition: color var(--transition);
            padding-bottom: 2px;
            border-bottom: 2px solid transparent;
        }
        .nav-center a:hover,
        .nav-center a.active { color: var(--clay-light); border-color: var(--clay); }

        /* ── Profile Dropdown ───────────────────────────── */
        .profile-menu {
            position: relative;
        }
        .profile-trigger {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            background: rgba(255,255,255,0.07);
            border: 1px solid rgba(196,180,164,0.2);
            border-radius: 99px;
            padding: 0.3rem 0.75rem 0.3rem 0.3rem;
            cursor: pointer;
            transition: background var(--transition);
            color: var(--clay-pale);
            font-size: 0.82rem;
        }
        .profile-trigger:hover {
            background: rgba(255,255,255,0.13);
        }
        .profile-avatar {
            width: 30px; height: 30px;
            border-radius: 50%;
            background: var(--clay);
            display: flex; align-items: center; justify-content: center;
            font-size: 0.75rem;
            font-weight: 700;
            color: #fff;
            letter-spacing: 0.02em;
            flex-shrink: 0;
            overflow: hidden;
        }
        .profile-avatar img {
            width: 100%; height: 100%;
            object-fit: cover;
        }
        .profile-name {
            max-width: 120px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .profile-caret {
            width: 0; height: 0;
            border-left: 4px solid transparent;
            border-right: 4px solid transparent;
            border-top: 5px solid var(--stone-light);
            transition: transform var(--transition);
            flex-shrink: 0;
        }
        .profile-menu.open .profile-caret { transform: rotate(180deg); }

        .profile-dropdown {
            position: absolute;
            top: calc(100% + 10px);
            right: 0;
            background: var(--cream);
            border: 1px solid rgba(196,180,164,0.5);
            border-radius: var(--radius);
            box-shadow: var(--shadow-lg);
            min-width: 220px;
            overflow: hidden;
            opacity: 0;
            transform: translateY(-8px) scale(0.97);
            pointer-events: none;
            transition: opacity 0.15s ease, transform 0.15s ease;
            z-index: 300;
        }
        .profile-menu.open .profile-dropdown {
            opacity: 1;
            transform: translateY(0) scale(1);
            pointer-events: all;
        }
        .dropdown-header {
            padding: 1rem 1.1rem 0.75rem;
            border-bottom: 1px solid var(--clay-pale);
        }
        .dropdown-header .d-name {
            font-weight: 700;
            font-size: 0.9rem;
            color: var(--bark);
        }
        .dropdown-header .d-email {
            font-size: 0.75rem;
            color: var(--stone);
            margin-top: 0.1rem;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .dropdown-header .d-role {
            display: inline-block;
            margin-top: 0.4rem;
            font-size: 0.68rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            padding: 0.15rem 0.5rem;
            border-radius: 99px;
            background: var(--clay-pale);
            color: var(--clay);
        }
        .dropdown-body { padding: 0.4rem 0; }
        .dropdown-item {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            padding: 0.6rem 1.1rem;
            text-decoration: none;
            font-size: 0.86rem;
            color: var(--ink);
            transition: background var(--transition);
            cursor: pointer;
            width: 100%;
            background: none;
            border: none;
            font-family: var(--font-ui);
            text-align: left;
        }
        .dropdown-item:hover { background: var(--clay-pale); }
        .dropdown-item .icon {
            width: 20px;
            text-align: center;
            font-size: 0.9rem;
            color: var(--stone);
        }
        .dropdown-divider {
            height: 1px;
            background: var(--clay-pale);
            margin: 0.35rem 0;
        }
        .dropdown-item.danger { color: var(--error); }
        .dropdown-item.danger .icon { color: var(--error); }
        .dropdown-item.danger:hover { background: var(--error-pale); }

        /* ── Page Layout ────────────────────────────────── */
        .page       { max-width: 1100px; margin: 0 auto; padding: 2.5rem 1.5rem 4rem; }
        .page-narrow{ max-width: 680px;  margin: 0 auto; padding: 2.5rem 1.5rem 4rem; }
        .page-sm    { max-width: 500px;  margin: 0 auto; padding: 2.5rem 1.5rem 4rem; }

        .section-header { margin-bottom: 2rem; padding-bottom: 1rem; border-bottom: 2px solid var(--clay-pale); }
        .section-header h1 { font-family: var(--font); font-size: 1.9rem; color: var(--bark); font-weight: normal; line-height: 1.3; }
        .section-header p  { color: var(--stone); margin-top: 0.35rem; font-size: 0.9rem; }

        /* ── Cards ──────────────────────────────────────── */
        .card { background: var(--cream); border: 1px solid rgba(196,180,164,0.4); border-radius: var(--radius); padding: 2rem; box-shadow: var(--shadow-sm); }
        .card + .card { margin-top: 1.5rem; }

        /* ── Flash ──────────────────────────────────────── */
        .flash { padding: 0.9rem 1.25rem; border-radius: var(--radius-sm); margin-bottom: 1.5rem; font-size: 0.9rem; display: flex; align-items: flex-start; gap: 0.6rem; border-left: 4px solid; }
        .flash-success   { background: var(--success-pale); border-color: var(--success); color: #2d5c3a; }
        .flash-error     { background: var(--error-pale);   border-color: var(--error);   color: #7a2a28; }
        .flash-info      { background: var(--warn-pale);    border-color: var(--warn);    color: #7a5010; }

        /* ── Forms ──────────────────────────────────────── */
        .form-group { margin-bottom: 1.4rem; }
        label { display: block; font-size: 0.82rem; font-weight: 600; letter-spacing: 0.05em; text-transform: uppercase; color: var(--stone); margin-bottom: 0.45rem; }
        input[type="text"], input[type="email"], input[type="password"], input[type="number"], input[type="date"], select, textarea {
            width: 100%; padding: 0.65rem 0.9rem; border: 1.5px solid var(--stone-light);
            border-radius: var(--radius-sm); background: #fff; font-size: 0.95rem;
            color: var(--ink); font-family: var(--font-ui); outline: none; appearance: none;
            transition: border-color var(--transition), box-shadow var(--transition);
        }
        input:focus, select:focus, textarea:focus { border-color: var(--clay); box-shadow: 0 0 0 3px rgba(196,122,74,0.12); }
        select { background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath fill='%237a6a5a' d='M6 8L0 0h12z'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 0.9rem center; padding-right: 2.5rem; }
        textarea { resize: vertical; min-height: 80px; }
        .has-error input, .has-error select, .has-error textarea { border-color: var(--error); }
        .field-error { font-size: 0.78rem; color: var(--error); margin-top: 0.35rem; display: flex; align-items: center; gap: 0.3rem; }
        .field-error::before { content: '⚠'; font-size: 0.7rem; }

        /* ── Buttons ────────────────────────────────────── */
        .btn { display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.65rem 1.4rem; border: none; border-radius: var(--radius-sm); font-size: 0.88rem; font-weight: 600; letter-spacing: 0.02em; cursor: pointer; text-decoration: none; transition: all var(--transition); font-family: var(--font-ui); white-space: nowrap; }
        .btn-primary { background: var(--clay); color: #fff; }
        .btn-primary:hover { background: #a5663c; box-shadow: var(--shadow-sm); }
        .btn-moss { background: var(--moss); color: #fff; }
        .btn-moss:hover { background: #496347; }
        .btn-outline { background: transparent; border: 1.5px solid var(--stone-light); color: var(--stone); }
        .btn-outline:hover { border-color: var(--clay); color: var(--clay); }
        .btn-danger { background: var(--error); color: #fff; }
        .btn-danger:hover { background: #943f3c; }
        .btn-sm { padding: 0.35rem 0.8rem; font-size: 0.78rem; }
        .btn-full { width: 100%; justify-content: center; }

        /* ── Badges ─────────────────────────────────────── */
        .badge { display: inline-flex; align-items: center; gap: 0.3rem; padding: 0.2rem 0.7rem; border-radius: 99px; font-size: 0.75rem; font-weight: 700; letter-spacing: 0.05em; text-transform: uppercase; }
        .badge::before { content: ''; width: 6px; height: 6px; border-radius: 50%; background: currentColor; }
        .badge-pending     { background: var(--warn-pale);    color: var(--warn);    }
        .badge-enrolled    { background: var(--success-pale); color: var(--success); }
        .badge-disapproved { background: var(--error-pale);   color: var(--error);   }

        /* ── Table ──────────────────────────────────────── */
        .table-wrap { overflow-x: auto; border-radius: var(--radius); }
        table { width: 100%; border-collapse: collapse; font-size: 0.875rem; background: var(--cream); }
        thead th { background: var(--bark); color: var(--clay-pale); padding: 0.85rem 1rem; text-align: left; font-size: 0.75rem; letter-spacing: 0.06em; text-transform: uppercase; font-weight: 600; }
        thead th:first-child { border-radius: var(--radius) 0 0 0; }
        thead th:last-child  { border-radius: 0 var(--radius) 0 0; }
        tbody tr { border-bottom: 1px solid var(--clay-pale); transition: background var(--transition); }
        tbody tr:hover { background: var(--moss-pale); }
        tbody td { padding: 0.8rem 1rem; vertical-align: middle; }

        /* ── Avatar ─────────────────────────────────────── */
        .avatar    { width: 40px; height: 40px; border-radius: 50%; object-fit: cover; border: 2px solid var(--clay-pale); }
        .avatar-lg { width: 90px; height: 90px; }

        /* ── File Upload ────────────────────────────────── */
        .file-input-wrap { position: relative; }
        .file-input-wrap input[type="file"] { position: absolute; inset: 0; opacity: 0; cursor: pointer; }
        .file-label { display: flex; align-items: center; gap: 0.6rem; padding: 0.65rem 0.9rem; border: 1.5px dashed var(--stone-light); border-radius: var(--radius-sm); background: var(--sand); color: var(--stone); font-size: 0.88rem; cursor: pointer; transition: border-color var(--transition), background var(--transition); }
        .file-label:hover { border-color: var(--clay); background: var(--clay-pale); }

        /* ── Stats ──────────────────────────────────────── */
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 1rem; margin-bottom: 2rem; }
        .stat-box { background: var(--cream); border: 1px solid rgba(196,180,164,0.4); border-radius: var(--radius); padding: 1.25rem 1.5rem; text-align: center; }
        .stat-box .stat-number { font-size: 2.2rem; font-family: var(--font); font-weight: normal; line-height: 1; color: var(--bark); }
        .stat-box .stat-label  { font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.07em; color: var(--stone); margin-top: 0.3rem; }

        /* ── Filter Row ─────────────────────────────────── */
        .filter-row { display: flex; gap: 0.75rem; flex-wrap: wrap; margin-bottom: 1.5rem; align-items: flex-end; }
        .filter-row .form-group { margin-bottom: 0; }
        .filter-row input, .filter-row select { width: auto; min-width: 140px; }

        /* ── Status Card ────────────────────────────────── */
        .status-card { padding: 2rem; border-radius: var(--radius); text-align: center; margin-bottom: 2rem; }
        .status-card.pending     { background: var(--warn-pale);    border: 2px solid rgba(184,125,48,0.3); }
        .status-card.enrolled    { background: var(--success-pale); border: 2px solid rgba(74,122,92,0.3); }
        .status-card.disapproved { background: var(--error-pale);   border: 2px solid rgba(184,84,80,0.3); }
        .status-card .status-icon { font-size: 2.5rem; display: block; margin-bottom: 0.5rem; }
        .status-card h2 { font-family: var(--font); font-size: 1.4rem; font-weight: normal; margin-bottom: 0.4rem; }

        /* ── Pagination ─────────────────────────────────── */
        .pagination-wrap { display: flex; justify-content: center; margin-top: 2rem; gap: 0.3rem; flex-wrap: wrap; }
        .pagination-wrap a, .pagination-wrap span { padding: 0.4rem 0.75rem; border-radius: var(--radius-sm); font-size: 0.85rem; text-decoration: none; color: var(--stone); border: 1px solid var(--stone-light); transition: all var(--transition); }
        .pagination-wrap a:hover { border-color: var(--clay); color: var(--clay); }
        .pagination-wrap span[aria-current] { background: var(--clay); color: #fff; border-color: var(--clay); }

        /* ── Profile Page Specifics ─────────────────────── */
        .profile-tabs { display: flex; gap: 0; border-bottom: 2px solid var(--clay-pale); margin-bottom: 2rem; }
        .profile-tab { padding: 0.7rem 1.4rem; font-size: 0.875rem; font-weight: 600; color: var(--stone); text-decoration: none; border-bottom: 2px solid transparent; margin-bottom: -2px; transition: color var(--transition), border-color var(--transition); }
        .profile-tab:hover { color: var(--clay); }
        .profile-tab.active { color: var(--clay); border-color: var(--clay); }

        .profile-hero { display: flex; align-items: center; gap: 1.25rem; padding: 1.5rem; background: linear-gradient(135deg, var(--clay-pale), var(--moss-pale)); border-radius: var(--radius); margin-bottom: 1.75rem; }
        .profile-hero-avatar { width: 64px; height: 64px; border-radius: 50%; background: var(--bark); display: flex; align-items: center; justify-content: center; font-size: 1.4rem; font-weight: 700; color: var(--clay-light); border: 3px solid var(--cream); flex-shrink: 0; }
        .profile-hero-name { font-family: var(--font); font-size: 1.3rem; font-weight: normal; color: var(--bark); }
        .profile-hero-email { font-size: 0.82rem; color: var(--stone); margin-top: 0.15rem; }

        /* ── Helpers ────────────────────────────────────── */
        .flex             { display: flex; }
        .items-center     { align-items: center; }
        .justify-between  { justify-content: space-between; }
        .gap-1 { gap: 0.5rem; }
        .gap-2 { gap: 1rem; }
        .mt-1 { margin-top: 0.5rem; }
        .mt-2 { margin-top: 1rem; }
        .mt-3 { margin-top: 1.5rem; }
        .mb-2 { margin-bottom: 1rem; }
        .text-sm     { font-size: 0.85rem; }
        .text-muted  { color: var(--stone); }
        .text-center { text-align: center; }
        .divider { height: 1px; background: var(--clay-pale); margin: 1.5rem 0; }
        .detail-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.2rem; }
        .detail-item label  { margin-bottom: 0.15rem; }
        .detail-item .value { font-size: 1rem; color: var(--ink); }

        /* ── Responsive ─────────────────────────────────── */
        @media (max-width: 640px) {
            .nav { padding: 0 1rem; }
            .nav-center { gap: 1rem; }
            .profile-name { display: none; }
            .page, .page-narrow, .page-sm { padding: 1.5rem 1rem 3rem; }
            .detail-grid { grid-template-columns: 1fr; }
        }
    </style>
    @stack('styles')
</head>
<body>

@auth
<nav class="nav">
    {{-- Brand --}}
    <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : route('student.dashboard') }}"
       class="nav-brand">
        <span class="dot"></span> Enrollment System
    </a>

    {{-- Center links --}}
    <ul class="nav-center">
        @if(auth()->user()->isAdmin())
            <li><a href="{{ route('admin.dashboard') }}"
                   class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">Enrollments</a></li>
            <li><a href="{{ route('admin.students') }}"
                   class="{{ request()->routeIs('admin.students') ? 'active' : '' }}">Students</a></li>
        @else
            <li><a href="{{ route('student.dashboard') }}"
                class="{{ request()->routeIs('student.dashboard') ? 'active' : '' }}">My Enrollment</a></li>
        @endif
    </ul>

    {{-- Profile Dropdown --}}
    <div class="profile-menu" id="profileMenu">
        @php
            $user = auth()->user();
            $profilePic = null;
            if ($user->isAdmin()) {
                $profilePic = $user->profile_picture;
            } else {
                // For students, use enrollment profile picture if exists
                $profilePic = $user->enrollment?->profile_picture;
            }
            $initials = collect(explode(' ', $user->name))
                ->map(fn($w) => strtoupper(substr($w, 0, 1)))
                ->take(2)->implode('');
        @endphp

        <button class="profile-trigger" onclick="toggleDropdown()" type="button" aria-haspopup="true" aria-expanded="false">
            <div class="profile-avatar">
                @if($profilePic && Storage::disk('public')->exists($profilePic))
                    <img src="{{ asset('storage/' . $profilePic) }}" alt="avatar" style="width:100%;height:100%;object-fit:cover;border-radius:50%;">
                @else
                    {{ $initials }}
                @endif
            </div>
            <span class="profile-name">{{ $user->name }}</span>
            <span class="profile-caret"></span>
        </button>

        <div class="profile-dropdown" role="menu">
            <div class="dropdown-header">
                <div class="d-name">{{ $user->name }}</div>
                <div class="d-email">{{ $user->email }}</div>
                <span class="d-role">{{ $user->role }}</span>
            </div>
            <div class="dropdown-body">
                <a href="{{ route('profile.edit') }}" class="dropdown-item" role="menuitem">
                    <span class="icon">👤</span> Edit Profile
                </a>
                <a href="{{ route('profile.password') }}" class="dropdown-item" role="menuitem">
                    <span class="icon">🔒</span> Change Password
                </a>
                <div class="dropdown-divider"></div>
                @if($user->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="dropdown-item" role="menuitem">
                        <span class="icon">📊</span> Admin Panel
                    </a>
                    <div class="dropdown-divider"></div>
                @endif
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="dropdown-item danger" role="menuitem">
                        <span class="icon">🚪</span> Sign Out
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>
@endauth

<main>
    @yield('content')
</main>

<script>
function toggleDropdown() {
    const menu = document.getElementById('profileMenu');
    const isOpen = menu.classList.toggle('open');
    menu.querySelector('.profile-trigger').setAttribute('aria-expanded', isOpen);
}

// Close when clicking outside
document.addEventListener('click', function(e) {
    const menu = document.getElementById('profileMenu');
    if (menu && !menu.contains(e.target)) {
        menu.classList.remove('open');
        menu.querySelector('.profile-trigger').setAttribute('aria-expanded', 'false');
    }
});

// Close on Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const menu = document.getElementById('profileMenu');
        if (menu) {
            menu.classList.remove('open');
            menu.querySelector('.profile-trigger').setAttribute('aria-expanded', 'false');
        }
    }
});
</script>
</body>
</html>