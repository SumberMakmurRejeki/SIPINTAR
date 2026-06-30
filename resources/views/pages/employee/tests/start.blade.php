@extends('layouts.employee', ['title' => $test->title])

@section('content')
    <div class="space-y-6">
        <div>
            <a href="{{ route('karyawan.training-saya.show', $training) }}" class="inline-flex items-center gap-1 text-[13px] text-[#64748b] hover:text-[#0f172a] transition-colors mb-2">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" /></svg>
                Kembali
            </a>
            <h1 class="text-[28px] font-bold text-[#0f172a] tracking-tight">{{ $test->title }}</h1>
            <p class="mt-1 text-[14px] text-[#64748b]">{{ $test->type === 'pre_test' ? 'Pre-Test' : 'Post-Test' }} - {{ $training->title }}</p>
        </div>

        <x-ui.card>
            <div class="space-y-4">
                @if($test->instruction)
                    <div>
                        <h2 class="text-[15px] font-semibold text-[#0f172a] mb-2">Instruksi</h2>
                        <p class="text-[14px] text-[#64748b] whitespace-pre-line">{{ $test->instruction }}</p>
                    </div>
                @endif

                <div class="grid grid-cols-2 gap-4 sm:grid-cols-3">
                    <div class="rounded-[12px] bg-[#f8fafc] p-4">
                        <p class="text-[12px] text-[#94a3b8] mb-1">Jumlah Soal</p>
                        <p class="text-[18px] font-semibold text-[#0f172a]">{{ $test->questions()->where('status', 'active')->count() }}</p>
                    </div>
                    @if($test->duration_minutes)
                        <div class="rounded-[12px] bg-[#f8fafc] p-4">
                            <p class="text-[12px] text-[#94a3b8] mb-1">Durasi</p>
                            <p class="text-[18px] font-semibold text-[#0f172a]">{{ $test->duration_minutes }} menit</p>
                        </div>
                    @endif
                    @if($test->passing_grade && $test->type === 'post_test')
                        <div class="rounded-[12px] bg-[#f8fafc] p-4">
                            <p class="text-[12px] text-[#94a3b8] mb-1">Passing Grade</p>
                            <p class="text-[18px] font-semibold text-[#0f172a]">{{ $test->passing_grade }}</p>
                        </div>
                    @endif
                </div>

                @if($test->type === 'pre_test')
                    <div class="rounded-[12px] bg-blue-50 p-4">
                        <p class="text-[13px] text-blue-800">Pre-test hanya untuk mengukur kemampuan awal. Setelah selesai, materi training akan terbuka.</p>
                    </div>
                @endif

                @if($hasInProgress)
                    <div class="rounded-[12px] bg-amber-50 p-4">
                        <p class="text-[13px] text-amber-800">Anda memiliki attempt yang belum selesai. Klik "Lanjutkan" untuk melanjutkan pengerjaan.</p>
                    </div>
                @endif
            </div>
        </x-ui.card>

        <div class="flex justify-end gap-3">
            <x-ui.button href="{{ route('karyawan.training-saya.show', $training) }}" variant="secondary">Batal</x-ui.button>
            <form method="POST" action="{{ route('karyawan.training-saya.tests.begin', [$training, $test]) }}">
                @csrf
                <x-ui.button type="submit">{{ $hasInProgress ? 'Lanjutkan' : 'Mulai' }}</x-ui.button>
            </form>
        </div>
    </div>
@endsection
