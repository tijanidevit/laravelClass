<?php

namespace App\Services\User;

use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

/**
 * Class AuthService.
 */
class AuthService
{
    public function register(array $data): string
    {
        $data['password'] = Hash::make($data['password']);
        $user = User::create($data);
        $token = $user->createToken('LaravelClassToken')->accessToken;
        return $token;
    }
    public function login(array $data)
    {
        if (auth()->attempt($data)) {
            $token = auth()->user()->createToken('LaravelClassToken')->accessToken;
            return $token;
        } else {
            return response()->json(
                ['error' => 'Unauthorised'], 401
            );
        }

    }
}