@extends('layouts.admin', ['title' => 'Penilaian'])

@section('content')
    <div class="space-y-6">
        <x-ui.page-header title="Penilaian" subtitle="Kelola penilaian essay pre-test dan post-test." />

        <x-ui.card>
            <form method="GET" action="{{ route('admin.grading.index') }}" class="mb-4 grid gap-3 lg:grid-cols-12">
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
                    <x-ui.select name="test_type" placeholder="Semua test">
                        <option value="pre_test" @selected(request('test_type') === 'pre_test')>Pre-Test</option>
                        <option value="post_test" @selected(request('test_type') === 'post_test')>Post-Test</option>
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

                <div class="lg:col-span-1">
                    <x-ui.select name="grading_status" placeholder="Status">
                        <option value="waiting" @selected(request('grading_status') === 'waiting')>Menunggu</option>
                        <option value="graded" @selected(request('grading_status') === 'graded')>Dinilai</option>
                    </x-ui.select>
                </div>

                <div class="flex gap-2 lg:col-span-12">
                    <x-ui.button type="submit" variant="primary">Cari</x-ui.button>
                    <x-ui.button href="{{ route('admin.grading.index') }}" variant="ghost">Reset</x-ui.button>
                </div>
            </form>

            @if($attempts->isEmpty())
                <x-ui.empty-state
                    title="Belum ada penilaian"
                    description="Tidak ada essay yang sesuai dengan filter saat ini."
                />
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-[#D8D8D8] bg-gray-50">
                                <th class="px-3 py-3 text-left font-medium text-[#5A5A5A]">Nama Karyawan</th>
                                <th class="px-3 py-3 text-left font-medium text-[#5A5A5A]">Training</th>
                                <th class="px-3 py-3 text-left font-medium text-[#5A5A5A]">Jenis Test</th>
                                <th class="px-3 py-3 text-left font-medium text-[#5A5A5A]">Status Penilaian</th>
                                <th class="px-3 py-3 text-left font-medium text-[#5A5A5A]">Tanggal Submit</th>
                                <th class="px-3 py-3 text-left font-medium text-[#5A5A5A]">Nilai Sementara</th>
                                <th class="px-3 py-3 text-right font-medium text-[#5A5A5A]">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($attempts as $attempt)
                                @php
                                    $isWaiting = $attempt->status === 'waiting_grading';
                                @endphp
                                <tr class="border-b border-[#D8D8D8] hover:bg-gray-50">
                                    <td class="px-3 py-3 text-[#080808]">
                                        <div class="font-medium">{{ $attempt->employee?->name ?? '-' }}</div>
                                        <div class="text-xs text-[#5A5A5A]">{{ $attempt->employee?->employee_code ?? '-' }}</div>
                                    </td>
                                    <td class="px-3 py-3 text-[#080808]">{{ $attempt->test?->training?->title ?? '-' }}</td>
                                    <td class="px-3 py-3 text-[#080808]">{{ $attempt->test?->type === 'pre_test' ? 'Pre-Test' : 'Post-Test' }}</td>
                                    <td class="px-3 py-3">
                                        <x-ui.badge :variant="$isWaiting ? 'warning' : 'success'">{{ $isWaiting ? 'Menunggu' : 'Dinilai' }}</x-ui.badge>
                                    </td>
                                    <td class="px-3 py-3 text-[#080808]">{{ $attempt->submitted_at?->format('d M Y H:i') ?? '-' }}</td>
                                    <td class="px-3 py-3 text-[#080808]">{{ $attempt->final_score !== null ? number_format((float) $attempt->final_score, 2) : '-' }}</td>
                                    <td class="px-3 py-3 text-right">
                                        <x-ui.button href="{{ route('admin.grading.show', $attempt) }}" variant="ghost" size="sm">Detail</x-ui.button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $attempts->links() }}
                </div>
            @endif
        </x-ui.card>
    </div>
@endsection
