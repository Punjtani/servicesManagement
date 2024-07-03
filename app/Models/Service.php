<?php

namespace App\Models;

use Fico7489\Laravel\EloquentJoin\Traits\EloquentJoin;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class Service extends Model
{
    // use SoftDeletes;
    protected $table = 'services';
    use HasFactory;
    use EloquentJoin;
    static function boot(){
        parent::boot();
         static::addGlobalScope('active', function (Builder $builder) {
            $builder->where('isActive', 1);  
        });
    }
    public function subServices()
    {
        return $this->hasMany(ServiceSubCategory::class, 'parent_category_id', 'id');
    }

    public function jobsubServices(){
        return $this->hasMany(ServiceSubCategory::class, 'parent_category_id', 'id')->select('parent_category_id','name','id');

    }

    public function department(){
        return $this->belongsTo(Department::class);
    }
}
