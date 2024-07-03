<?php

namespace App\Models;

use Fico7489\Laravel\EloquentJoin\Traits\EloquentJoin;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestedJobService extends Model
{
    protected $table = 'requested_job_services';
    use HasFactory;
    use EloquentJoin;

    public function service(){

        return $this->belongsTo(Service::class, 'service_id', 'id');
    }
    public function employee(){

        return $this->belongsTo(Employe::class, 'assigned_to_id', 'id');
    }
    public function employeeCrew(){

        return $this->belongsTo(Crew::class, 'assigned_to_id', 'id');
    }
    public function Job(){

        return $this->belongsTo(Job::class, 'job_id', 'id');
    }
    public function jobProgress(){

        return $this->hasOne(ServiceJobProgress::class, 'requested_job_service_id', 'id');
    }
    public function requestedJobSubService()
    {
        return $this->hasMany(RequestedJobSubService::class, 'requested_job_service_id', 'id');
    }
    public function serviceJobProgresAttatchment(){

        return $this->hasMany(ServiceJobProgresAttatchment::class, 'requested_job_service_id', 'id');
    }
    public function serviceJobProgressAfterAttatchment(){

        return $this->hasMany(ServiceJobProgressAfterAttatchment::class, 'requested_job_service_id', 'id');
    }
    public function jobAttatchment()
    {
        return $this->hasMany(JobAttatchment::class, 'rjs_id', 'id');
    }



}
