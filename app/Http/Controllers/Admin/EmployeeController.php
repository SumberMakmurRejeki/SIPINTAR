<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Position;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $query = Employee::with(['department', 'position', 'user'])->whereHas('user', function ($q) {
            $q->where('role', 'employee');
        });

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%'.$search.'%')
                    ->orWhereHas('user', function ($u) use ($search) {
                        $u->where('username', 'like', '%'.$search.'%');
                    })
                    ->orWhere('employee_code', 'like', '%'.$search.'%');
            });
        }

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        if ($request->filled('position_id')) {
            $query->where('position_id', $request->position_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $employees = $query->latest()->paginate(10)->withQueryString();
        $departments = Department::where('status', 'active')->orderBy('name')->get();
        $positions = Position::where('status', 'active')->orderBy('name')->get();

        return view('pages::admin.master.karyawan.index', compact('employees', 'departments', 'positions'));
    }

    public function create()
    {
        return view('pages::admin.master.karyawan.create', [
            'departments' => Department::where('status', 'active')->orderBy('name')->get(),
            'positions' => Position::where('status', 'active')->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'username' => ['required', 'string', 'max:100', 'unique:users,username'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'department_id' => ['required', 'exists:departments,id'],
            'position_id' => ['required', 'exists:positions,id'],
            'employee_code' => ['nullable', 'string', 'max:50', 'unique:employees,employee_code'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        DB::transaction(function () use ($validated) {
            $user = User::create([
                'name' => $validated['name'],
                'username' => $validated['username'],
                'password' => $validated['password'],
                'role' => 'employee',
                'status' => $validated['status'],
            ]);

            Employee::create([
                'user_id' => $user->id,
                'department_id' => $validated['department_id'],
                'position_id' => $validated['position_id'],
                'employee_code' => $validated['employee_code'] ?? null,
                'name' => $validated['name'],
                'status' => $validated['status'],
            ]);
        });

        return redirect()->route('admin.master.karyawan.index')->with('success', 'Karyawan berhasil ditambahkan.');
    }

    public function show(Employee $karyawan)
    {
        $karyawan->load(['user', 'department', 'position']);

        return view('pages::admin.master.karyawan.show', compact('karyawan'));
    }

    public function edit(Employee $karyawan)
    {
        $karyawan->load('user');

        return view('pages::admin.master.karyawan.edit', [
            'karyawan' => $karyawan,
            'departments' => Department::where('status', 'active')->orderBy('name')->get(),
            'positions' => Position::where('status', 'active')->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Employee $karyawan)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'username' => ['required', 'string', 'max:100', Rule::unique('users', 'username')->ignore($karyawan->user_id)],
            'password' => ['nullable', 'string', 'min:6', 'confirmed'],
            'department_id' => ['required', 'exists:departments,id'],
            'position_id' => ['required', 'exists:positions,id'],
            'employee_code' => ['nullable', 'string', 'max:50', Rule::unique('employees', 'employee_code')->ignore($karyawan->id)],
            'status' => ['required', 'in:active,inactive'],
        ]);

        DB::transaction(function () use ($validated, $karyawan) {
            $userData = [
                'name' => $validated['name'],
                'username' => $validated['username'],
                'status' => $validated['status'],
            ];

            if ($validated['password']) {
                $userData['password'] = $validated['password'];
            }

            $karyawan->user->update($userData);

            $karyawan->update([
                'department_id' => $validated['department_id'],
                'position_id' => $validated['position_id'],
                'employee_code' => $validated['employee_code'] ?? null,
                'name' => $validated['name'],
                'status' => $validated['status'],
            ]);
        });

        return redirect()->route('admin.master.karyawan.index')->with('success', 'Karyawan berhasil diperbarui.');
    }

    public function destroy(Employee $karyawan)
    {
        if ($karyawan->trainingParticipants()->exists() || $karyawan->testAttempts()->exists()) {
            return redirect()->back()->with('error', 'Karyawan terhubung dengan data training/progress. Nonaktifkan saja untuk menjaga histori.');
        }

        DB::transaction(function () use ($karyawan) {
            $karyawan->user->forceDelete();
            $karyawan->forceDelete();
        });

        return redirect()->route('admin.master.karyawan.index')->with('success', 'Karyawan berhasil dihapus permanen.');
    }

    public function toggleStatus(Employee $karyawan)
    {
        $newStatus = $karyawan->status === 'active' ? 'inactive' : 'active';

        DB::transaction(function () use ($karyawan, $newStatus) {
            $karyawan->update(['status' => $newStatus]);
            $karyawan->user->update(['status' => $newStatus]);
        });

        $action = $newStatus === 'active' ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->back()->with('success', "Karyawan berhasil {$action}.");
    }
}
