<?php

namespace App\Listeners;

use App\Events\UserSignedUpEvent;
use App\Events\UserResetPasswordRequestEvent;
use App\Events\UserResetPasswordSuccessEvent;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Jobs\SignUpEmailNotificationJob;
use App\Jobs\ResetPasswordRequestEmailNotificationJob;
use App\Jobs\ResetPasswordSucessEmailNotificationJob;

use Log;

class UserEventListener
{
    /**
     *  Send Email when user registers
     */
    public function onUserSignUp(UserSignedUpEvent $event)
    {
       dispatch(new SignUpEmailNotificationJob($event->user));

       //Save log file after user is created
       Log::info("A new user has been registered : {$event->user['email']}");
    }

    /**
     *  Send Email when user resets his password
     */
    public function onUserResetPasswordRequest(UserResetPasswordRequestEvent $event)
    {
       dispatch(new ResetPasswordRequestEmailNotificationJob($event->passwordReset));

       //Save log file after user is created
       Log::info("A user has requested to reset his password : {$event->passwordReset['email']}");
    }

    /**
     *  Send Email when user reset password is successfull
     */
    public function onUserResetPasswordSuccess(UserResetPasswordSuccessEvent $event)
    {
       dispatch(new ResetPasswordSucessEmailNotificationJob($event->user));

       //Save log file after user is created
       Log::info("A user has successfully reset his password : {$event->user['email']}");
    }


    /**
     * Register the listeners for the subscriber.
     *
     * @param  Illuminate\Events\Dispatcher  $events
     */
    public function subscribe($events)
    {
        $events->listen(
            'App\Events\UserSignedUpEvent',
            'App\Listeners\UserEventListener@onUserSignUp'
        );

        $events->listen(
            'App\Events\UserResetPasswordRequestEvent',
            'App\Listeners\UserEventListener@onUserResetPasswordRequest'
        );

        $events->listen(
            'App\Events\UserResetPasswordSuccessEvent',
            'App\Listeners\UserEventListener@onUserResetPasswordSuccess'
        );
    }
}
