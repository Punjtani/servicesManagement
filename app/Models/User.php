<?php

namespace App\Models;

use Fico7489\Laravel\EloquentJoin\Traits\EloquentJoin;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Builder;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable, SoftDeletes, EloquentJoin;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    static function boot()
    {
        parent::boot();
        static::addGlobalScope('active', function (Builder $builder) {
            $builder->where('is_active', true);
        });
    }
    public function setEncryptedPasswordAttribute($value)
    {
        isset($value) ? $this->attributes['encrypted_password'] = encrypt($value) : '';
    }

    public function getEncryptedPasswordAttribute($value)
    {
        if (isset($value)) {
            try {
                return decrypt($value);
            } catch (\Throwable $th) {
                return '';
            }
        } else
            return '';
    }
    public function getProfileImageAttribute($value)
    {
        return isset($value) ? asset($value) : null;
    }
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    public function getJWTCustomClaims()
    {
        return [];
    }
    public function employee()
    {
        return $this->hasOne(Employe::class, 'user_id', 'id');
    }
    public function client()
    {
        return $this->hasOne(Client::class, 'user_id', 'id');
    }
    public function staff()
    {
        return $this->hasOne(Staff::class, 'user_id', 'id')->where('isActive', 1)->with('staff_roles');
    }
    public function roles()
    {
        return $this->belongsTo(Role::class, 'role');
    }
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new \App\Notifications\MailResetPasswordNotification($token));
    }
    public function tmpAttributeSetter($key, $value)
    {
        parent::setAttribute ($key, $value);
        return $this;
    }

}
