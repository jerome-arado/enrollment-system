<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
  body { font-family: Georgia, serif; background: #f9f4ee; color: #2a2018; margin: 0; padding: 2rem; }
  .container { max-width: 560px; margin: 0 auto; background: #fefcf8; border-radius: 12px; padding: 2.5rem; border: 1px solid rgba(196,180,164,0.4); }
  h1 { color: #3d2f22; font-size: 1.5rem; font-weight: normal; margin-bottom: 0.5rem; }
  .tag { display: inline-block; background: #eef5ef; color: #4a7a5c; padding: 0.25rem 0.75rem; border-radius: 99px; font-size: 0.8rem; font-weight: 700; letter-spacing: 0.05em; margin-bottom: 1.5rem; }
  p { line-height: 1.7; color: #7a6a5a; }
  .btn { display: inline-block; background: #c47a4a; color: #fff; padding: 0.7rem 1.5rem; border-radius: 6px; text-decoration: none; font-weight: 700; margin-top: 1.25rem; }
  .footer { margin-top: 2rem; padding-top: 1rem; border-top: 1px solid #f5e6d8; font-size: 0.8rem; color: #c4b4a4; }
</style>
</head>
<body>
<div class="container">
  <div class="tag">Welcome</div>
  <h1>Hello, {{ $user->name }}!</h1>
  <p>Your account has been created successfully. You can now log in and fill out your enrollment form to begin your application.</p>
  <a href="{{ url('/student/dashboard') }}" class="btn">Go to Dashboard</a>
  <div class="footer">Enrollment System &mdash; Academic Year 2024&ndash;2025</div>
</div>
</body>
</html>