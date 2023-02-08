<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\User\AuthService;
use App\Http\Traits\ResponseTrait;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    use ResponseTrait;
    public function register(RegisterRequest $request): Response
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


        if (auth()->attempt($data)) {
            $token = auth()->user()->createToken('LaravelClassToken')->accessToken;
            return response()->json(['token' => $token], 200);
        } else {
            return response()->json(
                ['error' => 'Unauthorised'], 401
            );
        }
    }
    public function resetPassword(Request $request,$id)
    {
        // find user by id
        $user = User::findByID($id);
        $this->validate($request, [
            'password' => 'required|min:8',
        ]);

        // if user not in the model
        if (!$user)
        {
            return response()->json([
                'error' => 'User not found '
            ], 404);
        }
        else
        {
            // if user is present update the password

            $user->password = Hash::make($request->get('password'));
            $user->save();
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

}
