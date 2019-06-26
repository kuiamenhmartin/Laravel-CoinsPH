<?php

namespace App\Http\Controllers\Api\Profile;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

#Import Custom Requests
use App\Http\Requests\Profile\CredentialRequest;

#Import Custom Services
use App\Services\Profile\ApiCredential\StoreService;
use App\Services\Profile\ApiCredential\UpdateService;

#Import App Helper
use App\Helpers\QioskApp;

class ApiCredentialController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(CredentialRequest $request, StoreService $action)
    {
        // return QioskApp::checkAction(function () use ($request, $action) {

            $user = $action->execute($request->validated(), $request->user());

            //throw success when action executes succesfully
            return QioskApp::httpResponse(QioskApp::SUCCESS, ['message' => 'New Api has been added!'], 200);
        // });
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(CredentialRequest $request, UpdateService $action)
    {
        // return QioskApp::checkAction(function () use ($request, $action) {
            $data = array_merge(
              [
                'id' => $request->route('api_credential_id')
              ],
              $request->validated()
            );

            $user = $action->execute($data, $request->user());

            //throw success when action executes succesfully
            return QioskApp::httpResponse(QioskApp::SUCCESS, ['message' => 'New Api has been updated!'], 200);
        // });
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
