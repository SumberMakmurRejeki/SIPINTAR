@extends('layouts.employee', ['title' => $training->title])

@section('content')
    @php
        $progressColors = [
            'not_started' => 'muted',
            'in_progress' => 'info',
            'passed' => 'success',
            'failed' => 'danger',
            'retake' => 'warning',
            'waiting_grading' => 'warning',
        ];
        $progressLabels = [
            'not_started' => 'Belum Mulai',
            'in_progress' => 'Sedang Berjalan',
            'passed' => 'Lulus',
            'failed' => 'Tidak Lulus',
            'retake' => 'Mengulang',
            'waiting_grading' => 'Menunggu Penilaian',
        ];
        $statusColors = [
            'locked' => 'muted',
            'not_started' => 'muted',
            'in_progress' => 'warning',
            'completed' => 'success',
            'accessed' => 'info',
            'failed' => 'danger',
            'retake' => 'warning',
            'waiting_grading' => 'warning',
        ];
        $statusLabels = [
            'locked' => 'Terkunci',
            'not_started' => 'Belum Mulai',
            'in_progress' => 'Sedang Dikerjakan',
            'completed' => 'Selesai',
            'accessed' => 'Sudah Diakses',
            'failed' => 'Tidak Lulus',
            'retake' => 'Mengulang',
            'waiting_grading' => 'Menunggu Penilaian',
        ];

        // Determine primary action
        $primaryAction = null;
        if ($participant->pre_test_status === 'not_started') {
            $primaryAction = ['label' => 'Mulai Pre-Test', 'url' => '#', 'disabled' => true];
        } elseif ($participant->material_status === 'locked') {
            $primaryAction = ['label' => 'Materi Terkunci', 'url' => null, 'disabled' => true];
        } elseif (in_array($participant->material_status, ['not_started', 'accessed'])) {
            $primaryAction = ['label' => 'Buka Materi', 'url' => '#material-section', 'disabled' => false];
        } elseif ($participant->post_test_status === 'locked') {
            $primaryAction = ['label' => 'Post-Test Terkunci', 'url' => null, 'disabled' => true];
        } elseif ($participant->post_test_status === 'not_started') {
            $primaryAction = ['label' => 'Mulai Post-Test', 'url' => '#', 'disabled' => true];
        } elseif ($participant->grading_status === 'waiting') {
            $primaryAction = ['label' => 'Menunggu Penilaian', 'url' => null, 'disabled' => true];
        } elseif ($participant->progress_status === 'passed') {
            $primaryAction = ['label' => 'Lihat Hasil', 'url' => '#result-section', 'disabled' => false];
        } elseif (in_array($participant->progress_status, ['failed', 'retake'])) {
            $primaryAction = ['label' => 'Lihat Hasil', 'url' => '#result-section', 'disabled' => false];
        }
    @endphp

    <div class="space-y-6">
        {{-- Header --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
            <div>
                <a href="{{ route('karyawan.training-saya.index') }}" class="inline-flex items-center gap-1 text-[13px] text-[#64748b] hover:text-[#0f172a] transition-colors mb-2">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" /></svg>
                    Kembali
                </a>
                <h1 class="text-[28px] font-bold text-[#0f172a] tracking-tight">{{ $training->title }}</h1>
                @if($training->description)
                    <p class="mt-1 text-[14px] text-[#64748b]">{{ $training->description }}</p>
                @endif
                <div class="mt-2 flex items-center gap-3 text-[13px] text-[#94a3b8]">
                    <span>{{ $training->start_date->format('d M Y') }} - {{ $training->end_date->format('d M Y') }}</span>
                    <x-ui.badge variant="{{ $progressColors[$participant->progress_status] ?? 'default' }}">
                        {{ $progressLabels[$participant->progress_status] ?? $participant->progress_status }}
                    </x-ui.badge>
                </div>
            </div>
            @if($primaryAction)
                @if($primaryAction['url'] && !$primaryAction['disabled'])
                    <x-ui.button href="{{ $primaryAction['url'] }}" size="md">{{ $primaryAction['label'] }}</x-ui.button>
                @else
                    <x-ui.button disabled size="md">{{ $primaryAction['label'] }}</x-ui.button>
                @endif
            @endif
        </div>

        {{-- Status Overview --}}
        <x-ui.card>
            <h2 class="text-[15px] font-semibold text-[#0f172a] mb-4">Status Training</h2>
            <div class="grid grid-cols-2 gap-4 sm:grid-cols-4">
                <div>
                    <p class="text-[12px] text-[#94a3b8] mb-1">Pre-Test</p>
                    <x-ui.badge variant="{{ $statusColors[$participant->pre_test_status] ?? 'default' }}">
                        {{ $statusLabels[$participant->pre_test_status] ?? $participant->pre_test_status }}
                    </x-ui.badge>
                </div>
                <div>
                    <p class="text-[12px] text-[#94a3b8] mb-1">Materi</p>
                    <x-ui.badge variant="{{ $statusColors[$participant->material_status] ?? 'default' }}">
                        {{ $statusLabels[$participant->material_status] ?? $participant->material_status }}
                    </x-ui.badge>
                </div>
                <div>
                    <p class="text-[12px] text-[#94a3b8] mb-1">Post-Test</p>
                    <x-ui.badge variant="{{ $statusColors[$participant->post_test_status] ?? 'default' }}">
                        {{ $statusLabels[$participant->post_test_status] ?? $participant->post_test_status }}
                    </x-ui.badge>
                </div>
                <div>
                    <p class="text-[12px] text-[#94a3b8] mb-1">Penilaian</p>
                    <x-ui.badge variant="{{ $statusColors[$participant->grading_status] ?? 'default' }}">
                        {{ $statusLabels[$participant->grading_status] ?? $participant->grading_status }}
                    </x-ui.badge>
                </div>
            </div>
        </x-ui.card>

        {{-- Pre-Test Section --}}
        <x-ui.card>
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-[15px] font-semibold text-[#0f172a]">Pre-Test</h2>
                    <p class="text-[13px] text-[#64748b] mt-0.5">{{ $training->preTest->title ?? 'Belum ada pre-test' }}</p>
                </div>
                @if($participant->pre_test_status === 'not_started')
                    <x-ui.button size="sm" disabled>Mulai Pre-Test</x-ui.button>
                @elseif($participant->pre_test_status === 'completed')
                    <x-ui.badge variant="success">Selesai</x-ui.badge>
                @else
                    <x-ui.badge variant="{{ $statusColors[$participant->pre_test_status] ?? 'default' }}">
                        {{ $statusLabels[$participant->pre_test_status] ?? $participant->pre_test_status }}
                    </x-ui.badge>
                @endif
            </div>
        </x-ui.card>

        {{-- Material Section --}}
        <x-ui.card id="material-section">
            <h2 class="text-[15px] font-semibold text-[#0f172a] mb-4">Materi Training</h2>

            @if($participant->material_status === 'locked')
                <div class="py-8 text-center">
                    <svg class="mx-auto h-10 w-10 text-[#94a3b8]" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                    </svg>
                    <p class="mt-2 text-[13px] text-[#64748b]">Selesaikan pre-test terlebih dahulu untuk membuka materi.</p>
                </div>
            @elseif($training->materials->isEmpty())
                <x-ui.empty-state title="Belum ada materi" description="Materi training belum tersedia." />
            @else
                <div class="space-y-3">
                    @foreach($training->materials as $material)
                        @php
                            $isAccessed = in_array($material->id, $accessedMaterialIds);
                        @endphp
                        <div class="flex items-center justify-between gap-3 rounded-[12px] border border-[#e2e8f0] p-4 {{ $isAccessed ? 'bg-green-50/50' : '' }}">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg {{ $material->type === 'link' ? 'bg-blue-50' : 'bg-gray-50' }}">
                                    @if($material->type === 'link')
                                        <svg class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m13.35-.622l1.757-1.757a4.5 4.5 0 00-6.364-6.364l-4.5 4.5a4.5 4.5 0 001.242 7.244" /></svg>
                                    @else
                                        <svg class="h-5 w-5 text-[#64748b]" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" /></svg>
                                    @endif
                                </div>
                                <div class="min-w-0">
                                    <p class="text-[14px] font-medium text-[#0f172a] truncate">{{ $material->title }}</p>
                                    <div class="flex items-center gap-2 text-[12px] text-[#94a3b8]">
                                        <span>{{ strtoupper($material->type === 'file' ? ($material->file_type ?? 'FILE') : 'LINK') }}</span>
                                        @if($isAccessed)
                                            <x-ui.badge variant="success" size="sm">Diakses</x-ui.badge>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 shrink-0">
                                @if($material->type === 'link')
                                    <form method="POST" action="{{ route('karyawan.training-saya.materials.access', [$training, $material]) }}">
                                        @csrf
                                        <x-ui.button type="submit" size="sm" variant="secondary">Buka</x-ui.button>
                                    </form>
                                @else
                                    <form method="POST" action="{{ route('karyawan.training-saya.materials.access', [$training, $material]) }}">
                                        @csrf
                                        <x-ui.button type="submit" size="sm" variant="secondary">Buka</x-ui.button>
                                    </form>
                                    @if($material->is_downloadable)
                                        <x-ui.button href="{{ route('karyawan.training-saya.materials.download', [$training, $material]) }}" size="sm" variant="ghost">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" /></svg>
                                        </x-ui.button>
                                    @endif
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </x-ui.card>

        {{-- Post-Test Section --}}
        <x-ui.card>
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-[15px] font-semibold text-[#0f172a]">Post-Test</h2>
                    <p class="text-[13px] text-[#64748b] mt-0.5">{{ $training->postTest->title ?? 'Belum ada post-test' }}</p>
                </div>
                @if($participant->post_test_status === 'locked')
                    <x-ui.badge variant="muted">Terkunci</x-ui.badge>
                @elseif($participant->post_test_status === 'not_started')
                    <x-ui.button size="sm" disabled>Mulai Post-Test</x-ui.button>
                @elseif($participant->post_test_status === 'completed')
                    <x-ui.badge variant="success">Selesai</x-ui.badge>
                @else
                    <x-ui.badge variant="{{ $statusColors[$participant->post_test_status] ?? 'default' }}">
                        {{ $statusLabels[$participant->post_test_status] ?? $participant->post_test_status }}
                    </x-ui.badge>
                @endif
            </div>
        </x-ui.card>

        {{-- Result Section --}}
        @if(in_array($participant->progress_status, ['passed', 'failed', 'retake', 'waiting_grading']))
            <x-ui.card id="result-section">
                <h2 class="text-[15px] font-semibold text-[#0f172a] mb-4">Hasil</h2>
                <div class="grid grid-cols-2 gap-4 sm:grid-cols-3">
                    <div>
                        <p class="text-[12px] text-[#94a3b8]">Nilai Pre-Test</p>
                        <p class="text-[18px] font-semibold text-[#0f172a]">{{ $participant->pre_test_score ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-[12px] text-[#94a3b8]">Nilai Post-Test</p>
                        <p class="text-[18px] font-semibold text-[#0f172a]">{{ $participant->post_test_score ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-[12px] text-[#94a3b8]">Nilai Akhir</p>
                        <p class="text-[18px] font-semibold text-[#0f172a]">{{ $participant->final_score ?? '-' }}</p>
                    </div>
                </div>
            </x-ui.card>
        @endif
    </div>
@endsection
