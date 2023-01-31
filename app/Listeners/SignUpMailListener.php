<?php

namespace App\Listeners;

use App\Mail\SignUpMail;
use App\Events\SignUpMailEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SignUpMailListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Providers\SignUpMailEvent  $event
     * @return void
     */
    public function handle(SignUpMailEvent $event)
    {
        Mail::to($event->user->email)->send(new SignUpMail($event->user));

    }
}