<?php

namespace App\Http\Requests\Profile;

use Illuminate\Foundation\Http\FormRequest;
use App\Helpers\Traits\CustomizedValidationResponseTrait;
use Illuminate\Http\Request;

class CredentialRequest extends FormRequest
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
    public function rules(Request $request)
    {
        $uniqueness = $this->forUniqueValuesRule($request->segment(4));

        return [
            'app_name' => 'required|string|max:255|unique:user_external_api_credentials'.$uniqueness,
            'client_id' => 'required|string|min:5|max:255|unique:user_external_api_credentials'.$uniqueness,
            'client_secret' => 'required|string|min:5|unique:user_external_api_credentials'.$uniqueness
        ];
    }

    /**
     * Check if id of external api of the user is present in the URL segment
     * otherwise, rules will not be adjusted
     * @param  int    $id the ID in user_external_api_credentials table
     * @return string|null
     */
    private function forUniqueValuesRule(int $id)
    {
        if (!is_int($id)) {
            return '';
        }

        return ',id,'.$id;
    }
}
