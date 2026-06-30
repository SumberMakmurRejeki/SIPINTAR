@extends('layouts.admin', ['title' => 'Detail Penilaian'])

@section('content')
    @php
        $isGraded = $attempt->status === 'graded';
        $attemptBadge = $isGraded ? 'success' : 'warning';
        $attemptLabel = $isGraded ? 'Dinilai' : 'Menunggu Penilaian';
    @endphp

    <div class="space-y-6">
        <x-ui.page-header title="Detail Penilaian" subtitle="Periksa jawaban dan simpan nilai essay.">
            <x-slot:actions>
                <x-ui.button href="{{ route('admin.grading.index') }}" variant="secondary">Kembali</x-ui.button>
            </x-slot:actions>
        </x-ui.page-header>

        <x-ui.card>
            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                <div>
                    <div class="text-xs font-medium text-[#5A5A5A]">Nama Karyawan</div>
                    <div class="mt-1 text-sm font-semibold text-[#080808]">{{ $attempt->employee?->name ?? '-' }}</div>
                </div>
                <div>
                    <div class="text-xs font-medium text-[#5A5A5A]">Departemen</div>
                    <div class="mt-1 text-sm font-semibold text-[#080808]">{{ $attempt->employee?->department?->name ?? '-' }}</div>
                </div>
                <div>
                    <div class="text-xs font-medium text-[#5A5A5A]">Jabatan</div>
                    <div class="mt-1 text-sm font-semibold text-[#080808]">{{ $attempt->employee?->position?->name ?? '-' }}</div>
                </div>
                <div>
                    <div class="text-xs font-medium text-[#5A5A5A]">Status</div>
                    <div class="mt-1"><x-ui.badge :variant="$attemptBadge">{{ $attemptLabel }}</x-ui.badge></div>
                </div>
                <div>
                    <div class="text-xs font-medium text-[#5A5A5A]">Judul Training</div>
                    <div class="mt-1 text-sm font-semibold text-[#080808]">{{ $attempt->test?->training?->title ?? '-' }}</div>
                </div>
                <div>
                    <div class="text-xs font-medium text-[#5A5A5A]">Jenis Test</div>
                    <div class="mt-1 text-sm font-semibold text-[#080808]">{{ $attempt->test?->type === 'pre_test' ? 'Pre-Test' : 'Post-Test' }}</div>
                </div>
                <div>
                    <div class="text-xs font-medium text-[#5A5A5A]">Tanggal Submit</div>
                    <div class="mt-1 text-sm font-semibold text-[#080808]">{{ $attempt->submitted_at?->format('d M Y H:i') ?? '-' }}</div>
                </div>
                <div>
                    <div class="text-xs font-medium text-[#5A5A5A]">Auto Score</div>
                    <div class="mt-1 text-sm font-semibold text-[#080808]">{{ number_format($multipleChoiceScore, 2) }}</div>
                </div>
            </div>
        </x-ui.card>

        <form id="grade-form" method="POST" action="{{ route('admin.grading.grade', $attempt) }}" class="space-y-4">
            @csrf

            @foreach($attempt->answers as $answer)
                @php
                    $question = $answer->question;
                    $isMultipleChoice = $question->question_type === 'multiple_choice';
                    $correctOption = $question->options->firstWhere('is_correct', true);
                @endphp

                <x-ui.card>
                    <div class="space-y-4">
                        <div class="flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between">
                            <div>
                                <div class="text-xs font-medium uppercase tracking-wide text-[#5A5A5A]">{{ $isMultipleChoice ? 'Pilihan Ganda' : 'Essay' }}</div>
                                <div class="mt-1 text-sm font-semibold text-[#080808]">{!! nl2br(e($question->question_text)) !!}</div>
                            </div>
                            <x-ui.badge variant="muted">Maks {{ number_format((float) $question->score, 2) }}</x-ui.badge>
                        </div>

                        @if($isMultipleChoice)
                            <div class="grid gap-3 md:grid-cols-2">
                                <div class="rounded-[10px] border border-[#e2e8f0] bg-gray-50 p-3">
                                    <div class="text-xs font-medium text-[#5A5A5A]">Jawaban Dipilih</div>
                                    <div class="mt-1 text-sm text-[#080808]">{{ $answer->selectedOption?->option_label }}. {{ $answer->selectedOption?->option_text ?? '-' }}</div>
                                </div>
                                <div class="rounded-[10px] border border-[#e2e8f0] bg-gray-50 p-3">
                                    <div class="text-xs font-medium text-[#5A5A5A]">Jawaban Benar</div>
                                    <div class="mt-1 text-sm text-[#080808]">{{ $correctOption?->option_label }}. {{ $correctOption?->option_text ?? '-' }}</div>
                                </div>
                            </div>
                            <div class="flex flex-wrap items-center gap-3">
                                <x-ui.badge :variant="$answer->is_correct ? 'success' : 'danger'">{{ $answer->is_correct ? 'Benar' : 'Salah' }}</x-ui.badge>
                                <div class="text-sm font-medium text-[#080808]">Auto Score: {{ number_format((float) $answer->auto_score, 2) }}</div>
                            </div>
                        @else
                            <div class="rounded-[10px] border border-[#e2e8f0] bg-gray-50 p-4">
                                <div class="text-xs font-medium text-[#5A5A5A]">Jawaban Essay</div>
                                <div class="mt-2 whitespace-pre-line text-sm text-[#080808]">{{ $answer->essay_answer }}</div>
                            </div>

                            <div class="max-w-xs">
                                <label for="score-{{ $answer->id }}" class="mb-1.5 block text-[13px] font-medium text-[#0f172a]">Nilai Essay</label>
                                <input
                                    id="score-{{ $answer->id }}"
                                    name="scores[{{ $answer->id }}]"
                                    type="number"
                                    min="0"
                                    max="{{ (float) $question->score }}"
                                    step="0.01"
                                    value="{{ old('scores.'.$answer->id, $answer->manual_score) }}"
                                    @disabled($isGraded)
                                    class="block w-full rounded-[10px] border border-[#e2e8f0] bg-white px-4 py-2.5 text-[14px] text-[#0f172a] transition-colors focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 disabled:cursor-not-allowed disabled:opacity-60"
                                >
                                <div class="mt-1.5 text-xs text-[#5A5A5A]">Maksimal {{ number_format((float) $question->score, 2) }}</div>
                                @error('scores.'.$answer->id)
                                    <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        @endif
                    </div>
                </x-ui.card>
            @endforeach
        </form>

        <div class="flex flex-wrap justify-end gap-3">
            <x-ui.button href="{{ route('admin.grading.index') }}" variant="secondary">Kembali</x-ui.button>

            @unless($isGraded)
                <div x-data="{ open: false }" class="inline">
                    <x-ui.button type="button" variant="primary" @click="open = true">Simpan Nilai</x-ui.button>

                    <div x-show="open" x-cloak @keydown.escape.window="open = false" class="fixed inset-0 z-50 flex items-center justify-center p-4">
                        <div x-show="open" x-transition @click="open = false" class="fixed inset-0 bg-black/50"></div>
                        <div x-show="open" x-transition class="relative w-full max-w-lg rounded-lg border border-[#D8D8D8] bg-white p-6 shadow-lg">
                            <h3 class="text-base font-semibold text-[#080808]">Simpan Nilai Essay?</h3>
                            <p class="mt-1 text-sm text-[#5A5A5A]">Pastikan semua nilai sudah benar sebelum disimpan.</p>
                            <div class="mt-6 flex items-center justify-end gap-3">
                                <x-ui.button type="button" variant="secondary" @click="open = false">Batal</x-ui.button>
                                <x-ui.button type="submit" form="grade-form" variant="primary">Simpan Nilai</x-ui.button>
                            </div>
                        </div>
                    </div>
                </div>
            @endunless
        </div>
    </div>
@endsection
