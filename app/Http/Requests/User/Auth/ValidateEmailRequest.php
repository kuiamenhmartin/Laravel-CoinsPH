<?php

namespace App\Http\Requests\User\Auth;

use Illuminate\Foundation\Http\FormRequest;
use App\Helpers\Traits\CustomizedValidationResponseTrait;

class ValidateEmailRequest extends FormRequest
{
    use CustomizedValidationResponseTrait;
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'required|string|email|max:255'
        ];
    }
}