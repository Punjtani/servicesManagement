<?php

namespace App\Listeners;

use App\Events\SendInvoiceEmailEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Log;
use Mail;
use Auth;

class SendInvoiceEmailListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Handle the event.
     *
     * @param  SendInvoiceEmailEvent  $event
     * @return void
     */
    public function handle(SendInvoiceEmailEvent $event)
    {

        try{
            $ccEmail = config('mail.cc_address');
            $data = $event->data;
            $client = $data['job']['property']['client']['user'];
            $totalInvoice = $event->totalInvoice;
            $totalTax = $event->totalTax;
            // $manager = $data->job->property->manager;
            // if($manager && $manager->is_email_enabled){
            //     $emails = [env('MAIL_ADMIN_ADDRESS'), $manager->email];
            // }else{
            //     $emails = [env('MAIL_ADMIN_ADDRESS'), $client->email];
            // }
            // $emails = [env('MAIL_ADMIN_ADDRESS'), $data->email];
            $emails = $data->email;
            $emailList = array();
            foreach ($emails as $key => $email){
                array_push($emailList,$email['email']);
            }
            // if(!is_array($emails)){
            //     $emails = [$data->email];
            // }
            // array_push($emails, env("MAIL_ADMIN_ADDRESS"));
            array_push($emailList, config('mail.admin_address'));
            $pdf = $event->pdf;
            Mail::send('pdf/invoice/invoice',compact('data','totalInvoice','totalTax'), function($mailData) use ($emailList,$ccEmail,$pdf,$data){
            $mailData->to($emailList);
            // $mailData->to('zubair.zahid@hashehouse.com');
            $mailData->cc($ccEmail);
            $mailData->from(config('mail.cc_address'));
            $mailData->attachData($pdf->output(),'invoice.pdf',['mime' => 'application/pdf']);
            //$mailData->subject($data['job']['property']->title ." (". $data['job']->apartment_number .") ". 'Invoice');
            $mailData->subject($data->subject);
        });
    } catch(\Exception $e){}
    }
}
