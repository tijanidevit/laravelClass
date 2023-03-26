<?php

namespace App\Http\Controllers;

use App\Events\RegistrationSucessful;
use App\Events\SendToken;
use App\Http\Requests\User\Auth\LoginRequest;
use App\Mail\ForgotPassword;

use Illuminate\Support\Str;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;
use App\Http\Requests\User\Auth\RegisterRequest;
use App\Http\Requests\User\Auth\SendResetPasswordRequest;


use App\Models\User;
use App\Http\Requests\User\Auth\ResetTokenRequest;
use App\Http\Traits\ResponseTrait;
use App\Models\PasswordReset;
use App\Services\User\AuthService;
use App\Services\User\PasswordService;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{


    use ResponseTrait;

    public function __construct(protected AuthService $authService, protected PasswordService $passwordService){

    }

    public function register(RegisterRequest $request)
    {
        $data = $request->validated();

        try {
            $user = $this->authService->register($data);
            $token = $user->createToken(config('services.auth.token'))->accessToken;
            RegistrationSucessful::dispatch($user);

            return $this->successResponse('Registered Successfully', ['token' => $token, 201]);
        } catch (\Exception $ex) {
            Log::alert($ex->getMessage());
            return $this->serverError();
        }


    }

    public function login(LoginRequest $request):object
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
    public function forgotPassword(SendResetPasswordRequest $request) : object
    {
        try{

           $data =  $this->passwordService->insertToken($request->validated());
             SendToken::dispatch($data);
            return $this->successResponse('Email sent Successfully', $data,201);
        }catch(\Exception $e){
            Log::alert($e->getMessage());
            return $this->errorResponse("Something went wrong",401);

        }

    }
    public function resetPassword(ResetTokenRequest $request):object
    {
        $data = $request->validated();
        try{
           $data = $this->passwordService->resetPassword($data);
           return $this->successResponse('Password updated Successfully', $data,201);
        }catch(\Exception $e){
            Log::alert($e->getMessage());
            return $this->errorResponse("Something went wrong",401);
        }
        // $token = $data['token'];
        // $passwordResets = PasswordReset::where('token', $token)->first;
        // $user = User::where('email', $passwordResets->email)->first();
        // if(!$user){
        //     return $this->errorResponse('User does not exist', 404);
        // }
        // $user->password = Hash::make($request->input('password'));
        // $user->save();

    }


    public function logout(Request $request):object
    {
        $request->user()->token()->revoke();
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }


}