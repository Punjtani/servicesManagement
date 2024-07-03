<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;

class PaintFinishing extends Model
{
    // use SoftDeletes;
    protected $table = 'paint_finishings';
    use HasFactory;

    public function clientPaintSpec(){

        return $this->hasMany(ClientpaintSpec::class, 'paint_finish_id', 'id');
    }
}
