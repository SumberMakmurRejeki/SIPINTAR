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

        return redirect()->back()->with('success', 'Post-test berhasil ditambahkan.');
    }

    public function update(UpdatePostTestRequest $request, Training $training, Test $test): RedirectResponse
    {
        if (! $this->isPostTestForTraining($training, $test)) {
            return redirect()->back()->with('error', 'Post-test tidak ditemukan untuk training ini.');
        }

        $test->update($request->validated());

        return redirect()->back()->with('success', 'Post-test berhasil diperbarui.');
    }

    private function isPostTestForTraining(Training $training, Test $test): bool
    {
        return $test->training_id === $training->id && $test->type === 'post_test';
    }
}
