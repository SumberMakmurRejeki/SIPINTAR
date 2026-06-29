@extends('layouts.admin', ['title' => 'Karyawan'])

@section('content')
    <div class="space-y-6">
        <x-ui.page-header
            title="Karyawan"
            subtitle="Kelola data dan akun login karyawan."
        >
            <x-slot:actions>
                <x-ui.button href="{{ route('admin.master.karyawan.create') }}" variant="primary">
                    <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Tambah Karyawan
                </x-ui.button>
            </x-slot:actions>
        </x-ui.page-header>

        <x-ui.card>
            @php
                $hasFilters = request()->filled('search') || request()->filled('department_id') || request()->filled('position_id') || request()->filled('status');
            @endphp

            <form method="GET" action="{{ route('admin.master.karyawan.index') }}" class="mb-4 flex flex-col gap-3 lg:flex-row">
                <div class="flex-1">
                    <x-ui.input name="search" placeholder="Cari nama/username/kode" value="{{ request('search') }}" />
                </div>

                <div class="w-full lg:w-56">
                    <x-ui.select name="department_id" placeholder="Semua departemen">
                        @foreach($departments as $department)
                            <option value="{{ $department->id }}" @selected((string) request('department_id') === (string) $department->id)>
                                {{ $department->name }}
                            </option>
                        @endforeach
                    </x-ui.select>
                </div>

                <div class="w-full lg:w-56">
                    <x-ui.select name="position_id" placeholder="Semua jabatan">
                        @foreach($positions as $position)
                            <option value="{{ $position->id }}" @selected((string) request('position_id') === (string) $position->id)>
                                {{ $position->name }}
                            </option>
                        @endforeach
                    </x-ui.select>
                </div>

                <div class="w-full lg:w-44">
                    <x-ui.select name="status" placeholder="Semua status">
                        <option value="active" @selected(request('status') === 'active')>Aktif</option>
                        <option value="inactive" @selected(request('status') === 'inactive')>Nonaktif</option>
                    </x-ui.select>
                </div>

                <div class="flex gap-2">
                    <x-ui.button type="submit" variant="primary">Cari</x-ui.button>
                    @if($hasFilters)
                        <x-ui.button href="{{ route('admin.master.karyawan.index') }}" variant="ghost">Reset</x-ui.button>
                    @endif
                </div>
            </form>

            @if($employees->isEmpty())
                <x-ui.empty-state
                    title="Belum ada karyawan"
                    description="Tambahkan karyawan baru untuk memulai."
                    actionText="Tambah Karyawan"
                    actionUrl="{{ route('admin.master.karyawan.create') }}"
                />
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-[#D8D8D8]">
                                <th class="px-3 py-3 text-left font-medium text-[#5A5A5A]">Nama Karyawan</th>
                                <th class="px-3 py-3 text-left font-medium text-[#5A5A5A]">Username</th>
                                <th class="px-3 py-3 text-left font-medium text-[#5A5A5A]">Departemen</th>
                                <th class="px-3 py-3 text-left font-medium text-[#5A5A5A]">Jabatan</th>
                                <th class="px-3 py-3 text-left font-medium text-[#5A5A5A]">Status</th>
                                <th class="px-3 py-3 text-right font-medium text-[#5A5A5A]">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($employees as $employee)
                                @php
                                    $isActive = $employee->status === 'active';
                                @endphp

                                <tr class="border-b border-[#D8D8D8] hover:bg-gray-50">
                                    <td class="px-3 py-3">
                                        <div class="font-medium text-[#080808]">{{ $employee->name }}</div>
                                        <div class="mt-0.5 text-xs text-[#898989]">{{ $employee->employee_code ?: '-' }}</div>
                                    </td>
                                    <td class="px-3 py-3 text-[#080808]">{{ $employee->user?->username ?? '-' }}</td>
                                    <td class="px-3 py-3 text-[#080808]">{{ $employee->department?->name ?? '-' }}</td>
                                    <td class="px-3 py-3 text-[#080808]">{{ $employee->position?->name ?? '-' }}</td>
                                    <td class="px-3 py-3">
                                        <x-ui.badge :variant="$isActive ? 'success' : 'muted'">
                                            {{ $isActive ? 'Aktif' : 'Nonaktif' }}
                                        </x-ui.badge>
                                    </td>
                                    <td class="px-3 py-3 text-right">
                                        <div class="flex flex-wrap justify-end gap-1">
                                            <x-ui.button href="{{ route('admin.master.karyawan.show', $employee) }}" variant="ghost" size="sm">Detail</x-ui.button>
                                            <x-ui.button href="{{ route('admin.master.karyawan.edit', $employee) }}" variant="ghost" size="sm">Edit</x-ui.button>

                                            <form action="{{ route('admin.master.karyawan.toggle-status', $employee) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <x-ui.button type="submit" variant="outline" size="sm">
                                                    {{ $isActive ? 'Nonaktifkan' : 'Aktifkan' }}
                                                </x-ui.button>
                                            </form>

                                            <div x-data="{ open: false }" class="inline">
                                                <x-ui.button type="button" variant="danger" size="sm" @click="open = true">Hapus</x-ui.button>

                                                <div x-show="open" x-cloak @keydown.escape.window="open = false" class="fixed inset-0 z-50 flex items-center justify-center p-4">
                                                    <div x-show="open" x-transition:enter="transition-opacity ease-linear duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="open = false" class="fixed inset-0 bg-black/50"></div>
                                                    <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="relative w-full max-w-lg rounded-lg border border-[#D8D8D8] bg-white p-6 shadow-lg">
                                                        <h3 class="text-base font-semibold text-[#080808]">Hapus Permanen Data?</h3>
                                                        <p class="mt-1 text-sm text-[#5A5A5A]">Data karyawan yang dihapus permanen tidak dapat dikembalikan.</p>
                                                        <div class="mt-6 flex items-center justify-end gap-3">
                                                            <x-ui.button type="button" variant="secondary" @click="open = false">Batal</x-ui.button>
                                                            <form action="{{ route('admin.master.karyawan.destroy', $employee) }}" method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <x-ui.button type="submit" variant="danger">Hapus Permanen</x-ui.button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $employees->links() }}
                </div>
            @endif
        </x-ui.card>
    </div>
@endsection
