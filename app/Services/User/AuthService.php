<?php

namespace App\Services\User;

use App\Models\User;

use App\Services\User\PasswordService;


/**
 * Class AuthService.
 */
class AuthService
{

    public function __construct(protected PasswordService $passwordService) {

    }
    public function register(array $data): object
    {
        return User::create($data);
    }
    public function login(array $data)
    {
        if (auth()->attempt($data)) {
            return auth()->user()->createToken(config('services.auth.token'))->accessToken;
        }

    }
}