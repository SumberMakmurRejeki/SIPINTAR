@extends('layouts.admin', ['title' => 'Data Training'])

@section('content')
    <div class="space-y-6">
        <x-ui.page-header title="Data Training" subtitle="Kelola data training perusahaan.">
            <x-slot:actions>
                <x-ui.button href="{{ route('admin.training.create') }}" variant="primary">
                    <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Tambah Training
                </x-ui.button>
            </x-slot:actions>
        </x-ui.page-header>

        <x-ui.card>
            {{-- Search & Filter --}}
            <form method="GET" action="{{ route('admin.training.index') }}" class="mb-4 flex flex-col gap-3 lg:flex-row">
                <div class="flex-1">
                    <x-ui.input name="search" placeholder="Cari judul training" value="{{ request('search') }}" />
                </div>

                <div class="w-full lg:w-44">
                    <x-ui.select name="status" placeholder="Semua status">
                        <option value="draft" @selected(request('status') === 'draft')>Draft</option>
                        <option value="published" @selected(request('status') === 'published')>Published</option>
                        <option value="closed" @selected(request('status') === 'closed')>Closed</option>
                        <option value="archived" @selected(request('status') === 'archived')>Archived</option>
                    </x-ui.select>
                </div>

                <div class="w-full lg:w-40">
                    <x-ui.input name="date_from" type="date" value="{{ request('date_from') }}" />
                </div>

                <div class="w-full lg:w-40">
                    <x-ui.input name="date_to" type="date" value="{{ request('date_to') }}" />
                </div>

                <div class="flex gap-2">
                    <x-ui.button type="submit" variant="primary">Cari</x-ui.button>
                    <x-ui.button href="{{ route('admin.training.index') }}" variant="ghost">Reset</x-ui.button>
                </div>
            </form>

            @if($trainings->isEmpty())
                <x-ui.empty-state
                    title="Belum ada training"
                    description="Buat training baru untuk mulai mengelola pelatihan."
                    actionText="Tambah Training"
                    actionUrl="{{ route('admin.training.create') }}"
                />
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-[#D8D8D8] bg-gray-50">
                                <th class="px-3 py-3 text-left font-medium text-[#5A5A5A]">Judul Training</th>
                                <th class="px-3 py-3 text-left font-medium text-[#5A5A5A]">Peserta</th>
                                <th class="px-3 py-3 text-left font-medium text-[#5A5A5A]">Status</th>
                                <th class="px-3 py-3 text-left font-medium text-[#5A5A5A]">Tanggal Mulai</th>
                                <th class="px-3 py-3 text-left font-medium text-[#5A5A5A]">Tanggal Selesai</th>
                                <th class="px-3 py-3 text-right font-medium text-[#5A5A5A]">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($trainings as $training)
                                @php
                                    $statusBadge = match($training->status) {
                                        'draft' => 'muted',
                                        'published' => 'success',
                                        'closed' => 'warning',
                                        'archived' => 'default',
                                    };
                                    $statusLabel = match($training->status) {
                                        'draft' => 'Draft',
                                        'published' => 'Published',
                                        'closed' => 'Closed',
                                        'archived' => 'Archived',
                                    };
                                @endphp

                                <tr class="border-b border-[#D8D8D8] hover:bg-gray-50">
                                    <td class="px-3 py-3 text-[#080808]">
                                        <div class="font-medium">{{ $training->title }}</div>
                                    </td>
                                    <td class="px-3 py-3 text-[#080808]">{{ $training->participants_count }}</td>
                                    <td class="px-3 py-3">
                                        <x-ui.badge :variant="$statusBadge">{{ $statusLabel }}</x-ui.badge>
                                    </td>
                                    <td class="px-3 py-3 text-[#080808]">
                                        {{ $training->start_date?->format('d M Y') ?? '-' }}
                                    </td>
                                    <td class="px-3 py-3 text-[#080808]">
                                        {{ $training->end_date?->format('d M Y') ?? '-' }}
                                    </td>
                                    <td class="px-3 py-3 text-right">
                                        <div class="flex flex-wrap justify-end gap-1">
                                            <x-ui.button href="{{ route('admin.training.show', $training) }}" variant="ghost" size="sm">Detail</x-ui.button>
                                            <x-ui.button href="{{ route('admin.training.edit', $training) }}" variant="ghost" size="sm">Edit</x-ui.button>

                                            @if($training->status === 'draft')
                                                <form action="{{ route('admin.training.publish', $training) }}" method="POST" class="inline">
                                                    @csrf
                                                    <x-ui.button type="submit" variant="outline" size="sm">Publish</x-ui.button>
                                                </form>
                                            @endif

                                            @if($training->status === 'published')
                                                <form action="{{ route('admin.training.close', $training) }}" method="POST" class="inline">
                                                    @csrf
                                                    <x-ui.button type="submit" variant="outline" size="sm">Tutup</x-ui.button>
                                                </form>
                                            @endif

                                            <div x-data="{ open: false }" class="inline">
                                                <x-ui.button type="button" variant="ghost" size="sm" @click="open = true">
                                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                    </svg>
                                                </x-ui.button>

                                                <div x-show="open" x-cloak @keydown.escape.window="open = false" class="fixed inset-0 z-50 flex items-center justify-center p-4">
                                                    <div x-show="open" x-transition:enter="transition-opacity ease-linear duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="open = false" class="fixed inset-0 bg-black/50"></div>
                                                    <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="relative w-full max-w-lg rounded-lg border border-[#D8D8D8] bg-white p-6 shadow-lg">
                                                        <h3 class="text-base font-semibold text-[#080808]">Hapus Permanen Data?</h3>
                                                        <p class="mt-1 text-sm text-[#5A5A5A]">"{{ $training->title }}" akan dihapus permanen dan tidak dapat dikembalikan.</p>
                                                        <div class="mt-6 flex items-center justify-end gap-3">
                                                            <x-ui.button type="button" variant="secondary" @click="open = false">Batal</x-ui.button>
                                                            <form action="{{ route('admin.training.destroy', $training) }}" method="POST">
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
                    {{ $trainings->links() }}
                </div>
            @endif
        </x-ui.card>
    </div>
@endsection
