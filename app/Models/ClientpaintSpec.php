<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientpaintSpec extends Model
{
    protected $table = 'client_paint_specs';
    use HasFactory;

    public function Property(){

        return $this->belongsTo(ClientProperties::class, 'property_id', 'id');
    }
    public function paintColor(){

        return $this->belongsTo(PaintColor::class, 'paint_color_id', 'id');
    }
    public function paintManufacturer(){

        return $this->belongsTo(PaintManufacturer::class, 'paint_manufacturer_id', 'id');
    }
    public function paintMethod(){

        return $this->belongsTo(PaintMethod::class, 'paint_method_id', 'id');
    }
    public function paintSurface(){

        return $this->belongsTo(PaintSurface::class, 'paint_surface_id', 'id');
    }
    public function paintFinish(){

        return $this->belongsTo(PaintFinishing::class, 'paint_finish_id', 'id');
    }
    public function paintGrade(){

        return $this->belongsTo(PaintGrade::class, 'paint_grade_id', 'id');
    }
}
