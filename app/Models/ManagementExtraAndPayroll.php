<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManagementExtraAndPayroll extends Model
{
    protected $table = 'management_extra_and_payroll';
    use HasFactory;

    public function employee(){
        return $this->belongsTo(Employe::class, 'employee_id', 'id');
    }

    public function Job(){
        return $this->belongsTo(Job::class, 'job_id', 'id');
    }
}
