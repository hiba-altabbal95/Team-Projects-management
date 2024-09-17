<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Services\AuthService;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{   
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function register(RegisterRequest $request)
    {

            
            $validatedUser = $this->authService->register($request->validated());
            
            return response()->json([
                'status' => 'success',
                'message' => 'User registered successfully',
                'user Information' => $validatedUser,
            ], 201);
       

    }


    public function login(LoginRequest $request)
    {
       
            
            $validatedUser = $this->authService->Login($request->validated());
            return response()->json([
                'status' => 'success',
                'message' => 'User loggedin successfully',
                'user Information' => $validatedUser,
            ], 201);
    }

    public function logout()
    {
        $this->authService->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }
}