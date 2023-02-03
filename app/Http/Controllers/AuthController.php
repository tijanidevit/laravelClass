<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{
    public function register(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|min:4',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        $token = $user->createToken('LaravelClassToken')->accessToken;
        $response = [
            'token' => $token,
            'user'=>$user,
        ];

        return response()->json(['token' => $token], 200);
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
