@extends('layouts.admin', ['title' => 'Departemen'])

@section('content')
<div class="space-y-6">
    <x-ui.page-header title="Departemen" subtitle="Kelola data departemen perusahaan.">
        <x-slot:actions>
            <x-ui.button href="{{ route('admin.master.departemen.create') }}" variant="primary">
                <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Tambah Departemen
            </x-ui.button>
        </x-slot:actions>
    </x-ui.page-header>

    <x-ui.card>
        <form method="GET" action="{{ route('admin.master.departemen.index') }}" class="mb-4 flex flex-col gap-3 sm:flex-row">
            <div class="flex-1">
                <x-ui.input name="search" placeholder="Cari departemen..." value="{{ request('search') }}" />
            </div>
            <div class="w-full sm:w-40">
                <x-ui.select name="status" placeholder="Semua status">
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                </x-ui.select>
            </div>
            <x-ui.button type="submit" variant="primary">Cari</x-ui.button>
            @if(request('search') || request('status'))
                <x-ui.button href="{{ route('admin.master.departemen.index') }}" variant="ghost">Reset</x-ui.button>
            @endif
        </form>

        @if($departments->isEmpty())
            <x-ui.empty-state
                title="Belum ada departemen"
                description="Mulai dengan menambahkan departemen baru."
                actionText="Tambah Departemen"
                actionUrl="{{ route('admin.master.departemen.create') }}"
            />
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-[#D8D8D8]">
                            <th class="px-3 py-2.5 text-left font-medium text-[#5A5A5A]">Nama Departemen</th>
                            <th class="px-3 py-2.5 text-center font-medium text-[#5A5A5A]">Karyawan</th>
                            <th class="px-3 py-2.5 text-left font-medium text-[#5A5A5A]">Status</th>
                            <th class="px-3 py-2.5 text-right font-medium text-[#5A5A5A]">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($departments as $dept)
                        <tr class="border-b border-[#D8D8D8] hover:bg-gray-50">
                            <td class="px-3 py-3">
                                <div class="font-medium text-[#080808]">{{ $dept->name }}</div>
                                @if($dept->description)
                                    <div class="mt-0.5 text-xs text-[#898989]">{{ Str::limit($dept->description, 50) }}</div>
                                @endif
                            </td>
                            <td class="px-3 py-3 text-center text-[#080808]">{{ $dept->employees_count }}</td>
                            <td class="px-3 py-3">
                                <x-ui.badge :variant="$dept->status === 'active' ? 'success' : 'muted'">
                                    {{ $dept->status === 'active' ? 'Aktif' : 'Nonaktif' }}
                                </x-ui.badge>
                            </td>
                            <td class="px-3 py-3 text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <x-ui.button href="{{ route('admin.master.departemen.show', $dept) }}" variant="ghost" size="sm">Detail</x-ui.button>
                                    <x-ui.button href="{{ route('admin.master.departemen.edit', $dept) }}" variant="ghost" size="sm">Edit</x-ui.button>
                                    {{-- Toggle Status --}}
                                    <form action="{{ route('admin.master.departemen.toggle-status', $dept) }}" method="POST" class="inline" x-data="{ label: '{{ $dept->status === 'active' ? 'Nonaktifkan' : 'Aktifkan' }}' }">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="rounded-md px-2 py-1 text-xs text-[#5A5A5A] hover:bg-gray-100 transition-colors" x-text="label"></button>
                                    </form>
                                    {{-- Delete Button + Modal --}}
                                    <div x-data="{ open: false }" class="inline">
                                        <button type="button" @click="open = true" class="rounded-md px-2 py-1 text-xs text-[#EE1D36] hover:bg-red-50 transition-colors">Hapus</button>
                                        <div x-show="open" @keydown.escape.window="open = false" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
                                            <div x-show="open" x-transition:enter="transition-opacity ease-linear duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="open = false" class="fixed inset-0 bg-black/50"></div>
                                            <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="relative w-full max-w-lg rounded-lg border border-[#D8D8D8] bg-white p-6 shadow-lg">
                                                <h3 class="text-base font-semibold text-[#080808]">Hapus Permanen Data?</h3>
                                                <p class="mt-1 text-sm text-[#5A5A5A]">Data departemen yang dihapus permanen tidak dapat dikembalikan.</p>
                                                <div class="mt-6 flex items-center justify-end gap-3">
                                                    <button type="button" @click="open = false" class="rounded-md border border-[#D8D8D8] bg-white px-4 py-2 text-sm font-medium text-[#080808] hover:bg-gray-50 transition-colors">Batal</button>
                                                    <form action="{{ route('admin.master.departemen.destroy', $dept) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="rounded-md bg-[#EE1D36] px-4 py-2 text-sm font-medium text-white hover:bg-[#EE1D36]/90 transition-colors">Hapus Permanen</button>
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
                {{ $departments->links() }}
            </div>
        @endif
    </x-ui.card>
</div>
@endsection
