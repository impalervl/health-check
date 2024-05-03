<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HealthCheckRequest extends FormRequest
{

    public const OWNER_HEADER = 'X-Owner';

    /**
     * @return void
     */
    public function prepareForValidation()
    {
        $this->merge([
            'x_owner' => $this->headers->get(self::OWNER_HEADER),
        ]);
    }


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'x_owner' => 'required|uuid'
        ];
    }
}
