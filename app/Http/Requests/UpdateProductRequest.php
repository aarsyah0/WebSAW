<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'age_range' => ['nullable', 'string', 'max:50'],
            'category' => ['nullable', 'string', 'max:100'],
            'image' => ['nullable', 'image', 'max:2048'],
            'kualitas' => ['required', 'numeric', 'min:0', 'max:5'],
            'keamanan' => ['required', 'numeric', 'min:0', 'max:5'],
            'edukasi' => ['required', 'numeric', 'min:0', 'max:5'],
            'popularitas' => ['required', 'numeric', 'min:0', 'max:5'],
        ];
    }
}
