<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepartmentPayrollPrice extends Model
{
    protected $table = 'department_payroll_price';
    public $timestamps = false;
    use HasFactory;
}
