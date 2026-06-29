@extends('layouts.admin', ['title' => $test->title])

@section('content')
    <div class="space-y-6">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $test->title }}</h1>
            @if ($test->instruction)
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">{{ $test->instruction }}</p>
            @endif
        </div>

        <div class="space-y-4">
            @forelse ($test->questions as $question)
                <div class="rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-800">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="font-medium text-gray-900 dark:text-white">{{ $loop->iteration }}. {{ $question->question_text }}</p>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ $question->score }} poin · {{ $question->question_type === 'multiple_choice' ? 'Pilihan ganda' : 'Esai' }}</p>
                        </div>
                        <span class="rounded-full px-2 py-1 text-xs {{ $question->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                            {{ $question->status }}
                        </span>
                    </div>

                    @if ($question->question_type === 'multiple_choice')
                        <div class="mt-4 space-y-2">
                            @foreach ($question->options as $option)
                                <div class="rounded border border-gray-100 p-3 text-sm dark:border-gray-700">
                                    <span class="font-medium">{{ $option->option_label }}.</span>
                                    {{ $option->option_text }}
                                    @if ($option->is_correct)
                                        <span class="ml-2 text-xs text-green-600">Jawaban benar</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            @empty
                <div class="rounded-lg border border-dashed border-gray-300 p-6 text-center text-gray-500 dark:border-gray-700">
                    Belum ada pertanyaan pre-test.
                </div>
            @endforelse
        </div>
    </div>
@endsection
