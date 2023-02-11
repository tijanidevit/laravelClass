<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\Auth\ResetRequest;
use App\Http\Requests\User\Auth\SendResetPasswordRequest;
use App\Mail\ForgotPassword;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Mail\Message as MailMessage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Message;



class ForgotPasswordController extends Controller
{
    public function forgotPassword(SendResetPasswordRequest $request)
    {
        $email = $request->input('email');
        $data = $request->validated();
        $user = User::where('email', $data['email']);
        if(User::where('email', $email)->doesntExist())
        {
            return response([
                'message' => 'User does not exist',
            ],status:404);
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
                 return response()->json(['message'=> 'Sorry! Please try again latter']);
            }else{
                 return response()->json(['message'=>'Great! Successfully send in your mail']);
               }



        }catch(\Exception $e){
            return response()->json(
                [
                    'message' => $e->getMessage(),
                ],status:400
                );
        }


    }
    public function resetPassword(ResetRequest $request)
    {
        $token = $request->input('token');
        if (!$passwordResets = DB::table('password_resets')->where('token', $token)->first()){
            return response()->json([
                'message'=>'invalid token',
            ],status:400);

        }
        if(!$user = User::where('email', $passwordResets->email)->first()){
            return response()->json([
                'message' => 'User does not exist',
            ],404);
        }
        $user->password = Hash::make($request->input('password'));
        $user->save();
        return response()->json([
            'message' =>'Success'
        ]);
    }
}