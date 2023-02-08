<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\Auth\LoginRequest;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;
use App\Http\Requests\User\Auth\RegisterRequest;

use App\Http\Requests\User\Auth\ResetPasswordRequest;

use App\Http\Traits\ResponseTrait;
use App\Services\User\AuthService;
use Illuminate\Support\Facades\Log;


class AuthController extends Controller
{
    use ResponseTrait;
    public function register(RegisterRequest $request)
    {

        $data = $request->validated();
        try {
            $token = (new AuthService)->register($data);
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
            if($token = (new AuthService)->login($data)){
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
    public function userDetails()
    {

     $user = auth()->user();

     return response()->json(['user' => $user], 200);

    }

    public function resetPassword(ResetPasswordRequest $request)
    {

        $data = $request->validated();
        try {
            if($token = (new AuthService)->resetPassword($data)){
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