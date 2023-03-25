<?php

namespace App\Services\User;

use App\Models\User;
use App\Mail\ForgotPassword;
use App\Models\PasswordReset;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Facade\FlareClient\Http\Response;
use App\Services\User\PasswordService;


/**
 * Class AuthService.
 */
class AuthService
{
    public PasswordService $passwordService;
    public function __construct(PasswordService $passwordService) {
        $this->passwordService = $passwordService;
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



	/**
	 * @return PasswordService
	 */
	public function getPasswordService(): App\Services\User\PasswordService {
		return $this->passwordService;
	}

	/**
	 * @param PasswordService $passwordService
	 * @return self
	 */
	public function setPasswordService(App\Services\User\PasswordService $passwordService): self {
		$this->passwordService = $passwordService;
		return $this;
	}
}