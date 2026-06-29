<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class UpdatePostTestQuestionRequest extends FormRequest
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
        return [
            'question_text' => ['required', 'string'],
            'question_type' => ['required', 'in:multiple_choice,essay'],
            'score' => ['required', 'numeric', 'min:0'],
            'sort_order' => ['required', 'integer', 'min:0'],
            'status' => ['required', 'in:active,inactive'],
            'options' => ['nullable', 'array'],
            'options.*.option_label' => ['required_with:options', 'string', 'max:10'],
            'options.*.option_text' => ['required_with:options', 'string'],
            'options.*.is_correct' => ['nullable', 'boolean'],
        ];
    }

    /**
     * @return array<int, callable(Validator): void>
     */
    public function after(): array
    {
        return [
            function (Validator $validator): void {
                if ($this->input('question_type') !== 'multiple_choice') {
                    return;
                }

                $hasCorrectOption = collect($this->input('options', []))
                    ->contains(fn (mixed $option): bool => is_array($option) && filter_var($option['is_correct'] ?? false, FILTER_VALIDATE_BOOLEAN));

                if (! $hasCorrectOption) {
                    $validator->errors()->add('options', 'Minimal satu opsi jawaban harus benar untuk pilihan ganda.');
                }
            },
        ];
    }
}
