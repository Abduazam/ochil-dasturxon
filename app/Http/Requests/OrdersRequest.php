<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrdersRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'meal_id' => 'required|integer',
            'count' => 'required|integer',
            'user_id' => 'required|integer'
        ];
    }
}
