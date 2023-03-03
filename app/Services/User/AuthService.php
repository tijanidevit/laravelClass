<?php

namespace App\Services\User;

use App\Mail\ForgotPassword;
use App\Models\PasswordReset;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Facade\FlareClient\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;


/**
 * Class AuthService.
 */
class AuthService
{
    public function register(array $data): object
    {
        $data['password'] = Hash::make($data['password']);
        $user = User::create($data);
        return $user;
    }
    public function login(array $data)
    {



        if (auth()->attempt($data)) {
            $token = auth()->user()->createToken(config('services.auth.token'))->accessToken;
            return $token;
        }

    }
    public function insertToken(array $data) : object
    {

       $user = PasswordReset::create($data);
        // $id = DB::table('password_resets')->insertGetID(
        //     [
        //         'email' => $data['email'],
        //         'token' => $data['token']
        //     ]);
       return  $user;


    }
    // public function sendEmail(array $data) : Response
    // {
    //       // send an Email
    //       Mail::to($data['email'])->send(new ForgotPassword($data['token']),['token'=>$data['token']]);

    //       if (Mail::failures()) {
    //           return $this->errorResponse('Sorry Please try again', 404);
    //       }else{
    //           return $this->successResponse('Email sent Successfully', $data,201);
    //          }

    // }


}