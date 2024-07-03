<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Fico7489\Laravel\EloquentJoin\Traits\EloquentJoin;

class Job extends Model
{
    protected $table = 'jobs';
    use HasFactory;
    use EloquentJoin;

    public function jobServices(){
        return $this->hasMany(RequestedJobService::class, 'job_id', 'id')->orderBy('scheduled_date');
    }

    public function jobParentService(){

        return $this->hasMany(RequestedJobService::class, 'job_id', 'id')->orderBy('service_id')->unique('service_id');;
    }

    public function property(){

        return $this->belongsTo(ClientProperties::class, 'property_id', 'id')->with('taxType');
    }

    public function apartment_type(){

        return $this->belongsTo(AppartmentType::class, 'apartment_type_id', 'id');
    }

    public function jobServiceReadyCheckList(){

        return $this->hasMany(JobServiceReadyChecklist::class, 'job_id', 'id');
    }

    public function serviceReadyCheckList(){

        return $this->belongsToMany(ServiceReadyChecklist::class, 'job_service_ready_checklist');
    }

    public function requestedJobService()
    {
        return $this->hasMany(RequestedJobService::class, 'job_id', 'id');
    }
    public function managementExtraAndPayroll()
    {
        return $this->hasMany(ManagementExtraAndPayroll::class, 'job_id', 'id');
    }
    // public function jobAttatchment()
    // {
    //     return $this->hasMany(JobAttatchment::class, 'job_id', 'id');
    // }

    public function invoice()
    {
        return $this->hasOne(Invoice::class, 'job_id', 'id');
    }

    public function requestedBy(){

        return $this->hasOne(User::class, 'id', 'requested_by');
    }

    public function createdBy() {
        return $this->belongsTo(User::class,'created_by', 'id');
    }

}
