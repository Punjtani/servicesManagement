<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MultispectColor extends Model
{
    protected $table = 'refinish_multispect_color';
    use HasFactory;

    public function clientRefinishSpec(){

        return $this->hasMany(ClientrefinishSpec::class, 'multispect_color_id', 'id');
    }
}
