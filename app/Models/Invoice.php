<?php

namespace App\Models;

use Fico7489\Laravel\EloquentJoin\Traits\EloquentJoin;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;
    use EloquentJoin;

    public function job(){
        return $this->belongsTo(Job::class, 'job_id','id');
    }

    public function transactions(){
        return $this->hasMany(Deposits::class, 'invoice_id','id');
    }

    // public function invoice_line_item(){
    //     return $this->hasMany(InvoiceAddition::class, 'invoice_id', 'id');
    // }
}
