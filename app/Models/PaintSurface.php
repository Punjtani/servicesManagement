<?php

namespace App\Models;

use Fico7489\Laravel\EloquentJoin\Traits\EloquentJoin;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;

class PaintSurface extends Model
{
    // use SoftDeletes;
    protected $table = 'paint_surfaces';
    use HasFactory;
    use EloquentJoin;

    public function clientPaintSpec(){

        return $this->hasMany(ClientpaintSpec::class, 'paint_surface_id', 'id');
    }
    public function paint_method(){

        return $this->belongsTo(PaintMethod::class, 'paint_method_id');
    }
}
