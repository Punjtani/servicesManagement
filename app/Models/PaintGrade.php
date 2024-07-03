<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;

class PaintGrade extends Model
{
    // use SoftDeletes;
    protected $table = 'paint_grades';
    use HasFactory;

    public function clientPaintSpec(){

        return $this->hasMany(ClientpaintSpec::class, 'paint_grade_id', 'id');
    }
}
