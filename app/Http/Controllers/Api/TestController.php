<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;

class TestController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth:api');
    }

    public function listings(Request $request)
    {
        dd($request);
        return response()->json($request->user());
    }
}
