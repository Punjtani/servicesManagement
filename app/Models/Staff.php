<?php

namespace App\Models;

use Carbon\Carbon;
use Fico7489\Laravel\EloquentJoin\Traits\EloquentJoin;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Staff extends Model
{
    protected $table = 'staff';
    use HasFactory;
    use EloquentJoin;
    static function boot(){
        parent::boot();
         static::addGlobalScope('active', function (Builder $builder) {
            $builder->where('isActive', true);
        });
    }
    public function client(){
        return $this->belongsTo(Client::class, 'parent_id', 'id');
    }
    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function staff_roles(){
        return $this->belongsTo(StaffRole::class, 'staff_role');
    }

    public function property(){
        return $this->belongsToMany(ClientProperties::class, 'staff_client_properties');
    }

    // /**
    //  * Get the user's date of birth.
    //  *
    //  * @param  string  $value
    //  * @return string
    //  */
    // public function getdateOfBirthAttribute($value)
    // {
    //     return Carbon::createFromFormat('Y-m-d', $value)->format('m-d-Y');
    // }
}
