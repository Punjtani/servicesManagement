<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $table = 'attendance';
    use HasFactory;

    public function employee()
    {
        return $this->belongsTo('App\Employe', 'employee_id');
    }
}

