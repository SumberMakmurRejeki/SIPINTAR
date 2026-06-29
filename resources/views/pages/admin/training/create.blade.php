@extends('layouts.admin', ['title' => 'Tambah Training'])

@section('content')
    <div class="space-y-6">
        <x-ui.page-header title="Tambah Training" subtitle="Buat training baru untuk perusahaan." />

        <x-ui.breadcrumb :items="[
            'Data Training' => route('admin.training.index'),
            'Tambah Training' => route('admin.training.create'),
        ]" />

        @php
            $training = new \App\Models\Training(['status' => 'draft']);
        @endphp

        <x-ui.card>
            <form method="POST" action="{{ route('admin.training.store') }}" class="space-y-6">
                @csrf

                <x-ui.form-section title="Informasi Training">
                    @include('pages::admin.training._form')
                </x-ui.form-section>

                <div class="flex flex-wrap gap-3">
                    <x-ui.button type="submit" variant="primary">Simpan</x-ui.button>
                    <x-ui.button href="{{ route('admin.training.index') }}" variant="secondary">Batal</x-ui.button>
                </div>
            </form>
        </x-ui.card>
    </div>
@endsection
