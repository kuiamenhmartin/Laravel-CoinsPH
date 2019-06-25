<?php

namespace App\Helpers\Traits;

use Illuminate\Http\JsonResponse;

trait CustomizedValidationResponseTrait
{
    /**
     * Return formatted failed validaiton response
     * following the required response format of this app
     * @param  IlluminateContractsValidationValidator $validator Contains validation instance
     * @return JsonResponse Json encoded failed validation response
     */
    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        $response = new JsonResponse([
                 'status' => \QioskApp::FORM_ERROR,
                 'payload' => [
                    'message' => 'The given data is invalid',
                    'errors' => $validator->errors()
                 ]], 422);

        throw new \Illuminate\Validation\ValidationException($validator, $response);
    }
}
