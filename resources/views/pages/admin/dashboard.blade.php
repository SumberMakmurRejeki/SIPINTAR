@extends('layouts.admin', ['title' => 'Dashboard'])

@section('content')
    <div class="space-y-6">
        <x-ui.page-header title="Dashboard" subtitle="Selamat datang di SI-PINTAR." />

        {{-- Stat cards --}}
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            @foreach($stats as $stat)
            <x-ui.card>
                <a href="{{ $stat['route'] }}" class="block">
                    <p class="text-sm text-[#5A5A5A]">{{ $stat['label'] }}</p>
                    <p class="mt-2 text-3xl font-semibold text-[#080808]">{{ $stat['value'] }}</p>
                </a>
            </x-ui.card>
            @endforeach
        </div>

        {{-- Placeholder content --}}
        <x-ui.card>
            <div class="py-8 text-center">
                <p class="text-sm text-[#5A5A5A]">Statistik dashboard akan ditampilkan setelah data tersedia.</p>
            </div>
        </x-ui.card>
    </div>
@endsection
