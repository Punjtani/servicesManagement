<?php

namespace App\Models;

use Fico7489\Laravel\EloquentJoin\Traits\EloquentJoin;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Employe extends Model
{
    protected $table = 'employees';
    use HasFactory;
    use EloquentJoin;

    static function boot()
    {
        parent::boot();
        static::addGlobalScope('active', function (Builder $builder) {
            $builder->where('isActive', true);
        });
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }
    public function documents()
    {
        return $this->hasMany(EmployeDocument::class, 'employee_id', 'id');
    }
    public function crew()
    {
        return $this->belongsToMany(Crew::class, 'employee_crews', 'crew_id', 'employee_id')->withPivot('percentage', 'is_lead');
    }
}
