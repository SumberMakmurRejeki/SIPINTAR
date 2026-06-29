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
                    <button type="button" @click="active = 'materi'" :class="active === 'materi' ? 'border-b-2 border-[#080808] text-[#080808] font-medium' : 'text-[#5A5A5A] hover:text-[#080808]'" class="px-4 py-3 text-sm transition-colors whitespace-nowrap">
                        Materi
                    </button>
                    <button type="button" @click="active = 'pretest'" :class="active === 'pretest' ? 'border-b-2 border-[#080808] text-[#080808] font-medium' : 'text-[#5A5A5A] hover:text-[#080808]'" class="px-4 py-3 text-sm transition-colors whitespace-nowrap">
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
                                    <x-ui.badge :variant="$statusBadge">{{ $statusLabel }}</x-ui.badge>
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

                {{-- Tab content: Materi --}}
                <div x-show="active === 'materi'" x-data="materialForm()" x-init="initAddForm()" class="p-6">
                    <div class="space-y-4">
                        {{-- Add Material Button --}}
                        <div class="flex justify-end">
                            <x-ui.button type="button" variant="primary" @click="showAddModal = true">
                                <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                </svg>
                                Tambah Materi
                            </x-ui.button>
                        </div>

                        {{-- Materials Table --}}
                        @if($training->materials->isEmpty())
                            <x-ui.empty-state
                                title="Belum ada materi"
                                description="Tambahkan materi untuk training ini. Materi dapat berupa file atau link."
                            />
                        @else
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm">
                                    <thead>
                                        <tr class="border-b border-[#D8D8D8] bg-gray-50">
                                            <th class="px-3 py-3 text-left font-medium text-[#5A5A5A]">Judul Materi</th>
                                            <th class="px-3 py-3 text-left font-medium text-[#5A5A5A]">Tipe</th>
                                            <th class="px-3 py-3 text-left font-medium text-[#5A5A5A]">File / Link</th>
                                            <th class="px-3 py-3 text-left font-medium text-[#5A5A5A]">Wajib</th>
                                            <th class="px-3 py-3 text-left font-medium text-[#5A5A5A]">Status</th>
                                            <th class="px-3 py-3 text-right font-medium text-[#5A5A5A]">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($training->materials as $material)
                                            <tr class="border-b border-[#D8D8D8] hover:bg-gray-50">
                                                <td class="px-3 py-3">
                                                    <div class="font-medium text-[#080808]">{{ $material->title }}</div>
                                                    @if($material->description)
                                                        <div class="text-xs text-[#898989] mt-0.5">{{ Str::limit($material->description, 60) }}</div>
                                                    @endif
                                                </td>
                                                <td class="px-3 py-3">
                                                    @if($material->type === 'file')
                                                        <x-ui.badge variant="default">File</x-ui.badge>
                                                    @else
                                                        <x-ui.badge variant="muted">Link</x-ui.badge>
                                                    @endif
                                                </td>
                                                <td class="px-3 py-3 text-[#080808]">
                                                    @if($material->type === 'file')
                                                        <span class="text-xs">{{ strtoupper($material->file_type) }}</span>
                                                    @else
                                                        <a href="{{ $material->url }}" target="_blank" rel="noopener noreferrer" class="text-xs text-blue-600 hover:underline break-all">{{ $material->url }}</a>
                                                    @endif
                                                </td>
                                                <td class="px-3 py-3">
                                                    @if($material->is_required)
                                                        <x-ui.badge variant="warning">Ya</x-ui.badge>
                                                    @else
                                                        <span class="text-xs text-[#898989]">Tidak</span>
                                                    @endif
                                                </td>
                                                <td class="px-3 py-3">
                                                    <form action="{{ route('admin.training.materials.toggle-status', [$training, $material]) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        @if($material->status === 'active')
                                                            <x-ui.badge variant="success">Aktif</x-ui.badge>
                                                        @else
                                                            <x-ui.badge variant="default">Nonaktif</x-ui.badge>
                                                        @endif
                                                    </form>
                                                </td>
                                                <td class="px-3 py-3 text-right">
                                                    <div class="flex flex-wrap justify-end gap-1">
                                                        @if($material->type === 'link' || ($material->type === 'file' && $material->file_path))
                                                            <x-ui.button href="{{ route('admin.training.materials.preview', [$training, $material]) }}" target="_blank" variant="ghost" size="sm">Preview</x-ui.button>
                                                        @endif

                                                        @if($material->type === 'file' && $material->is_downloadable && $material->file_path)
                                                            <x-ui.button href="{{ route('admin.training.materials.download', [$training, $material]) }}" variant="ghost" size="sm">Download</x-ui.button>
                                                        @endif

                                                        <x-ui.button type="button" variant="ghost" size="sm" @click="openEditModal({{ $loop->index }})">Edit</x-ui.button>

                                                        <x-ui.button type="button" variant="ghost" size="sm" @click="openDeleteModal({{ $loop->index }})" class="text-[#EE1D36]">
                                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                            </svg>
                                                        </x-ui.button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>

                    {{-- Add Material Modal --}}
                    <div x-show="showAddModal" x-cloak @keydown.escape.window="showAddModal = false" class="fixed inset-0 z-50 flex items-start justify-center p-4 overflow-y-auto">
                        <div x-show="showAddModal" x-transition:enter="transition-opacity ease-linear duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="showAddModal = false" class="fixed inset-0 bg-black/50"></div>
                        <div x-show="showAddModal" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="relative w-full max-w-2xl rounded-lg border border-[#D8D8D8] bg-white shadow-lg my-8">
                            <div class="p-6">
                                <h3 class="text-base font-semibold text-[#080808]">Tambah Materi</h3>
                                <form id="add-material-form" action="{{ route('admin.training.materials.store', $training) }}" method="POST" enctype="multipart/form-data" class="mt-4 space-y-4">
                                    @csrf
                                    <input type="hidden" name="training_id" value="{{ $training->id }}">

                                    <div class="grid gap-4 sm:grid-cols-2">
                                        <x-ui.input name="title" label="Judul Materi" required placeholder="Masukkan judul materi" />

                                        <x-ui.select name="type" label="Tipe Materi" required x-model="materialType">
                                            <option value="">Pilih tipe</option>
                                            <option value="file">File</option>
                                            <option value="link">Link</option>
                                        </x-ui.select>
                                    </div>

                                    <x-ui.textarea name="description" label="Deskripsi" placeholder="Ringkasan materi (opsional)" rows="2"></x-ui.textarea>

                                    <div x-show="materialType === 'file'">
                                        <label class="block text-sm font-medium text-[#080808] mb-1">
                                            File <span class="text-[#EE1D36]">*</span>
                                        </label>
                                        <input type="file" name="file" class="block w-full text-sm text-[#080808] file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-gray-100 file:text-[#080808] hover:file:bg-gray-200">
                                        <p class="mt-1 text-xs text-[#898989]">Format: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, MP4, JPG, JPEG, PNG, WEBP, CSV. Maks 10MB.</p>
                                    </div>

                                    <div x-show="materialType === 'link'">
                                        <x-ui.input name="url" type="url" label="URL Link" placeholder="https://example.com atau https://youtube.com/watch?v=..." />
                                    </div>

                                    <div class="grid gap-4 sm:grid-cols-3">
                                        <x-ui.select name="is_downloadable" label="Boleh Download">
                                            <option value="1" selected>Ya</option>
                                            <option value="0">Tidak</option>
                                        </x-ui.select>

                                        <x-ui.select name="is_required" label="Wajib Dibuka">
                                            <option value="1">Ya</option>
                                            <option value="0" selected>Tidak</option>
                                        </x-ui.select>

                                        <x-ui.input name="sort_order" type="number" label="Urutan" placeholder="0" value="0" />
                                    </div>

                                    <x-ui.select name="status" label="Status" required>
                                        <option value="active" selected>Aktif</option>
                                        <option value="inactive">Nonaktif</option>
                                    </x-ui.select>
                                </form>
                            </div>
                            <div class="flex items-center justify-end gap-3 border-t border-[#D8D8D8] px-6 py-4">
                                <x-ui.button type="button" variant="secondary" @click="showAddModal = false">Batal</x-ui.button>
                                <x-ui.button type="submit" form="add-material-form" variant="primary">Simpan</x-ui.button>
                            </div>
                        </div>
                    </div>

                    {{-- Edit Material Modal --}}
                    <div x-data="{ editFormId: `edit-material-form` }" x-show="showEditModal" x-cloak @keydown.escape.window="showEditModal = false" class="fixed inset-0 z-50 flex items-start justify-center p-4 overflow-y-auto">
                        <div x-show="showEditModal" x-transition:enter="transition-opacity ease-linear duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="showEditModal = false" class="fixed inset-0 bg-black/50"></div>
                        <div x-show="showEditModal" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="relative w-full max-w-2xl rounded-lg border border-[#D8D8D8] bg-white shadow-lg my-8">
                            <div class="p-6">
                                <h3 class="text-base font-semibold text-[#080808]">Edit Materi</h3>
                                <form id="edit-material-form" :action="editAction" method="POST" enctype="multipart/form-data" class="mt-4 space-y-4">
                                    @csrf
                                    @method('PUT')

                                    <input type="hidden" name="training_id" value="{{ $training->id }}">

                                    <div class="grid gap-4 sm:grid-cols-2">
                                        <x-ui.input name="title" label="Judul Materi" required placeholder="Masukkan judul materi" />

                                        <x-ui.select name="type" label="Tipe Materi" required x-model="editMaterialType">
                                            <option value="">Pilih tipe</option>
                                            <option value="file">File</option>
                                            <option value="link">Link</option>
                                        </x-ui.select>
                                    </div>

                                    <x-ui.textarea name="description" label="Deskripsi" placeholder="Ringkasan materi (opsional)" rows="2"></x-ui.textarea>

                                    <div x-show="editMaterialType === 'file'">
                                        <label class="block text-sm font-medium text-[#080808] mb-1">
                                            Ganti File (opsional)
                                        </label>
                                        <input type="file" name="file" class="block w-full text-sm text-[#080808] file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-gray-100 file:text-[#080808] hover:file:bg-gray-200">
                                        <p class="mt-1 text-xs text-[#898989]">Kosongkan jika tidak ingin mengganti file. Format: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, MP4, JPG, JPEG, PNG, WEBP, CSV. Maks 10MB.</p>
                                    </div>

                                    <div x-show="editMaterialType === 'link'">
                                        <x-ui.input name="url" type="url" label="URL Link" placeholder="https://example.com atau https://youtube.com/watch?v=..." />
                                    </div>

                                    <div class="grid gap-4 sm:grid-cols-3">
                                        <x-ui.select name="is_downloadable" label="Boleh Download">
                                            <option value="1">Ya</option>
                                            <option value="0">Tidak</option>
                                        </x-ui.select>

                                        <x-ui.select name="is_required" label="Wajib Dibuka">
                                            <option value="1">Ya</option>
                                            <option value="0">Tidak</option>
                                        </x-ui.select>

                                        <x-ui.input name="sort_order" type="number" label="Urutan" placeholder="0" />
                                    </div>

                                    <x-ui.select name="status" label="Status" required>
                                        <option value="active">Aktif</option>
                                        <option value="inactive">Nonaktif</option>
                                    </x-ui.select>
                                </form>
                            </div>
                            <div class="flex items-center justify-end gap-3 border-t border-[#D8D8D8] px-6 py-4">
                                <x-ui.button type="button" variant="secondary" @click="showEditModal = false">Batal</x-ui.button>
                                <x-ui.button type="submit" form="edit-material-form" variant="primary">Simpan Perubahan</x-ui.button>
                            </div>
                        </div>
                    </div>

                    {{-- Delete Modal (shared) --}}
                    <div x-show="showDeleteModal" x-cloak @keydown.escape.window="showDeleteModal = false" class="fixed inset-0 z-50 flex items-center justify-center p-4">
                        <div x-show="showDeleteModal" x-transition:enter="transition-opacity ease-linear duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="showDeleteModal = false" class="fixed inset-0 bg-black/50"></div>
                        <div x-show="showDeleteModal" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="relative w-full max-w-lg rounded-lg border border-[#D8D8D8] bg-white p-6 shadow-lg">
                            <h3 class="text-base font-semibold text-[#080808]">Hapus Materi Permanen?</h3>
                            <p x-text="deleteMessage" class="mt-1 text-sm text-[#5A5A5A]"></p>
                            <p class="mt-2 text-xs text-[#898989]">Materi yang dihapus tidak dapat dikembalikan.</p>
                            <div class="mt-6 flex items-center justify-end gap-3">
                                <x-ui.button type="button" variant="secondary" @click="showDeleteModal = false">Batal</x-ui.button>
                                <form :action="deleteAction" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <x-ui.button type="submit" variant="danger">Hapus Permanen</x-ui.button>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
                <div x-show="active === 'pretest'" x-data="preTestForm()" x-init="init()" class="p-6">
                    @php
                        $preTest = $training->tests->firstWhere('type', 'pre_test');
                    @endphp

                    {{-- Pre-Test Setting Section --}}
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <h3 class="text-base font-semibold text-[#080808]">Pengaturan Pre-Test</h3>
                            @if($preTest)
                                <x-ui.button type="button" variant="ghost" size="sm" @click="showSettingModal = true">Edit Pengaturan</x-ui.button>
                            @endif
                        </div>

                        @if($preTest)
                            <div class="grid gap-4 sm:grid-cols-2">
                                <div>
                                    <dt class="text-sm font-medium text-[#5A5A5A]">Judul</dt>
                                    <dd class="mt-1 text-sm text-[#080808]">{{ $preTest->title }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-[#5A5A5A]">Durasi</dt>
                                    <dd class="mt-1 text-sm text-[#080808]">{{ $preTest->duration_minutes ? $preTest->duration_minutes . ' menit' : '-' }}</dd>
                                </div>
                                <div class="sm:col-span-2">
                                    <dt class="text-sm font-medium text-[#5A5A5A]">Instruksi</dt>
                                    <dd class="mt-1 text-sm text-[#080808]">{{ $preTest->instruction ?? '-' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-[#5A5A5A]">Status</dt>
                                    <dd class="mt-1">
                                        @if($preTest->status === 'active')
                                            <x-ui.badge variant="success">Aktif</x-ui.badge>
                                        @else
                                            <x-ui.badge variant="default">Nonaktif</x-ui.badge>
                                        @endif
                                    </dd>
                                </div>
                            </div>

                            {{-- Questions Section --}}
                            <div class="mt-6 border-t border-[#D8D8D8] pt-6">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="text-base font-semibold text-[#080808]">Daftar Soal</h3>
                                    <div class="flex gap-2">
                                        <x-ui.button href="{{ route('admin.training.pre-test.preview', [$training, $preTest]) }}" target="_blank" variant="ghost" size="sm">Preview</x-ui.button>
                                        <x-ui.button type="button" variant="primary" size="sm" @click="openAddQuestionModal()">
                                            <svg class="mr-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                            </svg>
                                            Tambah Soal
                                        </x-ui.button>
                                    </div>
                                </div>

                                @php
                                    $questions = $preTest->questions ?? collect();
                                @endphp
                                @if($questions->isEmpty())
                                    <x-ui.empty-state
                                        title="Belum ada soal"
                                        description="Tambahkan soal pilihan ganda atau essay untuk pre-test ini."
                                    />
                                @else
                                    <div class="overflow-x-auto">
                                        <table class="w-full text-sm">
                                            <thead>
                                                <tr class="border-b border-[#D8D8D8] bg-gray-50">
                                                    <th class="px-3 py-3 text-left font-medium text-[#5A5A5A] w-12">No</th>
                                                    <th class="px-3 py-3 text-left font-medium text-[#5A5A5A]">Pertanyaan</th>
                                                    <th class="px-3 py-3 text-left font-medium text-[#5A5A5A]">Jenis</th>
                                                    <th class="px-3 py-3 text-center font-medium text-[#5A5A5A]">Skor</th>
                                                    <th class="px-3 py-3 text-left font-medium text-[#5A5A5A]">Status</th>
                                                    <th class="px-3 py-3 text-right font-medium text-[#5A5A5A]">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($questions as $question)
                                                    <tr class="border-b border-[#D8D8D8] hover:bg-gray-50">
                                                        <td class="px-3 py-3 text-[#898989]">{{ $loop->iteration }}</td>
                                                        <td class="px-3 py-3">
                                                            <div class="text-sm text-[#080808]">{{ Str::limit($question->question_text, 80) }}</div>
                                                        </td>
                                                        <td class="px-3 py-3">
                                                            @if($question->question_type === 'multiple_choice')
                                                                <x-ui.badge variant="default">Pilihan Ganda</x-ui.badge>
                                                            @else
                                                                <x-ui.badge variant="muted">Essay</x-ui.badge>
                                                            @endif
                                                        </td>
                                                        <td class="px-3 py-3 text-center text-sm text-[#080808]">{{ $question->score }}</td>
                                                        <td class="px-3 py-3">
                                                            @if($question->status === 'active')
                                                                <x-ui.badge variant="success">Aktif</x-ui.badge>
                                                            @else
                                                                <x-ui.badge variant="default">Nonaktif</x-ui.badge>
                                                            @endif
                                                        </td>
                                                        <td class="px-3 py-3 text-right">
                                                            <div class="flex flex-wrap justify-end gap-1">
                                                                <x-ui.button type="button" variant="ghost" size="sm" @click="openEditQuestionModal({{ $loop->index }})">Edit</x-ui.button>
                                                                <form action="{{ route('admin.training.pre-test.questions.toggle-status', [$training, $preTest, $question]) }}" method="POST" class="inline">
                                                                    @csrf
                                                                    @method('PATCH')
                                                                    <x-ui.button type="submit" variant="ghost" size="sm">{{ $question->status === 'active' ? 'Nonaktifkan' : 'Aktifkan' }}</x-ui.button>
                                                                </form>
                                                                <x-ui.button type="button" variant="ghost" size="sm" @click="openDeleteQuestionModal({{ $loop->index }})" class="text-[#EE1D36]">
                                                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                                    </svg>
                                                                </x-ui.button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                            </div>
                        @else
                            <div class="flex items-center justify-between">
                                <p class="text-sm text-[#5A5A5A]">Belum ada pre-test untuk training ini.</p>
                                <x-ui.button type="button" variant="primary" @click="showSettingModal = true">
                                    <svg class="mr-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                    </svg>
                                    Buat Pre-Test
                                </x-ui.button>
                            </div>
                        @endif
                    </div>

                    {{-- Pre-Test Setting Modal (Create/Edit) --}}
                    <div x-show="showSettingModal" x-cloak @keydown.escape.window="showSettingModal = false" class="fixed inset-0 z-50 flex items-start justify-center p-4 overflow-y-auto">
                        <div x-show="showSettingModal" x-transition:enter="transition-opacity ease-linear duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="showSettingModal = false" class="fixed inset-0 bg-black/50"></div>
                        <div x-show="showSettingModal" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="relative w-full max-w-lg rounded-lg border border-[#D8D8D8] bg-white shadow-lg my-8">
                            <div class="p-6">
                                <h3 class="text-base font-semibold text-[#080808]" x-text="settingFormMode === 'edit' ? 'Edit Pengaturan Pre-Test' : 'Buat Pre-Test'"></h3>
                                <form id="pretest-setting-form" :action="settingFormAction" method="POST" class="mt-4 space-y-4">
                                    @csrf
                                    <template x-if="settingFormMode === 'edit'">
                                        <input type="hidden" name="_method" value="PUT">
                                    </template>

                                    <x-ui.input name="title" label="Judul Pre-Test" required placeholder="Masukkan judul pre-test" />

                                    <x-ui.textarea name="instruction" label="Instruksi" placeholder="Petunjuk pengerjaan (opsional)" rows="3"></x-ui.textarea>

                                    <div class="grid gap-4 sm:grid-cols-2">
                                        <x-ui.input name="duration_minutes" type="number" label="Durasi (menit)" placeholder="30" min="1" />
                                        <x-ui.select name="status" label="Status" required>
                                            <option value="active" selected>Aktif</option>
                                            <option value="inactive">Nonaktif</option>
                                        </x-ui.select>
                                    </div>
                                </form>
                            </div>
                            <div class="flex items-center justify-end gap-3 border-t border-[#D8D8D8] px-6 py-4">
                                <x-ui.button type="button" variant="secondary" @click="showSettingModal = false">Batal</x-ui.button>
                                <x-ui.button type="submit" form="pretest-setting-form" variant="primary">Simpan</x-ui.button>
                            </div>
                        </div>
                    </div>

                    {{-- Add/Edit Question Modal --}}
                    <div x-show="showQuestionModal" x-cloak @keydown.escape.window="showQuestionModal = false" class="fixed inset-0 z-50 flex items-start justify-center p-4 overflow-y-auto">
                        <div x-show="showQuestionModal" x-transition:enter="transition-opacity ease-linear duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="showQuestionModal = false" class="fixed inset-0 bg-black/50"></div>
                        <div x-show="showQuestionModal" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="relative w-full max-w-2xl rounded-lg border border-[#D8D8D8] bg-white shadow-lg my-8">
                            <div class="p-6">
                                <h3 class="text-base font-semibold text-[#080808]" x-text="questionFormMode === 'edit' ? 'Edit Soal' : 'Tambah Soal'"></h3>
                                <form id="pretest-question-form" :action="questionFormAction" method="POST" class="mt-4 space-y-4">
                                    @csrf
                                    <template x-if="questionFormMode === 'edit'">
                                        <input type="hidden" name="_method" value="PUT">
                                    </template>

                                    <x-ui.textarea name="question_text" label="Pertanyaan" required placeholder="Tulis pertanyaan..." rows="3"></x-ui.textarea>

                                    <div class="grid gap-4 sm:grid-cols-3">
                                        <x-ui.select name="question_type" label="Jenis Soal" required x-model="questionType">
                                            <option value="multiple_choice">Pilihan Ganda</option>
                                            <option value="essay">Essay</option>
                                        </x-ui.select>
                                        <x-ui.input name="score" type="number" label="Skor" required placeholder="10" min="0" step="0.01" />
                                        <x-ui.input name="sort_order" type="number" label="Urutan" required placeholder="0" min="0" />
                                    </div>

                                    <x-ui.select name="status" label="Status Soal" required>
                                        <option value="active" selected>Aktif</option>
                                        <option value="inactive">Nonaktif</option>
                                    </x-ui.select>

                                    {{-- Options for multiple choice --}}
                                    <div x-show="questionType === 'multiple_choice'">
                                        <div class="flex items-center justify-between mb-2">
                                            <label class="block text-sm font-medium text-[#080808]">Pilihan Jawaban</label>
                                            <x-ui.button type="button" variant="ghost" size="sm" @click="addOption()">+ Tambah Opsi</x-ui.button>
                                        </div>
                                        <div class="space-y-3">
                                            <template x-for="(option, index) in questionOptions" :key="index">
                                                <div class="flex items-start gap-2">
                                                    <input type="hidden" :name="`options[${index}][option_label]`" :value="option.label">
                                                    <div class="w-8 flex-shrink-0 pt-2 text-sm font-medium text-[#5A5A5A]" x-text="option.label + '.'"></div>
                                                    <input type="text" :name="`options[${index}][option_text]`" x-model="option.text" placeholder="Teks jawaban..." class="flex-1 rounded-md border border-[#D8D8D8] px-3 py-2 text-sm text-[#080808] focus:border-[#080808] focus:outline-none focus:ring-1 focus:ring-[#080808]">
                                                    <label class="flex items-center gap-1 pt-2 text-sm text-[#5A5A5A] whitespace-nowrap cursor-pointer">
                                                        <input type="radio" name="correct_option" :value="index" @change="option.is_correct = true" :checked="option.is_correct" class="accent-[#080808]">
                                                        Benar
                                                    </label>
                                                    <input type="hidden" :name="`options[${index}][is_correct]`" :value="option.is_correct ? '1' : '0'">
                                                    <x-ui.button type="button" variant="ghost" size="sm" @click="removeOption(index)" class="text-[#EE1D36] pt-1" x-show="questionOptions.length > 2">
                                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                                        </svg>
                                                    </x-ui.button>
                                                </div>
                                            </template>
                                        </div>
                                        <p class="mt-1 text-xs text-[#898989]">Minimal 2 opsi. Pilih radio "Benar" untuk kunci jawaban.</p>
                                    </div>
                                </form>
                            </div>
                            <div class="flex items-center justify-end gap-3 border-t border-[#D8D8D8] px-6 py-4">
                                <x-ui.button type="button" variant="secondary" @click="showQuestionModal = false">Batal</x-ui.button>
                                <x-ui.button type="submit" form="pretest-question-form" variant="primary">Simpan</x-ui.button>
                            </div>
                        </div>
                    </div>

                    {{-- Delete Question Modal --}}
                    <div x-show="showDeleteQuestionModal" x-cloak @keydown.escape.window="showDeleteQuestionModal = false" class="fixed inset-0 z-50 flex items-center justify-center p-4">
                        <div x-show="showDeleteQuestionModal" x-transition:enter="transition-opacity ease-linear duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="showDeleteQuestionModal = false" class="fixed inset-0 bg-black/50"></div>
                        <div x-show="showDeleteQuestionModal" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="relative w-full max-w-lg rounded-lg border border-[#D8D8D8] bg-white p-6 shadow-lg">
                            <h3 class="text-base font-semibold text-[#080808]">Hapus Soal Permanen?</h3>
                            <p x-text="deleteQuestionMessage" class="mt-1 text-sm text-[#5A5A5A]"></p>
                            <p class="mt-2 text-xs text-[#898989]">Soal yang dihapus tidak dapat dikembalikan.</p>
                            <div class="mt-6 flex items-center justify-end gap-3">
                                <x-ui.button type="button" variant="secondary" @click="showDeleteQuestionModal = false">Batal</x-ui.button>
                                <form :action="deleteQuestionAction" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <x-ui.button type="submit" variant="danger">Hapus Permanen</x-ui.button>
                                </form>
                            </div>
                        </div>
                    </div>
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
        {{-- Toast notifications for flash messages --}}
        @if(session('success'))
            <div x-data x-init="$store.toast?.success('{{ session('success') }}')" class="hidden"></div>
        @endif
        @if(session('error'))
            <div x-data x-init="$store.toast?.error('{{ session('error') }}')" class="hidden"></div>
        @endif
        @if($errors->any())
            @php
                $firstError = collect($errors->all())->first();
            @endphp
            <div x-data x-init="$store.toast?.error('{{ addslashes($firstError) }}')" class="hidden"></div>
        @endif

        {{-- Alpine.js material modal data --}}
        <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('materialForm', () => ({
                showAddModal: false,
                showEditModal: false,
                showDeleteModal: false,
                materialType: 'file',
                editMaterialType: 'file',
                editAction: '',
                deleteAction: '',
                deleteMessage: '',
                materials: @json($training->materials->values()),
                initAddForm() {
                    this.materialType = 'file';
                },
                openEditModal(index) {
                    const material = this.materials[index];
                    this.editMaterialType = material.type;
                    this.editAction = `/admin/training/${material.training_id}/materials/${material.id}`;

                    this.$nextTick(() => {
                        const form = document.getElementById('edit-material-form');
                        if (form) {
                            form.querySelector('[name="title"]').value = material.title;
                            form.querySelector('[name="type"]').value = material.type;
                            form.querySelector('[name="description"]').value = material.description || '';
                            form.querySelector('[name="url"]').value = material.url || '';
                            form.querySelector('[name="sort_order"]').value = material.sort_order || 0;
                            form.querySelector('[name="is_downloadable"]').value = material.is_downloadable ? '1' : '0';
                            form.querySelector('[name="is_required"]').value = material.is_required ? '1' : '0';
                            form.querySelector('[name="status"]').value = material.status;
                        }
                    });

                    this.showEditModal = true;
                },
                openDeleteModal(index) {
                    const material = this.materials[index];
                    this.deleteAction = `/admin/training/${material.training_id}/materials/${material.id}`;
                    this.deleteMessage = `"${material.title}" akan dihapus permanen dan tidak dapat dikembalikan.`;
                    this.showDeleteModal = true;
                }
            }));

            Alpine.data('preTestForm', () => ({
                showSettingModal: false,
                settingFormMode: 'create',
                settingFormAction: '',
                showQuestionModal: false,
                questionFormMode: 'create',
                questionFormAction: '',
                questionType: 'multiple_choice',
                questionOptions: [],
                showDeleteQuestionModal: false,
                deleteQuestionAction: '',
                deleteQuestionMessage: '',
                questions: @json($questions ?? collect()),
                preTest: @json($preTest),

                init() {
                    @if($preTest)
                        this.settingFormMode = 'edit';
                        this.settingFormAction = `/admin/training/{{ $training->id }}/pre-test/{{ $preTest->id }}`;
                    @else
                        this.settingFormMode = 'create';
                        this.settingFormAction = `/admin/training/{{ $training->id }}/pre-test`;
                    @endif
                },

                openAddQuestionModal() {
                    this.questionFormMode = 'create';
                    this.questionType = 'multiple_choice';
                    this.questionOptions = [
                        { label: 'A', text: '', is_correct: true },
                        { label: 'B', text: '', is_correct: false },
                        { label: 'C', text: '', is_correct: false },
                        { label: 'D', text: '', is_correct: false },
                    ];
                    this.questionFormAction = `/admin/training/{{ $training->id }}/pre-test/{{ $preTest->id }}/questions`;
                    this.$nextTick(() => {
                        const form = document.getElementById('pretest-question-form');
                        if (form) {
                            form.querySelector('[name="question_text"]').value = '';
                            form.querySelector('[name="question_type"]').value = 'multiple_choice';
                            form.querySelector('[name="score"]').value = '';
                            form.querySelector('[name="sort_order"]').value = this.questions.length;
                            form.querySelector('[name="status"]').value = 'active';
                        }
                    });
                    this.showQuestionModal = true;
                },

                openEditQuestionModal(index) {
                    const question = this.questions[index];
                    this.questionFormMode = 'edit';
                    this.questionType = question.question_type;
                    this.questionFormAction = `/admin/training/{{ $training->id }}/pre-test/{{ $preTest->id }}/questions/${question.id}`;

                    const existingOptions = (question.options || []).map(opt => ({
                        label: opt.option_label,
                        text: opt.option_text,
                        is_correct: opt.is_correct,
                    }));

                    this.questionOptions = existingOptions.length >= 2
                        ? existingOptions
                        : [
                            { label: 'A', text: '', is_correct: true },
                            { label: 'B', text: '', is_correct: false },
                        ];

                    this.$nextTick(() => {
                        const form = document.getElementById('pretest-question-form');
                        if (form) {
                            form.querySelector('[name="question_text"]').value = question.question_text;
                            form.querySelector('[name="question_type"]').value = question.question_type;
                            form.querySelector('[name="score"]').value = question.score;
                            form.querySelector('[name="sort_order"]').value = question.sort_order;
                            form.querySelector('[name="status"]').value = question.status;

                            // Clear and repopulate hidden option inputs
                            form.querySelectorAll('input[name^="options["]').forEach(el => el.remove());
                            this.questionOptions.forEach((opt, i) => {
                                const labelInput = document.createElement('input');
                                labelInput.type = 'hidden';
                                labelInput.name = `options[${i}][option_label]`;
                                labelInput.value = opt.label;
                                form.querySelector('#pretest-question-form').appendChild(labelInput);

                                const textInput = document.createElement('input');
                                textInput.type = 'hidden';
                                textInput.name = `options[${i}][option_text]`;
                                textInput.value = opt.text;
                                form.appendChild(textInput);

                                const correctInput = document.createElement('input');
                                correctInput.type = 'hidden';
                                correctInput.name = `options[${i}][is_correct]`;
                                correctInput.value = opt.is_correct ? '1' : '0';
                                form.appendChild(correctInput);
                            });

                            // Set correct radio
                            const correctIdx = this.questionOptions.findIndex(o => o.is_correct);
                            if (correctIdx >= 0) {
                                form.querySelectorAll('input[name="correct_option"]').forEach((radio, i) => {
                                    radio.checked = (i === correctIdx);
                                });
                            }
                        }
                    });

                    this.showQuestionModal = true;
                },

                openDeleteQuestionModal(index) {
                    const question = this.questions[index];
                    this.deleteQuestionAction = `/admin/training/{{ $training->id }}/pre-test/{{ $preTest->id }}/questions/${question.id}`;
                    this.deleteQuestionMessage = `"${question.question_text.substring(0, 60)}..." akan dihapus permanen.`;
                    this.showDeleteQuestionModal = true;
                },

                addOption() {
                    const labels = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                    const nextLabel = labels[this.questionOptions.length] || (this.questionOptions.length + 1);
                    this.questionOptions.push({
                        label: nextLabel,
                        text: '',
                        is_correct: false,
                    });
                },

                removeOption(index) {
                    this.questionOptions.splice(index, 1);
                    // Re-assign labels
                    const labels = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                    this.questionOptions.forEach((opt, i) => {
                        opt.label = labels[i] || (i + 1);
                    });
                },
            }));
        });
        </script>
    </div>
@endsection
