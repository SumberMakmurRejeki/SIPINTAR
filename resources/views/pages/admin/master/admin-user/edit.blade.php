@extends('layouts.admin', ['title' => 'Edit Admin'])

@section('content')
    <div class="space-y-6">
        <x-ui.page-header title="Edit Admin" />

        <x-ui.breadcrumb :items="[
            'Master Data' => route('admin.master.admin-user.index'),
            'Edit Admin' => route('admin.master.admin-user.edit', $adminUser),
        ]" />

        <x-ui.card>
            <form method="POST" action="{{ route('admin.master.admin-user.update', $adminUser) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <x-ui.form-section title="Edit Admin">
                    <div class="space-y-4">
                        <x-ui.input name="name" label="Nama Admin" :value="$adminUser->name" required />
                        <x-ui.input name="username" label="Username" :value="$adminUser->username" required />
                        <x-ui.input name="password" label="Password" type="password" helper="Kosongkan jika tidak ingin mengubah password" />
                        <x-ui.input name="password_confirmation" label="Konfirmasi Password" type="password" />
                        <x-ui.select name="status" label="Status" required placeholder="Pilih status">
                            <option value="active" @selected(old('status', $adminUser->status) === 'active')>Aktif</option>
                            <option value="inactive" @selected(old('status', $adminUser->status) === 'inactive')>Nonaktif</option>
                        </x-ui.select>
                    </div>
                </x-ui.form-section>

                <div class="flex flex-wrap gap-3">
                    <x-ui.button type="submit" variant="primary">Simpan Perubahan</x-ui.button>
                    <x-ui.button href="{{ route('admin.master.admin-user.index') }}" variant="secondary">Batal</x-ui.button>
                </div>
            </form>
        </x-ui.card>
    </div>
@endsection
