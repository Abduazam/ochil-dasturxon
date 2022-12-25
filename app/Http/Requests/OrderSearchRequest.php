<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderSearchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'organ_id' => 'numeric|nullable',
            'first_date' => 'date|required',
            'second_date' => 'date|required'
        ];
    }
}
