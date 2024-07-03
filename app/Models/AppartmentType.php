<?php

namespace App\Models;
// use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class AppartmentType extends Model
{
    // use SoftDeletes;
    protected $table = 'apartment_types';
    use HasFactory;
    static function boot(){
        parent::boot();
         static::addGlobalScope('active', function (Builder $builder) {
            $builder->where('isActive', true);  
        });
    }
    public function client_properties()
    {
        return $this->belongsToMany(ClientProperties::class);
    }
}
