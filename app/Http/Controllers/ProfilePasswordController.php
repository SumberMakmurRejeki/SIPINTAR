<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProfilePasswordController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        $role = $user->isAdmin() ? 'admin' : 'employee';

        // ponytail: eager load employee only if exists, avoids N+1
        $user->load(['employee.department', 'employee.position']);

        return view("pages::{$role}.profil-password", compact('user'));
    }

    public function updateProfile(UpdateProfileRequest $request): RedirectResponse
    {
        $user = auth()->user();
        $user->update($request->validated());

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function updatePassword(UpdatePasswordRequest $request): RedirectResponse
    {
        $user = auth()->user();
        $user->update(['password' => $request->validated('password')]);

        return back()->with('success', 'Password berhasil diubah.');
    }
}
