<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class StaffRole extends Model
{
    protected $table = 'staff_role';
    use HasFactory;
    protected $guarded = [];
//    static function boot(){
//        parent::boot();
//         static::addGlobalScope('active', function (Builder $builder) {
//            $builder->where('isActive', true);
//        });
//    }
    public function staff(){
        return $this->hasMany(Staff::class, 'staff_role','id');
    }
}
