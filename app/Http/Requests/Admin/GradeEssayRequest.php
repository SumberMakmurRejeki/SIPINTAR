<?php

namespace App\Http\Requests\Admin;

use App\Models\TestAttempt;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class GradeEssayRequest extends FormRequest
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
            'scores' => ['required', 'array'],
            'scores.*' => ['required', 'numeric', 'min:0'],
        ];
    }

    /**
     * @return array<int, callable(Validator): void>
     */
    public function after(): array
    {
        return [
            function (Validator $validator): void {
                $attempt = $this->route('attempt');

                if (! $attempt instanceof TestAttempt) {
                    return;
                }

                $essayAnswers = $attempt->answers()
                    ->with('question')
                    ->whereHas('question', fn ($query) => $query->where('question_type', 'essay'))
                    ->get();

                $scores = $this->input('scores', []);

                foreach ($essayAnswers as $answer) {
                    $key = (string) $answer->id;

                    if (! array_key_exists($key, $scores)) {
                        $validator->errors()->add("scores.{$key}", 'Nilai essay wajib diisi.');

                        continue;
                    }

                    if ((float) $scores[$key] > (float) $answer->question->score) {
                        $validator->errors()->add("scores.{$key}", 'Nilai essay tidak boleh melebihi skor maksimal soal.');
                    }
                }

                foreach (array_keys($scores) as $answerId) {
                    if (! $essayAnswers->contains('id', (int) $answerId)) {
                        $validator->errors()->add("scores.{$answerId}", 'Jawaban essay tidak valid.');
                    }
                }
            },
        ];
    }
}
