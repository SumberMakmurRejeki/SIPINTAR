@extends('layouts.employee', ['title' => 'Training Saya'])

@section('content')
    <div class="space-y-6">
        <x-ui.page-header title="Training Saya" subtitle="Daftar training yang ditugaskan kepada Anda." />

        <x-ui.card>
            <div class="py-12 text-center">
                <svg class="mx-auto h-12 w-12 text-[#898989]" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                </svg>
                <h3 class="mt-4 text-sm font-semibold text-[#080808]">Belum ada training</h3>
                <p class="mt-1 text-sm text-[#5A5A5A]">Anda belum memiliki training yang ditugaskan.</p>
            </div>
        </x-ui.card>
    </div>
@endsection
