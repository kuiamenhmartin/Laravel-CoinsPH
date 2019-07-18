<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\PasswordReset;

use App\Services\User\Auth\ResetPassword\SendResetPasswordRequestEmailInterface;

class ResetPasswordRequestEmailNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $passwordReset;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(PasswordReset $passwordReset)
    {
        $this->passwordReset = $passwordReset;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(SendResetPasswordRequestEmailInterface $sendRequestEmail)
    {
        $sendRequestEmail->sendEmail($this->passwordReset);
    }
}
