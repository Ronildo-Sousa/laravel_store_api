<?php

declare(strict_types = 1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
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
            'name'        => ['string', 'max:255', 'unique:products,name'],
            'description' => ['string', 'min:15'],
            'price'       => ['numeric', 'min:1'],
            'stock'       => ['numeric', 'integer', 'min:1'],
        ];
    }
}
