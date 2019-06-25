<?php

namespace App\Listeners;

use App\Events\UserSignedUpEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Jobs\ProcessSignUpEmailNotificationJob;

use Log;

class SendConfirmationMailListener implements ShouldQueue
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
     * @param  UserSignedUp  $event
     * @return void
     */
    public function handle(UserSignedUpEvent $event)
    {
        //send confirmation email once user signed up
        //
        dispatch(new ProcessSignUpEmailNotificationJob($event->user));

        //Save log file after user is created
        Log::info("2nd time : {$event->user['email']}");
    }
}
