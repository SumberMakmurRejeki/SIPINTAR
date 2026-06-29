<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreTrainingMaterialRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'title' => ['required', 'string', 'max:200'],
            'description' => ['nullable', 'string', 'max:5000'],
            'type' => ['required', 'in:file,link'],
            'url' => ['nullable', 'url', 'max:255'],
            'is_downloadable' => ['nullable', 'boolean'],
            'is_required' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'status' => ['required', 'in:active,inactive'],
            'file' => ['nullable', 'file'],
        ];

        if ($this->input('type') === 'file') {
            $rules['file'] = ['required', 'file', 'max:10240'];
            unset($rules['url']);
        }

        if ($this->input('type') === 'link') {
            $rules['url'] = ['required', 'url', 'max:255'];
            unset($rules['file']);
        }

        return $rules;
    }

    /**
     * ponytail: keep it simple — conditional rules instead of After hook.
     */
    public function messages(): array
    {
        return [
            'file.required' => 'File wajib diunggah untuk tipe materi file.',
            'url.required' => 'URL wajib diisi untuk tipe materi link.',
        ];
    }
}
