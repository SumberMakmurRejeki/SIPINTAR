<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePreTestRequest;
use App\Http\Requests\Admin\UpdatePreTestRequest;
use App\Models\Test;
use App\Models\Training;
use Illuminate\Http\RedirectResponse;

class PreTestController extends Controller
{
    public function store(StorePreTestRequest $request, Training $training): RedirectResponse
    {
        $training->tests()->create([
            ...$request->validated(),
            'type' => 'pre_test',
        ]);

        return redirect(route('admin.training.show', $training).'?tab=pretest')->with('success', 'Pre-test berhasil ditambahkan.');
    }

    public function update(UpdatePreTestRequest $request, Training $training, Test $test): RedirectResponse
    {
        if (! $this->isPreTestForTraining($training, $test)) {
            return redirect(route('admin.training.show', $training).'?tab=pretest')->with('error', 'Pre-test tidak ditemukan untuk training ini.');
        }

        $test->update($request->validated());

        return redirect(route('admin.training.show', $training).'?tab=pretest')->with('success', 'Pre-test berhasil diperbarui.');
    }

    private function isPreTestForTraining(Training $training, Test $test): bool
    {
        return $test->training_id === $training->id && $test->type === 'pre_test';
    }
}
