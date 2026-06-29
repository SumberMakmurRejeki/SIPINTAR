@extends('layouts.admin', ['title' => 'Edit Jabatan'])

@section('content')
    <div class="space-y-6">
        <x-ui.page-header title="Edit Jabatan" />

        <x-ui.breadcrumb :items="[
            'Master Data' => route('admin.master.jabatan.index'),
            'Edit Jabatan' => route('admin.master.jabatan.edit', $jabatan),
        ]" />

        <x-ui.card>
            <form method="POST" action="{{ route('admin.master.jabatan.update', $jabatan) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <x-ui.form-section title="Informasi Jabatan">
                    <div class="space-y-4">
                        <x-ui.input
                            name="name"
                            label="Nama Jabatan"
                            :value="$jabatan->name"
                            required
                        />

                        <x-ui.textarea
                            name="description"
                            label="Deskripsi"
                            rows="3"
                        >{{ old('description', $jabatan->description) }}</x-ui.textarea>

                        @php
                            $status = old('status', $jabatan->status);
                        @endphp

                        <x-ui.select
                            name="status"
                            label="Status"
                            placeholder="Pilih status"
                            required
                        >
                            <option value="active" @selected($status === 'active')>Aktif</option>
                            <option value="inactive" @selected($status === 'inactive')>Nonaktif</option>
                        </x-ui.select>
                    </div>
                </x-ui.form-section>

                <div class="flex flex-wrap gap-3">
                    <x-ui.button type="submit" variant="primary">Simpan Perubahan</x-ui.button>
                    <x-ui.button href="{{ route('admin.master.jabatan.index') }}" variant="secondary">Batal</x-ui.button>
                </div>
            </form>
        </x-ui.card>
    </div>
@endsection
