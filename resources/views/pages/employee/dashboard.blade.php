@extends('layouts.employee', ['title' => 'Dashboard'])

@section('content')
    <div class="space-y-6">
        <div>
            <h1 class="text-2xl font-semibold text-[#080808]">Dashboard</h1>
            <p class="mt-1 text-sm text-[#5A5A5A]">Selamat datang di SI-PINTAR.</p>
        </div>

        {{-- ponytail: placeholder stats cards, real stats in Session 2 --}}
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @foreach(['Training Saya', 'Dalam Progress', 'Selesai'] as $label)
            <div class="rounded-lg border border-[#D8D8D8] bg-white p-6">
                <p class="text-sm text-[#5A5A5A]">{{ $label }}</p>
                <p class="mt-2 text-3xl font-semibold text-[#080808]">-</p>
            </div>
            @endforeach
        </div>

        <div class="rounded-lg border border-[#D8D8D8] bg-white p-12 text-center">
            <p class="text-sm text-[#5A5A5A]">Halaman dashboard karyawan — placeholder untuk Session 1.</p>
        </div>
    </div>
@endsection
