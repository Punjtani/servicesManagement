<?php

namespace App\Listeners;

use App\Events\LoginInfoEmailEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Mail;
use Auth;
class LoginInfoEmailListener
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
     * @param  LoginInfoEmailEvent  $event
     * @return void
     */
    public function handle(LoginInfoEmailEvent $event)
    {

        try{
            $emails = [config('mail.admin_address'), $event->data->email];
            $ccEmail = config('mail.cc_address');
            $data = $event->data;
            Mail::send('email_template/sign_in',compact('data'), function($mailData) use ($emails,$ccEmail){
            $mailData->to($emails);
            $mailData->cc($ccEmail);
            $mailData->from(config('mail.from_address'));
            $mailData->subject('Sign In Information!');
        });
    } catch(\Exception $e){}
    }
}
