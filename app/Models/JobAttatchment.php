<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobAttatchment extends Model
{
    protected $table = 'job_attatchments';
    use HasFactory;
    public function job()
    {
        return $this->belongsTo(RequestedJobService::class, 'rjs_id', 'id');
    }
}
