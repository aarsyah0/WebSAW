<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RecommendationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'age_min' => ['required', 'integer', 'min:0', 'max:18'],
            'age_max' => ['required', 'integer', 'min:0', 'max:18', 'gte:age_min'],
            'budget_min' => ['required', 'numeric', 'min:0'],
            'budget_max' => ['required', 'numeric', 'min:0', 'gte:budget_min'],
            'priorities' => ['nullable', 'array'],
            'priorities.*' => ['nullable', 'numeric', 'min:1', 'max:5'],
        ];
    }
}
