<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
  body { font-family: Georgia, serif; background: #f9f4ee; color: #2a2018; margin: 0; padding: 2rem; }
  .container { max-width: 560px; margin: 0 auto; background: #fefcf8; border-radius: 12px; padding: 2.5rem; border: 1px solid rgba(196,180,164,0.4); }
  h1 { color: #3d2f22; font-size: 1.5rem; font-weight: normal; margin-bottom: 0.5rem; }
  .tag { display: inline-block; background: #fdf4e7; color: #b87d30; padding: 0.25rem 0.75rem; border-radius: 99px; font-size: 0.8rem; font-weight: 700; letter-spacing: 0.05em; margin-bottom: 1.5rem; }
  p { line-height: 1.7; color: #7a6a5a; }
  .btn { display: inline-block; background: #c47a4a; color: #fff; padding: 0.8rem 2rem; border-radius: 6px; text-decoration: none; font-weight: 700; margin: 1.5rem 0; font-size: 1rem; }
  .url-box { background: #f5e6d8; border-radius: 6px; padding: 0.75rem 1rem; font-size: 0.78rem; color: #7a6a5a; word-break: break-all; margin-top: 1rem; border: 1px solid rgba(196,122,74,0.2); }
  .expire-note { font-size: 0.8rem; color: #c4b4a4; margin-top: 0.75rem; }
  .footer { margin-top: 2rem; padding-top: 1rem; border-top: 1px solid #f5e6d8; font-size: 0.8rem; color: #c4b4a4; }
</style>
</head>
<body>
<div class="container">
  <div class="tag">🔑 Password Reset</div>
  <h1>Hello, {{ $userName }}!</h1>
  <p>We received a request to reset the password for your account. Click the button below to set a new password:</p>

  <a href="{{ $resetUrl }}" class="btn">Reset My Password</a>

  <p class="expire-note">⏱ This link will expire in <strong>60 minutes</strong>.</p>

  <p style="margin-top:1.25rem;">If you didn't request a password reset, you can safely ignore this email — your password won't change.</p>

  <div class="url-box">
    If the button doesn't work, copy and paste this URL into your browser:<br>
    {{ $resetUrl }}
  </div>

  <div class="footer">Enrollment System &mdash; Academic Year 2024&ndash;2025</div>
</div>
</body>
</html>