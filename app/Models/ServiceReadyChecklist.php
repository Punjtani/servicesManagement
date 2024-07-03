<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceReadyChecklist extends Model
{
    protected $table = 'service_ready_checklists';
    use HasFactory;

    public function job(){

        return $this->belongsToMany(Job::class, 'job_service_ready_checklist');
    }
}
