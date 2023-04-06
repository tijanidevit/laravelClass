<?php

namespace App\Services\User;

use Illuminate\Support\Str;
use App\Models\PasswordReset;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

/**
 * Class PasswordService.
 */
class PasswordService
{
    public function __construct() {

    }
    public function insertToken(array $data) : object
    {
        $token = Str::random(10);
        $data = ['email' => $data['email'],  'token' => $token];

       return PasswordReset::create($data);

    }
    public function resetPassword(array $data) : int
    {

        $passwordResets = PasswordReset::where('token', $data['token'])->first();
        return User::where('email', $passwordResets->email)
        ->update([
            'password' => Hash::make($data['password'])
        ]);


    }
}