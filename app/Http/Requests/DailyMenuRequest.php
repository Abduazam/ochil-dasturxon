<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DailyMenuRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'day' => 'required|date',
            'meals' => 'required|array',
            'meals.*' => 'integer'
        ];
    }
}
