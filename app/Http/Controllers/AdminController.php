<?php

namespace App\Http\Controllers;

use App\Http\Requests\StatusUpdateRequest;
use App\Mail\EnrollmentStatusMail;
use App\Models\Enrollment;
use App\Models\User;
use App\Models\EnrollmentDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use App\Mail\AccountDeletedMail;


class AdminController extends Controller
{


    public function dashboard(Request $request)
    {
        $query = Enrollment::with('user')->latest();

        // Default to pending status if no status filter is present
        if (!$request->has('status')) {
            $query->where('status', 'pending');
        }

        // Apply status filter if provided (overrides default)
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
        $enrollment->load(['user', 'documents']);
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
        $query = User::where('role', 'student')
            ->with('enrollment')
            ->latest();

        // Search by name or email
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by course (if enrollment exists and course matches)
        if ($course = $request->get('course')) {
            $query->whereHas('enrollment', function ($q) use ($course) {
                $q->where('course', $course);
            });
        }

        // Filter by enrollment status
        if ($status = $request->get('status')) {
            if ($status === 'no_application') {
                $query->doesntHave('enrollment');
            } else {
                $query->whereHas('enrollment', function ($q) use ($status) {
                    $q->where('status', $status);
                });
            }
        }

        $students = $query->paginate(20)->withQueryString();

        return view('admin.students', compact('students'));
    }

    public function showStudent(User $user)
    {
        // Only allow viewing student accounts
        if ($user->role !== 'student') {
            abort(404);
        }
        $user->load('enrollment.documents');
        return view('admin.students.show', compact('user'));
    }

    /**
     * Delete a student account and all associated data
     */
    public function destroyStudent(User $user)
    {
        if ($user->role !== 'student') {
            abort(403, 'Only student accounts can be deleted.');
        }

        // Send deletion notification before deleting account
        try {
            Mail::to($user->email)->send(new AccountDeletedMail($user->name, null));
        } catch (\Exception $e) {
            // Log error but still delete the account
            logger()->error('Failed to send account deletion email: ' . $e->getMessage());
        }

        // Delete associated enrollment and its documents
        if ($enrollment = $user->enrollment) {
            // Delete profile picture
            if ($enrollment->profile_picture && Storage::disk('public')->exists($enrollment->profile_picture)) {
                Storage::disk('public')->delete($enrollment->profile_picture);
            }
            // Delete enrollment documents
            foreach ($enrollment->documents as $doc) {
                if (Storage::disk('public')->exists($doc->path)) {
                    Storage::disk('public')->delete($doc->path);
                }
                $doc->delete();
            }
            $enrollment->delete();
        }

        // Delete user's profile picture (if admin uses separate field)
        if ($user->profile_picture && Storage::disk('public')->exists($user->profile_picture)) {
            Storage::disk('public')->delete($user->profile_picture);
        }

        $user->delete();

        return redirect()->route('admin.students')
            ->with('success', "Student account for {$user->name} has been deleted. A notification email has been sent.");
    }
}