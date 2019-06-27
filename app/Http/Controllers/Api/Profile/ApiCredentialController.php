<?php

namespace App\Http\Controllers\Api\Profile;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;

#Import Custom Requests
use App\Http\Requests\Profile\CredentialRequest;

#Import Custom Services
use App\Services\Profile\ApiCredential\StoreService;
use App\Services\Profile\ApiCredential\UpdateService;
use App\Services\Profile\ApiCredential\DeleteService;

#Import App Helper
use App\Helpers\QioskApp;

class ApiCredentialController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(CredentialRequest $request, StoreService $action): Response
    {
        $user = $action->execute($request->validated(), $request->user());

        //throw success when action executes succesfully
        return QioskApp::httpResponse(QioskApp::SUCCESS, 'New Api has been added!');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(CredentialRequest $request, UpdateService $action): Response
    {
        $data = array_merge(
            [
                'id' => $request->route('api_credential_id')
            ],
            $request->validated()
        );

        $action->execute($data, $request->user());

        //throw success when action executes succesfully
        return QioskApp::httpResponse(QioskApp::SUCCESS, 'New Api has been updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id, Request $request, DeleteService $action): Response
    {
        $action->execute(["id" => $id], $request->user());

        //throw success when action executes succesfully
        return QioskApp::httpResponse(QioskApp::SUCCESS, 'Your Api has been deleted!');
    }
}
