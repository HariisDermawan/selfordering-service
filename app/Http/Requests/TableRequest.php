<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TableRequest extends FormRequest
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
        $table = $this->route('table');
        $tableId = is_object($table) ? $table->id : $table;

        return [
            'table_number' => [
                'required',
                'string',
                'max:255',
                Rule::unique('tables', 'table_number')->ignore($tableId),
            ],
            'capacity' => ['nullable', 'integer', 'min:1'],
            'status' => ['nullable', Rule::in(['available', 'occupied', 'reserved', 'maintenance'])],
            'qr_code' => ['nullable', 'string', 'max:255'],
        ];
    }
}
