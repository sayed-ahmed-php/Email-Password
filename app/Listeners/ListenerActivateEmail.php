<?php

namespace App\Listeners;

use App\Events\EventActivateEmail;
use App\Mail\MailActivation;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class ListenerActivateEmail
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
     * @param  EventActivateEmail  $event
     * @return void
     */
    public function handle(EventActivateEmail $event)
    {
        if ($event->user->active){
            return;
        }

        Mail::to($event->user->email) -> send(new MailActivation($event->user));
    }
}
