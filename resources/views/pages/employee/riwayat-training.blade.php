@extends('layouts.employee', ['title' => 'Riwayat Training'])

@section('content')
    <div class="space-y-6">
        <x-ui.page-header title="Riwayat Training" subtitle="Daftar training yang telah Anda selesaikan." />

        <x-ui.card>
            <div class="py-12 text-center">
                <svg class="mx-auto h-12 w-12 text-[#898989]" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
                <h3 class="mt-4 text-sm font-semibold text-[#080808]">Belum ada riwayat training</h3>
                <p class="mt-1 text-sm text-[#5A5A5A]">Anda belum menyelesaikan training apapun.</p>
            </div>
        </x-ui.card>
    </div>
@endsection
