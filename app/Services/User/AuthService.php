<?php

namespace App\Services\User;

use Illuminate\Support\Facades\Hash;
use App\Models\User;


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

}
