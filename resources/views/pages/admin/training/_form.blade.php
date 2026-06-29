<div class="grid gap-4 sm:grid-cols-2">
    <x-ui.input name="title" label="Judul Training" value="{{ $training->title ?? '' }}" required placeholder="Masukkan judul training" />

    <x-ui.select name="status" label="Status" required>
        <option value="draft" @selected(old('status', $training->status ?? 'draft') === 'draft')>Draft</option>
        <option value="published" @selected(old('status', $training->status ?? 'draft') === 'published')>Published</option>
        <option value="closed" @selected(old('status', $training->status ?? 'draft') === 'closed')>Closed</option>
        <option value="archived" @selected(old('status', $training->status ?? 'draft') === 'archived')>Archived</option>
    </x-ui.select>
</div>

<x-ui.textarea name="description" label="Deskripsi" placeholder="Penjelasan training (opsional)">{{ old('description', $training->description ?? '') }}</x-ui.textarea>

<div class="grid gap-4 sm:grid-cols-2">
    <x-ui.input name="start_date" type="date" label="Tanggal Mulai" value="{{ old('start_date', $training->start_date?->format('Y-m-d') ?? '') }}" />
    <x-ui.input name="end_date" type="date" label="Tanggal Selesai" value="{{ old('end_date', $training->end_date?->format('Y-m-d') ?? '') }}" helper="Tidak boleh sebelum tanggal mulai" />
</div>

<div class="grid gap-4 sm:grid-cols-2">
    <x-ui.input name="passing_grade" type="number" label="Passing Grade" value="{{ old('passing_grade', $training->passing_grade ?? '') }}" placeholder="0-100" helper="Nilai minimal kelulusan post-test (0-100)" />
    <x-ui.input name="max_attempts" type="number" label="Batas Percobaan Post-Test" value="{{ old('max_attempts', $training->max_attempts ?? '') }}" placeholder="Minimal 1" helper="Jumlah maksimal mengulang post-test" />
</div>
