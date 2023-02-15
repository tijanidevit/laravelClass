<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\Auth\ResetRequest;
use App\Http\Requests\User\Auth\SendResetPasswordRequest;
use App\Mail\ForgotPassword;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

use App\Http\Traits\ResponseTrait;
use Illuminate\Support\Facades\Log;

class ForgotPasswordController extends Controller
{
    use ResponseTrait;
    public function forgotPassword(SendResetPasswordRequest $request):object
    {

        $data = $request->validated();
        $email = $data['email'];
        $user = User::where('email', $data['email']);
        if(User::where('email', $email)->doesntExist())
        {
            return $this->errorResponse('User does not exist', 404);

        }
        $token = Str::random(10);
        try{
            DB::table('password_resets')->insert(
                [
                    'email' => $email,
                    'token' => $token
                ]
                );
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
            return $this->errorResponse('Invalid', 404);

        }
        if(!$user = User::where('email', $passwordResets->email)->first()){
            return $this->errorResponse('User does not exist', 404);
        }
        $user->password = Hash::make($request->input('password'));
        $user->save();
        return $this->successResponse('Password updated Successfully', ['token'=>$token],201);
    }
}
