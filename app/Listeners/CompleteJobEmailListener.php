<?php

namespace App\Listeners;

use App\Events\CompleteJobEmailEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Mail;
use Auth;

class CompleteJobEmailListener
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
     * @param  CompleteJobEmailEvent  $event
     * @return void
     */
    public function handle(CompleteJobEmailEvent $event)
    {

        try {
                 //
        $staff = $event->data->property->staff;
        $emails = [];
        if (!$staff->isEmpty()) {
            foreach ($staff as $s) {
                if ($s->user->is_email_enabled) {
                    \Log::info("if cond");
                    array_push($emails, $s->user->email);
                }
            }
            array_push($emails, config('mail.admin_address'));
        } else {
            \Log::info("else cond");
            $emails = [$event->data->property->client->user->email];
            array_push($emails, config('mail.admin_address'));
        }
        $services = $event->data->jobServices;
        foreach ($services as $service) {
            if ($service->assigned_to_type == 'individual') {
                array_push($emails, $service->employee->user->email);
            } else {
                $crew = collect($service->employeeCrew->employee_crew)->where('is_lead', 1)->first();
                if (isset($crew) && $crew->count() > 0) {
                    array_push($emails, $crew->employe->user->email);
                }
            }
        }
        $ccEmail = config('mail.cc_address');
        $data = $event->data;
        Mail::send('email_template/complete_job', compact('data'), function ($mailData) use ($emails, $ccEmail) {
            $mailData->to($emails);
            $mailData->cc($ccEmail);
            $mailData->from(config('mail.from_address'));
            $mailData->subject('A Job Service is Completed!');
        });
    } catch(\Exception $e){}
    }
}
