<?php

namespace App\Http\Controllers;

use App\Events\RegistrationSucessful;
use App\Http\Requests\User\Auth\LoginRequest;
use App\Mail\ForgotPassword;

use Illuminate\Support\Str;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;
use App\Http\Requests\User\Auth\RegisterRequest;
use App\Http\Requests\User\Auth\SendResetPasswordRequest;


use App\Models\User;
use App\Http\Requests\User\Auth\ResetRequest;
use App\Http\Traits\ResponseTrait;

use App\Services\User\AuthService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    protected AuthService $authService;
    use ResponseTrait;

    public function __construct(AuthService $authService){
        $this->authService = $authService;
    }

    public function register(RegisterRequest $request):object
    {
        $data = $request->validated();
        try {
            $token = $this->authService->register($data);
            RegistrationSucessful::dispatch($data);
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
    public function forgotPassword(SendResetPasswordRequest $request):object
    {

        $data = $request->validated();
        $email = $data['email'];


        if(!User::where('email', $data['email'])->first())
        {
            return $this->errorResponse('User does not exist', 404);

        }
        $token = Str::random(10);
        try{
            $data = ['email' => $email,  'token' => $token];
            $this->authService->insertToken($data);

            // send an Email
            Mail::to('basharu83@gmail.com')->send(new ForgotPassword($token),['token'=>$token]);

            if (Mail::failures()) {
                return $this->errorResponse('Sorry Please try again', 404);
            }else{
                return $this->successResponse('Email sent Successfully', $data,201);
               }

        }catch(\Exception $e){
            Log::alert($e->getMessage());
            return $this->errorResponse("Something went wrong",401);

        }


    }
    public function resetPassword(ResetRequest $request):object
    {
        $data = $request->validated();
        $token = $data['token'];
        if (!$passwordResets = DB::table('password_resets')->where('token', $token)->first()){
            return $this->errorResponse('Invalid token provided', 404);

        }
        $user = User::where('email', $passwordResets->email)->first();
        if(!$user = User::where('email', $passwordResets->email)->first()){
            return $this->errorResponse('User does not exist', 404);
        }
        $user->password = Hash::make($request->input('password'));
        $user->save();
        return $this->successResponse('Password updated Successfully', ['token'=>$token],201);
    }


    public function logout(Request $request):object
    {
        $request->user()->token()->revoke();
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }


}