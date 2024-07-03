<?php

namespace App\Listeners;

use App\Events\CreateJobEmailEvent;
use App\Models\Setting;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Mail;
use Auth;
class CreateJobEmailListener
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
     * @param  CreateJobEmailEvent  $event
     * @return void
     */
    public function handle(CreateJobEmailEvent $event)
    {

        try {
            $emails = [config('mail.admin_address'),$event->data->property->client->user->email];
            $ccEmail = config('mail.cc_address');
            $data = $event->data;
            Mail::send('email_template/new_job',compact('data'), function($mailData) use ($emails,$ccEmail){
            $mailData->to($emails);
            $mailData->cc($ccEmail);
            $mailData->from(config('mail.from_address'));
            $mailData->subject('New Job Created!');
        });
    } catch(\Exception $e){}
    }
}
