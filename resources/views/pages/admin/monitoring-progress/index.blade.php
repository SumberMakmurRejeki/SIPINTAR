@extends('layouts.admin', ['title' => 'Monitoring Progress'])

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
        ];
    @endphp

    <div class="space-y-6">
        <x-ui.page-header title="Monitoring Progress" subtitle="Pantau progress training seluruh karyawan." />

        <x-ui.card>
            <form method="GET" action="{{ route('admin.monitoring-progress.index') }}" class="mb-4 grid gap-3 lg:grid-cols-12">
                <div class="lg:col-span-3">
                    <x-ui.input name="search" placeholder="Cari karyawan/training" value="{{ request('search') }}" />
                </div>

                <div class="lg:col-span-2">
                    <x-ui.select name="training_id" placeholder="Semua training">
                        @foreach($trainings as $training)
                            <option value="{{ $training->id }}" @selected((string) request('training_id') === (string) $training->id)>{{ $training->title }}</option>
                        @endforeach
                    </x-ui.select>
                </div>

                <div class="lg:col-span-2">
                    <x-ui.select name="department_id" placeholder="Semua departemen">
                        @foreach($departments as $department)
                            <option value="{{ $department->id }}" @selected((string) request('department_id') === (string) $department->id)>{{ $department->name }}</option>
                        @endforeach
                    </x-ui.select>
                </div>

                <div class="lg:col-span-2">
                    <x-ui.select name="position_id" placeholder="Semua jabatan">
                        @foreach($positions as $position)
                            <option value="{{ $position->id }}" @selected((string) request('position_id') === (string) $position->id)>{{ $position->name }}</option>
                        @endforeach
                    </x-ui.select>
                </div>

                <div class="lg:col-span-3">
                    <x-ui.select name="progress_status" placeholder="Semua status progress">
                        @foreach($progressBadges as $key => $badge)
                            <option value="{{ $key }}" @selected(request('progress_status') === $key)>{{ $badge['label'] }}</option>
                        @endforeach
                    </x-ui.select>
                </div>

                <div class="lg:col-span-2">
                    <x-ui.select name="pre_test_status" placeholder="Pre-Test">
                        <option value="locked" @selected(request('pre_test_status') === 'locked')>Terkunci</option>
                        <option value="not_started" @selected(request('pre_test_status') === 'not_started')>Belum Mulai</option>
                        <option value="in_progress" @selected(request('pre_test_status') === 'in_progress')>Sedang Berjalan</option>
                        <option value="completed" @selected(request('pre_test_status') === 'completed')>Selesai</option>
                    </x-ui.select>
                </div>

                <div class="lg:col-span-2">
                    <x-ui.select name="material_status" placeholder="Materi">
                        <option value="locked" @selected(request('material_status') === 'locked')>Terkunci</option>
                        <option value="not_started" @selected(request('material_status') === 'not_started')>Belum Mulai</option>
                        <option value="accessed" @selected(request('material_status') === 'accessed')>Diakses</option>
                        <option value="completed" @selected(request('material_status') === 'completed')>Selesai</option>
                    </x-ui.select>
                </div>

                <div class="lg:col-span-2">
                    <x-ui.select name="post_test_status" placeholder="Post-Test">
                        <option value="locked" @selected(request('post_test_status') === 'locked')>Terkunci</option>
                        <option value="not_started" @selected(request('post_test_status') === 'not_started')>Belum Mulai</option>
                        <option value="in_progress" @selected(request('post_test_status') === 'in_progress')>Sedang Berjalan</option>
                        <option value="waiting_grading" @selected(request('post_test_status') === 'waiting_grading')>Menunggu Nilai</option>
                        <option value="completed" @selected(request('post_test_status') === 'completed')>Selesai</option>
                        <option value="failed" @selected(request('post_test_status') === 'failed')>Gagal</option>
                    </x-ui.select>
                </div>

                <div class="lg:col-span-2">
                    <x-ui.select name="grading_status" placeholder="Penilaian">
                        <option value="none" @selected(request('grading_status') === 'none')>Tidak Ada</option>
                        <option value="waiting" @selected(request('grading_status') === 'waiting')>Menunggu</option>
                        <option value="graded" @selected(request('grading_status') === 'graded')>Dinilai</option>
                    </x-ui.select>
                </div>

                <div class="flex gap-2 lg:col-span-12">
                    <x-ui.button type="submit" variant="primary">Cari</x-ui.button>
                    <x-ui.button href="{{ route('admin.monitoring-progress.index') }}" variant="ghost">Reset</x-ui.button>
                </div>
            </form>

            @if($participants->isEmpty())
                <x-ui.empty-state
                    title="Belum ada data monitoring"
                    description="Tidak ada peserta training yang sesuai dengan filter saat ini."
                />
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-[#D8D8D8] bg-gray-50">
                                <th class="px-3 py-3 text-left font-medium text-[#5A5A5A]">Nama Karyawan</th>
                                <th class="px-3 py-3 text-left font-medium text-[#5A5A5A]">Training</th>
                                <th class="px-3 py-3 text-left font-medium text-[#5A5A5A]">Departemen</th>
                                <th class="px-3 py-3 text-left font-medium text-[#5A5A5A]">Jabatan</th>
                                <th class="px-3 py-3 text-left font-medium text-[#5A5A5A]">Progress</th>
                                <th class="px-3 py-3 text-left font-medium text-[#5A5A5A]">Pre-Test</th>
                                <th class="px-3 py-3 text-left font-medium text-[#5A5A5A]">Materi</th>
                                <th class="px-3 py-3 text-left font-medium text-[#5A5A5A]">Post-Test</th>
                                <th class="px-3 py-3 text-left font-medium text-[#5A5A5A]">Penilaian</th>
                                <th class="px-3 py-3 text-left font-medium text-[#5A5A5A]">Nilai Akhir</th>
                                <th class="px-3 py-3 text-right font-medium text-[#5A5A5A]">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($participants as $participant)
                                @php
                                    $progress = $progressBadges[$participant->progress_status] ?? ['variant' => 'muted', 'label' => $participant->progress_status];
                                    $preTest = $statusBadges[$participant->pre_test_status] ?? ['variant' => 'muted', 'label' => $participant->pre_test_status];
                                    $material = $statusBadges[$participant->material_status] ?? ['variant' => 'muted', 'label' => $participant->material_status];
                                    $postTest = $statusBadges[$participant->post_test_status] ?? ['variant' => 'muted', 'label' => $participant->post_test_status];
                                    $grading = $statusBadges[$participant->grading_status] ?? ['variant' => 'muted', 'label' => $participant->grading_status];
                                @endphp
                                <tr class="border-b border-[#D8D8D8] hover:bg-gray-50">
                                    <td class="px-3 py-3 text-[#080808]">
                                        <div class="font-medium">{{ $participant->employee?->name ?? '-' }}</div>
                                        <div class="text-xs text-[#5A5A5A]">{{ $participant->employee?->employee_code ?? '-' }}</div>
                                    </td>
                                    <td class="px-3 py-3 text-[#080808]">{{ $participant->training?->title ?? '-' }}</td>
                                    <td class="px-3 py-3 text-[#080808]">{{ $participant->employee?->department?->name ?? '-' }}</td>
                                    <td class="px-3 py-3 text-[#080808]">{{ $participant->employee?->position?->name ?? '-' }}</td>
                                    <td class="px-3 py-3">
                                        <x-ui.badge :variant="$progress['variant']">{{ $progress['label'] }}</x-ui.badge>
                                    </td>
                                    <td class="px-3 py-3">
                                        <x-ui.badge :variant="$preTest['variant']">{{ $preTest['label'] }}</x-ui.badge>
                                    </td>
                                    <td class="px-3 py-3">
                                        <x-ui.badge :variant="$material['variant']">{{ $material['label'] }}</x-ui.badge>
                                    </td>
                                    <td class="px-3 py-3">
                                        <x-ui.badge :variant="$postTest['variant']">{{ $postTest['label'] }}</x-ui.badge>
                                    </td>
                                    <td class="px-3 py-3">
                                        <x-ui.badge :variant="$grading['variant']">{{ $grading['label'] }}</x-ui.badge>
                                    </td>
                                    <td class="px-3 py-3 text-[#080808]">
                                        {{ $participant->final_score !== null ? number_format((float) $participant->final_score, 2) : '-' }}
                                    </td>
                                    <td class="px-3 py-3 text-right">
                                        <x-ui.button href="{{ route('admin.monitoring-progress.show', $participant) }}" variant="ghost" size="sm">Detail</x-ui.button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $participants->links() }}
                </div>
            @endif
        </x-ui.card>
    </div>
@endsection
