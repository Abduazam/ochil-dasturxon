<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrgansRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|min:3',
        ];
    }
}
