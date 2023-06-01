<?php

declare(strict_types = 1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<string>|string>
     */
    public function rules(): array
    {
        return [
            'name'        => ['required', 'string', 'max:255', 'unique:products,name'],
            'description' => ['required', 'string', 'min:15'],
            'price'       => ['required', 'numeric', 'min:1'],
            'stock'       => ['required', 'numeric', 'min:1'],
            'categories'  => ['required', 'array', 'exists:categories,id'],
            'images'      => ['array'],
            'images.*'    => ['image', 'mimes:jpg,jpeg,png'],
        ];
    }
}