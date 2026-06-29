@extends('layouts.admin', ['title' => 'Detail Training'])

@section('content')
    <div class="space-y-6">
        <x-ui.page-header title="Detail Training" :subtitle="$training->title">
            <x-slot:actions>
                <x-ui.button href="{{ route('admin.training.index') }}" variant="secondary">Kembali</x-ui.button>
            </x-slot:actions>
        </x-ui.page-header>

        <x-ui.breadcrumb :items="[
            'Data Training' => route('admin.training.index'),
            $training->title => route('admin.training.show', $training),
        ]" />

        {{-- Summary Card --}}
        <x-ui.card>
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-center gap-3">
                    <div>
                        <h2 class="text-lg font-semibold text-[#080808]">{{ $training->title }}</h2>
                        @if($training->description)
                            <p class="mt-1 text-sm text-[#5A5A5A]">{{ Str::limit($training->description, 120) }}</p>
                        @endif
                    </div>
                </div>
                <div class="flex items-center gap-2">
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
                    <x-ui.badge :variant="$statusBadge">{{ $statusLabel }}</x-ui.badge>
                </div>
            </div>
        </x-ui.card>

        {{-- Tabs --}}
        <x-ui.card class="p-0">
            <div x-data="{ active: 'informasi' }" class="overflow-hidden">
                {{-- Tab headers --}}
                <div class="flex border-b border-[#D8D8D8] overflow-x-auto">
                    <button type="button" @click="active = 'informasi'" :class="active === 'informasi' ? 'border-b-2 border-[#080808] text-[#080808] font-medium' : 'text-[#5A5A5A] hover:text-[#080808]'" class="px-4 py-3 text-sm transition-colors whitespace-nowrap">
                        Informasi
                    </button>
                    <button type="button" @click="active = 'materi'" class="px-4 py-3 text-sm text-[#898989] transition-colors whitespace-nowrap cursor-not-allowed" disabled>
                        Materi
                    </button>
                    <button type="button" @click="active = 'pretest'" class="px-4 py-3 text-sm text-[#898989] transition-colors whitespace-nowrap cursor-not-allowed" disabled>
                        Pre-Test
                    </button>
                    <button type="button" @click="active = 'posttest'" class="px-4 py-3 text-sm text-[#898989] transition-colors whitespace-nowrap cursor-not-allowed" disabled>
                        Post-Test
                    </button>
                    <button type="button" @click="active = 'peserta'" class="px-4 py-3 text-sm text-[#898989] transition-colors whitespace-nowrap cursor-not-allowed" disabled>
                        Peserta
                    </button>
                    <button type="button" @click="active = 'hasil'" class="px-4 py-3 text-sm text-[#898989] transition-colors whitespace-nowrap cursor-not-allowed" disabled>
                        Hasil
                    </button>
                </div>

                {{-- Tab content: Informasi --}}
                <div x-show="active === 'informasi'" class="p-6">
                    <div class="space-y-6">
                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <dt class="text-sm font-medium text-[#5A5A5A]">Judul Training</dt>
                                <dd class="mt-1 text-sm text-[#080808]">{{ $training->title }}</dd>
                            </div>

                            <div>
                                <dt class="text-sm font-medium text-[#5A5A5A]">Status</dt>
                                <dd class="mt-1">
                                    <x-ui-badge :variant="$statusBadge">{{ $statusLabel }}</x-ui-badge>
                                </dd>
                            </div>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-[#5A5A5A]">Deskripsi</dt>
                            <dd class="mt-1 text-sm text-[#080808]">{{ $training->description ?? '-' }}</dd>
                        </div>

                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <dt class="text-sm font-medium text-[#5A5A5A]">Tanggal Mulai</dt>
                                <dd class="mt-1 text-sm text-[#080808]">{{ $training->start_date?->format('d M Y') ?? '-' }}</dd>
                            </div>

                            <div>
                                <dt class="text-sm font-medium text-[#5A5A5A]">Tanggal Selesai</dt>
                                <dd class="mt-1 text-sm text-[#080808]">{{ $training->end_date?->format('d M Y') ?? '-' }}</dd>
                            </div>
                        </div>

                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <dt class="text-sm font-medium text-[#5A5A5A]">Passing Grade</dt>
                                <dd class="mt-1 text-sm text-[#080808]">{{ $training->passing_grade !== null ? $training->passing_grade : '-' }}</dd>
                            </div>

                            <div>
                                <dt class="text-sm font-medium text-[#5A5A5A]">Batas Percobaan</dt>
                                <dd class="mt-1 text-sm text-[#080808]">{{ $training->max_attempts !== null ? $training->max_attempts : '-' }}</dd>
                            </div>
                        </div>

                        <div class="grid gap-4 sm:grid-cols-3">
                            <div>
                                <dt class="text-sm font-medium text-[#5A5A5A]">Dibuat Oleh</dt>
                                <dd class="mt-1 text-sm text-[#080808]">{{ $training->creator->name ?? '-' }}</dd>
                            </div>

                            <div>
                                <dt class="text-sm font-medium text-[#5A5A5A]">Tanggal Dibuat</dt>
                                <dd class="mt-1 text-sm text-[#080808]">{{ $training->created_at?->format('d M Y H:i') ?? '-' }}</dd>
                            </div>

                            <div>
                                <dt class="text-sm font-medium text-[#5A5A5A]">Tanggal Diperbarui</dt>
                                <dd class="mt-1 text-sm text-[#080808]">{{ $training->updated_at?->format('d M Y H:i') ?? '-' }}</dd>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Placeholder tabs --}}
                <div x-show="active === 'materi'" class="p-6">
                    <x-ui.empty-state title="Coming Soon" description="Tab Materi akan tersedia di session berikutnya." icon="<svg class='h-6 w-6 text-[#898989]' fill='none' viewBox='0 0 24 24' stroke-width='1.5' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' d='M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9z' /></svg>" />
                </div>
                <div x-show="active === 'pretest'" class="p-6">
                    <x-ui.empty-state title="Coming Soon" description="Tab Pre-Test akan tersedia di session berikutnya." icon="<svg class='h-6 w-6 text-[#898989]' fill='none' viewBox='0 0 24 24' stroke-width='1.5' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' d='M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.846-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25z' /></svg>" />
                </div>
                <div x-show="active === 'posttest'" class="p-6">
                    <x-ui.empty-state title="Coming Soon" description="Tab Post-Test akan tersedia di session berikutnya." icon="<svg class='h-6 w-6 text-[#898989]' fill='none' viewBox='0 0 24 24' stroke-width='1.5' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' d='M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.846-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25z' /></svg>" />
                </div>
                <div x-show="active === 'peserta'" class="p-6">
                    <x-ui.empty-state title="Coming Soon" description="Tab Peserta akan tersedia di session berikutnya." icon="<svg class='h-6 w-6 text-[#898989]' fill='none' viewBox='0 0 24 24' stroke-width='1.5' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' d='M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.953 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z' /></svg>" />
                </div>
                <div x-show="active === 'hasil'" class="p-6">
                    <x-ui.empty-state title="Coming Soon" description="Tab Hasil akan tersedia di session berikutnya." icon="<svg class='h-6 w-6 text-[#898989]' fill='none' viewBox='0 0 24 24' stroke-width='1.5' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' d='M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z' /></svg>" />
                </div>
            </div>
        </x-ui.card>

        {{-- Actions --}}
        <x-ui.card>
            <div class="flex flex-wrap gap-3">
                <x-ui.button href="{{ route('admin.training.edit', $training) }}" variant="secondary">
                    Edit Informasi
                </x-ui.button>

                @if($training->status === 'draft')
                    <div x-data="{ open: false, warnings: @json(session('training_publish_warnings', [])) }" class="inline">
                        <x-ui.button type="button" variant="primary" @click="open = true">Publish</x-ui.button>

                        <div x-show="open" x-cloak @keydown.escape.window="open = false" class="fixed inset-0 z-50 flex items-center justify-center p-4">
                            <div x-show="open" x-transition:enter="transition-opacity ease-linear duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="open = false" class="fixed inset-0 bg-black/50"></div>
                            <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="relative w-full max-w-lg rounded-lg border border-[#D8D8D8] bg-white p-6 shadow-lg">
                                <h3 class="text-base font-semibold text-[#080808]">Konfirmasi Publish Training</h3>
                                <template x-if="warnings.length > 0">
                                    <div class="mt-4">
                                        <p class="text-sm font-medium text-[#854d0e]">Training belum lengkap:</p>
                                        <ul class="mt-2 list-inside list-disc text-sm text-[#a16207]">
                                            <template x-for="warning in warnings" :key="warning">
                                                <li x-text="warning"></li>
                                            </template>
                                        </ul>
                                        <p class="mt-2 text-sm text-[#5A5A5A]">Tetap lanjutkan publish?</p>
                                    </div>
                                </template>
                                <form action="{{ route('admin.training.publish', $training) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="force" value="1">
                                    <div class="mt-6 flex items-center justify-end gap-3">
                                        <x-ui.button type="button" variant="secondary" @click="open = false">Batal</x-ui.button>
                                        <x-ui.button type="submit" variant="primary">Ya, Publish</x-ui.button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endif

                @if($training->status === 'published')
                    <form action="{{ route('admin.training.close', $training) }}" method="POST">
                        @csrf
                        <x-ui.button type="submit" variant="danger">Tutup Training</x-ui.button>
                    </form>
                @endif

                @if(in_array($training->status, ['draft', 'closed']))
                    <form action="{{ route('admin.training.archive', $training) }}" method="POST">
                        @csrf
                        <x-ui.button type="submit" variant="outline">Arsipkan</x-ui.button>
                    </form>
                @endif

                <div x-data="{ open: false }" class="inline">
                    <x-ui.button type="button" variant="danger" @click="open = true">Hapus Permanen</x-ui.button>

                    <div x-show="open" x-cloak @keydown.escape.window="open = false" class="fixed inset-0 z-50 flex items-center justify-center p-4">
                        <div x-show="open" x-transition:enter="transition-opacity ease-linear duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="open = false" class="fixed inset-0 bg-black/50"></div>
                        <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="relative w-full max-w-lg rounded-lg border border-[#D8D8D8] bg-white p-6 shadow-lg">
                            <h3 class="text-base font-semibold text-[#080808]">Hapus Permanen Training?</h3>
                            <p class="mt-1 text-sm text-[#5A5A5A]">"{{ $training->title }}" akan dihapus permanen dan tidak dapat dikembalikan.</p>
                            <p class="mt-2 text-xs text-[#898989]">Jika training sudah memiliki peserta, materi, atau test, disarankan untuk mengarsipkan training terlebih dahulu.</p>
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

                <x-ui.button href="{{ route('admin.training.index') }}" variant="ghost">Kembali</x-ui.button>
            </div>
        </x-ui.card>
    </div>
@endsection
