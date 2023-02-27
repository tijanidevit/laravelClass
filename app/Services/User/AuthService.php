<?php

namespace App\Services\User;

use App\Mail\ForgotPassword;
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
    public function register(array $data): string
    {
        $data['password'] = Hash::make($data['password']);
        $user = User::create($data);
        $token = $user->createToken(config('services.auth.token'))->accessToken;
        return $token;
    }
    public function login(array $data)
    {



        if (auth()->attempt($data)) {
            $token = auth()->user()->createToken(config('services.auth.token'))->accessToken;
            return $token;
        }

    }
    public function insertToken(array $data) : void
    {
        DB::table('password_resets')->insert(
            [
                'email' => $data['email'],
                'token' => $data['token']
            ]);
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