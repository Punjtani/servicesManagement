<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Client extends Model
{
    protected $table = 'clients';
    use HasFactory;
    static function boot(){
        parent::boot();
         static::addGlobalScope('active', function (Builder $builder) {
            $builder->where('isActive', true);
        });
    }
    public function properties(){
        return $this->hasMany(ClientProperties::class, 'client_id', 'id')->withCount(['jobs as total_quotes'=> function($q){
            $q->where('type','quote');
        }]);;
    }
    public function staff(){
        return $this->hasMany(Staff::class, 'parent_id', 'id');
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function logo(){
        return $this->hasOne(ClientLogo::class, 'client_id', 'id');
    }

}
