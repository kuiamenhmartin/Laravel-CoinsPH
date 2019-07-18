<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

# Import Custom Requests
use App\Http\Requests\User\Auth\ValidateEmailRequest;
use App\Http\Requests\User\Auth\ValidateResetPasswordRequest;

# Import Custom Services
use App\Services\User\Auth\ResetPassword\CreateResetPasswordRequestService;
use App\Services\User\Auth\ResetPassword\FindResetPasswordTokenService;
use App\Services\User\Auth\ResetPassword\ResetPasswordService;

use QioskApp;

class PasswordResetController extends Controller
{
    /**
     * Create token password reset
     *
     * @param  $request Validate request
     * @return $action Service to process reset password request
     */
    public function create(ValidateEmailRequest $request, CreateResetPasswordRequestService $action): Response
    {
        $user = $action->execute($request->all());

        return QioskApp::httpResponse(QioskApp::SUCCESS, 'We have e-mailed your password reset link!');
    }
    /**
     * Find token password reset
     *
     * @param  [string] $token
     * @return [string] message
     * @return [json] passwordReset object
     */
    public function find($token, FindResetPasswordTokenService $action): Response
    {
        $passwordReset = $action->execute([$token]);

        return QioskApp::httpResponse(QioskApp::SUCCESS, 'Reset password allowed!', ['info' => $passwordReset]);
    }
     /**
     * Reset password
     *
     * @param  [string] email
     * @param  [string] password
     * @param  [string] password_confirmation
     * @param  [string] token
     * @return [string] message
     * @return [json] user object
     */
    public function reset(ValidateResetPasswordRequest $request, ResetPasswordService $action): Response
    {
        $passwordReset = $action->execute($request->all());

        return QioskApp::httpResponse(QioskApp::SUCCESS, 'Password has been reset!', ['info' => $passwordReset]);
    }
}
