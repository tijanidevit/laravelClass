<?php

namespace App\Listeners;

use App\Mail\ForgotPassword;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use App\Events\SendToken;

class SendResetTokenToEmail
{

    public function handle($event)
    {

        $user = $event->user;

        Mail::to($user->email)->send(new ForgotPassword($user));

    }
}