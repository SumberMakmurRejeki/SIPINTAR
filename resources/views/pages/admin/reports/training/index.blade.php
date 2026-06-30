@extends('layouts.admin', ['title' => 'Laporan Training'])

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
    @endphp

    <div class="space-y-6">
        <x-ui.page-header title="Laporan Training" subtitle="Laporan data training, peserta, dan hasil kelulusan." />

        {{-- Filter Panel --}}
        <x-ui.card>
            <form method="GET" action="{{ route('admin.laporan-training') }}" class="mb-4 grid gap-3 lg:grid-cols-12">
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

                <div class="lg:col-span-2">
                    <x-ui.select name="status" placeholder="Semua status">
                        @foreach($progressBadges as $key => $badge)
                            <option value="{{ $key }}" @selected(request('status') === $key)>{{ $badge['label'] }}</option>
                        @endforeach
                    </x-ui.select>
                </div>

                <div class="lg:col-span-2">
                    <x-ui.input type="date" name="start_date" value="{{ request('start_date', now()->startOfMonth()->format('Y-m-d')) }}" />
                </div>

                <div class="lg:col-span-2">
                    <x-ui.input type="date" name="end_date" value="{{ request('end_date', now()->endOfMonth()->format('Y-m-d')) }}" />
                </div>

                <div class="flex gap-2 lg:col-span-12">
                    <x-ui.button type="submit" variant="primary">Cari</x-ui.button>
                    <x-ui.button href="{{ route('admin.laporan-training') }}" variant="ghost">Reset</x-ui.button>
                </div>
            </form>
        </x-ui.card>

        {{-- Summary Cards --}}
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <x-ui.card>
                <div class="p-4">
                    <div class="text-sm text-[#5A5A5A]">Total Peserta</div>
                    <div class="mt-1 text-2xl font-bold text-[#080808]">{{ $summary['total_peserta'] }}</div>
                </div>
            </x-ui.card>
            <x-ui.card>
                <div class="p-4">
                    <div class="text-sm text-[#5A5A5A]">Total Training</div>
                    <div class="mt-1 text-2xl font-bold text-[#080808]">{{ $summary['total_training'] }}</div>
                </div>
            </x-ui.card>
            <x-ui.card>
                <div class="p-4">
                    <div class="text-sm text-[#5A5A5A]">Total Lulus</div>
                    <div class="mt-1 text-2xl font-bold text-green-600">{{ $summary['total_lulus'] }}</div>
                </div>
            </x-ui.card>
            <x-ui.card>
                <div class="p-4">
                    <div class="text-sm text-[#5A5A5A]">Total Tidak Lulus</div>
                    <div class="mt-1 text-2xl font-bold text-red-600">{{ $summary['total_tidak_lulus'] }}</div>
                </div>
            </x-ui.card>
            <x-ui.card>
                <div class="p-4">
                    <div class="text-sm text-[#5A5A5A]">Menunggu Penilaian</div>
                    <div class="mt-1 text-2xl font-bold text-yellow-600">{{ $summary['total_menunggu'] }}</div>
                </div>
            </x-ui.card>
            <x-ui.card>
                <div class="p-4">
                    <div class="text-sm text-[#5A5A5A]">Persentase Kelulusan</div>
                    <div class="mt-1 text-2xl font-bold text-blue-600">{{ $summary['persentase_kelulusan'] }}%</div>
                </div>
            </x-ui.card>
            <x-ui.card>
                <div class="p-4">
                    <div class="text-sm text-[#5A5A5A]">Rata-rata Pre-Test</div>
                    <div class="mt-1 text-2xl font-bold text-[#080808]">{{ $summary['rata_pre_test'] !== null ? number_format((float) $summary['rata_pre_test'], 2) : '-' }}</div>
                </div>
            </x-ui.card>
            <x-ui.card>
                <div class="p-4">
                    <div class="text-sm text-[#5A5A5A]">Rata-rata Post-Test</div>
                    <div class="mt-1 text-2xl font-bold text-[#080808]">{{ $summary['rata_post_test'] !== null ? number_format((float) $summary['rata_post_test'], 2) : '-' }}</div>
                </div>
            </x-ui.card>
        </div>

        {{-- Export Buttons --}}
        <div class="flex gap-3">
            <x-ui.button href="{{ route('admin.laporan-training.export.pdf', request()->query()) }}" variant="primary">
                Export PDF
            </x-ui.button>
            <x-ui.button href="{{ route('admin.laporan-training.export.excel', request()->query()) }}" variant="secondary">
                Export Excel
            </x-ui.button>
        </div>

        {{-- Table --}}
        <x-ui.card>
            @if($participants->isEmpty())
                <x-ui.empty-state
                    title="Belum ada data laporan"
                    description="Tidak ada data training yang sesuai dengan filter saat ini."
                />
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-[#D8D8D8] bg-gray-50">
                                <th class="px-3 py-3 text-left font-medium text-[#5A5A5A]">Nama Karyawan</th>
                                <th class="px-3 py-3 text-left font-medium text-[#5A5A5A]">Departemen</th>
                                <th class="px-3 py-3 text-left font-medium text-[#5A5A5A]">Jabatan</th>
                                <th class="px-3 py-3 text-left font-medium text-[#5A5A5A]">Judul Training</th>
                                <th class="px-3 py-3 text-left font-medium text-[#5A5A5A]">Status</th>
                                <th class="px-3 py-3 text-left font-medium text-[#5A5A5A]">Pre-Test</th>
                                <th class="px-3 py-3 text-left font-medium text-[#5A5A5A]">Post-Test</th>
                                <th class="px-3 py-3 text-left font-medium text-[#5A5A5A]">Nilai Akhir</th>
                                <th class="px-3 py-3 text-left font-medium text-[#5A5A5A]">Kelulusan</th>
                                <th class="px-3 py-3 text-left font-medium text-[#5A5A5A]">Tgl Mulai</th>
                                <th class="px-3 py-3 text-left font-medium text-[#5A5A5A]">Tgl Selesai</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($participants as $participant)
                                @php
                                    $progress = $progressBadges[$participant->progress_status] ?? ['variant' => 'muted', 'label' => $participant->progress_status];
                                    $passFail = match($participant->progress_status) {
                                        'passed' => ['variant' => 'success', 'label' => 'Lulus'],
                                        'failed', 'remedial' => ['variant' => 'danger', 'label' => 'Tidak Lulus'],
                                        default => ['variant' => 'muted', 'label' => '-'],
                                    };
                                @endphp
                                <tr class="border-b border-[#D8D8D8] hover:bg-gray-50">
                                    <td class="px-3 py-3 text-[#080808]">{{ $participant->employee?->name ?? '-' }}</td>
                                    <td class="px-3 py-3 text-[#080808]">{{ $participant->employee?->department?->name ?? '-' }}</td>
                                    <td class="px-3 py-3 text-[#080808]">{{ $participant->employee?->position?->name ?? '-' }}</td>
                                    <td class="px-3 py-3 text-[#080808]">{{ $participant->training?->title ?? '-' }}</td>
                                    <td class="px-3 py-3">
                                        <x-ui.badge :variant="$progress['variant']">{{ $progress['label'] }}</x-ui.badge>
                                    </td>
                                    <td class="px-3 py-3 text-[#080808]">
                                        {{ $participant->pre_test_score !== null ? number_format((float) $participant->pre_test_score, 2) : '-' }}
                                    </td>
                                    <td class="px-3 py-3 text-[#080808]">
                                        {{ $participant->post_test_score !== null ? number_format((float) $participant->post_test_score, 2) : '-' }}
                                    </td>
                                    <td class="px-3 py-3 text-[#080808]">
                                        {{ $participant->final_score !== null ? number_format((float) $participant->final_score, 2) : '-' }}
                                    </td>
                                    <td class="px-3 py-3">
                                        <x-ui.badge :variant="$passFail['variant']">{{ $passFail['label'] }}</x-ui.badge>
                                    </td>
                                    <td class="px-3 py-3 text-[#080808]">
                                        {{ $participant->started_at?->format('d/m/Y') ?? '-' }}
                                    </td>
                                    <td class="px-3 py-3 text-[#080808]">
                                        {{ $participant->completed_at?->format('d/m/Y') ?? '-' }}
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
