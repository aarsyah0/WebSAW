<?php

namespace App\Http\Requests;

use App\Models\Criteria;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'age_range' => ['nullable', 'string', 'max:50'],
            'category' => ['nullable', 'string', 'max:100'],
            'image' => ['nullable', 'image', 'max:2048'],
            'criteria_values' => ['nullable', 'array'],
        ];

        foreach (Criteria::orderBy('weight_order')->get() as $c) {
            if ($c->name === 'Harga') {
                continue;
            }
            $rules['criteria_values.'.$c->id] = ['required', 'numeric', 'min:0', 'max:5'];
        }

        return $rules;
    }
}
