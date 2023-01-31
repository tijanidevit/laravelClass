<?php

namespace App\Services;

use App\Events\SignUpMailEvent;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Response;

class UserAuthService
{
  use ApiResponse;


  public function index()
  {
    $user = User::all();
    return $this->onSuccess("success", $user, "User list", Response::HTTP_OK);
  }


  public function register($request)
  {
    // $user = User::create([
    //     'name' => $request->name,
    //     'email' => $request->email,
    //     'password' => bcrypt($request->password)
    // ]);
   
    // $data = $user;

    // return response()->json(['token' => $token, 'data'=>$data,], 200);

    $user = User::create($request);
    if ($user) {
      SignUpMailEvent::dispatch($user);
        $token = $user->createToken('LaravelAuthApp')->accessToken;

      return $this->onSuccess("success", ['token' => $token, 'data'=>$user], "User saved successfully", Response::HTTP_OK);
    }
    return $this->onFailled("failled", "User not saved", Response::HTTP_BAD_REQUEST);
  }

 }