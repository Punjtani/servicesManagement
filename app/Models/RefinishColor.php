<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;

class RefinishColor extends Model
{
    // use SoftDeletes;
    protected $table = 'refinish_primer_color';
    use HasFactory;

    public function clientRefinishSpec(){

        return $this->hasMany(ClientrefinishSpec::class, 'paint_color_id', 'id');
    }
}
