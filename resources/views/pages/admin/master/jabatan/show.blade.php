@extends('layouts.admin', ['title' => 'Detail Jabatan'])

@section('content')
    <div class="space-y-6">
        <x-ui.page-header title="Detail Jabatan" :subtitle="$jabatan->name" />

        <x-ui.breadcrumb :items="[
            'Master Data' => route('admin.master.jabatan.index'),
            'Detail Jabatan' => route('admin.master.jabatan.show', $jabatan),
        ]" />

        <x-ui.card>
            <div class="space-y-6">
                <dl class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <dt class="text-sm font-medium text-[#5A5A5A]">Nama</dt>
                        <dd class="mt-1 text-sm text-[#080808]">{{ $jabatan->name }}</dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-[#5A5A5A]">Status</dt>
                        <dd class="mt-1">
                            <x-ui.badge :variant="$jabatan->status === 'active' ? 'success' : 'muted'">
                                {{ $jabatan->status === 'active' ? 'Aktif' : 'Nonaktif' }}
                            </x-ui.badge>
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-[#5A5A5A]">Deskripsi</dt>
                        <dd class="mt-1 text-sm text-[#080808]">{{ $jabatan->description ?: '-' }}</dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-[#5A5A5A]">Jumlah Karyawan</dt>
                        <dd class="mt-1 text-sm text-[#080808]">{{ $jabatan->employees_count }}</dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-[#5A5A5A]">Tanggal Dibuat</dt>
                        <dd class="mt-1 text-sm text-[#080808]">{{ $jabatan->created_at ? $jabatan->created_at->format('d M Y H:i') : '-' }}</dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-[#5A5A5A]">Tanggal Diperbarui</dt>
                        <dd class="mt-1 text-sm text-[#080808]">{{ $jabatan->updated_at ? $jabatan->updated_at->format('d M Y H:i') : '-' }}</dd>
                    </div>
                </dl>

                <div class="flex flex-wrap gap-3">
                    <x-ui.button href="{{ route('admin.master.jabatan.edit', $jabatan) }}" variant="secondary">Edit</x-ui.button>

                    <form method="POST" action="{{ route('admin.master.jabatan.toggle-status', $jabatan) }}">
                        @csrf
                        @method('PATCH')
                        <x-ui.button type="submit" :variant="$jabatan->status === 'active' ? 'danger' : 'primary'">
                            {{ $jabatan->status === 'active' ? 'Nonaktifkan' : 'Aktifkan' }}
                        </x-ui.button>
                    </form>

                    <div x-data="{ open: false }" class="relative">
                        <x-ui.button type="button" variant="danger" @click="open = true">Hapus Permanen</x-ui.button>

                        <div x-show="open" x-cloak @keydown.escape.window="open = false" class="fixed inset-0 z-50 flex items-center justify-center p-4" role="dialog" aria-modal="true">
                            <div x-show="open" x-transition:enter="transition-opacity ease-linear duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="open = false" class="fixed inset-0 bg-black/50"></div>

                            <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="relative w-full max-w-lg rounded-lg border border-[#D8D8D8] bg-white p-6 shadow-lg">
                                <h3 class="text-base font-semibold text-[#080808]">Hapus Permanen Data?</h3>
                                <p class="mt-1 text-sm text-[#5A5A5A]">Data jabatan yang dihapus permanen tidak dapat dikembalikan.</p>

                                <div class="mt-6 flex items-center justify-end gap-3">
                                    <x-ui.button type="button" variant="secondary" @click="open = false">Batal</x-ui.button>

                                    <form method="POST" action="{{ route('admin.master.jabatan.destroy', $jabatan) }}">
                                        @csrf
                                        @method('DELETE')
                                        <x-ui.button type="submit" variant="danger">Hapus Permanen</x-ui.button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <x-ui.button href="{{ route('admin.master.jabatan.index') }}" variant="ghost">Kembali</x-ui.button>
                </div>
            </div>
        </x-ui.card>
    </div>
@endsection
