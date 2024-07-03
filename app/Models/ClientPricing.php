<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientPricing extends Model
{
    protected $table = 'client_pricings';
    use HasFactory;

    public function apartment()
    {
        return $this->belongsTo(AppartmentType::class, 'apartment_type_id', 'id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_type_id', 'id')->select('id','category');
    }

    public function serviceSubCategory()
    {
        return $this->belongsTo(ServiceSubCategory::class, 'sub_service_id', 'id')->select('id','name');
    }

}
