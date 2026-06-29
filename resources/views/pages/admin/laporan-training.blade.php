@extends('layouts.admin', ['title' => 'Laporan Training'])

@section('content')
    <div class="space-y-6">
        <x-ui.page-header title="Laporan Training" subtitle="Generate laporan training dalam format PDF/Excel." />

        <x-ui.card>
            <div class="py-12 text-center">
                <svg class="mx-auto h-12 w-12 text-[#898989]" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                </svg>
                <h3 class="mt-4 text-sm font-semibold text-[#080808]">Belum ada laporan training</h3>
                <p class="mt-1 text-sm text-[#5A5A5A]">Fitur Laporan Training akan diimplementasikan di Session 6.</p>
            </div>
        </x-ui.card>
    </div>
@endsection
