<?php

namespace App\Listeners;

use App\Events\UserSignedUpEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Jobs\ProcessSignUpEmailNotificationJob;

use Log;

class UserEventListener
{
    /**
     *  Send Email when user registers
     */
    public function onUserSignUp(UserSignedUpEvent $event)
    {
       //send confirmation email once user signed up
       //
       dispatch(new ProcessSignUpEmailNotificationJob($event->user));

       //Save log file after user is created
       Log::info("A new user has been registered : {$event->user['email']}");
    }

    /**
     * Handle user login events.
     */
    public function onUserLogin($event)
    {
        //Save log file after user logged in
        Log::info("User is now logged in!");
    }

    /**
     * Handle user logout events.
     */
    public function onUserLogout($event)
    {
        //Save log file after user is logged out
        Log::info("User is now logged out!");
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
            'App\Events\UserLoginEvent',
            'App\Listeners\UserEventListener@onUserLogin'
        );

        $events->listen(
            'App\Events\UserLogoutEvent',
            'App\Listeners\UserEventListener@onUserLogout'
        );
    }
}
