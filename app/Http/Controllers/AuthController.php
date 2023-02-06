<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\Auth\LoginRequest;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;
use App\Http\Requests\User\Auth\RegisterRequest;
use App\Http\Traits\ResponseTrait;
use App\Services\User\AuthService;
use Illuminate\Support\Facades\Log;


class AuthController extends Controller
{
    use ResponseTrait;
    public function register(RegisterRequest $request)
    {

        $data = $request->validated();
        try {
            $token = (new AuthService)->register($data);
            return $this->successResponse('Registered Successfully', ['token' => $token, 201]);
        } catch (\Exception $ex) {
            Log::alert($ex->getMessage());
            return $this->serverError();
        }


    }
    public function login(Request $request)
    {
        $data = [
            'email' => $request->email,
            'password' => $request->password
        ];

        try {
            $token = (new AuthService)->login($data);
            return $this->successResponse('Login Success', ['token' => $token, 201]);
        } catch (\Exception $ex) {
            Log::alert($ex->getMessage());
            return $this->serverError();
        }
        // if (auth()->attempt($data)) {
        //     $token = auth()->user()->createToken('LaravelClassToken')->accessToken;
        //     return response()->json(['token' => $token], 202);
        // } else {
        //     return response()->json(
        //         ['error' => 'Unauthorised'], 401
        //     );
        // }
    }
    public function userDetails()
    {

     $user = auth()->user();

     return response()->json(['user' => $user], 200);

    }

    public function resetPassword(Request $request)
    {



        // find user by id
        $user = auth()->user();
        // $user = User::find($id);
        $data = $this->validate($request, [
            'password' => 'required|min:8',
        ]);
        $user->save();


            $user->password = Hash::make($data['password']);

            // check if user  password is updated
            if($user->isDirty('password'))
            {
                $token = $user->createToken('LaravelClassToken')->accessToken;
                return response()->json([
                    'message' => 'Password reset sucessfully ',
                    'token' => $token
                ], 200);

            }
            else
            {
                // if the user password not updated
                return response()->json([
                    'error' => 'Password not reset'
                ], 401);

            }


    }

}
