<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Position;
use Illuminate\Http\Request;

class PositionController extends Controller
{
    public function index(Request $request)
    {
        $query = Position::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%'.$request->search.'%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $positions = $query->latest()->paginate(10)->withQueryString();

        return view('pages::admin.master.jabatan.index', compact('positions'));
    }

    public function create()
    {
        return view('pages::admin.master.jabatan.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150', 'unique:positions,name'],
            'description' => ['nullable', 'string', 'max:1000'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        Position::create($validated);

        return redirect()->route('admin.master.jabatan.index')->with('success', 'Jabatan berhasil ditambahkan.');
    }

    public function show(Position $jabatan)
    {
        $jabatan->loadCount('employees');

        return view('pages::admin.master.jabatan.show', compact('jabatan'));
    }

    public function edit(Position $jabatan)
    {
        return view('pages::admin.master.jabatan.edit', compact('jabatan'));
    }

    public function update(Request $request, Position $jabatan)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150', 'unique:positions,name,'.$jabatan->id],
            'description' => ['nullable', 'string', 'max:1000'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        $jabatan->update($validated);

        return redirect()->route('admin.master.jabatan.index')->with('success', 'Jabatan berhasil diperbarui.');
    }

    public function destroy(Position $jabatan)
    {
        if ($jabatan->employees()->count() > 0) {
            return redirect()->back()->with('error', 'Jabatan masih memiliki karyawan. Nonaktifkan saja.');
        }

        $jabatan->delete();

        return redirect()->route('admin.master.jabatan.index')->with('success', 'Jabatan berhasil dihapus permanen.');
    }

    public function toggleStatus(Position $jabatan)
    {
        $jabatan->update([
            'status' => $jabatan->status === 'active' ? 'inactive' : 'active',
        ]);

        $action = $jabatan->status === 'active' ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->back()->with('success', "Jabatan berhasil {$action}.");
    }
}
