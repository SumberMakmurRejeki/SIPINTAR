@extends('layouts.admin', ['title' => 'Karyawan'])

@section('content')
    <div class="space-y-6">
        {{-- Header --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-[28px] font-bold text-[#0f172a] tracking-tight">Karyawan</h1>
                <p class="mt-1 text-[14px] text-[#64748b]">Kelola data dan akun login karyawan.</p>
            </div>
            <a href="{{ route('admin.master.karyawan.create') }}" class="inline-flex items-center gap-2 rounded-[10px] bg-blue-600 px-5 py-2.5 text-[14px] font-medium text-white shadow-sm transition-all hover:bg-blue-700 hover:shadow-md">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Tambah Karyawan
            </a>
        </div>

        {{-- Filter Card --}}
        @php
            $hasFilters = request()->filled('search') || request()->filled('department_id') || request()->filled('position_id') || request()->filled('status');
        @endphp

        <div class="rounded-[16px] border border-[#e2e8f0] bg-white p-5 shadow-sm">
            <form method="GET" action="{{ route('admin.master.karyawan.index') }}" class="flex flex-col gap-3 lg:flex-row lg:items-center">
                {{-- Search --}}
                <div class="flex-1 relative">
                    <svg class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-[#94a3b8]" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" /></svg>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama/username/kode" class="block w-full rounded-[10px] border border-[#e2e8f0] bg-white py-2.5 pl-10 pr-4 text-[14px] text-[#0f172a] placeholder:text-[#94a3b8] transition-colors focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                </div>

                {{-- Department --}}
                <div class="w-full lg:w-52">
                    <select name="department_id" class="block w-full rounded-[10px] border border-[#e2e8f0] bg-white px-4 py-2.5 text-[14px] text-[#0f172a] transition-colors focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                        <option value="">Semua departemen</option>
                        @foreach($departments as $department)
                            <option value="{{ $department->id }}" @selected((string) request('department_id') === (string) $department->id)>{{ $department->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Position --}}
                <div class="w-full lg:w-52">
                    <select name="position_id" class="block w-full rounded-[10px] border border-[#e2e8f0] bg-white px-4 py-2.5 text-[14px] text-[#0f172a] transition-colors focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                        <option value="">Semua jabatan</option>
                        @foreach($positions as $position)
                            <option value="{{ $position->id }}" @selected((string) request('position_id') === (string) $position->id)>{{ $position->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Status --}}
                <div class="w-full lg:w-44">
                    <select name="status" class="block w-full rounded-[10px] border border-[#e2e8f0] bg-white px-4 py-2.5 text-[14px] text-[#0f172a] transition-colors focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                        <option value="">Semua status</option>
                        <option value="active" @selected(request('status') === 'active')>Aktif</option>
                        <option value="inactive" @selected(request('status') === 'inactive')>Nonaktif</option>
                    </select>
                </div>

                {{-- Buttons --}}
                <div class="flex gap-2">
                    <button type="submit" class="inline-flex items-center gap-2 rounded-[10px] bg-blue-600 px-5 py-2.5 text-[14px] font-medium text-white transition-all hover:bg-blue-700">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" /></svg>
                        Cari
                    </button>
                    @if($hasFilters)
                        <a href="{{ route('admin.master.karyawan.index') }}" class="inline-flex items-center rounded-[10px] border border-[#e2e8f0] bg-white px-4 py-2.5 text-[14px] font-medium text-[#64748b] transition-colors hover:bg-gray-50">Reset</a>
                    @endif
                </div>
            </form>
        </div>

        {{-- Table Card --}}
        <div class="rounded-[16px] border border-[#e2e8f0] bg-white shadow-sm overflow-hidden">
            @if($employees->isEmpty())
                <div class="p-12 text-center">
                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-gray-50 mb-4">
                        <svg class="h-7 w-7 text-[#94a3b8]" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                        </svg>
                    </div>
                    <h3 class="text-[15px] font-semibold text-[#0f172a]">Belum ada data karyawan</h3>
                    <p class="mt-1 text-[13px] text-[#64748b]">Tambahkan karyawan untuk mulai mengelola akun login.</p>
                    <div class="mt-5">
                        <a href="{{ route('admin.master.karyawan.create') }}" class="inline-flex items-center gap-2 rounded-[10px] bg-blue-600 px-5 py-2.5 text-[14px] font-medium text-white transition-all hover:bg-blue-700">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                            Tambah Karyawan
                        </a>
                    </div>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-[14px]">
                        <thead>
                            <tr class="border-b border-[#e2e8f0] bg-[#f8fafc]">
                                <th class="px-5 py-3.5 text-left text-[13px] font-medium text-[#64748b]">Nama Karyawan</th>
                                <th class="px-5 py-3.5 text-left text-[13px] font-medium text-[#64748b]">Username</th>
                                <th class="px-5 py-3.5 text-left text-[13px] font-medium text-[#64748b]">Departemen</th>
                                <th class="px-5 py-3.5 text-left text-[13px] font-medium text-[#64748b]">Jabatan</th>
                                <th class="px-5 py-3.5 text-left text-[13px] font-medium text-[#64748b]">Status</th>
                                <th class="px-5 py-3.5 text-right text-[13px] font-medium text-[#64748b]">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($employees as $employee)
                                @php
                                    $isActive = $employee->status === 'active';
                                    $initials = strtoupper(substr($employee->name, 0, 1)) . ($employee->employee_code ? substr($employee->employee_code, -1) : '');
                                @endphp
                                <tr class="border-b border-[#e2e8f0] last:border-0 hover:bg-[#f8fafc] transition-colors">
                                    {{-- Name + Avatar --}}
                                    <td class="px-5 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-blue-50 text-[13px] font-semibold text-blue-600">
                                                {{ $initials }}
                                            </div>
                                            <div>
                                                <div class="font-medium text-[#0f172a]">{{ $employee->name }}</div>
                                                <div class="text-[12px] text-[#94a3b8]">{{ $employee->employee_code ?: '-' }}</div>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Username --}}
                                    <td class="px-5 py-4 text-[#0f172a]">{{ $employee->user?->username ?? '-' }}</td>

                                    {{-- Department --}}
                                    <td class="px-5 py-4 text-[#0f172a]">{{ $employee->department?->name ?? '-' }}</td>

                                    {{-- Position --}}
                                    <td class="px-5 py-4 text-[#0f172a]">{{ $employee->position?->name ?? '-' }}</td>

                                    {{-- Status --}}
                                    <td class="px-5 py-4">
                                        @if($isActive)
                                            <span class="inline-flex items-center gap-1.5 rounded-full bg-green-50 px-2.5 py-1 text-[12px] font-medium text-green-700 ring-1 ring-inset ring-green-600/20">
                                                <span class="h-1.5 w-1.5 rounded-full bg-green-500"></span>
                                                Aktif
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 rounded-full bg-gray-50 px-2.5 py-1 text-[12px] font-medium text-gray-600 ring-1 ring-inset ring-gray-500/10">
                                                <span class="h-1.5 w-1.5 rounded-full bg-gray-400"></span>
                                                Nonaktif
                                            </span>
                                        @endif
                                    </td>

                                    {{-- Actions --}}
                                    <td class="px-5 py-4">
                                        <div class="flex items-center justify-end gap-1">
                                            {{-- Detail --}}
                                            <a href="{{ route('admin.master.karyawan.show', $employee) }}" class="inline-flex items-center gap-1.5 rounded-lg px-3 py-1.5 text-[13px] font-medium text-blue-600 transition-colors hover:bg-blue-50">
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                                Detail
                                            </a>

                                            {{-- Edit --}}
                                            <a href="{{ route('admin.master.karyawan.edit', $employee) }}" class="inline-flex items-center gap-1.5 rounded-lg px-3 py-1.5 text-[13px] font-medium text-blue-600 transition-colors hover:bg-blue-50">
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" /></svg>
                                                Edit
                                            </a>

                                            {{-- Toggle Status --}}
                                            <div x-data="{ open: false }">
                                                <button type="button" @click="open = true" class="inline-flex items-center gap-1.5 rounded-lg border border-[#e2e8f0] px-3 py-1.5 text-[13px] font-medium text-[#64748b] transition-colors hover:bg-gray-50">
                                                    @if($isActive)
                                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25v13.5m-7.5-13.5v13.5" /></svg>
                                                        Nonaktifkan
                                                    @else
                                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M5.25 5.653c0-.856.917-1.398 1.667-.986l11.54 6.348a1.125 1.125 0 010 1.971l-11.54 6.347a1.125 1.125 0 01-1.667-.985V5.653z" /></svg>
                                                        Aktifkan
                                                    @endif
                                                </button>

                                                {{-- Toggle Modal --}}
                                                <div x-show="open" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
                                                    <div x-show="open" x-transition:enter="transition-opacity ease-linear duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="open = false" class="fixed inset-0 bg-black/50"></div>
                                                    <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="relative w-full max-w-md rounded-[14px] border border-[#e2e8f0] bg-white p-6 shadow-xl">
                                                        <div class="flex items-start gap-4">
                                                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full {{ $isActive ? 'bg-amber-50' : 'bg-green-50' }}">
                                                                @if($isActive)
                                                                    <svg class="h-5 w-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25v13.5m-7.5-13.5v13.5" /></svg>
                                                                @else
                                                                    <svg class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M5.25 5.653c0-.856.917-1.398 1.667-.986l11.54 6.348a1.125 1.125 0 010 1.971l-11.54 6.347a1.125 1.125 0 01-1.667-.985V5.653z" /></svg>
                                                                @endif
                                                            </div>
                                                            <div class="flex-1">
                                                                <h3 class="text-[15px] font-semibold text-[#0f172a]">{{ $isActive ? 'Nonaktifkan Karyawan?' : 'Aktifkan Karyawan?' }}</h3>
                                                                <p class="mt-1 text-[13px] text-[#64748b]">{{ $isActive ? 'Karyawan yang dinonaktifkan tidak dapat login ke sistem.' : 'Karyawan akan dapat login kembali ke sistem.' }}</p>
                                                            </div>
                                                            <button type="button" @click="open = false" class="text-[#94a3b8] hover:text-[#0f172a] transition-colors">
                                                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                                                            </button>
                                                        </div>
                                                        <div class="mt-6 flex items-center justify-end gap-3">
                                                            <button type="button" @click="open = false" class="rounded-[10px] border border-[#e2e8f0] bg-white px-4 py-2 text-[13px] font-medium text-[#0f172a] hover:bg-gray-50 transition-colors">Batal</button>
                                                            <form action="{{ route('admin.master.karyawan.toggle-status', $employee) }}" method="POST">
                                                                @csrf
                                                                @method('PATCH')
                                                                <button type="submit" class="rounded-[10px] {{ $isActive ? 'bg-amber-500 text-white hover:bg-amber-600' : 'bg-green-600 text-white hover:bg-green-700' }} px-4 py-2 text-[13px] font-medium transition-colors">
                                                                    {{ $isActive ? 'Nonaktifkan' : 'Aktifkan' }}
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Delete --}}
                                            <div x-data="{ open: false }">
                                                <button type="button" @click="open = true" class="inline-flex items-center gap-1.5 rounded-lg border border-red-200 px-3 py-1.5 text-[13px] font-medium text-red-600 transition-colors hover:bg-red-50">
                                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                                                    Hapus
                                                </button>

                                                {{-- Delete Modal --}}
                                                <div x-show="open" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
                                                    <div x-show="open" x-transition:enter="transition-opacity ease-linear duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="open = false" class="fixed inset-0 bg-black/50"></div>
                                                    <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="relative w-full max-w-md rounded-[14px] border border-[#e2e8f0] bg-white p-6 shadow-xl">
                                                        <div class="flex items-start gap-4">
                                                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-red-50">
                                                                <svg class="h-5 w-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                                                            </div>
                                                            <div class="flex-1">
                                                                <h3 class="text-[15px] font-semibold text-[#0f172a]">Hapus Permanen Data?</h3>
                                                                <p class="mt-1 text-[13px] text-[#64748b]">Data karyawan yang dihapus permanen tidak dapat dikembalikan.</p>
                                                            </div>
                                                            <button type="button" @click="open = false" class="text-[#94a3b8] hover:text-[#0f172a] transition-colors">
                                                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                                                            </button>
                                                        </div>
                                                        <div class="mt-6 flex items-center justify-end gap-3">
                                                            <button type="button" @click="open = false" class="rounded-[10px] border border-[#e2e8f0] bg-white px-4 py-2 text-[13px] font-medium text-[#0f172a] hover:bg-gray-50 transition-colors">Batal</button>
                                                            <form action="{{ route('admin.master.karyawan.destroy', $employee) }}" method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="rounded-[10px] bg-red-600 px-4 py-2 text-[13px] font-medium text-white transition-colors hover:bg-red-700">Hapus Permanen</button>
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

                {{-- Pagination --}}
                <div class="flex flex-col gap-4 border-t border-[#e2e8f0] px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
                    <p class="text-[13px] text-[#64748b]">
                        Menampilkan {{ $employees->firstItem() }}-{{ $employees->lastItem() }} dari {{ $employees->total() }} data
                    </p>
                    <div class="flex items-center gap-3">
                        {{ $employees->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
