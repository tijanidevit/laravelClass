<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\Auth\{LoginRequest,RegisterRequest,ResetPasswordRequest};
use App\Services\User\AuthService;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    protected AuthService $authService;

    public function __construct(AuthService $authService){
        $this->authService = $authService;
    }

    public function register(RegisterRequest $request)
    {
        $data = $request->validated();
        try {
            $token = $this->authService->register($data);
            return $this->successResponse('Registered Successfully', ['token' => $token, 201]);
        } catch (\Exception $ex) {
            Log::alert($ex->getMessage());
            return $this->serverError();
        }
    }    
   
    public function login(LoginRequest $request)
    {
        $data = $request->validated();

        try {
            if($token = $this->authService->login($data)){
                return $this->successResponse('Login Success', ['token' => $token]);
            }
            else {
                return $this->errorResponse("Invalid Login",401);
            }

        } catch (\Exception $ex) {
            Log::alert($ex->getMessage());
            return $this->errorResponse("Something went wrong",401);
        }

    }

    public function resetPassword(ResetPasswordRequest $request)
    {

        $data = $request->validated();
        try {
            if($token = $this->authService->resetPassword($data)){
                return $this->successResponse('Login Success', ['token' => $token]);
            }
            else {
                return $this->errorResponse("Not updated",401);
            }
        } catch (\Exception $ex) {
            Log::alert($ex->getMessage());
            return $this->serverError();
        }


    }

}