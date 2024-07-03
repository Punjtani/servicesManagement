<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Invoice;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;
use App\Notifications\UnPaidInvoiceNotification;
use Exception;

class UnpaidInvoiceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'unpaid:invoice';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send client invoice details that are not paid';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $invoices = Invoice::where('is_paid', 0)->whereDate('due_date', '<', Carbon::now())->with('job.property.staff')->whereHas('job.property.client.user')->get();
        foreach ($invoices as $invoice) {
            if ($invoice->last_mail_sent_at) {
                $date1 = Carbon::createFromFormat('Y-m-d', Carbon::now()->format('Y-m-d'));
                $date2 = Carbon::createFromFormat('Y-m-d', $invoice->last_mail_sent_at);
                $days = $date1->diffInDays($date2);
                $result = $date1->lt($date2);
                if ($result || $days < 1) {
                 continue;  
                }
            }

            $data['invoice_detail'] = ['invoice_no' => "IN-" . $invoice->id, "job" => $invoice->job->po_number, "property" => $invoice->job->property->title, "company" => $invoice->job->property->client->company, "due_date" => $invoice->due_date];
            $data['client'] = $invoice->job->property->client->user;
            $data['appartment'] = $invoice->job->apartment_number;
            $invoice->last_mail_sent_at = Carbon::now();
            $invoice->save();
            try{
                $staff = $invoice->job->property->staff;
                $email = [];
                if($staff){
                    foreach($staff as $s){
                        if($s->is_email_enabled){
                            array_push($email, $s->user->email);
                    }   }
                }else{
                    // $email=$invoice->job->property->client->user->email;
                    array_push($email, $invoice->job->property->client->user->email);
                }
                if(in_array($email,['duvy@reservicesystems.com'])){
                    return;
                }
                Notification::route("mail",$email)->notify(new UnPaidInvoiceNotification($email, $data));
                // Notification::send($invoice->job->property->client->user->email, new UnPaidInvoiceNotification($invoice->job->property->client->user->email, $data));
            }catch(Exception $ex){
                dump('some error');
            }
        }
        return 0;
    }
}
