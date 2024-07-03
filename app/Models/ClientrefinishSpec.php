<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientrefinishSpec extends Model
{
    protected $table = 'client_refinish_spec';
    use HasFactory;

    public function Property(){

        return $this->belongsTo(ClientProperties::class, 'property_id', 'id');
    }
    public function paintColor(){

        return $this->belongsTo(RefinishColor::class, 'paint_color_id', 'id');
    }
    public function multiSpectColor(){

        return $this->belongsTo(RefinishColor::class, 'multispect_color_id', 'id');
    }

}
