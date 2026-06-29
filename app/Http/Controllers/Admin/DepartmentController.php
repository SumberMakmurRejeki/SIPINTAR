<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Department::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%'.$request->search.'%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $departments = $query->latest()->paginate(10)->withQueryString();

        return view('pages::admin.master.departemen.index', compact('departments'));
    }

    public function create()
    {
        return view('pages::admin.master.departemen.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150', 'unique:departments,name'],
            'description' => ['nullable', 'string', 'max:1000'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        Department::create($validated);

        return redirect()->route('admin.master.departemen.index')->with('success', 'Departemen berhasil ditambahkan.');
    }

    public function show(Department $departemen)
    {
        $departemen->loadCount('employees');

        return view('pages::admin.master.departemen.show', compact('departemen'));
    }

    public function edit(Department $departemen)
    {
        return view('pages::admin.master.departemen.edit', compact('departemen'));
    }

    public function update(Request $request, Department $departemen)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150', 'unique:departments,name,'.$departemen->id],
            'description' => ['nullable', 'string', 'max:1000'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        $departemen->update($validated);

        return redirect()->route('admin.master.departemen.index')->with('success', 'Departemen berhasil diperbarui.');
    }

    public function destroy(Department $departemen)
    {
        if ($departemen->employees()->count() > 0) {
            return redirect()->back()->with('error', 'Departemen masih memiliki karyawan. Nonaktifkan saja.');
        }

        $departemen->delete();

        return redirect()->route('admin.master.departemen.index')->with('success', 'Departemen berhasil dihapus permanen.');
    }

    public function toggleStatus(Department $departemen)
    {
        $departemen->update([
            'status' => $departemen->status === 'active' ? 'inactive' : 'active',
        ]);

        $action = $departemen->status === 'active' ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->back()->with('success', "Departemen berhasil {$action}.");
    }
}
