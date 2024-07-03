<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'roles';
    use HasFactory;

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permissions');
    }
}
