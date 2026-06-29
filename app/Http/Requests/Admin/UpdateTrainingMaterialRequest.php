<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTrainingMaterialRequest extends FormRequest
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
        $type = $this->input('type') ?? $this->route('material')?->type;

        $rules = [
            'title' => ['required', 'string', 'max:200'],
            'description' => ['nullable', 'string', 'max:5000'],
            'type' => ['required', 'in:file,link'],
            'url' => ['nullable', 'url', 'max:255'],
            'is_downloadable' => ['nullable', 'boolean'],
            'is_required' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'status' => ['required', 'in:active,inactive'],
            'file' => ['nullable', 'file', 'max:10240'],
        ];

        if ($type === 'file' && $this->hasFile('file')) {
            $rules['file'] = ['required', 'file', 'max:10240'];
            unset($rules['url']);
        }

        if ($type === 'link') {
            $rules['url'] = ['required', 'url', 'max:255'];
            unset($rules['file']);
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'url.required' => 'URL wajib diisi untuk tipe materi link.',
        ];
    }
}
