@extends('layouts.admin', ['title' => 'Edit Karyawan'])

@section('content')
    <div class="space-y-6">
        <x-ui.page-header title="Edit Karyawan" />

        <x-ui.breadcrumb :items="[
            'Master Data' => route('admin.master.karyawan.index'),
            'Edit Karyawan' => route('admin.master.karyawan.edit', $karyawan),
        ]" />

        <x-ui.card>
            <form method="POST" action="{{ route('admin.master.karyawan.update', $karyawan) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <x-ui.form-section title="Edit Karyawan">
                    <div class="space-y-4">
                        <x-ui.input name="name" label="Nama Karyawan" :value="$karyawan->name" required />
                        <x-ui.input name="username" label="Username" :value="$karyawan->user?->username" required />
                        <x-ui.input name="password" label="Password" type="password" helper="Kosongkan jika tidak ingin mengubah password" />
                        <x-ui.input name="password_confirmation" label="Konfirmasi Password" type="password" />
                        <x-ui.input name="employee_code" label="Kode Karyawan" :value="$karyawan->employee_code" helper="Opsional" />

                        <x-ui.select name="department_id" label="Departemen" placeholder="Pilih departemen" required>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}" @selected((string) old('department_id', $karyawan->department_id) === (string) $department->id)>{{ $department->name }}</option>
                            @endforeach
                        </x-ui.select>

                        <x-ui.select name="position_id" label="Jabatan" placeholder="Pilih jabatan" required>
                            @foreach($positions as $position)
                                <option value="{{ $position->id }}" @selected((string) old('position_id', $karyawan->position_id) === (string) $position->id)>{{ $position->name }}</option>
                            @endforeach
                        </x-ui.select>

                        <x-ui.select name="status" label="Status" placeholder="Pilih status" required>
                            <option value="active" @selected(old('status', $karyawan->status) === 'active')>Aktif</option>
                            <option value="inactive" @selected(old('status', $karyawan->status) === 'inactive')>Nonaktif</option>
                        </x-ui.select>
                    </div>
                </x-ui.form-section>

                <div class="flex flex-wrap gap-3">
                    <x-ui.button type="submit" variant="primary">Simpan Perubahan</x-ui.button>
                    <x-ui.button href="{{ route('admin.master.karyawan.index') }}" variant="secondary">Batal</x-ui.button>
                </div>
            </form>
        </x-ui.card>
    </div>
@endsection
