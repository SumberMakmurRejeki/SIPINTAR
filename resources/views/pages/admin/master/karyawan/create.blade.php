@extends('layouts.admin', ['title' => 'Tambah Karyawan'])

@section('content')
    <div class="space-y-6">
        <x-ui.page-header title="Tambah Karyawan" />

        <x-ui.breadcrumb :items="[
            'Master Data' => route('admin.master.karyawan.index'),
            'Tambah Karyawan' => route('admin.master.karyawan.create'),
        ]" />

        <x-ui.card>
            <form method="POST" action="{{ route('admin.master.karyawan.store') }}" class="space-y-6">
                @csrf

                <x-ui.form-section title="Informasi Karyawan">
                    <div class="space-y-4">
                        <x-ui.input name="name" label="Nama Karyawan" required />
                        <x-ui.input name="username" label="Username" required helper="Username untuk login" />
                        <x-ui.input name="password" label="Password" type="password" required helper="Minimal 6 karakter" />
                        <x-ui.input name="password_confirmation" label="Konfirmasi Password" type="password" required />
                        <x-ui.input name="employee_code" label="Kode Karyawan" helper="Opsional" />

                        <x-ui.select name="department_id" label="Departemen" placeholder="Pilih departemen" required>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}" @selected(old('department_id') == $department->id)>{{ $department->name }}</option>
                            @endforeach
                        </x-ui.select>

                        <x-ui.select name="position_id" label="Jabatan" placeholder="Pilih jabatan" required>
                            @foreach($positions as $position)
                                <option value="{{ $position->id }}" @selected(old('position_id') == $position->id)>{{ $position->name }}</option>
                            @endforeach
                        </x-ui.select>

                        <x-ui.select name="status" label="Status" placeholder="Pilih status" required>
                            <option value="active" @selected(old('status') === 'active')>Aktif</option>
                            <option value="inactive" @selected(old('status') === 'inactive')>Nonaktif</option>
                        </x-ui.select>
                    </div>
                </x-ui.form-section>

                <div class="flex flex-wrap gap-3">
                    <x-ui.button type="submit" variant="primary">Simpan</x-ui.button>
                    <x-ui.button href="{{ route('admin.master.karyawan.index') }}" variant="secondary">Batal</x-ui.button>
                </div>
            </form>
        </x-ui.card>
    </div>
@endsection
