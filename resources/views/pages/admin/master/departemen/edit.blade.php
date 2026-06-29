@extends('layouts.admin', ['title' => 'Edit Departemen'])

@section('content')
    <div class="space-y-6">
        <x-ui.page-header title="Edit Departemen" />

        <x-ui.breadcrumb :items="[
            'Master Data' => route('admin.master.departemen.index'),
            'Edit Departemen' => route('admin.master.departemen.edit', $departemen),
        ]" />

        <x-ui.card>
            <form method="POST" action="{{ route('admin.master.departemen.update', $departemen) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <x-ui.form-section title="Edit Departemen">
                    <div class="space-y-4">
                        <x-ui.input
                            name="name"
                            label="Nama Departemen"
                            :value="$departemen->name"
                            required
                        />

                        <x-ui.textarea
                            name="description"
                            label="Deskripsi"
                            rows="3"
                        >{{ old('description', $departemen->description) }}</x-ui.textarea>

                        @php
                            $status = old('status', $departemen->status);
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
                    <x-ui.button href="{{ route('admin.master.departemen.index') }}" variant="secondary">Batal</x-ui.button>
                </div>
            </form>
        </x-ui.card>
    </div>
@endsection
