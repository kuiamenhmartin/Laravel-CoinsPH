<?php

namespace App\Services\Server\CoinsPh\Adapters\Gateway;

use App\Exceptions\CustomException;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ValidateCodeAndStateService
{
    public function __construct()
    {
        //
    }

    /**
     * Validate that response MUST contains the CODE and STATE that will be validated here
     * STATE param ensures that no csrf attack happens it should give us valid state values
     * CODE will be used to generate the access_token that we need to connect to the api
     *
     * @method execute
     * @param  array $data array of data filters
     * @return array
     */
    public function execute(array $data): array
    {
        //Check if api data are all valid
        $this->validateParams($data);

        //Validated state
        $decodeState = \QioskApp::unserializeParams($data['state']);

        //Return Code and State
        return Arr::add($decodeState, 'code', $data['code']);
    }

    /**
     * Validate $parameters
     *
     * Return exception when validation fails
     *
     * @param array $params the data returned by calling api for the first time
     *
     * @return void
     */
    private function validateParams(array $params): void
    {
        $state = Arr::except($params, ['app_name']);

        $filteredParams = array_filter($state);

        throw_if(
            (count($filteredParams) <= 0 || (!array_key_exists('code', $filteredParams) || !array_key_exists('state', $filteredParams))),
            CustomException::class,
            'Code and State must be valid and/or not empty',
            403
        );

        throw_if(
            $this->checkIfStateIsValid($params['state'], $params['app_name']),
            CustomException::class,
            'State is invalid',
            403
        );
    }

    /**
     * Validate State, make sure it has the data that we originally pass to the api
     *
     * @param string $state will be converted to array to get the subid, created and appname
     * @param string $app_name name of the app that owns the api
     * @return boolean
     */
    private function checkIfStateIsValid(string $state, string $app_name): bool
    {
        // Decode and Unserialize state to extract subid, created and appname it contians
        $filteredState = array_filter(\QioskApp::unserializeParams($state));

        if (!array_key_exists('subid', $filteredState) || !array_key_exists('created', $filteredState)
        || !array_key_exists('app_name', $filteredState)) {
            return true;
        }

        if (Carbon::parse($filteredState['created']) >= Carbon::now()) {
            return true;
        }

        if ($filteredState['app_name'] !== $app_name) {
            return true;
        }

        return false;
    }
}
