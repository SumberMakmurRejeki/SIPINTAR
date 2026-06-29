@extends('layouts.admin', ['title' => 'Tambah Jabatan'])

@section('content')
    <div class="space-y-6">
        <x-ui.page-header title="Tambah Jabatan" />

        <x-ui.breadcrumb :items="[
            'Master Data' => route('admin.master.jabatan.index'),
            'Tambah Jabatan' => route('admin.master.jabatan.create'),
        ]" />

        <x-ui.card>
            <form method="POST" action="{{ route('admin.master.jabatan.store') }}" class="space-y-6">
                @csrf

                <x-ui.form-section title="Informasi Jabatan">
                    <div class="space-y-4">
                        <x-ui.input
                            name="name"
                            label="Nama Jabatan"
                            required
                        />

                        <x-ui.textarea
                            name="description"
                            label="Deskripsi"
                            rows="3"
                        >{{ old('description') }}</x-ui.textarea>

                        <x-ui.select
                            name="status"
                            label="Status"
                            placeholder="Pilih status"
                            required
                        >
                            <option value="active" @selected(old('status') === 'active')>Aktif</option>
                            <option value="inactive" @selected(old('status') === 'inactive')>Nonaktif</option>
                        </x-ui.select>
                    </div>
                </x-ui.form-section>

                <div class="flex flex-wrap gap-3">
                    <x-ui.button type="submit" variant="primary">Simpan</x-ui.button>
                    <x-ui.button href="{{ route('admin.master.jabatan.index') }}" variant="secondary">Batal</x-ui.button>
                </div>
            </form>
        </x-ui.card>
    </div>
@endsection
