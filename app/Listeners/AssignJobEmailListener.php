<?php

namespace App\Listeners;

use App\Events\AssignJobEmailEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Mail;
use Auth;
class AssignJobEmailListener
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
     * @param  AssignJobEmailEvent  $event
     * @return void
     */
    public function handle(AssignJobEmailEvent $event)
    {
        try {
            $emails = [config('mail.admin_address'), $event->data->property->client->user->email];
            $services = $event->data->jobServices;
            foreach($services as $service){
                if($service->assigned_to_type == 'individual'){
                    array_push($emails, $service->employee->user->email);
                }else{
                    $crew = collect($service->employeeCrew->employee_crew)->where('is_lead', 1)->first();
                    if($crew && $crew->count() > 0){
                        array_push($emails, $crew->employe->user->email);
                    }
                }
            }
            $ccEmail = config('mail.cc_address');
            $data = $event->data;
            Mail::send('email_template/assign_job',compact('data'), function($mailData) use ($emails,$ccEmail){
                $mailData->to($emails);
                $mailData->cc($ccEmail);
                $mailData->from(config('mail.from_address'));
                $mailData->subject('Job is Assigned!');
            });
        } catch(\Exception $e){}

    }
}
