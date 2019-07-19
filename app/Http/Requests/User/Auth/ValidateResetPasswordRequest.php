<?php

namespace App\Http\Requests\User\Auth;

use Illuminate\Foundation\Http\FormRequest;
use App\Helpers\Traits\CustomizedValidationResponseTrait;

class ValidateResetPasswordRequest extends FormRequest
{
    use CustomizedValidationResponseTrait;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check() ? false : true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'password' => 'required|string|min:6|confirmed',
            'token' => 'required|string',
        ];
    }
}
