<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Enrollment System')</title>
    <style>
        :root {
            --clay: #c47a4a; --clay-light: #e8b48a; --clay-pale: #f5e6d8;
            --moss: #5a7a5c; --bark: #3d2f22; --sand: #f9f4ee;
            --stone: #7a6a5a; --stone-light: #c4b4a4; --cream: #fefcf8;
            --ink: #2a2018; --error: #b85450; --error-pale: #fdf0ef;
            --success: #4a7a5c; --success-pale: #eef5ef;
            --radius-sm: 6px; --radius: 12px; --radius-lg: 20px;
            --shadow: 0 4px 24px rgba(61,47,34,0.12);
            --font: 'Georgia', serif; --font-ui: system-ui, sans-serif;
            --transition: 0.18s ease;
        }
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: var(--font-ui);
            background: var(--sand);
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 2rem 1rem;
            color: var(--ink);
        }
        /* Decorative background */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background:
                radial-gradient(ellipse 60% 50% at 15% 20%, rgba(196,122,74,0.08) 0%, transparent 60%),
                radial-gradient(ellipse 50% 60% at 85% 80%, rgba(90,122,92,0.07) 0%, transparent 60%);
            pointer-events: none;
        }
        .auth-container {
            width: 100%;
            max-width: 460px;
            position: relative;
        }
        .auth-brand {
            text-align: center;
            margin-bottom: 2rem;
        }
        .auth-brand .logo {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
        }
        .auth-brand .logo-mark {
            width: 36px; height: 36px;
            background: var(--bark);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
        }
        .auth-brand .logo-mark::after {
            content: '';
            width: 14px; height: 14px;
            background: var(--clay-light);
            border-radius: 50%;
        }
        .auth-brand h1 {
            font-family: var(--font);
            font-size: 1.5rem;
            color: var(--bark);
            font-weight: normal;
        }
        .auth-brand p {
            color: var(--stone);
            font-size: 0.85rem;
            margin-top: 0.2rem;
        }
        .auth-card {
            background: var(--cream);
            border: 1px solid rgba(196,180,164,0.4);
            border-radius: var(--radius-lg);
            padding: 2.5rem;
            box-shadow: var(--shadow);
        }
        .auth-card h2 {
            font-family: var(--font);
            font-size: 1.4rem;
            font-weight: normal;
            color: var(--bark);
            margin-bottom: 1.75rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--clay-pale);
        }
        .form-group { margin-bottom: 1.3rem; }
        label {
            display: block;
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            color: var(--stone);
            margin-bottom: 0.4rem;
        }
        input[type="text"], input[type="email"], input[type="password"] {
            width: 100%;
            padding: 0.7rem 0.95rem;
            border: 1.5px solid var(--stone-light);
            border-radius: var(--radius-sm);
            background: #fff;
            font-size: 0.95rem;
            color: var(--ink);
            font-family: var(--font-ui);
            outline: none;
            transition: border-color var(--transition), box-shadow var(--transition);
        }
        input:focus {
            border-color: var(--clay);
            box-shadow: 0 0 0 3px rgba(196,122,74,0.12);
        }
        .has-error input { border-color: var(--error); }
        .field-error {
            font-size: 0.77rem; color: var(--error);
            margin-top: 0.3rem;
        }
        .btn-auth {
            width: 100%;
            padding: 0.78rem;
            background: var(--clay);
            color: #fff;
            border: none;
            border-radius: var(--radius-sm);
            font-size: 0.95rem;
            font-weight: 700;
            letter-spacing: 0.03em;
            cursor: pointer;
            font-family: var(--font-ui);
            margin-top: 0.5rem;
            transition: background var(--transition);
        }
        .btn-auth:hover { background: #a5663c; }
        .auth-footer {
            text-align: center;
            margin-top: 1.75rem;
            font-size: 0.85rem;
            color: var(--stone);
        }
        .auth-footer a {
            color: var(--clay);
            text-decoration: none;
            font-weight: 600;
        }
        .auth-footer a:hover { text-decoration: underline; }
        .flash {
            padding: 0.85rem 1.1rem;
            border-radius: var(--radius-sm);
            margin-bottom: 1.25rem;
            font-size: 0.88rem;
            border-left: 4px solid;
        }
        .flash-error    { background: var(--error-pale); border-color: var(--error); color: #7a2a28; }
        .flash-success  { background: var(--success-pale); border-color: var(--success); color: #2d5c3a; }
        .remember-row {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.85rem;
            color: var(--stone);
            margin-top: -0.3rem;
        }
        .remember-row input[type="checkbox"] {
            width: auto;
            accent-color: var(--clay);
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="auth-brand">
            <a href="{{ route('login') }}" class="logo">
                
                <h1>Enrollment System</h1>
            </a>
            <p>Academic Year 2026 – 2027</p>
        </div>
        @yield('content')
    </div>
</body>
</html>