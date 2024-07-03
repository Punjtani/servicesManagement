<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeDocument extends Model
{
    protected $table = 'employee_documents';
    use HasFactory;
    protected $guarded=['id'];

    public function employee(){
        return $this->belongsTo(Employe::class, 'employee_id', 'id');
    }
}
