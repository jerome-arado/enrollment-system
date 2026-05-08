<?php

namespace App\Http\Controllers;

use App\Http\Requests\DocumentUploadRequest;
use App\Models\Enrollment;
use App\Models\EnrollmentDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    // ── Student: show their document upload page ───────────────
    public function index()
    {
        $enrollment = Auth::user()->enrollment;

        if (!$enrollment) {
            return redirect()->route('student.dashboard')
                ->with('info', 'Please submit your enrollment form first before uploading documents.');
        }

        $documents = $enrollment->documents;

        // Predefined required document labels
        $requiredDocs = [
            'Form 137 / SF9'        => 'Official school record from previous school.',
            'Birth Certificate (PSA)' => 'PSA-authenticated birth certificate.',
            'Good Moral Certificate' => 'Signed by your previous school principal.',
            'Medical Certificate'    => 'From a licensed physician, within 6 months.',
            'ID Picture (2x2)'       => 'White background, recent photo.',
        ];

        return view('student.documents', compact('enrollment', 'documents', 'requiredDocs'));
    }

    // ── Student: upload a document ─────────────────────────────
    public function store(DocumentUploadRequest $request)
    {
        $enrollment = Auth::user()->enrollment;

        if (!$enrollment) {
            return redirect()->route('student.dashboard')
                ->with('error', 'No enrollment found.');
        }

        $file = $request->file('document');

        $path = $file->store(
            'documents/' . $enrollment->id,
            'public'
        );

        EnrollmentDocument::create([
            'enrollment_id' => $enrollment->id,
            'label'         => trim($request->label),
            'original_name' => $file->getClientOriginalName(),
            'path'          => $path,
            'mime_type'     => $file->getMimeType(),
            'size'          => $file->getSize(),
            'status'        => 'pending',
        ]);

        return redirect()->route('student.documents')
            ->with('success', '"' . $request->label . '" uploaded successfully.');
    }

    // ── Student: delete their own document ────────────────────
    public function destroy(EnrollmentDocument $document)
    {
        // Ensure document belongs to this student
        if ($document->enrollment->user_id !== Auth::id()) {
            abort(403);
        }

        // Don't allow deleting already-approved docs
        if ($document->status === 'approved') {
            return redirect()->route('student.documents')
                ->with('error', 'Approved documents cannot be removed.');
        }

        Storage::disk('public')->delete($document->path);
        $document->delete();

        return redirect()->route('student.documents')
            ->with('success', 'Document removed.');
    }

    // ── Shared: download / view a document ────────────────────
    public function download(EnrollmentDocument $document)
    {
        $user = Auth::user();

        // Students can only access their own docs
        if ($user->isStudent() && $document->enrollment->user_id !== $user->id) {
            abort(403);
        }

        if (!Storage::disk('public')->exists($document->path)) {
            abort(404, 'File not found.');
        }

        $fullPath = Storage::disk('public')->path($document->path);

        return response()->download(
            $fullPath,
            $document->original_name,
            ['Content-Type' => $document->mime_type]
        );
    }

    // ── Admin: update document status ─────────────────────────
    public function updateStatus(Request $request, EnrollmentDocument $document)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $request->validate([
            'status'  => ['required', 'in:pending,approved,rejected'],
            'remarks' => ['nullable', 'string', 'max:300'],
        ]);

        $document->update([
            'status'  => $request->status,
            'remarks' => $request->remarks,
        ]);

        return redirect()->back()
            ->with('success', 'Document status updated to "' . ucfirst($request->status) . '".');
    }
}