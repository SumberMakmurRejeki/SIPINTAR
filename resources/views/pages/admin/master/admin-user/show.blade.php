@extends('layouts.admin', ['title' => 'Detail Admin'])

@section('content')
    <div class="space-y-6">
        <x-ui.page-header title="Detail Admin" :subtitle="$adminUser->name" />

        <x-ui.breadcrumb :items="[
            'Master Data' => route('admin.master.admin-user.index'),
            'Detail Admin' => route('admin.master.admin-user.show', $adminUser),
        ]" />

        <x-ui.card>
            @php
                $isActive = $adminUser->status === 'active';
            @endphp

            <div class="space-y-6">
                <dl class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <dt class="text-sm font-medium text-[#5A5A5A]">Nama</dt>
                        <dd class="mt-1 text-sm text-[#080808]">{{ $adminUser->name }}</dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-[#5A5A5A]">Username</dt>
                        <dd class="mt-1 text-sm text-[#080808]">{{ $adminUser->username }}</dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-[#5A5A5A]">Status</dt>
                        <dd class="mt-1">
                            <x-ui.badge :variant="$isActive ? 'success' : 'muted'">
                                {{ $isActive ? 'Aktif' : 'Nonaktif' }}
                            </x-ui.badge>
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-[#5A5A5A]">Last Login</dt>
                        <dd class="mt-1 text-sm text-[#080808]">{{ $adminUser->last_login_at?->format('d M Y H:i') ?? 'Belum pernah' }}</dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-[#5A5A5A]">Tanggal Dibuat</dt>
                        <dd class="mt-1 text-sm text-[#080808]">{{ $adminUser->created_at?->format('d M Y H:i') ?? '-' }}</dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-[#5A5A5A]">Tanggal Diperbarui</dt>
                        <dd class="mt-1 text-sm text-[#080808]">{{ $adminUser->updated_at?->format('d M Y H:i') ?? '-' }}</dd>
                    </div>
                </dl>

                <div class="flex flex-wrap gap-3">
                    <x-ui.button href="{{ route('admin.master.admin-user.edit', $adminUser) }}" variant="secondary">Edit</x-ui.button>

                    <form method="POST" action="{{ route('admin.master.admin-user.toggle-status', $adminUser) }}">
                        @csrf
                        @method('PATCH')
                        <x-ui.button type="submit" :variant="$isActive ? 'danger' : 'primary'">
                            {{ $isActive ? 'Nonaktifkan' : 'Aktifkan' }}
                        </x-ui.button>
                    </form>

                    <div x-data="{ open: false }">
                        <x-ui.button type="button" variant="danger" @click="open = true">Hapus Permanen</x-ui.button>

                        <div x-show="open" x-cloak @keydown.escape.window="open = false" class="fixed inset-0 z-50 flex items-center justify-center p-4">
                            <div x-show="open" x-transition:enter="transition-opacity ease-linear duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="open = false" class="fixed inset-0 bg-black/50"></div>
                            <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="relative w-full max-w-lg rounded-lg border border-[#D8D8D8] bg-white p-6 shadow-lg">
                                <h3 class="text-base font-semibold text-[#080808]">Hapus Permanen Data?</h3>
                                <p class="mt-1 text-sm text-[#5A5A5A]">Data admin yang dihapus permanen tidak dapat dikembalikan.</p>
                                <div class="mt-6 flex items-center justify-end gap-3">
                                    <x-ui.button type="button" variant="secondary" @click="open = false">Batal</x-ui.button>
                                    <form action="{{ route('admin.master.admin-user.destroy', $adminUser) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <x-ui.button type="submit" variant="danger">Hapus Permanen</x-ui.button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <x-ui.button href="{{ route('admin.master.admin-user.index') }}" variant="ghost">Kembali</x-ui.button>
                </div>
            </div>
        </x-ui.card>
    </div>
@endsection
