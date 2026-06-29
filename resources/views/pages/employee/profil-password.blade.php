@extends('layouts.employee', ['title' => 'Profil & Password'])

@section('content')
    <div class="space-y-6">
        <x-ui.page-header title="Profil & Password" subtitle="Kelola profil dan password akun Anda." />

        <x-ui.card>
            <div class="py-12 text-center">
                <svg class="mx-auto h-12 w-12 text-[#898989]" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                </svg>
                <h3 class="mt-4 text-sm font-semibold text-[#080808]">Pengaturan Profil & Password</h3>
                <p class="mt-1 text-sm text-[#5A5A5A]">Fitur Profil & Password akan diimplementasikan di Session 5.</p>
            </div>
        </x-ui.card>
    </div>
@endsection
