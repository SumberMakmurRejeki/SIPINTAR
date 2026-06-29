@extends('layouts.admin', ['title' => 'Edit Training'])

@section('content')
    <div class="space-y-6">
        <x-ui.page-header title="Edit Training" :subtitle="$training->title" />

        <x-ui.breadcrumb :items="[
            'Data Training' => route('admin.training.index'),
            $training->title => route('admin.training.show', $training),
            'Edit Training' => route('admin.training.edit', $training),
        ]" />

        <x-ui.card>
            <form method="POST" action="{{ route('admin.training.update', $training) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <x-ui.form-section title="Informasi Training">
                    @include('pages::admin.training._form')
                </x-ui.form-section>

                <div class="flex flex-wrap gap-3">
                    <x-ui.button type="submit" variant="primary">Simpan</x-ui.button>
                    <x-ui.button href="{{ route('admin.training.show', $training) }}" variant="secondary">Batal</x-ui.button>
                </div>
            </form>
        </x-ui.card>
    </div>
@endsection
