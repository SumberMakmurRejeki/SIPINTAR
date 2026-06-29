<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePostTestQuestionRequest;
use App\Http\Requests\Admin\UpdatePostTestQuestionRequest;
use App\Models\Question;
use App\Models\Test;
use App\Models\Training;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PostTestQuestionController extends Controller
{
    public function store(StorePostTestQuestionRequest $request, Training $training, Test $test): RedirectResponse
    {
        if (! $this->isPostTestForTraining($training, $test)) {
            return redirect()->back()->with('error', 'Post-test tidak ditemukan untuk training ini.');
        }

        $data = $request->validated();
        $question = $test->questions()->create(collect($data)->except('options')->all());

        $this->syncOptions($question, $data);

        return redirect()->back()->with('success', 'Pertanyaan post-test berhasil ditambahkan.');
    }

    public function update(UpdatePostTestQuestionRequest $request, Training $training, Test $test, Question $question): RedirectResponse
    {
        if (! $this->isQuestionForPostTest($training, $test, $question)) {
            return redirect()->back()->with('error', 'Pertanyaan post-test tidak ditemukan.');
        }

        $data = $request->validated();
        $question->update(collect($data)->except('options')->all());

        $this->syncOptions($question, $data);

        return redirect()->back()->with('success', 'Pertanyaan post-test berhasil diperbarui.');
    }

    public function destroy(Training $training, Test $test, Question $question): RedirectResponse
    {
        if (! $this->isQuestionForPostTest($training, $test, $question)) {
            return redirect()->back()->with('error', 'Pertanyaan post-test tidak ditemukan.');
        }

        $question->options()->delete();
        $question->forceDelete();

        return redirect()->back()->with('success', 'Pertanyaan post-test berhasil dihapus permanen.');
    }

    public function toggleStatus(Training $training, Test $test, Question $question): RedirectResponse
    {
        if (! $this->isQuestionForPostTest($training, $test, $question)) {
            return redirect()->back()->with('error', 'Pertanyaan post-test tidak ditemukan.');
        }

        $question->update([
            'status' => $question->status === 'active' ? 'inactive' : 'active',
        ]);

        $action = $question->status === 'active' ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->back()->with('success', "Pertanyaan post-test berhasil {$action}.");
    }

    public function preview(Training $training, Test $test): View|RedirectResponse
    {
        if (! $this->isPostTestForTraining($training, $test)) {
            return redirect()->back()->with('error', 'Post-test tidak ditemukan untuk training ini.');
        }

        $test->load(['questions' => function ($query) {
            $query->orderBy('sort_order')->orderBy('created_at')->with(['options' => function ($query) {
                $query->orderBy('sort_order')->orderBy('created_at');
            }]);
        }]);

        return view('pages::admin.training.partials.post-test-preview', compact('training', 'test'));
    }

    /**
     * @param  array<string, mixed>  $data
     */
    private function syncOptions(Question $question, array $data): void
    {
        $question->options()->delete();

        if ($data['question_type'] !== 'multiple_choice') {
            return;
        }

        $options = collect($data['options'] ?? [])->values()->map(fn (array $option, int $index): array => [
            'option_label' => $option['option_label'],
            'option_text' => $option['option_text'],
            'is_correct' => (bool) ($option['is_correct'] ?? false),
            'sort_order' => $index,
        ])->all();

        $question->options()->createMany($options);
    }

    private function isQuestionForPostTest(Training $training, Test $test, Question $question): bool
    {
        return $this->isPostTestForTraining($training, $test) && $question->test_id === $test->id;
    }

    private function isPostTestForTraining(Training $training, Test $test): bool
    {
        return $test->training_id === $training->id && $test->type === 'post_test';
    }
}
