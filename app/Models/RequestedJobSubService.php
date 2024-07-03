<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestedJobSubService extends Model
{
    use HasFactory;
    protected $table = 'requested_job_sub_services';

    public function requestedJobService(){
        return $this->belongsTo(RequestedJobService::class, 'requested_job_service_id', 'id');
    }

     public function subService(){
        return $this->belongsTo(ServiceSubCategory::class, 'sub_service_id', 'id');
    }
}
