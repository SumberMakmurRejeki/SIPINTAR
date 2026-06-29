<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreTrainingRequest;
use App\Http\Requests\Admin\UpdateTrainingRequest;
use App\Models\Training;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TrainingController extends Controller
{
    public function index(Request $request): View
    {
        $query = Training::withCount('participants')->with('creator');

        if ($request->filled('search')) {
            $query->where('title', 'like', '%'.$request->search.'%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('start_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('end_date', '<=', $request->date_to);
        }

        $trainings = $query->latest()->paginate(10)->withQueryString();

        return view('pages::admin.training.index', compact('trainings'));
    }

    public function create(): View
    {
        return view('pages::admin.training.create');
    }

    public function store(StoreTrainingRequest $request): RedirectResponse
    {
        Training::create([
            ...$request->validated(),
            'created_by' => $request->user()->id,
        ]);

        return redirect()->route('admin.training.index')->with('success', 'Training berhasil ditambahkan.');
    }

    public function show(Training $training): View
    {
        $training->load([
            'creator',
            'materials' => function ($query) {
                $query->orderBy('sort_order')->orderBy('created_at');
            },
            'tests' => function ($query) {
                $query->where('type', 'pre_test')
                    ->with(['questions' => function ($q) {
                        $q->orderBy('sort_order')->orderBy('created_at')
                            ->with(['options' => function ($oq) {
                                $oq->orderBy('sort_order')->orderBy('created_at');
                            }]);
                    }]);
            },
        ]);

        return view('pages::admin.training.show', compact('training'));
    }

    public function edit(Training $training): View
    {
        return view('pages::admin.training.edit', compact('training'));
    }

    public function update(UpdateTrainingRequest $request, Training $training): RedirectResponse
    {
        $training->update($request->validated());

        return redirect()->route('admin.training.show', $training)->with('success', 'Training berhasil diperbarui.');
    }

    public function destroy(Training $training): RedirectResponse
    {
        if ($training->participants()->exists()) {
            return redirect()->back()->with('error', 'Training sudah memiliki peserta. Arsipkan training untuk menjaga histori data.');
        }

        if ($training->materials()->exists() || $training->tests()->exists()) {
            return redirect()->back()->with('error', 'Training sudah memiliki materi atau test. Arsipkan training untuk menjaga histori data.');
        }

        $training->forceDelete();

        return redirect()->route('admin.training.index')->with('success', 'Training berhasil dihapus permanen.');
    }

    public function publish(Request $request, Training $training): RedirectResponse
    {
        if ($training->status === 'published') {
            return redirect()->back()->with('error', 'Training sudah dipublikasikan.');
        }

        if (! $request->boolean('force')) {
            $warnings = [];

            if (! $training->participants()->exists()) {
                $warnings[] = 'Belum ada peserta.';
            }

            if (! $training->materials()->exists()) {
                $warnings[] = 'Belum ada materi.';
            }

            if (! $training->tests()->where('type', 'pre_test')->exists()) {
                $warnings[] = 'Belum ada pre-test.';
            }

            if (! $training->tests()->where('type', 'post_test')->exists()) {
                $warnings[] = 'Belum ada post-test.';
            }

            if ($training->start_date && $training->start_date->isPast()) {
                $warnings[] = 'Tanggal mulai sudah lewat.';
            }

            if (filled($warnings)) {
                session()->flash('training_publish_warnings', $warnings);

                return redirect()->route('admin.training.show', $training);
            }
        }

        $training->update(['status' => 'published']);

        return redirect()->route('admin.training.show', $training)->with('success', 'Training berhasil dipublikasikan.');
    }

    public function close(Training $training): RedirectResponse
    {
        if (! in_array($training->status, ['published'])) {
            return redirect()->back()->with('error', 'Hanya training yang sudah dipublikasikan yang bisa ditutup.');
        }

        $training->update(['status' => 'closed']);

        return redirect()->route('admin.training.show', $training)->with('success', 'Training berhasil ditutup.');
    }

    public function archive(Training $training): RedirectResponse
    {
        if (! in_array($training->status, ['closed', 'draft'])) {
            return redirect()->back()->with('error', 'Hanya training dengan status closed atau draft yang bisa diarsipkan.');
        }

        $training->update(['status' => 'archived']);

        return redirect()->route('admin.training.index')->with('success', 'Training berhasil diarsipkan.');
    }
}
