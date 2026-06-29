@extends('layouts.admin', ['title' => 'Tambah Admin'])

@section('content')
    <div class="space-y-6">
        <x-ui.page-header title="Tambah Admin" />

        <x-ui.breadcrumb :items="[
            'Master Data' => route('admin.master.admin-user.index'),
            'Tambah Admin' => route('admin.master.admin-user.create'),
        ]" />

        <x-ui.card>
            <form method="POST" action="{{ route('admin.master.admin-user.store') }}" class="space-y-6">
                @csrf

                <x-ui.form-section title="Informasi Admin">
                    <div class="space-y-4">
                        <x-ui.input name="name" label="Nama Admin" required />
                        <x-ui.input name="username" label="Username" required />
                        <x-ui.input name="password" label="Password" type="password" required helper="Minimal 6 karakter" />
                        <x-ui.input name="password_confirmation" label="Konfirmasi Password" type="password" required />
                        <x-ui.select name="status" label="Status" required placeholder="Pilih status">
                            <option value="active" @selected(old('status') === 'active')>Aktif</option>
                            <option value="inactive" @selected(old('status') === 'inactive')>Nonaktif</option>
                        </x-ui.select>
                    </div>
                </x-ui.form-section>

                <div class="flex flex-wrap gap-3">
                    <x-ui.button type="submit" variant="primary">Simpan</x-ui.button>
                    <x-ui.button href="{{ route('admin.master.admin-user.index') }}" variant="secondary">Batal</x-ui.button>
                </div>
            </form>
        </x-ui.card>
    </div>
@endsection
