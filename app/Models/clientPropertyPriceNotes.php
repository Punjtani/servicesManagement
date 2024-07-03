<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientPropertyPriceNotes extends Model
{
    protected $table = 'client_property_pricing_notes';
    use HasFactory;

   
    public function property()
    {
        return $this->belongsTo(ClientProperties::class, 'property_id', 'id');
    }
    public function notes()
    {
        return $this->belongsTo(ClientPriceNotes::class, 'notes_id', 'id');
    }
}
