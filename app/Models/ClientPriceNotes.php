<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientPriceNotes extends Model
{
    protected $table = 'price_notes';
    use HasFactory;

    

    public function property_notes()
    {
        return $this->hasMany(ClientPropertyPriceNotes::class, 'notes_id', 'id');
    }

}
