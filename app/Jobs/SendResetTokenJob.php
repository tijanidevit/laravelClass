<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use App\Mail\ForgotPassword;
use App\Models\PasswordReset;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendResetTokenJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(public PasswordReset  $data)
    {

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {


        Mail::to($this->data->email)->send(new ForgotPassword($this->data));
    }
}
