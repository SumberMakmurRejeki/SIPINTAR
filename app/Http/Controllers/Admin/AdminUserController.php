<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'admin');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%'.$search.'%')
                    ->orWhere('username', 'like', '%'.$search.'%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $adminUsers = $query->latest()->paginate(10)->withQueryString();

        return view('pages::admin.master.admin-user.index', compact('adminUsers'));
    }

    public function create()
    {
        return view('pages::admin.master.admin-user.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'username' => ['required', 'string', 'max:100', 'unique:users,username'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        User::create([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'password' => $validated['password'],
            'role' => 'admin',
            'status' => $validated['status'],
        ]);

        return redirect()->route('admin.master.admin-user.index')->with('success', 'Admin berhasil ditambahkan.');
    }

    public function show(User $adminUser)
    {
        abort_if($adminUser->role !== 'admin', 404);

        return view('pages::admin.master.admin-user.show', compact('adminUser'));
    }

    public function edit(User $adminUser)
    {
        abort_if($adminUser->role !== 'admin', 404);

        return view('pages::admin.master.admin-user.edit', compact('adminUser'));
    }

    public function update(Request $request, User $adminUser)
    {
        abort_if($adminUser->role !== 'admin', 404);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'username' => ['required', 'string', 'max:100', Rule::unique('users', 'username')->ignore($adminUser->id)],
            'password' => ['nullable', 'string', 'min:6', 'confirmed'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        $data = [
            'name' => $validated['name'],
            'username' => $validated['username'],
            'status' => $validated['status'],
        ];

        if ($validated['password']) {
            $data['password'] = $validated['password'];
        }

        $adminUser->update($data);

        return redirect()->route('admin.master.admin-user.index')->with('success', 'Admin berhasil diperbarui.');
    }

    public function destroy(User $adminUser)
    {
        abort_if($adminUser->role !== 'admin', 404);

        if ($adminUser->id === auth()->id()) {
            return redirect()->back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $activeAdmins = User::where('role', 'admin')->where('status', 'active')->count();
        if ($activeAdmins <= 1 && $adminUser->status === 'active') {
            return redirect()->back()->with('error', 'Tidak dapat menghapus admin terakhir yang masih aktif.');
        }

        $adminUser->delete();

        return redirect()->route('admin.master.admin-user.index')->with('success', 'Admin berhasil dihapus permanen.');
    }

    public function toggleStatus(User $adminUser)
    {
        abort_if($adminUser->role !== 'admin', 404);

        if ($adminUser->id === auth()->id()) {
            return redirect()->back()->with('error', 'Anda tidak dapat menonaktifkan akun Anda sendiri.');
        }

        $newStatus = $adminUser->status === 'active' ? 'inactive' : 'active';

        if ($newStatus === 'inactive') {
            $activeAdmins = User::where('role', 'admin')->where('status', 'active')->count();
            if ($activeAdmins <= 1) {
                return redirect()->back()->with('error', 'Tidak dapat menonaktifkan admin terakhir yang masih aktif.');
            }
        }

        $adminUser->update(['status' => $newStatus]);

        $action = $newStatus === 'active' ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->back()->with('success', "Admin berhasil {$action}.");
    }
}
