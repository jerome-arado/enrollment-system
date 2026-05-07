<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
  body { font-family: Georgia, serif; background: #f9f4ee; color: #2a2018; margin: 0; padding: 2rem; }
  .container { max-width: 560px; margin: 0 auto; background: #fefcf8; border-radius: 12px; padding: 2.5rem; border: 1px solid rgba(196,180,164,0.4); }
  h1 { color: #3d2f22; font-size: 1.5rem; font-weight: normal; margin-bottom: 0.5rem; }
  .status-enrolled   { background: #eef5ef; color: #4a7a5c; }
  .status-disapproved { background: #fdf0ef; color: #b85450; }
  .status-pending    { background: #fdf4e7; color: #b87d30; }
  .tag { display: inline-block; padding: 0.25rem 0.75rem; border-radius: 99px; font-size: 0.8rem; font-weight: 700; letter-spacing: 0.05em; margin-bottom: 1.5rem; }
  p { line-height: 1.7; color: #7a6a5a; }
  .info-box { background: #f5e6d8; border-radius: 8px; padding: 1rem 1.25rem; margin: 1.25rem 0; }
  .info-box strong { color: #3d2f22; font-size: 0.85rem; display: block; margin-bottom: 0.2rem; }
  .btn { display: inline-block; background: #c47a4a; color: #fff; padding: 0.7rem 1.5rem; border-radius: 6px; text-decoration: none; font-weight: 700; margin-top: 1.25rem; }
  .footer { margin-top: 2rem; padding-top: 1rem; border-top: 1px solid #f5e6d8; font-size: 0.8rem; color: #c4b4a4; }
</style>
</head>
<body>
<div class="container">
  <div class="tag status-{{ $enrollment->status }}">
    @if($enrollment->isEnrolled()) ✅ Enrolled
    @elseif($enrollment->isDisapproved()) ❌ Disapproved
    @else ⏳ Status Update
    @endif
  </div>

  <h1>Dear {{ $enrollment->name }},</h1>

  <p>
    @if($enrollment->isEnrolled())
      Congratulations! Your enrollment application has been <strong>approved</strong>. You are now officially enrolled for the current academic year.
    @elseif($enrollment->isDisapproved())
      We regret to inform you that your enrollment application has been <strong>disapproved</strong>. You may log in to review the remarks and resubmit if needed.
    @else
      Your enrollment application status has been updated to <strong>{{ $enrollment->status_label }}</strong>.
    @endif
  </p>

  <div class="info-box">
    <strong>Application Summary</strong>
    Course: {{ $enrollment->course }} &mdash; {{ $enrollment->year }} Year
  </div>

  @if($enrollment->remarks)
    <div class="info-box" style="border-left: 3px solid #b87d30;">
      <strong>Admin Remarks</strong>
      {{ $enrollment->remarks }}
    </div>
  @endif

  <a href="{{ url('/student/dashboard') }}" class="btn">View My Enrollment</a>

  <div class="footer">Enrollment System &mdash; Academic Year 2024&ndash;2025</div>
</div>
</body>
</html>