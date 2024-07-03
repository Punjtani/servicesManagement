<?php

namespace App\Models;

use Fico7489\Laravel\EloquentJoin\Traits\EloquentJoin;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

// use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Model
{
    
    protected $table = 'departments';
    use HasFactory;
    use EloquentJoin;
    static function boot(){
        parent::boot();
         static::addGlobalScope('active', function (Builder $builder) {
            $builder->where('isActive', true);
        });
    }
    public function service()
    {
        return $this->hasOne(Service::class);
    }
    public function deparment_service()
    {
        return $this->belongsTo(Service::class,'service_id');
    }

    public function employee(){
        return $this->hasMany(Employe::class, 'department_id', 'id');
    }
}
