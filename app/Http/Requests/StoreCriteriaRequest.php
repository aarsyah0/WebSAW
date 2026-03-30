<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCriteriaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        $criteriaId = $this->route('criteria')?->id ?? $this->route('criteria');

        return [
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:cost,benefit'],
            'weight_order' => [
                'nullable',
                'integer',
                'min:0',
                Rule::unique('criterias', 'weight_order')->ignore($criteriaId),
            ],
        ];
    }
}
