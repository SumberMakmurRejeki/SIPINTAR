@extends('layouts.employee', ['title' => 'Training Saya'])

@section('content')
    <div class="space-y-6">
        <x-ui.page-header title="Training Saya" subtitle="Daftar training yang ditugaskan kepada Anda." />

        {{-- Filters --}}
        <x-ui.card>
            <form method="GET" class="flex flex-col gap-3 sm:flex-row sm:items-end">
                <div class="flex-1">
                    <x-ui.input name="search" placeholder="Cari judul training..." value="{{ request('search') }}" />
                </div>
                <div class="w-full sm:w-48">
                    <x-ui.select name="status" placeholder="Semua Status">
                        @foreach(['not_started' => 'Belum Mulai', 'in_progress' => 'Sedang Berjalan', 'passed' => 'Lulus', 'failed' => 'Tidak Lulus', 'retake' => 'Mengulang'] as $value => $label)
                            <option value="{{ $value }}" {{ request('status') === $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </x-ui.select>
                </div>
                <x-ui.button type="submit" variant="secondary" size="md">Cari</x-ui.button>
            </form>
        </x-ui.card>

        {{-- Training list --}}
        @if($trainings->isEmpty())
            <x-ui.empty-state
                title="Belum ada training"
                description="Anda belum memiliki training yang ditugaskan."
            />
        @else
            <div class="grid gap-4">
                @foreach($trainings as $training)
                    @php
                        $participant = $training->trainingParticipants->first();
                        $progressColors = [
                            'not_started' => 'muted',
                            'in_progress' => 'info',
                            'passed' => 'success',
                            'failed' => 'danger',
                            'retake' => 'warning',
                        ];
                        $progressLabels = [
                            'not_started' => 'Belum Mulai',
                            'in_progress' => 'Sedang Berjalan',
                            'passed' => 'Lulus',
                            'failed' => 'Tidak Lulus',
                            'retake' => 'Mengulang',
                        ];
                    @endphp
                    <x-ui.card class="hover:shadow-sm transition-shadow">
                        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <h3 class="text-[15px] font-semibold text-[#0f172a] truncate">{{ $training->title }}</h3>
                                    <x-ui.badge variant="{{ $progressColors[$participant->progress_status] ?? 'default' }}">
                                        {{ $progressLabels[$participant->progress_status] ?? $participant->progress_status }}
                                    </x-ui.badge>
                                </div>
                                @if($training->description)
                                    <p class="mt-1 text-[13px] text-[#64748b] line-clamp-2">{{ Str::limit($training->description, 120) }}</p>
                                @endif
                                <div class="mt-2 flex items-center gap-3 text-[12px] text-[#94a3b8] flex-wrap">
                                    <span>{{ $training->start_date->format('d M Y') }} - {{ $training->end_date->format('d M Y') }}</span>
                                    @if($participant->final_score)
                                        <span>Nilai: {{ $participant->final_score }}</span>
                                    @endif
                                </div>
                                {{-- Status indicators --}}
                                <div class="mt-2 flex items-center gap-2 flex-wrap">
                                    @php
                                        $statusFields = [
                                            ['field' => 'pre_test_status', 'label' => 'Pre-Test', 'colors' => ['locked' => 'muted', 'not_started' => 'muted', 'in_progress' => 'warning', 'completed' => 'success']],
                                            ['field' => 'material_status', 'label' => 'Materi', 'colors' => ['locked' => 'muted', 'not_started' => 'muted', 'accessed' => 'info', 'completed' => 'success']],
                                            ['field' => 'post_test_status', 'label' => 'Post-Test', 'colors' => ['locked' => 'muted', 'not_started' => 'muted', 'in_progress' => 'warning', 'completed' => 'success', 'failed' => 'danger', 'retake' => 'warning']],
                                        ];
                                    @endphp
                                    @foreach($statusFields as $sf)
                                        <x-ui.badge variant="{{ $sf['colors'][$participant->{$sf['field']}] ?? 'default' }}">{{ $sf['label'] }}</x-ui.badge>
                                    @endforeach
                                </div>
                            </div>
                            <div class="shrink-0">
                                <x-ui.button href="{{ route('karyawan.training-saya.show', $training) }}" size="sm">
                                    Detail
                                </x-ui.button>
                            </div>
                        </div>
                    </x-ui.card>
                @endforeach
            </div>

            @if($trainings->hasPages())
                <div class="mt-4">
                    {{ $trainings->links() }}
                </div>
            @endif
        @endif
    </div>
@endsection
