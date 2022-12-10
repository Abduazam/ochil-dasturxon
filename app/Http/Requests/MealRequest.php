<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MealRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required',
            'price' => 'required|numeric',
            'img' => 'mimes:jpg,png,jpeg,gif',
        ];
    }
}
