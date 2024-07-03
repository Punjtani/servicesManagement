<?php

namespace App\Console\Commands;

use App\Models\EmployeDocument;
use App\Notifications\DocumentExpireNotification;
use Carbon\Carbon;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class DocumentExpireCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'document:expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'it will send notification for expired document';

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
        $documents=EmployeDocument::where('expiry_mail_sent',0)->whereDate('expiry_date','<',Carbon::now())->whereHas('employee.user')->with('employee.user')->get();
        foreach($documents as $document){
            $data['document']=$document;
            $data['employee']=$document->employee->user;
            $document->expiry_mail_sent=1;
            $document->save();
            try{
                Notification::send($document->employee->user,new DocumentExpireNotification($document->employee->user->email,$data));
            }catch(Exception $ex){
                dump('some error');
            }
           
        }
        return 0;
    }
}
