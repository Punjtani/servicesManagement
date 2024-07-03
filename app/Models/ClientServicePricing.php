<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientServicePricing extends Model
{
    protected $table = 'client_service_pricings';
    use HasFactory;

    public function apartment()
    {
        return $this->belongsTo(AppartmentType::class, 'appartment_type_id', 'id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_type_id', 'id')->select('id','category');
    }


}
