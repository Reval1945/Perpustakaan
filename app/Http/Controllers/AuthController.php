<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AuthService;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;

class AuthController extends Controller
{
    private AuthService $service;

    public function __construct(AuthService $service)
    {
        $this->service = $service;
    }

    public function login(LoginRequest $request)
    {
        return response()->json(
            $this->service->login($request),
            200
        );
    }

    public function register(RegisterRequest $request)
    {
        return response()->json(
            $this->service->register($request->all()),
            200
        );
    }

    public function logout(Request $request)
    {
        return response()->json(
            $this->service->logout($request),
            200
        );
    }

}

