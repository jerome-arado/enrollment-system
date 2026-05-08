<?php

namespace App\Http\Controllers;

use App\Http\Requests\EnrollmentRequest;
use App\Models\Enrollment;
use App\Models\EnrollmentDocument;
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

        // Profile picture (optional)
        if ($request->hasFile('profile_picture')) {
            $data['profile_picture'] = $request->file('profile_picture')
                ->store('profiles', 'public');
        }

        $enrollment = Enrollment::create($data);

        // Required documents
        $docMapping = [
            'form137'    => 'Form 137 / SF9',
            'birth_cert' => 'Birth Certificate (PSA)',
            'good_moral' => 'Good Moral Certificate',
            'medical'    => 'Medical Certificate',
            'id_picture' => '2x2 ID Picture',
        ];

        foreach ($docMapping as $field => $label) {
            if ($request->hasFile($field)) {
                $file = $request->file($field);
                $path = $file->store('documents/' . $enrollment->id, 'public');
                EnrollmentDocument::create([
                    'enrollment_id' => $enrollment->id,
                    'label'         => $label,
                    'original_name' => $file->getClientOriginalName(),
                    'path'          => $path,
                    'mime_type'     => $file->getMimeType(),
                    'size'          => $file->getSize(),
                    'status'        => 'pending',
                ]);
            }
        }

        return redirect()->route('student.dashboard')
            ->with('success', 'Enrollment form submitted successfully! Your application is under review.');
    }

    public function edit()
    {
        $enrollment = Auth::user()->enrollment;

        if (!$enrollment) {
            return redirect()->route('student.enroll');
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
            return redirect()->route('student.enroll');
        }

        if ($enrollment->isEnrolled()) {
            return redirect()->route('student.dashboard')
                ->with('info', 'Approved enrollments cannot be modified.');
        }

        $data = $request->validated();

        // Profile picture update
        if ($request->hasFile('profile_picture')) {
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

        // Update required documents: replace if new file uploaded
        $docMapping = [
            'form137'    => 'Form 137 / SF9',
            'birth_cert' => 'Birth Certificate (PSA)',
            'good_moral' => 'Good Moral Certificate',
            'medical'    => 'Medical Certificate',
            'id_picture' => '2x2 ID Picture',
        ];

        foreach ($docMapping as $field => $label) {
            if ($request->hasFile($field)) {
                // Delete old document file if exists
                $oldDoc = $enrollment->documents()->where('label', $label)->first();
                if ($oldDoc) {
                    Storage::disk('public')->delete($oldDoc->path);
                    $oldDoc->delete();
                }
                // Save new document
                $file = $request->file($field);
                $path = $file->store('documents/' . $enrollment->id, 'public');
                EnrollmentDocument::create([
                    'enrollment_id' => $enrollment->id,
                    'label'         => $label,
                    'original_name' => $file->getClientOriginalName(),
                    'path'          => $path,
                    'mime_type'     => $file->getMimeType(),
                    'size'          => $file->getSize(),
                    'status'        => 'pending',
                ]);
            }
        }

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

        // Delete profile picture
        if ($enrollment->profile_picture) {
            Storage::disk('public')->delete($enrollment->profile_picture);
        }

        // Delete all documents and their files
        foreach ($enrollment->documents as $doc) {
            Storage::disk('public')->delete($doc->path);
            $doc->delete();
        }

        $enrollment->delete();

        return redirect()->route('student.dashboard')
            ->with('success', 'Enrollment application withdrawn successfully.');
    }
}