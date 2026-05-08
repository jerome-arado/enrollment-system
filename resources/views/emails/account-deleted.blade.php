<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
  body { font-family: Georgia, serif; background: #f9f4ee; color: #2a2018; margin: 0; padding: 2rem; }
  .container { max-width: 560px; margin: 0 auto; background: #fefcf8; border-radius: 12px; padding: 2.5rem; border: 1px solid rgba(196,180,164,0.4); }
  h1 { color: #3d2f22; font-size: 1.3rem; font-weight: normal; }
  .message { line-height: 1.7; color: #7a6a5a; }
  .footer { margin-top: 2rem; padding-top: 1rem; border-top: 1px solid #f5e6d8; font-size: 0.8rem; color: #c4b4a4; }
</style>
</head>
<body>
<div class="container">
  <h1>Account Deletion Notice</h1>
  <p class="message">Dear {{ $studentName }},</p>
  <p class="message">We inform you that your account in the Enrollment System has been deleted by an administrator.</p>
  @if($reason)
    <p class="message"><strong>Reason given:</strong> {{ $reason }}</p>
  @endif
  <p class="message">If you believe this was a mistake, please contact the school administration.</p>
  <p class="message">Thank you for your interest in our institution.</p>
  <div class="footer">Enrollment System — Academic Year 2025–2026</div>
</div>
</body>
</html>