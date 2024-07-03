<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobServiceReadyChecklist extends Model
{
    protected $table = 'job_service_ready_checklist';
    use HasFactory;


    public function serviceReady(){
        return $this->belongsTo(ServiceReadyChecklist::class, 'service_ready_checklist_id', 'id');
    }

}
