<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTrainingParticipantRequest extends FormRequest
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
            'assignment_type' => ['required', 'in:all,department,position,employees'],
            'department_id' => [
                Rule::requiredIf($this->input('assignment_type') === 'department'),
                'integer',
                'exists:departments,id',
            ],
            'position_id' => [
                Rule::requiredIf($this->input('assignment_type') === 'position'),
                'integer',
                'exists:positions,id',
            ],
            'employee_ids' => [
                Rule::requiredIf($this->input('assignment_type') === 'employees'),
                'array',
                'min:1',
            ],
            'employee_ids.*' => [
                'integer',
                'exists:employees,id',
            ],
        ];
    }

    /**
     * Get custom error messages.
     */
    public function messages(): array
    {
        return [
            'assignment_type.required' => 'Tipe penugasan wajib dipilih.',
            'assignment_type.in' => 'Tipe penugasan tidak valid.',
            'department_id.required' => 'Departemen wajib dipilih saat penugasan berdasarkan departemen.',
            'department_id.exists' => 'Departemen tidak ditemukan.',
            'position_id.required' => 'Jabatan wajib dipilih saat penugasan berdasarkan jabatan.',
            'position_id.exists' => 'Jabatan tidak ditemukan.',
            'employee_ids.required' => 'Pilih minimal satu karyawan.',
            'employee_ids.min' => 'Pilih minimal satu karyawan.',
            'employee_ids.*.exists' => 'Karyawan tidak ditemukan.',
        ];
    }
}
