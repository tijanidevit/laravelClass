<?php

namespace App\Listeners;

use App\Mail\RegistrationConfirmation;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendRegistrationConfirmationEmail implements ShouldQueue
{

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $user = $event->user;
        Mail::to($user->email)->send(new RegistrationConfirmation($user));
    }
}
