@extends('layouts.admin', ['title' => 'Monitoring Progress'])

@section('content')
    <div class="space-y-6">
        <x-ui.page-header title="Monitoring Progress" subtitle="Pantau progress training karyawan." />

        <x-ui.card>
            <div class="py-12 text-center">
                <svg class="mx-auto h-12 w-12 text-[#898989]" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />
                </svg>
                <h3 class="mt-4 text-sm font-semibold text-[#080808]">Belum ada data monitoring</h3>
                <p class="mt-1 text-sm text-[#5A5A5A]">Monitoring Progress akan diimplementasikan di Session 5.</p>
            </div>
        </x-ui.card>
    </div>
@endsection
