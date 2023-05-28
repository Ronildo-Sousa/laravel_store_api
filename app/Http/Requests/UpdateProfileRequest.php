<?php

declare(strict_types = 1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
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
            'first_name'       => ['string', 'max:255'],
            'last_name'        => ['string', 'max:255'],
            'current_password' => ['string', 'min:8', 'required_unless:password,null'],
            'password'         => ['string', 'min:8', 'confirmed'],
        ];
    }
}
