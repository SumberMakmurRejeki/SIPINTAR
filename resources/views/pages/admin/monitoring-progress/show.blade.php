@extends('layouts.admin', ['title' => 'Detail Progress'])

@section('content')
    @php
        $progressBadges = [
            'not_started' => ['variant' => 'muted', 'label' => 'Belum Mulai'],
            'in_progress' => ['variant' => 'info', 'label' => 'Sedang Berjalan'],
            'waiting_grading' => ['variant' => 'warning', 'label' => 'Menunggu Nilai'],
            'passed' => ['variant' => 'success', 'label' => 'Lulus'],
            'remedial' => ['variant' => 'orange', 'label' => 'Remedial'],
            'failed' => ['variant' => 'danger', 'label' => 'Gagal'],
        ];

        $statusBadges = [
            'locked' => ['variant' => 'muted', 'label' => 'Terkunci'],
            'not_started' => ['variant' => 'muted', 'label' => 'Belum Mulai'],
            'in_progress' => ['variant' => 'info', 'label' => 'Sedang Berjalan'],
            'accessed' => ['variant' => 'info', 'label' => 'Diakses'],
            'waiting_grading' => ['variant' => 'warning', 'label' => 'Menunggu Nilai'],
            'waiting' => ['variant' => 'warning', 'label' => 'Menunggu'],
            'completed' => ['variant' => 'success', 'label' => 'Selesai'],
            'graded' => ['variant' => 'success', 'label' => 'Dinilai'],
            'passed' => ['variant' => 'success', 'label' => 'Lulus'],
            'remedial' => ['variant' => 'orange', 'label' => 'Remedial'],
            'failed' => ['variant' => 'danger', 'label' => 'Gagal'],
            'retake' => ['variant' => 'orange', 'label' => 'Remedial'],
            'none' => ['variant' => 'muted', 'label' => '-'],
            'auto_submitted' => ['variant' => 'warning', 'label' => 'Auto Submit'],
            'submitted' => ['variant' => 'info', 'label' => 'Dikumpulkan'],
        ];

        $progress = $progressBadges[$participant->progress_status] ?? ['variant' => 'muted', 'label' => $participant->progress_status];
        $preTest = $statusBadges[$participant->pre_test_status] ?? ['variant' => 'muted', 'label' => $participant->pre_test_status];
        $material = $statusBadges[$participant->material_status] ?? ['variant' => 'muted', 'label' => $participant->material_status];
        $postTest = $statusBadges[$participant->post_test_status] ?? ['variant' => 'muted', 'label' => $participant->post_test_status];
        $grading = $statusBadges[$participant->grading_status] ?? ['variant' => 'muted', 'label' => $participant->grading_status];
    @endphp

    <div class="space-y-6">
        <x-ui.page-header title="Detail Progress" subtitle="Lihat detail progress training karyawan.">
            <x-slot:actions>
                <x-ui.button href="{{ route('admin.monitoring-progress.index') }}" variant="secondary">Kembali</x-ui.button>
            </x-slot:actions>
        </x-ui.page-header>

        {{-- Summary Card --}}
        <x-ui.card>
            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                <div>
                    <div class="text-xs font-medium text-[#5A5A5A]">Nama Karyawan</div>
                    <div class="mt-1 text-sm font-semibold text-[#080808]">{{ $participant->employee?->name ?? '-' }}</div>
                </div>
                <div>
                    <div class="text-xs font-medium text-[#5A5A5A]">Departemen</div>
                    <div class="mt-1 text-sm font-semibold text-[#080808]">{{ $participant->employee?->department?->name ?? '-' }}</div>
                </div>
                <div>
                    <div class="text-xs font-medium text-[#5A5A5A]">Jabatan</div>
                    <div class="mt-1 text-sm font-semibold text-[#080808]">{{ $participant->employee?->position?->name ?? '-' }}</div>
                </div>
                <div>
                    <div class="text-xs font-medium text-[#5A5A5A]">Training</div>
                    <div class="mt-1 text-sm font-semibold text-[#080808]">{{ $participant->training?->title ?? '-' }}</div>
                </div>
            </div>
        </x-ui.card>

        {{-- Status Cards --}}
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-5">
            <x-ui.card>
                <div class="text-xs font-medium text-[#5A5A5A]">Progress</div>
                <div class="mt-2"><x-ui.badge :variant="$progress['variant']">{{ $progress['label'] }}</x-ui.badge></div>
            </x-ui.card>
            <x-ui.card>
                <div class="text-xs font-medium text-[#5A5A5A]">Pre-Test</div>
                <div class="mt-2"><x-ui.badge :variant="$preTest['variant']">{{ $preTest['label'] }}</x-ui.badge></div>
                @if($participant->pre_test_score !== null)
                    <div class="mt-1 text-sm font-semibold text-[#080808]">Nilai: {{ number_format((float) $participant->pre_test_score, 2) }}</div>
                @endif
            </x-ui.card>
            <x-ui.card>
                <div class="text-xs font-medium text-[#5A5A5A]">Materi</div>
                <div class="mt-2"><x-ui.badge :variant="$material['variant']">{{ $material['label'] }}</x-ui.badge></div>
            </x-ui.card>
            <x-ui.card>
                <div class="text-xs font-medium text-[#5A5A5A]">Post-Test</div>
                <div class="mt-2"><x-ui.badge :variant="$postTest['variant']">{{ $postTest['label'] }}</x-ui.badge></div>
                @if($participant->post_test_score !== null)
                    <div class="mt-1 text-sm font-semibold text-[#080808]">Nilai: {{ number_format((float) $participant->post_test_score, 2) }}</div>
                @endif
            </x-ui.card>
            <x-ui.card>
                <div class="text-xs font-medium text-[#5A5A5A]">Penilaian</div>
                <div class="mt-2"><x-ui.badge :variant="$grading['variant']">{{ $grading['label'] }}</x-ui.badge></div>
                @if($participant->final_score !== null)
                    <div class="mt-1 text-sm font-semibold text-[#080808]">Nilai Akhir: {{ number_format((float) $participant->final_score, 2) }}</div>
                @endif
            </x-ui.card>
        </div>

        {{-- Info Tambahan --}}
        <x-ui.card>
            <div class="grid gap-4 md:grid-cols-3">
                <div>
                    <div class="text-xs font-medium text-[#5A5A5A]">Tanggal Mulai</div>
                    <div class="mt-1 text-sm font-semibold text-[#080808]">{{ $participant->started_at?->format('d M Y H:i') ?? '-' }}</div>
                </div>
                <div>
                    <div class="text-xs font-medium text-[#5A5A5A]">Tanggal Selesai</div>
                    <div class="mt-1 text-sm font-semibold text-[#080808]">{{ $participant->completed_at?->format('d M Y H:i') ?? '-' }}</div>
                </div>
                <div>
                    <div class="text-xs font-medium text-[#5A5A5A]">Training Periode</div>
                    <div class="mt-1 text-sm font-semibold text-[#080808]">
                        {{ $participant->training?->start_date?->format('d M Y') ?? '-' }} - {{ $participant->training?->end_date?->format('d M Y') ?? '-' }}
                    </div>
                </div>
            </div>
        </x-ui.card>

        {{-- Riwayat Attempt Pre-Test --}}
        <x-ui.card>
            <h3 class="mb-4 text-sm font-semibold text-[#080808]">Riwayat Attempt Pre-Test</h3>
            @if($preTestAttempts->isEmpty())
                <x-ui.empty-state title="Belum ada attempt" description="Peserta belum mengerjakan pre-test." />
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-[#D8D8D8] bg-gray-50">
                                <th class="px-3 py-3 text-left font-medium text-[#5A5A5A]">Attempt ke-</th>
                                <th class="px-3 py-3 text-left font-medium text-[#5A5A5A]">Status</th>
                                <th class="px-3 py-3 text-left font-medium text-[#5A5A5A]">Auto Score</th>
                                <th class="px-3 py-3 text-left font-medium text-[#5A5A5A]">Essay Score</th>
                                <th class="px-3 py-3 text-left font-medium text-[#5A5A5A]">Final Score</th>
                                <th class="px-3 py-3 text-left font-medium text-[#5A5A5A]">Mulai</th>
                                <th class="px-3 py-3 text-left font-medium text-[#5A5A5A]">Selesai</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($preTestAttempts as $attempt)
                                @php
                                    $attemptBadge = $statusBadges[$attempt->status] ?? ['variant' => 'muted', 'label' => $attempt->status];
                                @endphp
                                <tr class="border-b border-[#D8D8D8] hover:bg-gray-50">
                                    <td class="px-3 py-3 text-[#080808]">{{ $attempt->attempt_number }}</td>
                                    <td class="px-3 py-3">
                                        <x-ui.badge :variant="$attemptBadge['variant']">{{ $attemptBadge['label'] }}</x-ui.badge>
                                    </td>
                                    <td class="px-3 py-3 text-[#080808]">{{ $attempt->multiple_choice_score !== null ? number_format((float) $attempt->multiple_choice_score, 2) : '-' }}</td>
                                    <td class="px-3 py-3 text-[#080808]">{{ $attempt->essay_score !== null ? number_format((float) $attempt->essay_score, 2) : '-' }}</td>
                                    <td class="px-3 py-3 text-[#080808]">{{ $attempt->final_score !== null ? number_format((float) $attempt->final_score, 2) : '-' }}</td>
                                    <td class="px-3 py-3 text-[#080808]">{{ $attempt->started_at?->format('d M Y H:i') ?? '-' }}</td>
                                    <td class="px-3 py-3 text-[#080808]">{{ $attempt->submitted_at?->format('d M Y H:i') ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </x-ui.card>

        {{-- Riwayat Attempt Post-Test --}}
        <x-ui.card>
            <h3 class="mb-4 text-sm font-semibold text-[#080808]">Riwayat Attempt Post-Test</h3>
            @if($postTestAttempts->isEmpty())
                <x-ui.empty-state title="Belum ada attempt" description="Peserta belum mengerjakan post-test." />
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-[#D8D8D8] bg-gray-50">
                                <th class="px-3 py-3 text-left font-medium text-[#5A5A5A]">Attempt ke-</th>
                                <th class="px-3 py-3 text-left font-medium text-[#5A5A5A]">Status</th>
                                <th class="px-3 py-3 text-left font-medium text-[#5A5A5A]">Auto Score</th>
                                <th class="px-3 py-3 text-left font-medium text-[#5A5A5A]">Essay Score</th>
                                <th class="px-3 py-3 text-left font-medium text-[#5A5A5A]">Final Score</th>
                                <th class="px-3 py-3 text-left font-medium text-[#5A5A5A]">Mulai</th>
                                <th class="px-3 py-3 text-left font-medium text-[#5A5A5A]">Selesai</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($postTestAttempts as $attempt)
                                @php
                                    $attemptBadge = $statusBadges[$attempt->status] ?? ['variant' => 'muted', 'label' => $attempt->status];
                                @endphp
                                <tr class="border-b border-[#D8D8D8] hover:bg-gray-50">
                                    <td class="px-3 py-3 text-[#080808]">{{ $attempt->attempt_number }}</td>
                                    <td class="px-3 py-3">
                                        <x-ui.badge :variant="$attemptBadge['variant']">{{ $attemptBadge['label'] }}</x-ui.badge>
                                    </td>
                                    <td class="px-3 py-3 text-[#080808]">{{ $attempt->multiple_choice_score !== null ? number_format((float) $attempt->multiple_choice_score, 2) : '-' }}</td>
                                    <td class="px-3 py-3 text-[#080808]">{{ $attempt->essay_score !== null ? number_format((float) $attempt->essay_score, 2) : '-' }}</td>
                                    <td class="px-3 py-3 text-[#080808]">{{ $attempt->final_score !== null ? number_format((float) $attempt->final_score, 2) : '-' }}</td>
                                    <td class="px-3 py-3 text-[#080808]">{{ $attempt->started_at?->format('d M Y H:i') ?? '-' }}</td>
                                    <td class="px-3 py-3 text-[#080808]">{{ $attempt->submitted_at?->format('d M Y H:i') ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </x-ui.card>

        {{-- Riwayat Akses Materi --}}
        <x-ui.card>
            <h3 class="mb-4 text-sm font-semibold text-[#080808]">Riwayat Akses Materi</h3>
            @if($participant->materialAccessLogs->isEmpty())
                <x-ui.empty-state title="Belum ada akses materi" description="Peserta belum mengakses materi training." />
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-[#D8D8D8] bg-gray-50">
                                <th class="px-3 py-3 text-left font-medium text-[#5A5A5A]">Judul Materi</th>
                                <th class="px-3 py-3 text-left font-medium text-[#5A5A5A]">Tipe</th>
                                <th class="px-3 py-3 text-left font-medium text-[#5A5A5A]">Waktu Akses</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($participant->materialAccessLogs as $log)
                                <tr class="border-b border-[#D8D8D8] hover:bg-gray-50">
                                    <td class="px-3 py-3 text-[#080808]">{{ $log->trainingMaterial?->title ?? '-' }}</td>
                                    <td class="px-3 py-3 text-[#080808]">{{ $log->trainingMaterial?->type === 'file' ? 'File' : 'Link' }}</td>
                                    <td class="px-3 py-3 text-[#080808]">{{ $log->accessed_at?->format('d M Y H:i') ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </x-ui.card>

        {{-- Tombol Kembali --}}
        <div class="flex justify-start">
            <x-ui.button href="{{ route('admin.monitoring-progress.index') }}" variant="secondary">Kembali</x-ui.button>
        </div>
    </div>
@endsection
