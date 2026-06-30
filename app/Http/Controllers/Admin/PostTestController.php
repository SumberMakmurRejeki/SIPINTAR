<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePostTestRequest;
use App\Http\Requests\Admin\UpdatePostTestRequest;
use App\Models\Test;
use App\Models\Training;
use Illuminate\Http\RedirectResponse;

class PostTestController extends Controller
{
    public function store(StorePostTestRequest $request, Training $training): RedirectResponse
    {
        $training->tests()->create([
            ...$request->validated(),
            'type' => 'post_test',
        ]);

        return redirect(route('admin.training.show', $training).'?tab=posttest')->with('success', 'Post-test berhasil ditambahkan.');
    }

    public function update(UpdatePostTestRequest $request, Training $training, Test $test): RedirectResponse
    {
        if (! $this->isPostTestForTraining($training, $test)) {
            return redirect(route('admin.training.show', $training).'?tab=posttest')->with('error', 'Post-test tidak ditemukan untuk training ini.');
        }

        $test->update($request->validated());

        return redirect(route('admin.training.show', $training).'?tab=posttest')->with('success', 'Post-test berhasil diperbarui.');
    }

    public function copyFromPretest(Training $training, Test $test): RedirectResponse
    {
        if (! $this->isPostTestForTraining($training, $test)) {
            return redirect(route('admin.training.show', $training).'?tab=posttest')->with('error', 'Post-test tidak ditemukan untuk training ini.');
        }

        $preTest = $training->tests()->where('type', 'pre_test')->first();

        if (! $preTest) {
            return redirect(route('admin.training.show', $training).'?tab=posttest')->with('error', 'Belum ada pre-test untuk disalin.');
        }

        $preQuestions = $preTest->questions()->with('options')->get();

        if ($preQuestions->isEmpty()) {
            return redirect(route('admin.training.show', $training).'?tab=posttest')->with('error', 'Pre-test belum memiliki soal untuk disalin.');
        }

        $existingCount = $test->questions()->count();

        foreach ($preQuestions as $preQuestion) {
            $newQuestion = $test->questions()->create([
                'question_text' => $preQuestion->question_text,
                'question_type' => $preQuestion->question_type,
                'score' => $preQuestion->score,
                'sort_order' => $existingCount + $preQuestion->sort_order,
                'status' => 'active',
            ]);

            if ($preQuestion->options->isNotEmpty()) {
                $newQuestion->options()->createMany(
                    $preQuestion->options->map(fn ($opt) => [
                        'option_label' => $opt->option_label,
                        'option_text' => $opt->option_text,
                        'is_correct' => $opt->is_correct,
                        'sort_order' => $opt->sort_order,
                    ])->all()
                );
            }
        }

        return redirect(route('admin.training.show', $training).'?tab=posttest')->with('success', $preQuestions->count().' soal berhasil disalin dari pre-test.');
    }

    private function isPostTestForTraining(Training $training, Test $test): bool
    {
        return $test->training_id === $training->id && $test->type === 'post_test';
    }
}
