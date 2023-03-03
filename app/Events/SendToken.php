<?php

namespace App\Events;

use App\Models\PasswordReset;


use Illuminate\Broadcasting\InteractsWithSockets;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SendToken
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public  $user;

    public function __construct(PasswordReset $user)
    {

        $this->user = $user;




    }


}