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
            'priorities.harga' => ['nullable', 'numeric', 'min:1', 'max:5'],
            'priorities.kualitas' => ['nullable', 'numeric', 'min:1', 'max:5'],
            'priorities.keamanan' => ['nullable', 'numeric', 'min:1', 'max:5'],
            'priorities.edukasi' => ['nullable', 'numeric', 'min:1', 'max:5'],
            'priorities.popularitas' => ['nullable', 'numeric', 'min:1', 'max:5'],
        ];
    }
}
