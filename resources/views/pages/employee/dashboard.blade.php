@extends('layouts.employee', ['title' => 'Dashboard'])

@section('content')
    <div class="space-y-6">
        <x-ui.page-header title="Dashboard" subtitle="Selamat datang, {{ auth()->user()->name }}." />

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @foreach([
                ['label' => 'Training Aktif', 'value' => '-'],
                ['label' => 'Training Selesai', 'value' => '-'],
                ['label' => 'Menunggu Penilaian', 'value' => '-'],
            ] as $stat)
            <x-ui.card>
                <p class="text-sm text-[#5A5A5A]">{{ $stat['label'] }}</p>
                <p class="mt-2 text-3xl font-semibold text-[#080808]">{{ $stat['value'] }}</p>
            </x-ui.card>
            @endforeach
        </div>

        <x-ui.card>
            <div class="py-8 text-center">
                <p class="text-sm text-[#5A5A5A]">Anda belum memiliki training yang ditugaskan.</p>
            </div>
        </x-ui.card>
    </div>
@endsection
