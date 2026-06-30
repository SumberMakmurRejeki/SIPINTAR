@extends('layouts.employee', ['title' => $test->title])

@section('content')
    @php
        $startedAt = $attempt->started_at ?? now();
        $durationSeconds = ($test->duration_minutes ?? 0) * 60;
        $deadline = $durationSeconds > 0 ? $startedAt->addSeconds($durationSeconds) : null;
    @endphp

    <div class="space-y-6" x-data="testForm()" x-init="init({{ $deadline ? "'".$deadline->toIso8601String()."'" : 'null' }})">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
            <div>
                <h1 class="text-[22px] font-bold text-[#0f172a] tracking-tight">{{ $test->title }}</h1>
                <p class="mt-1 text-[13px] text-[#64748b]">{{ $test->type === 'pre_test' ? 'Pre-Test' : 'Post-Test' }} - Attempt #{{ $attempt->attempt_number }}</p>
            </div>
            @if($deadline)
                <div class="flex items-center gap-2 rounded-[12px] border border-[#e2e8f0] px-4 py-2">
                    <svg class="h-4 w-4 text-[#64748b]" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <span class="text-[14px] font-medium text-[#0f172a]" x-text="timeRemaining">--:--</span>
                </div>
            @endif
        </div>

        <form id="test-form" method="POST" action="{{ route('karyawan.training-saya.tests.submit', [$training, $test, $attempt]) }}" @submit.prevent="confirmSubmit">
            @csrf

            <div class="space-y-4">
                @foreach($questions as $index => $question)
                    <x-ui.card>
                        <div class="space-y-3">
                            <div class="flex items-start gap-3">
                                <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-[#f1f5f9] text-[12px] font-medium text-[#64748b]">{{ $index + 1 }}</span>
                                <div class="flex-1 min-w-0">
                                    <p class="text-[14px] text-[#0f172a]">{{ $question->question_text }}</p>
                                    <p class="mt-1 text-[12px] text-[#94a3b8]">{{ $question->question_type === 'multiple_choice' ? 'Pilihan Ganda' : 'Essay' }} - {{ $question->score }} poin</p>
                                </div>
                            </div>

                            @if($question->question_type === 'multiple_choice')
                                <div class="ml-9 space-y-2">
                                    @foreach($question->options->sortBy('sort_order') as $option)
                                        <label class="flex items-center gap-3 rounded-[8px] border border-[#e2e8f0] p-3 cursor-pointer hover:bg-[#f8fafc] transition-colors {{ (old("answers.{$question->id}") ?? ($answers[$question->id] ?? null)) == $option->id ? 'border-[#3b82f6] bg-blue-50' : '' }}">
                                            <input type="radio" name="answers[{{ $question->id }}]" value="{{ $option->id }}" class="h-4 w-4 text-[#3b82f6] focus:ring-[#3b82f6]" {{ (old("answers.{$question->id}") ?? ($answers[$question->id] ?? null)) == $option->id ? 'checked' : '' }}>
                                            <span class="text-[13px] text-[#0f172a]">{{ $option->option_label }}. {{ $option->option_text }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            @else
                                <div class="ml-9">
                                    <textarea name="answers[{{ $question->id }}]" rows="4" class="w-full rounded-[8px] border border-[#e2e8f0] px-3 py-2 text-[13px] text-[#0f172a] placeholder-[#94a3b8] focus:border-[#3b82f6] focus:ring-1 focus:ring-[#3b82f6] outline-none resize-y" placeholder="Tulis jawaban essay Anda di sini...">{{ old("answers.{$question->id}") ?? ($essayAnswers[$question->id] ?? '') }}</textarea>
                                </div>
                            @endif
                        </div>
                    </x-ui.card>
                @endforeach
            </div>

            <div class="mt-6 flex justify-end">
                <x-ui.button type="submit">Kirim Jawaban</x-ui.button>
            </div>
        </form>

        {{-- Confirm Modal --}}
        <div x-show="showConfirm" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
            <div class="mx-4 w-full max-w-md rounded-[16px] bg-white p-6 shadow-xl" @click.away="showConfirm = false">
                <h3 class="text-[16px] font-semibold text-[#0f172a]">Konfirmasi Pengiriman</h3>
                <p class="mt-2 text-[14px] text-[#64748b]">Apakah Anda yakin ingin mengirim jawaban? Setelah dikirim, jawaban tidak dapat diubah.</p>
                <div class="mt-6 flex justify-end gap-3">
                    <x-ui.button variant="secondary" @click="showConfirm = false">Batal</x-ui.button>
                    <x-ui.button @click="submitForm">Kirim</x-ui.button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function testForm() {
            return {
                showConfirm: false,
                deadline: null,
                timeRemaining: '--:--',
                timerInterval: null,

                init(deadline) {
                    this.deadline = deadline ? new Date(deadline) : null;
                    if (this.deadline) {
                        this.updateTimer();
                        this.timerInterval = setInterval(() => this.updateTimer(), 1000);
                    }
                },

                updateTimer() {
                    if (!this.deadline) return;
                    const now = new Date();
                    const diff = Math.max(0, Math.floor((this.deadline - now) / 1000));

                    if (diff <= 0) {
                        this.timeRemaining = '00:00';
                        clearInterval(this.timerInterval);
                        this.submitForm();
                        return;
                    }

                    const minutes = Math.floor(diff / 60);
                    const seconds = diff % 60;
                    this.timeRemaining = `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
                },

                confirmSubmit() {
                    this.showConfirm = true;
                },

                submitForm() {
                    this.showConfirm = false;
                    document.getElementById('test-form').submit();
                }
            }
        }
    </script>
@endsection
