<?php

namespace App\Http\Requests\Profile;

use Illuminate\Foundation\Http\FormRequest;
use App\Helpers\Traits\CustomizedValidationResponseTrait;
use Illuminate\Http\Request;
// use Waavi\Sanitizer\Laravel\SanitizesInput;

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
        /**
         * This is for updateRecord which will get the credential_id passed as parameter in the url
         * and compare against the rules for unique values except this record being edited.
         * For adding new record and empty string will be returned
         * @var string
         */
        $uniqueValue = $this->forUniqueValueRule($request->route('api_credential_id'));

        return [
            'app_name' => "required|string|max:255|unique:user_external_api_credentials,app_name{$uniqueValue}",
            'client_id' => "required|string|min:5|max:255|unique:user_external_api_credentials,client_id{$uniqueValue}",
            'client_secret' => "required|string|min:5|unique:user_external_api_credentials,client_secret{$uniqueValue}"
        ];
    }

    /**
     * Check if record id of external api is present in the URL segment
     * otherwise, return emptry string
     * @param  int $id the ID in user_external_api_credentials table
     * @return string
     */
    private function forUniqueValueRule($id): string
    {
        if (is_null($id)) {
            return "";
        }

        return ",{$id}";
    }
}
