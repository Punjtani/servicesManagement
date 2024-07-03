<?php

namespace App\Listeners;

use App\Events\ScheduleJobEmailEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Mail;
use Auth;
class ScheduleJobEmailListener
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
     * @param  ScheduleJobEmailEvent  $event
     * @return void
     */
    public function handle(ScheduleJobEmailEvent $event)
    {
        try{
            $staff = $event->data->property->staff;
            $emails = [];
            if(!$staff->isEmpty()){
                foreach($staff as $s){
                    if($s->user->is_email_enabled){
                        \Log::info("if cond");
                        array_push($emails , $s->user->email);
                    }
                }
                array_push($emails,config('mail.admin_address'));
            }else{
                \Log::info("else cond");
                $emails = [$event->data->property->client->user->email];
                array_push($emails,config('mail.admin_address'));
            }
            $ccEmail = config('mail.cc_address');
            $data = $event->data;
             Mail::send('email_template/schedule_job',compact('data'), function($mailData) use ($emails,$ccEmail){
            $mailData->to($emails);
            $mailData->cc($ccEmail);
            $mailData->from(config('mail.from_address'));
            $mailData->subject('Job Scheduled');
        });
    } catch(\Exception $e){}
    }
}
