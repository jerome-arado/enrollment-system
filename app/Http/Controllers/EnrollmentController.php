<?php

namespace App\Http\Controllers;

use App\Http\Requests\EnrollmentRequest;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class EnrollmentController extends Controller
{
    public function dashboard()
    {
        $enrollment = Auth::user()->enrollment;
        return view('student.dashboard', compact('enrollment'));
    }

    public function create()
    {
        if (Auth::user()->enrollment) {
            return redirect()->route('student.dashboard')
                ->with('info', 'You have already submitted an enrollment form.');
        }
        return view('student.enrollment-form');
    }

    public function store(EnrollmentRequest $request)
    {
        if (Auth::user()->enrollment) {
            return redirect()->route('student.dashboard')
                ->with('info', 'You have already submitted an enrollment form.');
        }

        $data = $request->validated();
        $data['user_id'] = Auth::id();
        $data['status']  = 'pending';

        if ($request->hasFile('profile_picture')) {
            $data['profile_picture'] = $request->file('profile_picture')
                ->store('profiles', 'public');
        }

        Enrollment::create($data);

        return redirect()->route('student.dashboard')
            ->with('success', 'Enrollment form submitted successfully! Your application is under review.');
    }

    public function edit()
    {
        $enrollment = Auth::user()->enrollment;

        if (!$enrollment) {
            return redirect()->route('enrollment.create');
        }

        if ($enrollment->isEnrolled()) {
            return redirect()->route('student.dashboard')
                ->with('info', 'Your enrollment has been approved and cannot be edited.');
        }

        return view('student.enrollment-edit', compact('enrollment'));
    }

    public function update(EnrollmentRequest $request)
    {
        $enrollment = Auth::user()->enrollment;

        if (!$enrollment) {
            return redirect()->route('enrollment.create');
        }

        if ($enrollment->isEnrolled()) {
            return redirect()->route('student.dashboard')
                ->with('info', 'Approved enrollments cannot be modified.');
        }

        $data = $request->validated();

        if ($request->hasFile('profile_picture')) {
            // Delete old picture
            if ($enrollment->profile_picture) {
                Storage::disk('public')->delete($enrollment->profile_picture);
            }
            $data['profile_picture'] = $request->file('profile_picture')
                ->store('profiles', 'public');
        }

        // Reset to pending if disapproved and re-submitting
        if ($enrollment->isDisapproved()) {
            $data['status']  = 'pending';
            $data['remarks'] = null;
        }

        $enrollment->update($data);

        return redirect()->route('student.dashboard')
            ->with('success', 'Enrollment updated successfully.');
    }

    public function destroy()
    {
        $enrollment = Auth::user()->enrollment;

        if (!$enrollment) {
            return redirect()->route('student.dashboard');
        }

        if ($enrollment->isEnrolled()) {
            return redirect()->route('student.dashboard')
                ->with('error', 'Approved enrollments cannot be withdrawn.');
        }

        if ($enrollment->profile_picture) {
            Storage::disk('public')->delete($enrollment->profile_picture);
        }

        $enrollment->delete();

        return redirect()->route('student.dashboard')
            ->with('success', 'Enrollment application withdrawn successfully.');
    }
}