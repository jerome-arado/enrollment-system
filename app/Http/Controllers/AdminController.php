<?php

namespace App\Http\Controllers;

use App\Http\Requests\StatusUpdateRequest;
use App\Mail\EnrollmentStatusMail;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class AdminController extends Controller
{


    public function dashboard(Request $request)
    {
        $query = Enrollment::with('user')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('course')) {
            $query->where('course', $request->course);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('course', 'like', "%{$search}%")
                  ->orWhereHas('user', fn($u) => $u->where('email', 'like', "%{$search}%"));
            });
        }

        $enrollments = $query->paginate(15)->withQueryString();

        $stats = [
            'total'       => Enrollment::count(),
            'pending'     => Enrollment::where('status', 'pending')->count(),
            'enrolled'    => Enrollment::where('status', 'enrolled')->count(),
            'disapproved' => Enrollment::where('status', 'disapproved')->count(),
        ];

        return view('admin.dashboard', compact('enrollments', 'stats'));
    }

    public function show(Enrollment $enrollment)
    {
        $enrollment->load('user');
        return view('admin.enrollment-detail', compact('enrollment'));
    }

    public function updateStatus(StatusUpdateRequest $request, Enrollment $enrollment)
    {
        $oldStatus = $enrollment->status;
        $enrollment->update($request->validated());

        // Send email notification only if status actually changed
        if ($oldStatus !== $enrollment->status) {
            try {
                Mail::to($enrollment->user->email)
                    ->send(new EnrollmentStatusMail($enrollment));
            } catch (\Exception $e) {
                // Silently fail on mail errors; log driver will catch it
                logger()->warning('Mail failed: ' . $e->getMessage());
            }
        }

        return redirect()->back()
            ->with('success', "Enrollment status updated to \"{$enrollment->status_label}\" successfully.");
    }

    public function destroy(Enrollment $enrollment)
    {
        if ($enrollment->profile_picture) {
            \Storage::disk('public')->delete($enrollment->profile_picture);
        }

        $enrollment->delete();

        return redirect()->route('admin.dashboard')
            ->with('success', 'Enrollment record deleted.');
    }

    public function students(Request $request)
    {
        $students = User::where('role', 'student')
            ->with('enrollment')
            ->latest()
            ->paginate(20);

        return view('admin.students', compact('students'));
    }
}