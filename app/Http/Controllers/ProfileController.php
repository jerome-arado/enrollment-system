<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Requests\UpdateProfilePictureRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    // ── Show edit form ────────────────────────────────────────
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    // ── Update name & email ───────────────────────────────────
    public function update(UpdateProfileRequest $request)
    {
        $user = Auth::user();

        $user->update([
            'name'  => $request->name,
            'email' => $request->email,
        ]);

        return redirect()->route('profile.edit')
            ->with('success', 'Profile updated successfully.');
    }

    // ── Update profile picture ────────────────────────────────
    public function updatePicture(UpdateProfilePictureRequest $request)
    {
        $user       = Auth::user();
        $enrollment = $user->enrollment;

        if (!$enrollment) {
            return redirect()->route('profile.edit')
                ->with('error', 'You need to submit an enrollment form before uploading a profile picture.');
        }

        // Delete old picture if it exists
        if ($enrollment->profile_picture) {
            Storage::disk('public')->delete($enrollment->profile_picture);
        }

        $path = $request->file('profile_picture')->store('profiles', 'public');

        $enrollment->update(['profile_picture' => $path]);

        return redirect()->route('profile.edit')
            ->with('success', 'Profile picture updated successfully.');
    }

    // ── Remove profile picture ────────────────────────────────
    public function removePicture()
    {
        $user       = Auth::user();
        $enrollment = $user->enrollment;

        if ($enrollment && $enrollment->profile_picture) {
            Storage::disk('public')->delete($enrollment->profile_picture);
            $enrollment->update(['profile_picture' => null]);
        }

        return redirect()->route('profile.edit')
            ->with('success', 'Profile picture removed.');
    }

    // ── Change password form ──────────────────────────────────
    public function changePasswordForm()
    {
        return view('profile.change-password');
    }

    // ── Change password ───────────────────────────────────────
    public function changePassword(ChangePasswordRequest $request)
    {
        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors([
                'current_password' => 'Your current password is incorrect.',
            ])->withInput();
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('profile.edit')
            ->with('success', 'Password changed successfully.');
    }
}