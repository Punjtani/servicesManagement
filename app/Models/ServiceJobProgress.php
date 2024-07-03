<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceJobProgress extends Model
{
    protected $table = 'service_job_progresses';
    use HasFactory;

    public function requestedJobService(){
         return $this->belongsTo(RequestedJobService::class, 'requested_job_service_id', 'id');
    }
    

}
