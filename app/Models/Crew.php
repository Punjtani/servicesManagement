<?php

namespace App\Models;

use Fico7489\Laravel\EloquentJoin\Traits\EloquentJoin;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
// use Illuminate\Database\Eloquent\SoftDeletes;

class Crew extends Model
{
    // use SoftDeletes;
    use HasFactory;
    use EloquentJoin;

    protected $table = 'crews';
    // protected $appends = ['members', 'department_name'];    
    static function boot(){
        parent::boot();
         static::addGlobalScope('active', function (Builder $builder) {
            $builder->where('is_active', true);
        });
    }
    public function department(){
        return $this->hasOne(Department::class, 'id','department_id');
    }
    public function employe(){
        return $this->belongsToMany(Employe::class, 'employee_crews', 'crew_id', 'employee_id')->withPivot('percentage', 'is_lead');
    }

    //'employee_crew.employe.user'
    // public function getMembersAttribute()
    // {
    //     $name = '';
    //     $collected = [];
    //     $employe_crews = EmployeCrew::select('employee_id')->where('crew_id', $this->id)->get();
    //     if($employe_crews)
    //     {
    //         foreach($employe_crews as $row)
    //         {
    //             $employe = Employe::select('user_id')->where('id', $row->employee_id)->first();
    //             if($employe) 
    //             {
    //                 $user = User::find($employe->user_id);
    //                 if($user)
    //                 {
    //                     $member_name = $user->first_name . ' ' . $user->middle_name . ' ' . $user->last_name;
    //                     if(!in_array($member_name, $collected)) {
    //                         $collected[] = $member_name;
    //                         if($name == '')
    //                         {
    //                             $name = $member_name;
    //                         }else{
    //                             $name .= ', ' . $member_name;
    //                         }
    //                     }
    //                 }
    //             }
    //         }
    //     }
    //     return $name;
    // }

    // public function getDepartmentNameAttribute()
    // {
    //     $name = '';
    //     $department = Department::find($this->department_id);
    //     if($department) {
    //         $name = $department->name;
    //     }
    //     return $name;
    // }
}
