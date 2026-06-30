<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreTrainingMaterialRequest;
use App\Http\Requests\Admin\UpdateTrainingMaterialRequest;
use App\Models\Training;
use App\Models\TrainingMaterial;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class TrainingMaterialController extends Controller
{
    /**
     * ponytail: materials are always managed inside training show page.
     * No index/create views — redirect back to training show tab.
     */
    public function store(StoreTrainingMaterialRequest $request, Training $training): RedirectResponse
    {
        $data = $request->safe()->except(['file']);
        $data['training_id'] = $training->id;
        $data['is_downloadable'] = $request->boolean('is_downloadable', true);
        $data['is_required'] = $request->boolean('is_required');

        if ($request->input('type') === 'file' && $request->hasFile('file')) {
            $file = $request->file('file');
            $path = $file->storeAs('training-materials', uniqid().'.'.$file->getClientOriginalExtension(), 'public');
            $data['file_path'] = $path;
            $data['file_type'] = $file->getClientOriginalExtension();
            $data['is_downloadable'] = true;
        }

        $training->materials()->create($data);

        return redirect(route('admin.training.show', $training).'?tab=materi')->with('success', 'Materi berhasil ditambahkan.');
    }

    public function update(UpdateTrainingMaterialRequest $request, Training $training, TrainingMaterial $material): RedirectResponse
    {
        $data = $request->safe()->except(['file']);
        $data['is_downloadable'] = $request->boolean('is_downloadable', true);
        $data['is_required'] = $request->boolean('is_required');

        if ($request->input('type') === 'file' && $request->hasFile('file')) {
            if ($material->file_path && Storage::disk('public')->exists($material->file_path)) {
                Storage::disk('public')->delete($material->file_path);
            }

            $file = $request->file('file');
            $path = $file->storeAs('training-materials', uniqid().'.'.$file->getClientOriginalExtension(), 'public');
            $data['file_path'] = $path;
            $data['file_type'] = $file->getClientOriginalExtension();
            $data['is_downloadable'] = true;
        }

        $material->update($data);

        return redirect(route('admin.training.show', $training).'?tab=materi')->with('success', 'Materi berhasil diperbarui.');
    }

    public function toggleStatus(Request $request, Training $training, TrainingMaterial $material): RedirectResponse
    {
        $material->update([
            'status' => $material->status === 'active' ? 'inactive' : 'active',
        ]);

        $action = $material->status === 'active' ? 'diaktifkan' : 'dinonaktifkan';

        return redirect(route('admin.training.show', $training).'?tab=materi')->with('success', "Materi berhasil {$action}.");
    }

    public function destroy(Training $training, TrainingMaterial $material): RedirectResponse
    {
        if ($material->file_path && Storage::disk('public')->exists($material->file_path)) {
            Storage::disk('public')->delete($material->file_path);
        }

        $material->forceDelete();

        return redirect(route('admin.training.show', $training).'?tab=materi')->with('success', 'Materi berhasil dihapus permanen.');
    }

    public function preview(Training $training, TrainingMaterial $material): BinaryFileResponse|RedirectResponse
    {
        if ($material->type === 'link') {
            return redirect()->away($material->url);
        }

        if (! $material->file_path || ! Storage::disk('public')->exists($material->file_path)) {
            return redirect(route('admin.training.show', $training).'?tab=materi')->with('error', 'File tidak ditemukan.');
        }

        $path = Storage::disk('public')->path($material->file_path);
        $mimeType = Storage::disk('public')->mimeType($material->file_path);

        $inline = in_array($mimeType, ['application/pdf', 'image/jpeg', 'image/png', 'image/webp', 'video/mp4']);

        return response()->file($path, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => $inline ? 'inline' : 'attachment',
        ]);
    }

    public function download(Training $training, TrainingMaterial $material): StreamedResponse|RedirectResponse
    {
        if ($material->type === 'link') {
            return redirect()->away($material->url);
        }

        if (! $material->file_path || ! Storage::disk('public')->exists($material->file_path)) {
            return redirect(route('admin.training.show', $training).'?tab=materi')->with('error', 'File tidak ditemukan.');
        }

        return Storage::disk('public')->download($material->file_path);
    }
}
